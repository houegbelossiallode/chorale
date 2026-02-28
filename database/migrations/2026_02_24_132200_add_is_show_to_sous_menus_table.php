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
        Schema::table('sous_menus', function (Blueprint $table) {
            $table->string('is_show')->default('OUI');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sous_menus', function (Blueprint $table) {
            $table->dropColumn('is_show');
        });
    }
};
