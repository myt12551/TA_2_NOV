<?php

namespace Database\Seeders;

use App\Models\InventorySetting;
use Illuminate\Database\Seeder;

class InventorySettingsSeeder extends Seeder
{
    public function run(): void
    {
        InventorySetting::create([
            'analysis_period' => 30,
            'fast_moving_threshold' => 3.0,
            'slow_moving_threshold' => 0.5,
            'lead_time_days' => 5,
            'safety_stock_days' => 2,
        ]);
    }
}