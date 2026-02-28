<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projets = Projet::withCount('donations')->get();
        return view('admin.finance.projets.index', compact('projets'));
    }

    public function create()
    {
        return view('admin.finance.projets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'objectif' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Projet::create($validated);

        return redirect()->route('admin.finance.projets.index')->with('success', 'Projet créé.');
    }

    public function edit(Projet $projet)
    {
        return view('admin.finance.projets.edit', compact('projet'));
    }

    public function update(Request $request, Projet $projet)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'objectif' => 'required|numeric|min:0',
            'atteint' => 'required|numeric|min:0',
        ]);

        $projet->update($validated);

        return redirect()->route('admin.finance.projets.index')->with('success', 'Projet mis à jour.');
    }

    public function destroy(Projet $projet)
    {
        $projet->delete();
        return back()->with('success', 'Projet supprimé.');
    }
}
