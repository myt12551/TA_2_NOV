<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        $requests = PurchaseRequest::with(['requester', 'supplier'])
            ->latest()
            ->get();
        return view('purchase-request.index', compact('requests'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('purchase-request.create', compact('suppliers'));
    }

    public function getSupplierItems(Supplier $supplier)
    {
        try {
            $products = $supplier->products()->get();
            
            // Debug log
            \Log::info('Fetching supplier products', [
                'supplier_id' => $supplier->id,
                'product_count' => $products->count(),
                'products' => $products->toArray()
            ]);
            
            $formattedProducts = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product_name' => $product->product_name
                ];
            });

            return response()->json($formattedProducts);
        } catch (\Exception $e) {
            \Log::error('Error fetching supplier products', [
                'supplier_id' => $supplier->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => 'Gagal mengambil data produk supplier: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'request_date' => 'required|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.supplier_product_id' => 'required|exists:supplier_products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit' => 'required|string|in:pcs,box,kg',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Create Purchase Request
            $pr = PurchaseRequest::create([
                'pr_number' => $this->generatePRNumber(),
                'requested_by' => Auth::id(),
                'request_date' => $request->request_date,
                'supplier_id' => $request->supplier_id,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

                        // Create Purchase Request Items
            foreach ($request->items as $item) {
                $product = SupplierProduct::findOrFail($item['supplier_product_id']);
                // Get current stock from Items table
                $currentStock = Item::where('name', $product->product_name)->value('stock') ?? 0;
                
                PurchaseRequestItem::create([
                    'purchase_request_id' => $pr->id,
                    'product_name' => $product->product_name,
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'current_stock' => $currentStock,
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('procurement.index')
                ->with('success', 'Permintaan pembelian berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan permintaan: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $pr = PurchaseRequest::with(['items', 'requester', 'supplier'])
            ->findOrFail($id);
        return view('purchase-request.show', compact('pr'));
    }

    public function approve(Request $request, $id)
    {
        if (Auth::user()->role !== 'supervisor') {
            return back()->withErrors(['error' => 'Hanya supervisor yang dapat menyetujui PR']);
        }

        $request->validate([
            'approval_notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            
            $pr = PurchaseRequest::findOrFail($id);
            
            $pr->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'approval_notes' => $request->approval_notes
            ]);

            DB::commit();
            return redirect()->route('procurement.index')
                ->with('success', 'Permintaan pembelian berhasil disetujui');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan persetujuan: ' . $e->getMessage()]);
        }
    }

    public function reject(Request $request, $id)
    {
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
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'rejection_reason' => $request->rejection_reason
            ]);

            DB::commit();
            return redirect()->route('procurement.index')
                ->with('success', 'Permintaan pembelian ditolak');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan penolakan: ' . $e->getMessage()]);
        }
    }

    protected function generatePRNumber()
    {
        $count = PurchaseRequest::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count() + 1;
            
        return sprintf("PR-%04d/%s/%s", 
            $count,
            now()->format('m'),
            now()->format('Y')
        );
    }
}