<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Module;
use App\Models\Menu;
use App\Models\SousMenu;
use Illuminate\Support\Facades\DB;

class ErpFoundationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Rôles
        $roles = ['Administrateur', 'Responsable Pupitre', 'Choriste', 'Donateur'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['libelle' => $r]);
        }

        // 2. Modules, Menus & Sous-Menus
        $structure = [
            'Configuration' => [
                'Paramètres' => [
                    'Général' => '/admin/config/general',
                    'Structure Navigation' => '/admin/modules'
                ]
            ],
            'Accès' => [
                'Sécurité' => [
                    'Rôles' => '/admin/roles'
                ]
            ],
            'Organisation' => [
                'Membres' => [
                    'Liste des Choristes' => '/admin/members'
                ],
                'Suivi' => [
                    'Présences' => '/admin/repetitions'
                ]
            ],
            'Répertoire' => [
                'Musique' => [
                    'Chants' => '/admin/chants'
                ]
            ],
            'Finances' => [
                'Dons' => [
                    'Projets' => '/admin/projets',
                    'Donations' => '/admin/donations'
                ],
                'Comptabilité' => [
                    'Transactions' => '/admin/transactions',
                    'Catégories' => '/admin/finance-categories'
                ]
            ],
            'Contenu' => [
                'Site Public' => [
                    'Actualités' => '/admin/posts',
                    'Événements' => '/admin/events'
                ]
            ]
        ];

        foreach ($structure as $moduleName => $menus) {
            $module = Module::firstOrCreate(['name' => $moduleName]);

            foreach ($menus as $menuName => $sousMenus) {
                $menu = Menu::firstOrCreate([
                    'name' => $menuName,
                    'module_id' => $module->id
                ]);

                foreach ($sousMenus as $smName => $url) {
                    SousMenu::firstOrCreate([
                        'name' => $smName,
                        'menu_id' => $menu->id,
                        'url' => $url
                    ]);
                }
            }
        }
    }
}
