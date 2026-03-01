<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CaisseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Caisse::firstOrCreate(
            ['nom' => 'Caisse Principale'],
            ['solde' => 0]
        );
    }
}
