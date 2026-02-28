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
        Schema::table('presences', function (Blueprint $table) {
            $table->renameColumn('statut', 'status');
            $table->renameColumn('raison', 'motif');
        });

        // After renaming, change the column type to include 'justifie'
        Schema::table('presences', function (Blueprint $table) {
            $table->string('status')->default('present')->change(); // Use string for more flexibility or re-declare enum
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            $table->renameColumn('status', 'statut');
            $table->renameColumn('motif', 'raison');
        });
    }
};
