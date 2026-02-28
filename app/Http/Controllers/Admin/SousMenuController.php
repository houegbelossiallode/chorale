<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SousMenu;
use Illuminate\Http\Request;

class SousMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = \App\Models\Menu::all();
        $sousMenus = SousMenu::with('menu')->latest()->paginate(10);
        return view('admin.sousmenus.index', compact('sousMenus', 'menus'));
    }

    public function create()
    {
        $menus = \App\Models\Menu::all();
        return view('admin.sousmenus.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'menu_id' => 'required|exists:menus,id',
            'url' => 'required|string|max:255'
        ]);
        SousMenu::create($validated);
        return back()->with('success', 'Sous-menu créé.');
    }

    public function edit(SousMenu $sousMenu)
    {
        $menus = \App\Models\Menu::all();
        return view('admin.sousmenus.edit', compact('sousMenu', 'menus'));
    }

    public function update(Request $request, SousMenu $sousMenu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'menu_id' => 'required|exists:menus,id',
            'url' => 'required|string|max:255'
        ]);
        $sousMenu->update($validated);
        return back()->with('success', 'Sous-menu mis à jour.');
    }

    public function destroy(SousMenu $sousMenu)
    {
        $sousMenu->delete();
        return back()->with('success', 'Sous-menu supprimé.');
    }
}
