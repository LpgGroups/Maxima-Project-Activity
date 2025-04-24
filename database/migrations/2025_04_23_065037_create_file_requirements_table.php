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
        Schema::create('file_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('file_mou')->nullable();
            $table->string('file_quotation')->nullable();
            $table->unsignedBigInteger('file_id')->nullable();
            $table->timestamps();
            $table->foreign('file_id')->references('id')->on('reg_training')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_requirements');
    }
};
