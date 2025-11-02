<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Item;
use App\Models\PaymentMethod;
use App\Models\Supplier;
use App\Models\User;
use App\Models\WholesalePrice;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            InventorySettingsSeeder::class,
        ]);
        User::factory(10)->create();

        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('4dm1n@123'),
            'role' => 'owner',
            'picture' => 'profile.jpg'
        ]);

        PaymentMethod::create(['name' => 'Tunai']);
        PaymentMethod::create(['name' => 'Debit']);
        PaymentMethod::create(['name' => 'Kredit']);
        PaymentMethod::create(['name' => 'Transfer']);
        PaymentMethod::create(['name' => 'OVO']);
        PaymentMethod::create(['name' => 'GoPay']);
        PaymentMethod::create(['name' => 'Dana']);
        PaymentMethod::create(['name' => 'QRIS']);

        Item::factory(300)->recycle(Category::factory(20)->create())->create();

        Item::factory()->create([
            'category_id' => 1,
            'code' => '0001',
            'name' => 'Beras',
            'cost_price' => 85000,
            'selling_price' => 120000,
            'stock' => 100
        ]);
        WholesalePrice::create(['item_id' => 301, 'min_qty' => 10, 'price' => 100000]);
        WholesalePrice::create(['item_id' => 301, 'min_qty' => 15, 'price' => 95000]);
        WholesalePrice::create(['item_id' => 301, 'min_qty' => 20, 'price' => 90000]);

        Supplier::factory(25)->create();
        Customer::factory(25)->create();
    }
}
