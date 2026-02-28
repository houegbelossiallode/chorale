<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modules = \App\Models\Module::all();
        $menus = Menu::with('module')->latest()->paginate(10);
        return view('admin.menus.index', compact('menus', 'modules'));
    }

    public function create()
    {
        $modules = \App\Models\Module::all();
        return view('admin.menus.create', compact('modules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'module_id' => 'required|exists:modules,id'
        ]);
        Menu::create($validated);
        return back()->with('success', 'Menu créé.');
    }

    public function edit(Menu $menu)
    {
        $modules = \App\Models\Module::all();
        return view('admin.menus.edit', compact('menu', 'modules'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'module_id' => 'required|exists:modules,id'
        ]);
        $menu->update($validated);
        return back()->with('success', 'Menu mis à jour.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return back()->with('success', 'Menu supprimé.');
    }
}
