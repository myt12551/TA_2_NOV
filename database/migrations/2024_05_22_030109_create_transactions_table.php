<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('invoice');
            $table->string('invoice_no');
            $table->integer('total');
            $table->integer('discount')->default(0);
            $table->foreignId('payment_method_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('amount')->nullable();
            $table->integer('change')->default(0);
            $table->enum('status', ['paid', 'debt'])->default('paid');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
