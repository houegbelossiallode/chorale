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
        Schema::table('chants', function (Blueprint $table) {
            $table->foreignId('categorie_chant_id')->nullable()->constrained('categorie_chants')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chants', function (Blueprint $table) {
            $table->dropForeign(['categorie_chant_id']);
            $table->dropColumn('categorie_chant_id');
        });
    }
};
