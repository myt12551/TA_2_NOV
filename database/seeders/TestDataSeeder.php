<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\SupplierProduct;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create a test supplier if none exists
        $supplier = Supplier::firstOrCreate(
            ['name' => 'Supplier Test'],
            [
                'address' => 'Alamat Test',
                'phone' => '08123456789',
                'email' => 'supplier@test.com'
            ]
        );

        // Create some test items if none exist
        $items = [
            ['name' => 'Item Test 1', 'code' => 'IT001', 'unit' => 'pcs'],
            ['name' => 'Item Test 2', 'code' => 'IT002', 'unit' => 'box'],
            ['name' => 'Item Test 3', 'code' => 'IT003', 'unit' => 'kg'],
        ];

        foreach ($items as $itemData) {
            $item = Item::firstOrCreate(
                ['code' => $itemData['code']],
                [
                    'name' => $itemData['name'],
                    'unit' => $itemData['unit']
                ]
            );

            // Create supplier product for each item
            SupplierProduct::firstOrCreate(
                [
                    'supplier_id' => $supplier->id,
                    'item_id' => $item->id
                ],
                [
                    'product_name' => $itemData['name'] . ' dari ' . $supplier->name
                ]
            );
        }
    }
}