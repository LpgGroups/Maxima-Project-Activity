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
        // Jika tabel kamu reg_training
        Schema::table('reg_training', function (Blueprint $table) {
            $table->string('provience')->nullable()->after('city');   // atau province/provinsi
            $table->string('address')->nullable()->after('provience');
            $table->string('link')->nullable()->after('address');
        });

        Schema::table('file_requirements', function (Blueprint $table) {
            $table->string('note')->nullable()->after('letter_implementation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reg_training', function (Blueprint $table) {
            $table->dropColumn(['provience', 'address', 'link']);
        });

        Schema::table('file_requirements', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
};
