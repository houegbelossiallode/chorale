<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repertoire_repetition', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repetition_id')->constrained()->onDelete('cascade');
            $table->foreignId('repertoire_id')->constrained('repertoire')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('repetitions', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repetitions', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('set null');
        });

        Schema::dropIfExists('repertoire_repetition');
    }
};
