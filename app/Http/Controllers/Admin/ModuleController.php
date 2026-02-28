<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modules = Module::with('menus.sousMenus')->get();
        return view('admin.config.navigation', compact('modules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        Module::create($validated);
        return back()->with('success', 'Module créé.');
    }

    public function update(Request $request, Module $module)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $module->update($validated);
        return back()->with('success', 'Module mis à jour.');
    }

    public function destroy(Module $module)
    {
        $module->delete();
        return back()->with('success', 'Module supprimé.');
    }
}
