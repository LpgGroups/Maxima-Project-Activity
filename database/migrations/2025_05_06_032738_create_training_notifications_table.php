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
        Schema::create('training_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reg_training_id')->constrained('reg_training')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('viewed_at')->nullable(); // null = belum dilihat
            $table->timestamp('last_update_at')->nullable(); // null = belum dilihat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_notifications');
    }
};
