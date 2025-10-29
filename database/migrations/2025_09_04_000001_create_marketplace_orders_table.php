<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('marketplace_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('code', 32)->unique();
            $table->string('status', 20)->default('pending'); // pending, picked, cancelled
            $table->string('pickup_name', 100);
            $table->string('phone', 30);
            $table->string('notes', 255)->nullable();
            $table->decimal('total_price', 14, 2)->default(0);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('marketplace_orders');
    }
};
