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
        Schema::table('roles', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });

        Schema::table('chants', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });

        Schema::table('configurations', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });

        Schema::table('pupitres', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });

        Schema::table('projets', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });

        Schema::table('repetitions', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });

        Schema::table('presences', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });


        Schema::table('donateurs', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });


        Schema::table('menus', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });

        Schema::table('sous_menus', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });

        Schema::table('role_permissions', function (Blueprint $table) {
            $table->string('actif')->default('OUI');
        });

       

        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn('actif');
        });

        Schema::table('presences', function (Blueprint $table) {
            $table->dropColumn('actif');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('actif');
        });


        Schema::table('donateurs', function (Blueprint $table) {
            $table->dropColumn('actif');
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('actif');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('actif');
        });

        Schema::table('sous_menus', function (Blueprint $table) {
            $table->dropColumn('actif');
        });

        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropColumn('actif');
        });


    }
};
