<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create the goods_receipts table.
 *
 * Goods receipts (GRNs) document the receipt of goods or services against
 * a purchase order. Each receipt can be matched against PO items for
 * quantity verification and quality control.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            // Link back to purchase order
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            // Unique GR number
            $table->string('gr_number')->unique();
            // Date goods were received
            $table->date('receipt_date');
            // User who created the GRN
            $table->foreignId('received_by')->constrained('users')->onDelete('cascade');
            // Status: pending, reviewed
            $table->string('status')->default('pending');
            // Additional notes
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_receipts');
    }
};