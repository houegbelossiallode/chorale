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
        Schema::table('enregistrements', function (Blueprint $table) {
            $table->foreignId('repertoire_id')->nullable()->after('chant_id')->constrained('repertoire')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enregistrements', function (Blueprint $table) {
            $table->dropForeign(['repertoire_id']);
            $table->dropColumn('repertoire_id');
        });
    }
};
