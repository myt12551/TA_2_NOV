<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create the invoices table.
 *
 * An invoice represents the bill from a supplier for a purchase order.
 * Invoices can be matched against the PO for verification before
 * processing payment.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            // Link back to the purchase order
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->decimal('amount', 12, 2);
            // Status: unpaid, paid, disputed
            $table->string('status')->default('unpaid');
            // File path for uploaded invoice document (PDF, image)
            $table->string('invoice_file')->nullable();
            // Notes or description
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};