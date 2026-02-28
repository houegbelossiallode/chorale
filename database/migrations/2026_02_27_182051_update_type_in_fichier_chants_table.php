<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Utilisation de SQL brut car change() est problématique avec les enums sur PostgreSQL
        DB::statement("ALTER TABLE fichier_chants ALTER COLUMN type TYPE VARCHAR(255)");
        DB::statement("ALTER TABLE fichier_chants DROP CONSTRAINT IF EXISTS fichier_chants_type_check");

        DB::table('fichier_chants')->where('type', 'lien_youtube')->update(['type' => 'youtube']);

        DB::statement("ALTER TABLE fichier_chants ADD CONSTRAINT fichier_chants_type_check CHECK (type IN ('partition', 'audio', 'video', 'youtube'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE fichier_chants ALTER COLUMN type TYPE VARCHAR(255)");
        DB::statement("ALTER TABLE fichier_chants DROP CONSTRAINT IF EXISTS fichier_chants_type_check");

        DB::table('fichier_chants')->where('type', 'youtube')->update(['type' => 'lien_youtube']);
        DB::table('fichier_chants')->whereIn('type', ['video'])->delete();

        DB::statement("ALTER TABLE fichier_chants ADD CONSTRAINT fichier_chants_type_check CHECK (type IN ('partition', 'audio', 'lien_youtube'))");
    }
};
