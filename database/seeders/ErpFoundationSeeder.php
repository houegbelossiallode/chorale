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
                    'Général' => '/dashboard/config/general',
                    'Structure Navigation' => '/dashboard/modules'
                ]
            ],
            'Accès' => [
                'Sécurité' => [
                    'Rôles' => '/dashboard/roles'
                ]
            ],
            'Organisation' => [
                'Membres' => [
                    'Liste des Choristes' => '/dashboard/members'
                ],
                'Suivi' => [
                    'Présences' => '/dashboard/repetitions'
                ]
            ],
            'Répertoire' => [
                'Musique' => [
                    'Chants' => '/dashboard/chants'
                ]
            ],
            'Finances' => [
                'Dons' => [
                    'Projets' => '/dashboard/projets',
                    'Donations' => '/dashboard/donations'
                ],
                'Comptabilité' => [
                    'Transactions' => '/dashboard/transactions',
                    'Catégories' => '/dashboard/finance-categories'
                ]
            ],
            'Contenu' => [
                'Site Public' => [
                    'Actualités' => '/dashboard/posts',
                    'Événements' => '/dashboard/events'
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
