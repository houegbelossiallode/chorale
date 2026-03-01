<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieFinanciereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['libelle' => 'Dons', 'type' => 'recette'],
            ['libelle' => 'Cotisations', 'type' => 'recette'],
            ['libelle' => 'Prestations', 'type' => 'recette'],
            ['libelle' => 'Achat partitions', 'type' => 'depense'],
            ['libelle' => 'Déplacements', 'type' => 'depense'],
            ['libelle' => 'Organisation événements', 'type' => 'depense'],
        ];

        foreach ($categories as $category) {
            \App\Models\CategorieFinanciere::updateOrCreate(
                ['libelle' => $category['libelle']],
                ['type' => $category['type']]
            );
        }
    }
}
