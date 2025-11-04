<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\Item;
use Illuminate\Http\Request;

class SupplierProductController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplierProduct::with(['supplier', 'item']);

        // Filter by supplier if provided
        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $supplierProducts = $query->paginate(10);
        $suppliers = Supplier::all();

        return view('supplier-product.index', compact('supplierProducts', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $items = Item::all();
        return view('supplier-product.create', compact('suppliers', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'item_id' => 'required|exists:items,id',
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:1',
            'lead_time' => 'nullable|integer|min:1',
        ]);

        SupplierProduct::create($validated);

        return redirect()->route('supplier-products.index')
            ->with('success', 'Produk supplier berhasil ditambahkan');
    }

    public function edit(SupplierProduct $supplierProduct)
    {
        $suppliers = Supplier::all();
        $items = Item::all();
        return view('supplier-product.edit', compact('supplierProduct', 'suppliers', 'items'));
    }

    public function update(Request $request, SupplierProduct $supplierProduct)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'item_id' => 'required|exists:items,id',
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:1',
            'lead_time' => 'nullable|integer|min:1',
        ]);

        $supplierProduct->update($validated);

        return redirect()->route('supplier-products.index')
            ->with('success', 'Produk supplier berhasil diperbarui');
    }

    public function destroy(SupplierProduct $supplierProduct)
    {
        $supplierProduct->delete();

        return redirect()->route('supplier-products.index')
            ->with('success', 'Produk supplier berhasil dihapus');
    }

    public function getBySupplier(Supplier $supplier)
    {
        $products = $supplier->products()
            ->with('item')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->product_name,
                    'item_name' => $product->item->name,
                    'price' => $product->price,
                    'min_order' => $product->min_order,
                    'lead_time' => $product->lead_time,
                    'unit' => $product->item->unit
                ];
            });

        return response()->json($products);
    }
}