<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pupitre;
use App\Models\User;
use Illuminate\Http\Request;

class PupitreController extends Controller
{
    public function index()
    {
        $pupitres = Pupitre::with('responsable')->get();
        return view('admin.pupitres.index', compact('pupitres'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.pupitres.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'responsable_id' => 'nullable|exists:users,id',
        ]);

        Pupitre::create($validated);

        return redirect()->route('admin.pupitres.index')
            ->with('success', 'Pupitre créé avec succès.');
    }

    public function edit(Pupitre $pupitre)
    {
        $users = User::all();
        return view('admin.pupitres.edit', compact('pupitre', 'users'));
    }

    public function update(Request $request, Pupitre $pupitre)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'responsable_id' => 'nullable|exists:users,id',
        ]);

        $pupitre->update($validated);

        return redirect()->route('admin.pupitres.index')
            ->with('success', 'Pupitre mis à jour avec succès.');
    }

    public function destroy(Pupitre $pupitre)
    {
        $pupitre->delete();
        return redirect()->route('admin.pupitres.index')
            ->with('success', 'Pupitre supprimé avec succès.');
    }
}
