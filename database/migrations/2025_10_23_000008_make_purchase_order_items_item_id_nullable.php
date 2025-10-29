<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom item_id menjadi nullable agar PO bisa menyimpan item baru yang belum terdaftar di master item.
        Schema::table('purchase_order_items', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_order_items', 'item_id')) {
                $table->dropForeign(['item_id']);
            }
        });

        DB::statement('ALTER TABLE purchase_order_items MODIFY item_id BIGINT UNSIGNED NULL');

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        // Hapus data item PO yang tidak memiliki relasi item sebelum mengembalikan kolom menjadi NOT NULL.
        DB::table('purchase_order_items')->whereNull('item_id')->delete();

        DB::statement('ALTER TABLE purchase_order_items MODIFY item_id BIGINT UNSIGNED NOT NULL');

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }
};
