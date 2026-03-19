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
        Schema::table('fichier_chants', function (Blueprint $table) {
            $table->longText('content')->nullable();
            $table->string('type')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fichier_chants', function (Blueprint $table) {
            $table->dropColumn('content');
        // Reverting to enum is complex and can lose data if types were added.
        // Keeping it as string is safer for rollbacks in this context.
        });
    }
};
