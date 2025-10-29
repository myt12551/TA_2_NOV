<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // tambahkan kolom hanya jika belum ada
            if (!Schema::hasColumn('transactions', 'channel')) {
                $table->string('channel')->nullable()->after('payment_method_id');
            }
            if (!Schema::hasColumn('transactions', 'payment_status')) {
                $table->string('payment_status')->nullable()->after('status');
            }
            if (!Schema::hasColumn('transactions', 'pickup_status')) {
                $table->string('pickup_status')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('transactions', 'pickup_code')) {
                $table->string('pickup_code')->nullable()->after('pickup_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // hapus kolom jika ada
            if (Schema::hasColumn('transactions', 'channel')) {
                $table->dropColumn('channel');
            }
            if (Schema::hasColumn('transactions', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('transactions', 'pickup_status')) {
                $table->dropColumn('pickup_status');
            }
            if (Schema::hasColumn('transactions', 'pickup_code')) {
                $table->dropColumn('pickup_code');
            }
        });
    }
};
