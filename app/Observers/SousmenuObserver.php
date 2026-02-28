<?php

namespace App\Observers;

use App\Models\Sousmenu;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;


class SousmenuObserver
{
    /**
     * Handle the Sousmenu "created" event.
     */
    public function created(Sousmenu $sousmenu): void
    {
            // Récupérer tous les rôles existants
            $roles = Role::all();
            // Parcourir chaque rôle pour créer une permission par défaut
            foreach ($roles as $role) { 
                RolePermission::create([
                    'sous_menu_id' => $sousmenu->id,
                    'role_id' => $role->id,
                    'is_granted'  => DB::raw('FALSE'), 
                ]);
            }
    }

    /**
     * Handle the Sousmenu "updated" event.
     */
    public function updated(Sousmenu $sousmenu): void
    {
        //
    }

    /**
     * Handle the Sousmenu "deleted" event.
     */
    public function deleted(Sousmenu $sousmenu): void
    {
        //
    }

    /**
     * Handle the Sousmenu "restored" event.
     */
    public function restored(Sousmenu $sousmenu): void
    {
        //
    }

    /**
     * Handle the Sousmenu "force deleted" event.
     */
    public function forceDeleted(Sousmenu $sousmenu): void
    {
        //
    }
}
