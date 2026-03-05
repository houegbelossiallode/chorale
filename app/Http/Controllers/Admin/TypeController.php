<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Type;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::where('actif','OUI')->withCount('events')->orderBy('updated_at','desc')->get();
        return view('admin.events.types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255|unique:types,libelle',
            'default_image' => 'nullable|string|max:1000',
        ]);
        Type::create($validated);
        return redirect()->route('admin.types.index')->with('success', 'Type d\'événement créé avec succès.');
    }

    public function update(Request $request, Type $type)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255|unique:types,libelle,' . $type->id,
            'default_image' => 'nullable|string|max:1000',
        ]);
        $type->update($validated);
        return redirect()->route('admin.types.index')->with('success', 'Type d\'événement mis à jour avec succès.');
    }

    public function destroy(Type $type)
    {
        // Soft-delete : on désactive simplement le type au lieu de le supprimer
        $type->update(['actif' => 'NON']);
        return redirect()->route('admin.types.index')->with('success', 'Type d\'événement désactivé avec succès.');
    }
}
