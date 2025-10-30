<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek apakah kolom username sudah ada
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->unique()->after('email');
            }
            
            // Cek apakah kolom picture sudah ada
            if (!Schema::hasColumn('users', 'picture')) {
                $table->string('picture')->nullable()->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hanya drop kolom jika mereka ada
            if (Schema::hasColumn('users', 'username')) {
                $table->dropColumn('username');
            }
            if (Schema::hasColumn('users', 'picture')) {
                $table->dropColumn('picture');
            }
        });
    }
};