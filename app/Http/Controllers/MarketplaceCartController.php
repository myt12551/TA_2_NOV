<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class MarketplaceCartController extends Controller
{
    protected string $sessionKey = 'marketplace_cart';

    public function index(Request $request)
    {
        $cart = $request->session()->get($this->sessionKey, []);
        $ids  = array_keys($cart);

        $items = $ids
            ? Item::whereIn('id', $ids)->get()->keyBy('id')
            : collect();

        $rows = [];
        $total = 0;

        foreach ($cart as $itemId => $qty) {
            $item = $items->get($itemId);
            if (!$item) continue;

            $price = (float) ($item->selling_price ?? 0); // ← harga jual
            $qty   = (int) $qty;
            $subtotal = $price * $qty;

            $rows[] = compact('item', 'qty', 'price', 'subtotal');
            $total += $subtotal;
        }

        return view('marketplace.cart', compact('rows', 'total'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'qty'     => ['required', 'integer', 'min:1'],
        ]);

        $item = Item::find($data['item_id']);

        // Validasi stok
        if ($data['qty'] > (int) $item->stock) {
            return back()->withErrors(['qty' => 'Jumlah melebihi stok tersedia.']);
        }

        $cart = $request->session()->get($this->sessionKey, []);
        $cart[$item->id] = ($cart[$item->id] ?? 0) + (int) $data['qty'];
        $request->session()->put($this->sessionKey, $cart);

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'qty'     => ['required', 'integer', 'min:1'],
        ]);

        $cart = $request->session()->get($this->sessionKey, []);
        if (!array_key_exists($data['item_id'], $cart)) {
            return back()->withErrors(['item' => 'Produk tidak ada di keranjang.']);
        }

        $item = Item::find($data['item_id']);
        if ($data['qty'] > (int) $item->stock) {
            return back()->withErrors(['qty' => 'Jumlah melebihi stok tersedia.']);
        }

        $cart[$data['item_id']] = (int) $data['qty'];
        $request->session()->put($this->sessionKey, $cart);

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer', 'exists:items,id'],
        ]);

        $cart = $request->session()->get($this->sessionKey, []);
        unset($cart[$data['item_id']]);
        $request->session()->put($this->sessionKey, $cart);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear(Request $request)
    {
        $request->session()->forget($this->sessionKey);
        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
