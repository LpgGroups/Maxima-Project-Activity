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
        Schema::create('reg_participants', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('nik')->nullable();
            $table->date('date_birth')->nullable();
            $table->date('blood_type')->nullable();
            $table->string('photo')->nullable();
            $table->string('ijazah')->nullable();
            $table->string('letter_employee')->nullable();
            $table->string('letter_health')->nullable();
            $table->string('cv')->nullable();
            $table->string('reason')->nullable();
            $table->integer('status')->default(0);
            $table->unsignedBigInteger('form_id')->nullable();
            $table->timestamps();
            $table->foreign('form_id')->references('id')->on('reg_training')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reg_participants');
    }
};
