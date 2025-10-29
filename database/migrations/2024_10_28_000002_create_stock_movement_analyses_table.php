<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movement_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('total_sold_30_days')->default(0);
            $table->decimal('avg_daily_sales', 8, 2)->default(0);
            $table->enum('movement_status', ['FAST', 'NORMAL', 'SLOW'])->default('NORMAL');
            $table->integer('current_stock')->default(0);
            $table->integer('days_until_empty')->nullable();
            $table->integer('non_moving_days')->default(0);
            $table->decimal('stuck_stock_value', 15, 2)->default(0);
            $table->string('recommendation')->nullable();
            $table->integer('suggested_reorder_qty')->nullable();
            $table->timestamp('last_sale_date')->nullable();
            $table->timestamp('last_analysis_date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movement_analyses');
    }
};