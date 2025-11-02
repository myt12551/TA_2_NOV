<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

/**
 * Controller for managing Purchase Requests (PR).
 *
 * Team members use this controller to submit new requests for goods or
 * services. Supervisors can review and approve/reject those requests.
 */
class PurchaseRequestController extends Controller
{
    /**
     * Display a list of purchase requests.
     */
    public function index()
    {
        $requests = PurchaseRequest::with('requester')->latest()->get();
        return view('purchase-request.index', compact('requests'));
    }

    /**
     * Show the form for creating a new purchase request.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('purchase-request.form', compact('suppliers'));
    }

    /**
     * Store a new purchase request and its items.
     */
    public function store(Request $request)
    {
        $request->validate([
            'request_date'          => 'required|date',
            'supplier_id'          => 'required|exists:suppliers,id',
            'items'                 => 'required|array|min:1',
            'items.*.product_name'  => 'required|string',
            'items.*.item_id'       => 'required|exists:items,id',
            'items.*.supplier_product_id' => 'nullable|exists:supplier_products,id',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.unit'          => 'required|string',
            'description'           => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $pr = PurchaseRequest::create([
                'pr_number'    => $this->generatePRNumber(),
                'requested_by' => Auth::id(),
                'request_date' => $request->request_date,
                'supplier_id'  => $request->supplier_id,
                'status'       => 'pending',
                'description'  => $request->description,
            ]);

            foreach ($request->items as $item) {
                // Get supplier product for price information
                $supplierProduct = SupplierProduct::find($item['supplier_product_id'] ?? null);
                
                PurchaseRequestItem::create([
                    'purchase_request_id' => $pr->id,
                    'item_id'            => $item['item_id'],
                    'supplier_product_id' => $supplierProduct ? $supplierProduct->id : null,
                    'product_name'       => $item['product_name'],
                    'quantity'           => $item['quantity'],
                    'unit'              => $item['unit'],
                    'unit_price'        => $supplierProduct ? $supplierProduct->price : null,
                    'current_stock'     => $item['current_stock'] ?? 0,
                    'min_order'         => $supplierProduct ? $supplierProduct->min_order : 1,
                    'lead_time'         => $supplierProduct ? $supplierProduct->lead_time : null,
                    'notes'             => $item['notes'] ?? null,
                ]);
            }

            DB::commit();
            // Redirect to procurement dashboard after creating PR
            return redirect()->route('procurement.index')->with('success', 'Permintaan pembelian berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan permintaan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified purchase request.
     */
    public function show($id)
    {
        $pr = PurchaseRequest::with('items', 'requester')->findOrFail($id);
        return view('purchase-request.show', compact('pr'));
    }

    /**
     * Validate and approve a purchase request (supervisor action).
     */
    public function approve(Request $request, $id)
    {
        // Check if user is supervisor
        if (Auth::user()->role !== 'supervisor') {
            return back()->withErrors(['error' => 'Hanya supervisor yang dapat menyetujui PR']);
        }

        $request->validate([
            'validation_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'approval_notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            
            $pr = PurchaseRequest::findOrFail($id);
            
            // Store validation document
            $path = $request->file('validation_document')->store('pr-validation-docs', 'public');
            
            $pr->update([
                'status' => 'approved',
                'approval_status' => 'approved',
                'validation_document_path' => $path,
                'is_validated' => true,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'approval_notes' => $request->approval_notes
            ]);

            DB::commit();
            return redirect()->route('new-purchase-orders.index')
                ->with('success', 'Permintaan pembelian berhasil divalidasi dan disetujui');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menyimpan validasi: ' . $e->getMessage()]);
        }
    }

    /**
     * Reject a purchase request with reason (supervisor action).
     */
    public function reject(Request $request, $id)
    {
        // Check if user is supervisor
        if (Auth::user()->role !== 'supervisor') {
            return back()->withErrors(['error' => 'Hanya supervisor yang dapat menolak PR']);
        }

        $request->validate([
            'rejection_reason' => 'required|string|min:10'
        ]);

        try {
            DB::beginTransaction();
            
            $pr = PurchaseRequest::findOrFail($id);
            
            $pr->update([
                'status' => 'rejected',
                'approval_status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'rejection_reason' => $request->rejection_reason
            ]);

            DB::commit();
            return redirect()->route('new-purchase-orders.index')
                ->with('success', 'Permintaan pembelian ditolak');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal menyimpan penolakan: ' . $e->getMessage()]);
        }
    }

    /**
     * Get supplier products with item details
     */
    public function getSupplierItems($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            
            // Get products with eager loading
            $products = SupplierProduct::with('item')
                ->where('supplier_id', $id)
                ->get();
            
            // Log untuk debugging
            Log::info('Supplier products loaded', [
                'supplier_id' => $id,
                'supplier_name' => $supplier->name,
                'product_count' => $products->count()
            ]);

            // Return response with empty data if no products found
            if ($products->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => "Tidak ada produk untuk supplier {$supplier->name}"
                ]);
            }

            // Transform product data
            $data = $products->map(function($product) {
                if (!$product->item) return null;
                
                return [
                    'id' => $product->item->id,
                    'supplier_product_id' => $product->id,
                    'name' => $product->product_name ?: $product->item->name,
                    'code' => $product->item->code ?: '-',
                    'unit' => $product->item->unit ?: 'pcs',
                    'stock' => $product->item->stock ?: 0,
                    'raw_price' => $product->price,
                    'price' => number_format($product->price, 0, ',', '.'),
                    'min_order' => $product->min_order ?: 1,
                    'lead_time' => $product->lead_time ? $product->lead_time . ' hari' : '-',
                ];
            })->filter()->values();

            // Return success response
            return response()->json([
                'success' => true,
                'data' => $data,
                'supplier' => [
                    'id' => $supplier->id,
                    'name' => $supplier->name
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getSupplierItems', [
                'supplier_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data produk: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate sequential PR numbers (format: PR-0001/MM/YYYY).
     */
    protected function generatePRNumber()
    {
        $count = PurchaseRequest::count() + 1;
        $month = date('m');
        $year  = date('Y');
        return sprintf("PR-%04d/%s/%s", $count, $month, $year);
    }
}