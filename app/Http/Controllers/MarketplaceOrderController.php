<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MarketplaceOrderController extends Controller
{
    protected string $cartSessionKey = 'marketplace_cart';

    /** Halaman ringkasan + form pickup_name/phone/notes */
    public function create(Request $request)
    {
        $cart = $request->session()->get($this->cartSessionKey, []);
        if (empty($cart)) {
            return redirect()->route('marketplace.cart')->with('error', 'Keranjang kosong.');
        }

        $ids = array_keys($cart);
        $items = Item::whereIn('id', $ids)->get()->keyBy('id');

        $rows = [];
        $total = 0;
        foreach ($cart as $itemId => $qty) {
            $item = $items->get($itemId);
            if (!$item) continue;

            $qty = (int) $qty;
            $price = (int) ($item->selling_price ?? 0); // int sesuai kolom items.selling_price
            $subtotal = $price * $qty;

            $rows[] = compact('item','qty','price','subtotal');
            $total += $subtotal;
        }
        if (empty($rows)) {
            return redirect()->route('marketplace.cart')->with('error', 'Keranjang tidak valid.');
        }

        // default isi nama dari user login
        $buyerName = Auth::user()->name ?? '';

        return view('marketplace.checkout', compact('rows','total','buyerName'));
    }

    /** Proses "Buat Pesanan" → simpan ke marketplace_orders/items & kurangi stok */
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'pickup_name' => ['required','string','max:100'],
            'phone'       => ['required','string','max:30'],
            'notes'       => ['nullable','string','max:255'],
        ]);

        $cart = $request->session()->get($this->cartSessionKey, []);
        if (empty($cart)) {
            return redirect()->route('marketplace.cart')->with('error', 'Keranjang kosong.');
        }

        $ids = array_keys($cart);
        // lockForUpdate untuk aman stock deduction
        $items = Item::whereIn('id', $ids)->lockForUpdate()->get()->keyBy('id');

        $rows = [];
        $total = 0;

        foreach ($cart as $itemId => $qty) {
            $item = $items->get($itemId);
            if (!$item) return back()->with('error', 'Ada produk yang tidak ditemukan.');

            $qty = (int) $qty;
            if ($qty < 1) return back()->with('error', 'Kuantitas tidak valid.');

            if ($qty > (int)$item->stock) {
                return back()->with('error', "Stok '{$item->name}' tidak mencukupi.");
            }

            $price = (int) ($item->selling_price ?? 0); // pakai selling_price (schema items) :contentReference[oaicite:4]{index=4}
            $subtotal = $price * $qty;

            $rows[] = [
                'item_id'  => $item->id,
                'name'     => $item->name,
                'code'     => $item->code,
                'qty'      => $qty,
                'price'    => $price, // marketplace_order_items.price decimal(14,2) → simpan angka utuh (akan diperlakukan sebagai 3500.00) :contentReference[oaicite:5]{index=5}
                'subtotal' => $subtotal,
            ];
            $total += $subtotal;
        }

        $code = 'PO-' . strtoupper(Str::random(8)); // contoh: PO-ABCD1234 (unik) :contentReference[oaicite:6]{index=6}

        DB::transaction(function () use ($user, $data, $rows, $total, $code) {
            // INSERT marketplace_orders (kolom sesuai dump: user_id, code, status, pickup_name, phone, notes, total_price) :contentReference[oaicite:7]{index=7}
            $orderId = DB::table('marketplace_orders')->insertGetId([
                'user_id'     => $user->id,
                'code'        => $code,
                'status'      => 'pending_pickup', // varchar(20) OK (default schema "pending") :contentReference[oaicite:8]{index=8}
                'pickup_name' => $data['pickup_name'],
                'phone'       => $data['phone'],
                'notes'       => $data['notes'] ?? null,
                'total_price' => $total, // decimal(14,2) di DB, kirim integer → 3500 menjadi 3500.00 :contentReference[oaicite:9]{index=9}
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // INSERT marketplace_order_items + kurangi stock
            foreach ($rows as $r) {
                DB::table('marketplace_order_items')->insert([
                    'order_id'   => $orderId,
                    'item_id'    => $r['item_id'],
                    'qty'        => $r['qty'],
                    'price'      => $r['price'], // decimal(14,2) di DB
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                // Kurangi stok di items.stock (kolom ada) :contentReference[oaicite:10]{index=10}
                DB::table('items')->where('id', $r['item_id'])->decrement('stock', $r['qty']);
            }
        });

        // kosongkan keranjang
        $request->session()->forget($this->cartSessionKey);

        return redirect()->route('marketplace.order.show', $code)
                ->with('success', 'Pesanan dibuat. Bayar tunai saat barang diambil.');
    }

    /** Nota berdasarkan data DB marketplace_orders & marketplace_order_items */
    public function show(string $code)
    {
        $order = DB::table('marketplace_orders')->where('code', $code)->first(); // kolom ada :contentReference[oaicite:11]{index=11}
        if (!$order) abort(404);

        $items = DB::table('marketplace_order_items')->where('order_id', $order->id)->get(); // kolom ada :contentReference[oaicite:12]{index=12}

        // Bentuk rows untuk view (dengan subtotal)
        $rows = $items->map(function ($i) {
            $price = (int)$i->price; // kita simpan integer (Rp), DB decimal(14,2)
            return [
                'item_id'  => $i->item_id,
                'qty'      => (int)$i->qty,
                'price'    => $price,
                'subtotal' => $price * (int)$i->qty,
            ];
        });

        $total = (int)$order->total_price;

        // Ambil snapshot nama/kode (opsional): join ke items untuk tampilkan nama/kode terkini jika mau
        $map = DB::table('items')->whereIn('id', $rows->pluck('item_id'))->get()->keyBy('id');
        $rows = $rows->map(function ($r) use ($map) {
            $it = $map->get($r['item_id']);
            return [
                'name'     => $it->name ?? '-',
                'code'     => $it->code ?? '-',
                'qty'      => $r['qty'],
                'price'    => $r['price'],
                'subtotal' => $r['subtotal'],
            ];
        });

        return view('marketplace.order-show', compact('order','rows','total'));
    }

    public function finalizeAtCashier(Request $request)
    {
        $data = $request->validate([
            'order_code'        => ['required','string','exists:marketplace_orders,code'],
            'payment_method_id' => ['nullable','integer','exists:payment_methods,id'], // default: Tunai=1
        ]);

        $cashier = Auth::user();                 // pastikan middleware role kasir/admin/supervisor
        $pmId    = $data['payment_method_id'] ?? 1; // 1 = Tunai

        DB::transaction(function () use ($data, $cashier, $pmId) {
            // 1) Ambil order PENDING dan kunci (hindari race)
            $order = DB::table('marketplace_orders')
                ->where('code', $data['order_code'])
                ->lockForUpdate()
                ->first();

            if (!$order) {
                abort(404, 'Order tidak ditemukan.');
            }
            if ($order->status !== 'pending_pickup') {
                abort(400, 'Order tidak dalam status pending_pickup.');
            }

            $items = DB::table('marketplace_order_items')
                ->where('order_id', $order->id)
                ->get();

            // 2) Buat nomor invoice harian (format: ddmmyy + 4 digit running)
            $today = now()->format('dmy');
            $last = DB::table('transactions')
                ->whereDate('created_at', now()->toDateString())
                ->orderByDesc('id')
                ->first();
            $seq = $last ? ((int)$last->invoice_no + 1) : 1;
            $invoice = $today . str_pad($seq, 4, '0', STR_PAD_LEFT); // contoh: 1109250001

            // 3) Insert transaksi POS
            $trxId = DB::table('transactions')->insertGetId([
                'user_id'           => $cashier->id,
                'channel'           => 'online',                 // enum: pos|online
                'payment_status'    => 'paid',
                'pickup_status'     => 'picked_up',
                'pickup_code'       => $order->code,            // jejak ke marketplace_orders
                'customer_id'       => $order->user_id,         // bisa null, sesuai schema
                'invoice'           => $invoice,
                'invoice_no'        => (string)$seq,
                'total'             => (int)$order->total_price, // int (rupiah)
                'discount'          => 0,
                'payment_method_id' => $pmId,                    // 1 = Tunai
                'amount'            => (int)$order->total_price,
                'change'            => 0,
                'status'            => 'paid',
                'note'              => 'Marketplace pickup: '.$order->pickup_name.' ('.$order->phone.')',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // 4) Insert detail transaksi
            foreach ($items as $i) {
                DB::table('transaction_details')->insert([
                    'transaction_id' => $trxId,
                    'item_id'        => $i->item_id,
                    'qty'            => (int)$i->qty,
                    'item_price'     => (int)$i->price,         // int (rupiah)
                    'total'          => (int)$i->price * (int)$i->qty,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            // 5) Update status order → completed
            DB::table('marketplace_orders')
                ->where('id', $order->id)
                ->update([
                    'status'     => 'completed',
                    'updated_at' => now(),
                ]);
        });

        return back()->with('success', 'Transaksi selesai dan dicatat pada modul POS.');
    }
    public function index()
    {
        $user = Auth::user();
        // Ambil semua pesanan user yg statusnya pending_pickup atau completed
        $orders = DB::table('marketplace_orders')
                   ->where('user_id', $user->id)
                   ->whereIn('status', ['pending_pickup', 'completed'])
                   ->orderByDesc('created_at')
                   ->get();

        return view('marketplace.orders', compact('orders'));
    }
}
