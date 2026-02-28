<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Module;
use App\Models\SousMenu;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|unique:roles,libelle|max:255',
        ]);

        Role::create($validated);

        return redirect()->route('admin.roles.index')->with('success', 'Rôle créé avec succès.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $modules = Module::with(['menus.sousMenus'])->get();
        $permissions = RolePermission::where('role_id', $role->id)->pluck('is_granted', 'sous_menu_id')->toArray();
        
        return view('admin.roles.edit', compact('role', 'modules', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'libelle' => 'required|string|max:255|unique:roles,libelle,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:sous_menus,id',
        ]);

        $role->update(['libelle' => $request->libelle]);

        // Reset permissions
        RolePermission::where('role_id', $role->id)->update(['is_granted' => false]);

        // Set granted permissions
        if ($request->has('permissions')) {
            foreach ($request->permissions as $smId) {
                RolePermission::updateOrCreate(
                    ['role_id' => $role->id, 'sous_menu_id' => $smId],
                    ['is_granted' => true]
                );
            }
        }

        return redirect()->route('admin.roles.index')->with('success', 'Rôle et permissions mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        
    }
}
