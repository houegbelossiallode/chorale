<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\SousMenu;
use App\Models\Menu;
use App\Models\RolePermission;

echo "--- CRÉATION DU SOUS-MENU CHORISTE ---\n";

// Trouver le menu parent existant pour les répétitions (ID 17 d'après les listings)
$smAdmin = SousMenu::find(17);
if (!$smAdmin) {
    // Tentons de le trouver par nom si l'ID a changé
    $smAdmin = SousMenu::where('name', 'like', '%épétition%')->first();
}

if (!$smAdmin) {
    echo "ERREUR: Sous-menu admin non trouvé\n";
    exit;
}

$menuId = $smAdmin->menu_id;

// Vérifier si le sous-menu existe déjà pour éviter les doublons
$existing = SousMenu::where('url', 'choriste.repetitions.index')->first();
if ($existing) {
    $newSm = $existing;
    echo "Le sous-menu existe déjà (ID: " . $newSm->id . ")\n";
} else {
    $newSm = SousMenu::create([
        'menu_id' => $menuId,
        'name' => 'Mon Agenda Répétitions',
        'url' => 'choriste.repetitions.index',
        'icone' => 'calendar-event',
        'is_show' => 'OUI',
        'actif' => 'OUI',
        'ordre' => 1
    ]);
    echo "Sous-menu créé (ID: " . $newSm->id . ")\n";
}

// Accorder la permission au rôle choriste (ID 1)
$roleId = 1;
$perm = RolePermission::where('role_id', $roleId)->where('sous_menu_id', $newSm->id)->first();
if (!$perm) {
    RolePermission::create([
        'role_id' => $roleId,
        'sous_menu_id' => $newSm->id,
        'is_granted' => true
    ]);
    echo "Permission accordée au rôle choriste.\n";
} else {
    $perm->update(['is_granted' => true]);
    echo "Permission mise à jour pour le rôle choriste.\n";
}

echo "--- FIN ---\n";
