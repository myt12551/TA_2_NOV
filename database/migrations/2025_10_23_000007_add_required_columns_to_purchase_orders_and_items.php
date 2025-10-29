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
        // Menambahkan kolom yang diperlukan ke purchase_orders
        Schema::table('purchase_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_orders', 'purchase_request_id')) {
                $table->foreignId('purchase_request_id')->nullable()->after('supplier_id')->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('purchase_orders', 'total_amount')) {
                $table->decimal('total_amount', 12, 2)->default(0)->after('status');
            }
            if (!Schema::hasColumn('purchase_orders', 'supplier_confirmed')) {
                $table->boolean('supplier_confirmed')->default(false)->after('total_amount');
            }
            if (!Schema::hasColumn('purchase_orders', 'supplier_notes')) {
                $table->text('supplier_notes')->nullable()->after('supplier_confirmed');
            }
            if (!Schema::hasColumn('purchase_orders', 'invoice_image_path')) {
                $table->string('invoice_image_path')->nullable()->after('supplier_notes');
            }
        });

        // Menambahkan kolom yang diperlukan ke purchase_order_items
        Schema::table('purchase_order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('purchase_order_items', 'product_name')) {
                $table->string('product_name')->after('item_id');
            }
            if (!Schema::hasColumn('purchase_order_items', 'unit')) {
                $table->string('unit')->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('purchase_order_items', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->storedAs('quantity * unit_price')->after('unit_price');
            }
            if (!Schema::hasColumn('purchase_order_items', 'notes')) {
                $table->text('notes')->nullable()->after('subtotal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['purchase_request_id']);
            $table->dropColumn([
                'purchase_request_id',
                'total_amount',
                'supplier_confirmed',
                'supplier_notes',
                'invoice_image_path'
            ]);
        });

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropColumn([
                'product_name',
                'unit',
                'subtotal',
                'notes'
            ]);
        });
    }
};