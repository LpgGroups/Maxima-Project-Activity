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
            $table->string('file_approval')->nullable();
            $table->string('proof_payment')->nullable();
            $table->string('budget_plan')->nullable();
            $table->string('letter_implementation')->nullable();
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
