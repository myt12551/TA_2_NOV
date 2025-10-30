<?php

namespace App\Http\Controllers;

use App\Models\{
    PurchaseOrder,
    PurchaseOrderItem,
    PurchaseRequest,
    Supplier,
    GoodsReceipt,
    Invoice
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class NewPurchaseOrderController extends Controller
{
    /**
     * Display a list of approved PRs ready for PO creation
     */
    public function index()
    {
        $approvedPRs = PurchaseRequest::with(['items', 'requester', 'approver'])
            ->orderBy('created_at', 'desc')
            ->get();

        $ongoingPOs = PurchaseOrder::whereIn('status', ['draft', 'sent', 'confirmed'])
            ->with(['supplier', 'purchaseRequest'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('new-purchase-order.index', compact('approvedPRs', 'ongoingPOs'));
    }

    /**
     * Get supplier's items as JSON for API
     */
    public function getItemsBySupplier(Supplier $supplier)
    {
        $items = $supplier->supplierProducts()
            ->with('item')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->item->id,
                    'name' => $product->item->name,
                    'code' => $product->item->code,
                    'unit' => $product->item->unit,
                    'price' => $product->price,
                    'min_order' => $product->min_order,
                    'lead_time' => $product->lead_time
                ];
            });

        return response()->json($items);
    }

    /**
     * Show form to create PO from approved PR
     */
    public function create($prId)
    {
        $pr = PurchaseRequest::with(['items', 'requester'])->findOrFail($prId);
        
        if (!$pr->canConvertToPO()) {
            return back()->withErrors(['error' => 'PR ini tidak dapat dikonversi ke PO']);
        }

        $suppliers = Supplier::orderBy('name')->get();
        return view('new-purchase-order.create', compact('pr', 'suppliers'));
    }

    /**
     * Create PO from approved PR
     */
    public function store(Request $request)
    {
        $request->validate([
            'pr_id' => 'required|exists:purchase_requests,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'contact_person' => 'required|string',
            'contact_phone' => 'required|string',
            'estimated_delivery_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:purchase_request_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $pr = PurchaseRequest::findOrFail($request->pr_id);
            
            // Create PO
            $po = PurchaseOrder::create([
                'purchase_request_id' => $pr->id,
                'supplier_id' => $request->supplier_id,
                'po_number' => $this->generatePONumber(),
                'po_date' => now(),
                'contact_person' => $request->contact_person,
                'contact_phone' => $request->contact_phone,
                'estimated_delivery_date' => $request->estimated_delivery_date,
                'notes' => $request->notes,
                'status' => 'draft',
                'created_by' => auth()->id() ?? 1,
                'total_amount' => collect($request->items)->sum(fn($item) => $item['quantity'] * $item['unit_price'])
            ]);

            // Create PO items
            foreach ($request->items as $item) {
                $prItem = $pr->items()->findOrFail($item['id']);
                
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_name' => $prItem->product_name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'unit' => $prItem->unit,
                    'notes' => $item['notes'] ?? null
                ]);
            }

            // Update PR status
            $pr->update(['status' => 'po_created']);

            DB::commit();
            return redirect()->route('new-purchase-orders.show', $po->id)
                ->with('success', 'PO berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membuat PO: ' . $e->getMessage()]);
        }
    }

    /**
     * Display PO details and generate PDF
     */
    public function show($id)
    {
        $po = PurchaseOrder::with(['items', 'supplier', 'purchaseRequest'])->findOrFail($id);
        return view('new-purchase-order.show', compact('po'));
    }

    /**
     * Generate and download PO PDF
     */
    public function generatePDF($id)
    {
        $po = PurchaseOrder::with(['items', 'supplier', 'purchaseRequest'])->findOrFail($id);
        $pdf = PDF::loadView('new-purchase-order.pdf', compact('po'));
        return $pdf->download('PO-'.$po->po_number.'.pdf');
    }

    /**
     * Mark PO as sent to supplier
     */
    public function markAsSent($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        $po->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
        return back()->with('success', 'PO telah ditandai terkirim ke supplier');
    }

    /**
     * Confirm PO with supplier
     */
    public function confirm(Request $request, $id)
    {
        $request->validate([
            'confirmation_date' => 'required|date',
            'confirmed_delivery_date' => 'required|date|after:confirmation_date',
            'supplier_notes' => 'nullable|string'
        ]);

        $po = PurchaseOrder::findOrFail($id);
        $po->update([
            'status' => 'confirmed',
            'confirmation_date' => $request->confirmation_date,
            'confirmed_delivery_date' => $request->confirmed_delivery_date,
            'supplier_notes' => $request->supplier_notes
        ]);

        return back()->with('success', 'PO telah dikonfirmasi dengan supplier');
    }

    /**
     * Create Goods Receipt for confirmed PO
     */
    public function createGR(Request $request, $id)
    {
        $request->validate([
            'receipt_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.quantity_received' => 'required|integer|min:0',
            'items.*.notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $po = PurchaseOrder::with('items')->findOrFail($id);
            
            if ($po->status !== 'confirmed') {
                throw new \Exception('PO harus dikonfirmasi terlebih dahulu');
            }

            // Create GR
            $gr = GoodsReceipt::create([
                'purchase_order_id' => $po->id,
                'gr_number' => 'GR-' . date('Ymd') . '-' . str_pad($po->id, 4, '0', STR_PAD_LEFT),
                'receipt_date' => $request->receipt_date,
                'received_by' => auth()->id() ?? 1,
                'notes' => $request->notes
            ]);

            // Create GR items and update inventory
            foreach ($request->items as $itemId => $itemData) {
                $poItem = $po->items()->findOrFail($itemId);
                
                $gr->items()->create([
                    'product_name' => $poItem->product_name,
                    'quantity_received' => $itemData['quantity_received'],
                    'unit' => $poItem->unit,
                    'notes' => $itemData['notes'] ?? null
                ]);

                // Update inventory if needed
                // ... add your inventory update logic here
            }

            // Update PO status
            $po->update(['status' => 'received']);

            DB::commit();
            return back()->with('success', 'Goods Receipt berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membuat GR: ' . $e->getMessage()]);
        }
    }

    /**
     * Create Invoice for completed PO
     */
    public function createInvoice(Request $request, $id)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after:invoice_date',
            'amount' => 'required|numeric|min:0',
            'invoice_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $po = PurchaseOrder::findOrFail($id);
            
            if ($po->status !== 'received') {
                throw new \Exception('Barang harus diterima terlebih dahulu');
            }

            // Store invoice file
            $path = $request->file('invoice_file')->store('invoices', 'public');

            // Create invoice
            Invoice::create([
                'purchase_order_id' => $po->id,
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'amount' => $request->amount,
                'file_path' => $path,
                'status' => 'pending'
            ]);

            // Update PO status
            $po->update(['status' => 'invoiced']);

            DB::commit();
            return back()->with('success', 'Invoice berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membuat invoice: ' . $e->getMessage()]);
        }
    }

    private function generatePONumber(): string
    {
        $count = PurchaseOrder::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count() + 1;
            
        return sprintf("PO/%s/%s/%04d", 
            now()->format('Y'),
            now()->format('m'),
            $count
        );
    }
}