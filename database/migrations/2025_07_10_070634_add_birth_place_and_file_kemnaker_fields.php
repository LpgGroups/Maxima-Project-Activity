<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan 'birth_place' ke tabel reg_participants
        Schema::table('reg_participants', function (Blueprint $table) {
            $table->string('birth_place')->nullable()->after('nik');
        });

        // Tambahkan 'file_kemnaker' ke tabel reg_training
        Schema::table('file_requirements', function (Blueprint $table) {
            $table->string('file_nobatch')->nullable()->after('letter_implementation');
        });
    }

    public function down(): void
    {
        // Drop kedua kolom jika di-rollback
        Schema::table('reg_participants', function (Blueprint $table) {
            $table->dropColumn('birth_place');
        });

        Schema::table('file_requirements', function (Blueprint $table) {
            $table->dropColumn('file_nobatch');
        });
    }
};
