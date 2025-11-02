<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\SupplierProduct;

class SupplierProductSeeder extends Seeder
{
    public function run()
    {
        // Get all suppliers and items
        $suppliers = Supplier::all();
        $items = Item::all();
        
        // For each supplier, assign some random items
        foreach ($suppliers as $supplier) {
            // Get 5 random items for this supplier
            $randomItems = $items->random(min(5, $items->count()));
            
            foreach ($randomItems as $item) {
                SupplierProduct::create([
                    'supplier_id' => $supplier->id,
                    'item_id' => $item->id,
                    'product_name' => $item->name,
                    'price' => rand(10000, 1000000),
                    'min_order' => rand(1, 10),
                    'lead_time' => rand(1, 14)
                ]);
            }
        }
    }
}