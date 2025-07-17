<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan 'viewer' ke enum role
        DB::statement("ALTER TABLE users MODIFY role ENUM('management', 'user', 'admin', 'dev', 'viewer') DEFAULT 'user'");
    }

    public function down(): void
    {
        // Rollback ke enum sebelumnya
        DB::statement("ALTER TABLE users MODIFY role ENUM('management', 'user', 'admin', 'dev') DEFAULT 'user'");
    }
};
