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
        Schema::create('sondages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('repetition_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('choix', ['oui', 'non', 'peut-etre'])->default('peut-etre');
            $table->timestamps();

            // Ensure a user can only have one choice per event or repetition
            $table->unique(['user_id', 'event_id']);
            $table->unique(['user_id', 'repetition_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sondages');
    }
};
