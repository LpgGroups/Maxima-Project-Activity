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
        Schema::create('carrouselads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->string('image'); // bisa URL atau path ke storage
            $table->string('url')->nullable();
            $table->unsignedBigInteger('carousel_id')->nullable(); // untuk relasi di masa depan
            $table->boolean('is_active')->default(true); // untuk menandai apakah carousel aktif
            $table->integer('order')->default(0); // untuk urutan tampil (sorting)
            $table->timestamps();

            // Optional: Jika nanti kamu pakai relasi ke tabel lain
            // $table->foreign('carousel_id')->references('id')->on('carousels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrouselads');
    }
};
