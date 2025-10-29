<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('analysis_period')->default(30); // in days
            $table->decimal('fast_moving_threshold', 8, 2)->default(3.0);
            $table->decimal('slow_moving_threshold', 8, 2)->default(0.5);
            $table->integer('lead_time_days')->default(5);
            $table->integer('safety_stock_days')->default(2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_settings');
    }
};