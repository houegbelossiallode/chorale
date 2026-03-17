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
        Schema::table('events', function (Blueprint $table) {
            $table->index('start_at');
            $table->index('actif');
            $table->index('is_public');
        });

        Schema::table('repetitions', function (Blueprint $table) {
            $table->index('start_time');
            $table->index('actif');
        });

        Schema::table('presences', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
        });

        Schema::table('chants', function (Blueprint $table) {
            $table->index('actif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['start_at']);
            $table->dropIndex(['actif']);
            $table->dropIndex(['is_public']);
        });

        Schema::table('repetitions', function (Blueprint $table) {
            $table->dropIndex(['start_time']);
            $table->dropIndex(['actif']);
        });

        Schema::table('presences', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('chants', function (Blueprint $table) {
            $table->dropIndex(['actif']);
        });
    }
};
