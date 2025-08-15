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
        // Ubah kolom enum, tambahkan 'finance'
        DB::statement("ALTER TABLE users MODIFY role ENUM('management', 'user', 'admin', 'dev', 'viewer', 'finance') DEFAULT 'user'");
    }

    public function down(): void
    {
        // Kembalikan ke enum sebelumnya tanpa 'finance'
        DB::statement("ALTER TABLE users MODIFY role ENUM('management', 'user', 'admin', 'dev', 'viewer') DEFAULT 'user'");
    }
};
