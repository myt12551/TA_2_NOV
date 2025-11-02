<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\SupplierProduct;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Create sample suppliers
        $suppliers = [
            [
                'name' => 'PT Jaya Utama Santikah',
                'address' => 'Jl. Raya Bogor No. 123',
                'phone' => '021-5551234',
                'email' => 'info@jayautama.com',
            ],
            [
                'name' => 'CV Maju Bersama',
                'address' => 'Jl. Gatot Subroto No. 45',
                'phone' => '021-5559876',
                'email' => 'sales@majubersama.com',
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Create sample items
        $items = [
            [
                'name' => 'Pensil 2B',
                'code' => 'ATK001',
                'description' => 'Pensil 2B Staedtler',
                'unit' => 'pcs',
                'stock' => 100,
            ],
            [
                'name' => 'Pulpen Hitam',
                'code' => 'ATK002',
                'description' => 'Pulpen Hitam Snowman',
                'unit' => 'pcs',
                'stock' => 150,
            ],
            [
                'name' => 'Kertas HVS A4',
                'code' => 'ATK003',
                'description' => 'Kertas HVS A4 80 gsm',
                'unit' => 'rim',
                'stock' => 50,
            ]
        ];

        foreach ($items as $item) {
            Item::create($item);
        }

        // Create supplier products
        $supplierProducts = [
            [
                'supplier_id' => 1,
                'item_id' => 1,
                'product_name' => 'Pensil 2B Staedtler',
                'price' => 3500,
                'min_order' => 12,
                'lead_time' => 2
            ],
            [
                'supplier_id' => 1,
                'item_id' => 2,
                'product_name' => 'Pulpen Hitam Snowman',
                'price' => 2500,
                'min_order' => 24,
                'lead_time' => 2
            ],
            [
                'supplier_id' => 2,
                'item_id' => 3,
                'product_name' => 'Kertas HVS A4 Sinar Dunia',
                'price' => 45000,
                'min_order' => 5,
                'lead_time' => 3
            ]
        ];

        foreach ($supplierProducts as $product) {
            SupplierProduct::create($product);
        }
    }
}