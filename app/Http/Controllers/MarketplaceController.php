<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        // Produk terbaru dulu
        $items = Item::query()
            ->select(['id','name','code','category_id','selling_price','stock','picture','created_at'])
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        // Statistik ringkas
        $lowStockThreshold = 5;
        $stats = [
            'total_items' => (int) Item::count(),
            'in_stock'    => (int) Item::where('stock', '>', 0)->count(),
            'low_stock'   => (int) Item::where('stock', '>', 0)->where('stock', '<=', $lowStockThreshold)->count(),
        ];

        // Hitung item di keranjang (session-based)
        $cart = $request->session()->get('marketplace_cart', []);
        $cartCount = array_sum(array_map('intval', $cart));

        return view('marketplace.index', compact(
            'items', 'stats', 'lowStockThreshold', 'cartCount'
        ));
    }

    public function show(\App\Models\Item $item)
    {
        // Debug info untuk gambar
        if (config('app.debug')) {
            Log::info('Item Picture Debug:', [
                'item_id' => $item->id,
                'picture_field' => $item->picture,
                'photo_url' => $item->photo_url,
                'storage_path' => storage_path('app/public/items/' . ($item->picture ?? '')),
                'public_path' => public_path('images/items/' . ($item->picture ?? '')),
            ]);
        }

        return view('marketplace.show', compact('item'));
    }
}
