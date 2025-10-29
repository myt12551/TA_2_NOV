<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tambah kolom jika belum ada. Aman untuk proyek eksisting.
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role') && !Schema::hasColumn('users', 'level')) {
                $table->string('role')->default('customer')->after('password');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address', 255)->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        // Revert hanya kolom tambahan kontak; kolom role/level dibiarkan (agar aman)
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone')) $table->dropColumn('phone');
            if (Schema::hasColumn('users', 'address')) $table->dropColumn('address');
        });
    }
};
