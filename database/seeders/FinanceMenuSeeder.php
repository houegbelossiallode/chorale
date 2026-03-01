<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinanceMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = \App\Models\Menu::updateOrCreate(
            ['name' => 'Gestion Caisse'],
            ['module_id' => 1, 'actif' => 'OUI']
        );

        \App\Models\SousMenu::updateOrCreate(
            ['url' => 'admin.transactions.index'],
            [
                'name' => 'Journal de Caisse',
                'menu_id' => $menu->id,
                'actif' => 'OUI',
                'is_show' => 'OUI'
            ]
        );

        \App\Models\SousMenu::updateOrCreate(
            ['url' => 'admin.finance-categories.index'],
            [
                'name' => 'Catégories Finances',
                'menu_id' => $menu->id,
                'actif' => 'OUI',
                'is_show' => 'OUI'
            ]
        );
    }
}
