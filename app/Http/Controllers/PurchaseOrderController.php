<?php

namespace App\Http\Controllers;

use App\Models\{
    PurchaseOrder,
    PurchaseOrderItem,
    Supplier,
    SupplierProduct,
    PurchaseRequest
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controller for managing Purchase Orders (PO).
 *
 * This version extends the original PO controller to support linking
 * purchase orders with purchase requests (PR) and calculating total
 * amounts based on item quantities and unit prices.
 */
class PurchaseOrderController extends Controller
{
    /**
     * Display a list of purchase orders.
     */
    public function index()
    {
        $orders = PurchaseOrder::with(['supplier', 'purchaseRequest', 'items', 'goodsReceipts', 'invoices'])->latest()->get();
        return view('purchase-order.index', compact('orders'));
    }

    /**
     * Show the form to create a new PO. If a purchase_request_id is provided
     * via query parameter, the items from the corresponding PR are used to
     * prefill the PO form.
     */
    public function create(Request $request)
    {
        $suppliers = Supplier::orderBy('name')->get();
        $pr = null;
        if ($request->has('purchase_request_id')) {
            $pr = PurchaseRequest::with('items')->find($request->purchase_request_id);
        }
        return view('purchase-order.form', compact('suppliers', 'pr'));
    }

    /**
     * Store a newly created purchase order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'          => 'required|exists:suppliers,id',
            'po_date'              => 'required|date',
            'purchase_request_id'  => 'nullable|exists:purchase_requests,id',
            'items'                => 'required|array|min:1',
            'items.*.product_name' => 'required|string',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.unit_price'   => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total amount from items
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }

            // Create the PO
            $po = PurchaseOrder::create([
                'supplier_id'         => $request->supplier_id,
                'po_date'             => $request->po_date,
                'purchase_request_id' => $request->purchase_request_id,
                'created_by'          => auth()->id(),
                'po_number'           => $this->generatePONumber(),
                'status'              => 'draft',
                'total_amount'        => $totalAmount,
            ]);

            // Create PO items
            foreach ($request->items as $item) {
                // Try map to existing Item by product_name to set item_id (optional)
                $itemModel = \App\Models\Item::where('name', $item['product_name'])->first();
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'item_id'           => $itemModel?->id, // nullable
                    'product_name'      => $item['product_name'],
                    'quantity'          => $item['quantity'],
                    'unit_price'        => $item['unit_price'],
                    'unit'              => $item['unit'] ?? null,
                ]);
            }

            // Update originating PR status to approved if provided
            if ($request->purchase_request_id) {
                $pr = PurchaseRequest::find($request->purchase_request_id);
                if ($pr) {
                    $pr->update(['status' => 'approved']);
                }
            }

            DB::commit();
            // After creating a PO, redirect back to the procurement dashboard
            return redirect()->route('procurement.index')
                ->with('success', 'PO berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan PO: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified purchase order.
     */
    public function show($id)
    {
        $po = PurchaseOrder::with('items', 'supplier')->findOrFail($id);
        return view('purchase-order.show', compact('po'));
    }

    /**
     * Validate a purchase order (change status to validated).
     */
    public function validatePO($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        $po->update(['status' => 'validated']);
        return redirect()->route('procurement.index')->with('success', 'PO berhasil divalidasi');
    }

    /**
     * Upload an invoice image and mark PO as received.
     */
    public function uploadInvoice(Request $request, $id)
    {
        $request->validate([
            'invoice' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);
        $po = PurchaseOrder::findOrFail($id);
        // Remove previous invoice if exists
        if ($po->invoice_image_path && Storage::disk('public')->exists($po->invoice_image_path)) {
            Storage::disk('public')->delete($po->invoice_image_path);
        }
        $path = $request->file('invoice')->store('invoices', 'public');
        $po->update([
            'invoice_image_path' => $path,
            'status'             => 'received'
        ]);
        return redirect()->route('purchase-orders.index')->with('success', 'Invoice berhasil diunggah dan status diperbarui.');
    }

    /**
     * Export PO as PDF.
     */
    public function exportPDF($id)
    {
        $po = PurchaseOrder::with('items', 'supplier')->findOrFail($id);
        $pdf = Pdf::loadView('purchase-order.pdf', compact('po'));
        $filename = str_replace(['/', '\\'], '-', $po->po_number);
        return $pdf->download("PO_{$filename}.pdf");
    }

    /**
     * API endpoint: get items for a supplier.
     */
    public function getItemsBySupplier($supplierId)
    {
        $supplier = Supplier::with('supplierProducts')->findOrFail($supplierId);
        $items = $supplier->supplierProducts->map(function ($product) {
            $itemData = \App\Models\Item::where('name', $product->product_name)->first();
            return [
                'id'           => $product->id,
                'product_name' => $product->product_name,
                'stock'        => $itemData ? $itemData->stock : '-',
                'unit'         => $itemData && $itemData->category ? $itemData->category->name : '-',
            ];
        });
        return response()->json($items);
    }

    /**
     * Helper: generate sequential PO numbers (format: PO-0001/MM/YYYY).
     */
    private function generatePONumber(): string
    {
        $count = PurchaseOrder::count() + 1;
        $month = date('m');
        $year  = date('Y');
        return sprintf("PO-%04d/%s/%s", $count, $month, $year);
    }

    /**
     * Delete all purchase orders and their items.
     */
    public function deleteAll()
    {
        DB::transaction(function () {
            // Hapus semua item PO terlebih dahulu
            \App\Models\PurchaseOrderItem::query()->delete();
            // Hapus semua PO
            PurchaseOrder::query()->delete();
        });
        return redirect()->route('purchase-orders.index')->with('success', 'Semua Purchase Order berhasil dihapus');
    }
}
