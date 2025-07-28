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
        Schema::table('carrouselads', function (Blueprint $table) {
            $table->string('url')->nullable()->after('image'); // kolom url, nullable, diletakkan setelah image
        });
    }

    public function down(): void
    {
        Schema::table('carrouselads', function (Blueprint $table) {
            $table->dropColumn('url');
        });
    }
};
