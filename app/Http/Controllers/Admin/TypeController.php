<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Type;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::withCount('events')->get();
        return view('admin.events.types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255|unique:types,libelle',
        ]);

        Type::create($validated);

        return redirect()->route('admin.events.types.index')
            ->with('success', 'Type d\'événement créé avec succès.');
    }

    public function update(Request $request, Type $type)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255|unique:types,libelle,' . $type->id,
        ]);

        $type->update($validated);

        return redirect()->route('admin.events.types.index')
            ->with('success', 'Type d\'événement mis à jour avec succès.');
    }

    public function destroy(Type $type)
    {
        if ($type->events()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer ce type car il est lié à des événements.');
        }

        $type->delete();

        return redirect()->route('admin.events.types.index')
            ->with('success', 'Type d\'événement supprimé avec succès.');
    }
}
