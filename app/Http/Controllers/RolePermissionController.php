<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RolePermission;
use App\Models\SousMenu;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roleid = request('roleid');
        if(empty($roleid)){
            return redirect()->route('hoost.roles.index')->with('error', 'Veuillez selectionner un profil.');
        }
        $role = Role::where('id',$roleid)->where('actif','OUI')->firstOrFail();
        if(!$role){
            return redirect()->route('hoost.roles.index')->with('error', 'Ce profil n\'existe pas');
        }
        $permissions = RolePermission::where('role_id',$roleid)->where('actif','OUI')->get();
        //dd($permissions);
        return view('roles.permissions.index', compact('permissions','role'));
    }

    public function create()
    {
        $roles = Role::latest()->where('actif','OUI')->get();
        $sousmenus = SousMenu::latest()->where('actif','OUI')->get();
        return view('roles.permissions.create', compact('roles','sousmenus'));
    }

    public function store(Request $request)
    {
        try{
        $request->validate([
            'sousmenu_id' => 'required',
            'role_id' => 'required',
        ], [
            'sousmenu_id.required' => "Le champs est requis",
            'role_id.required' => "Le champs est requis",
        ]);

        RolePermission::create([
            'sousmenu_id' => $request->sousmenu_id,
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission creée avec succès.');

    } catch (Exception $e) {
        // Gestion des erreurs : redirection avec un message d'erreur
        return redirect()->route('admin.permissions.index')->with(['error' => 'Une erreur inattendue s\'est produite : ' . $e->getMessage()]);
    }
    
    }


    public function edit(RolePermission $permission)
    {
        $roles = Role::latest()->where('actif','OUI')->get();
        $sousmenus = Sousmenu::latest()->where('actif','OUI')->get();
        return view('roles.permissions.edit', compact('permission', 'roles', 'sousmenus'));
    }

    public function update(Request $request, $roleId)
    {
        $permissions = $request->input('permissions', []);
        DB::table('role_permissions')->where('role_id', $roleId)->update(['is_granted' => DB::raw('false')]);
        foreach ($permissions as $permissionId => $value) {
            DB::table('role_permissions')->where('role_id', $roleId)->where('sousmenu_id', $permissionId)
                ->update(['is_granted' => DB::raw('true')]);
        }
        return redirect()->route('admin.roles.index')->with('success', 'Permissions mises à jour avec succès ✔');
    }
}
