<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Controller for handling goods receipts (GRNs).
 *
 * Warehouse/admin users use this controller to record the receipt of
 * goods against a purchase order. The GRN can later be reviewed by
 * supervisors before final approval.
 */
class GoodsReceiptController extends Controller
{
    /**
     * List all goods receipts.
     */
    public function index()
    {
        $receipts = GoodsReceipt::with('purchaseOrder')->latest()->get();
        return view('goods-receipt.index', compact('receipts'));
    }

    /**
     * Show the form for creating a new goods receipt for a specific PO.
     */
    public function create($purchaseOrderId)
    {
        $po = PurchaseOrder::with('items')->findOrFail($purchaseOrderId);
        return view('goods-receipt.form', compact('po'));
    }

    /**
     * Store a new goods receipt.
     */
    public function store(Request $request, $purchaseOrderId)
    {
        $request->validate([
            'receipt_date' => 'required|date',
            'items'        => 'required|array|min:1',
            'items.*.product_name' => 'required|string',
            'items.*.quantity_received' => 'required|integer|min:1',
            'notes'        => 'nullable|string',
            'delivery_order_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bbm_file'           => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $po = PurchaseOrder::findOrFail($purchaseOrderId);
        DB::beginTransaction();
        try {
            // Handle optional file uploads for delivery order and BBM
            $doPath  = null;
            $bbmPath = null;
            if ($request->hasFile('delivery_order_file')) {
                $doPath = $request->file('delivery_order_file')->store('delivery_orders', 'public');
            }
            if ($request->hasFile('bbm_file')) {
                $bbmPath = $request->file('bbm_file')->store('bbm', 'public');
            }

            $gr = GoodsReceipt::create([
                'purchase_order_id'   => $po->id,
                'gr_number'           => $this->generateGRNumber(),
                'receipt_date'        => $request->receipt_date,
                'received_by'         => Auth::id(),
                'status'              => 'completed',
                'notes'               => $request->notes,
                'delivery_order_file' => $doPath,
                'bbm_file'            => $bbmPath,
            ]);

            foreach ($request->items as $item) {
                GoodsReceiptItem::create([
                    'goods_receipt_id'  => $gr->id,
                    'product_name'      => $item['product_name'],
                    'quantity_received' => $item['quantity_received'],
                ]);
            }

            // Update PO status to received
            $po->update(['status' => 'received']);

            DB::commit();
            // Redirect back to procurement dashboard after storing GR
            return redirect()->route('procurement.index')->with('success', 'Penerimaan barang berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan penerimaan: ' . $e->getMessage()]);
        }
    }

    /**
     * Show a single goods receipt.
     */
    public function show($id)
    {
        $receipt = GoodsReceipt::with('items', 'purchaseOrder')->findOrFail($id);
        return view('goods-receipt.show', compact('receipt'));
    }

    /**
     * Helper: generate sequential GR numbers (format: GR-0001/MM/YYYY).
     */
    private function generateGRNumber(): string
    {
        $count = GoodsReceipt::count() + 1;
        $month = date('m');
        $year  = date('Y');
        return sprintf("GR-%04d/%s/%s", $count, $month, $year);
    }
}