<?php

namespace App\Observers;

use App\Models\Sousmenu;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
         // Récupérer tous les rôles existants
         $sousmenus = Sousmenu::all();
         foreach ($sousmenus as $sousmenu) { 
             RolePermission::create([
                 'sous_menu_id' => $sousmenu->id,
                 'role_id' => $role->id,
                 'is_granted'  => DB::raw('FALSE'),
             ]);
         }
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        //
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        //
    }

    /**
     * Handle the Role "restored" event.
     */
    public function restored(Role $role): void
    {
        //
    }

    /**
     * Handle the Role "force deleted" event.
     */
    public function forceDeleted(Role $role): void
    {
        //
    }
}
