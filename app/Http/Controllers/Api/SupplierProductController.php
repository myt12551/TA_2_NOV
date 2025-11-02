<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupplierProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Log::info('Incoming request to SupplierProductController', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ajax' => $request->ajax()
            ]);
            return $next($request);
        });
    }
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            Log::info('Incoming request to SupplierProductController', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ajax' => $request->ajax()
            ]);
            return $next($request);
        });
{
    /**
     * Get all items associated with a supplier
     */
    public function index(Supplier $supplier)
    {
        try {
            Log::info('Fetching products for supplier', [
                'supplier_id' => $supplier->id,
                'supplier_name' => $supplier->name
            ]);
            
            // Get products with eager loading of both item and supplier
            $products = SupplierProduct::with(['item.category', 'supplier'])
                ->where('supplier_id', $supplier->id)
                ->get();
            
            Log::info('Found products', [
                'count' => $products->count(),
                'supplier_id' => $supplier->id
            ]);

            if ($products->isEmpty()) {
                return response()->json([
                    'message' => 'No products found for this supplier',
                    'supplier_id' => $supplier->id,
                    'supplier_name' => $supplier->name,
                    'data' => []
                ], 404);
            }

            $mappedProducts = $products->map(function ($product) {
                // Log problematic records for debugging
                if (!$product->item) {
                    Log::warning("Product found without associated item", [
                        'supplier_product_id' => $product->id,
                        'supplier_id' => $product->supplier_id,
                        'product_name' => $product->product_name
                    ]);
                    return null;
                }
                
                return [
                    'id' => $product->item->id,
                    'supplier_product_id' => $product->id,
                    'name' => $product->product_name ?: $product->item->name,
                    'code' => $product->item->code,
                    'unit' => $product->item->unit ?: 'pcs',
                    'stock' => $product->item->stock ?: 0,
                    'raw_price' => $product->price,
                    'price' => number_format($product->price, 0, ',', '.'),
                    'min_order' => $product->min_order ?: 1,
                    'lead_time' => $product->lead_time ? $product->lead_time . ' hari' : '-',
                    'category' => $product->item->category ? $product->item->category->name : null,
                    'supplier_name' => $product->supplier->name
                ];
            })
            ->filter()
            ->values();

            return response()->json([
                'success' => true,
                'message' => 'Products retrieved successfully',
                'supplier' => [
                    'id' => $supplier->id,
                    'name' => $supplier->name
                ],
                'count' => $mappedProducts->count(),
                'data' => $mappedProducts
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching supplier products', [
                'supplier_id' => $supplier->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error fetching supplier products',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}