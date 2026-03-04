<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SousMenu;
use App\Models\RolePermission;
use App\Models\Role;

echo "--- FINALISATION DU MENU CHORISTE ---\n";

// 1. Trouver le parent (Menu) des répétitions
$smAdmin = SousMenu::where('url', 'admin.repetitions.index')->first();
if (!$smAdmin) {
    echo "ERREUR: Sous-menu admin non trouvé\n";
    exit;
}
$menuId = $smAdmin->menu_id;

// 2. Créer ou mettre à jour le sous-menu choriste
$newSm = SousMenu::updateOrCreate(
    ['url' => 'choriste.repetitions.index'],
    [
        'menu_id' => $menuId,
        'name' => 'Mon Agenda Répétitions',
        'icone' => 'calendar-event',
        'is_show' => 'OUI',
        'actif' => 'OUI',
        'ordre' => 1
    ]
);
echo "Sous-menu choriste configuré (ID: " . $newSm->id . ")\n";

// 3. Accorder la permission au rôle choriste
$role = Role::where('libelle', 'like', '%choriste%')->first();
if ($role) {
    RolePermission::updateOrCreate(
        ['role_id' => $role->id, 'sous_menu_id' => $newSm->id],
        ['is_granted' => true]
    );
    echo "Permission accordée au rôle: " . $role->libelle . "\n";
} else {
    echo "AVERTISSEMENT: Rôle choriste non trouvé par libellé.\n";
}

echo "--- TERMINÉ ---\n";
