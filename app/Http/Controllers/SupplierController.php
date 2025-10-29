<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
   
    public function index(): View
    {
        return view('inventory.supplier.index', [
            'suppliers' => Supplier::with('products')->orderBy('name')->get(),
            'type' => 'show'
        ]);
    }

    public function create(): View
    {
        return view('inventory.supplier.form', [    
            'type' => 'create'
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'address' => 'required|string',
            'phone' => 'required|numeric',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*' => 'nullable|string|max:255',
        ]);

        $supplier = Supplier::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'description' => $request->description,
        ]);

        // Simpan produk manual jika tersedia
            if ($request->has('products')) {
        foreach ($request->products as $product_name) {
            if (!empty($product_name)) {
                SupplierProduct::create([
                    'supplier_id' => $supplier->id,
                    'product_name' => $product_name
                ]);
            }
        }
    }

        return redirect()->route('supplier.index')->with('status', 'Supplier berhasil ditambahkan');
    }

    public function edit(Supplier $supplier): View
    {
        return view('inventory.supplier.form', [
            'supplier' => $supplier->load('products'),
            'type' => 'edit'
        ]);
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
            'address' => 'required|string',
            'phone' => 'required|numeric',
            'email' => 'nullable|email',
            'description' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*' => 'nullable|string|max:255',
        ]);

        $supplier->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'description' => $request->description,
        ]);

        // Hapus produk lama lalu insert ulang
        SupplierProduct::where('supplier_id', $supplier->id)->delete();

            if ($request->has('products')) {
                foreach ($request->products as $product_name) {
                    if (!empty($product_name)) {
                        SupplierProduct::create([
                            'supplier_id' => $supplier->id,
                            'product_name' => $product_name
                        ]);
                    }
                }
            }


        return redirect()->route('supplier.index')->with('status', 'Supplier berhasil diubah');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->products()->delete();
        $supplier->delete();

        return redirect()->route('supplier.index')->with('status', 'Supplier berhasil dihapus');
    }
}

