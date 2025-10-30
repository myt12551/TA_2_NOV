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
        // Skip jika kolom item_id belum ada
        if (!Schema::hasColumn('purchase_order_items', 'item_id')) {
            return;
        }

        // Cek apakah kolom sudah nullable
        $column = DB::select("SHOW COLUMNS FROM purchase_order_items WHERE Field = 'item_id'")[0];
        if (strtoupper($column->Null) === 'YES') {
            return; // Skip jika sudah nullable
        }

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
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
        // Skip jika kolom item_id belum ada
        if (!Schema::hasColumn('purchase_order_items', 'item_id')) {
            return;
        }

        // Cek apakah kolom perlu diubah
        $column = DB::select("SHOW COLUMNS FROM purchase_order_items WHERE Field = 'item_id'")[0];
        if (strtoupper($column->Null) === 'NO') {
            return; // Skip jika sudah NOT NULL
        }

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        // Hapus data item PO yang tidak memiliki relasi item sebelum mengembalikan kolom menjadi NOT NULL
        DB::table('purchase_order_items')->whereNull('item_id')->delete();

        DB::statement('ALTER TABLE purchase_order_items MODIFY item_id BIGINT UNSIGNED NOT NULL');

        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }
};
