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
        Schema::create('reg_training', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('name_pic')->nullable();
            $table->string('name_company')->nullable();
            $table->integer('phone_pic')->nullable();
            $table->string('email_pic')->nullable();
            $table->string('activity')->nullable();
            $table->date('date')->nullable();
            $table->string('place')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reg_training');
    }
};
