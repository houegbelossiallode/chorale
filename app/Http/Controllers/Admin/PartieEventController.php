<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartieEvent;
use Illuminate\Http\Request;

class PartieEventController extends Controller
{
    public function index()
    {
        $parties = PartieEvent::orderBy('ordre')->get();
        return view('admin.config.parts.index', compact('parties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'ordre' => 'required|integer',
        ]);

        PartieEvent::create($validated);

        return back()->with('success', 'Partie configurée avec succès.');
    }

    public function update(Request $request, PartieEvent $partieEvent)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'ordre' => 'required|integer',
        ]);

        $partieEvent->update($validated);

        return back()->with('success', 'Partie mise à jour.');
    }

    public function destroy(PartieEvent $partieEvent)
    {
        $partieEvent->delete();
        return back()->with('success', 'Partie supprimée.');
    }
}
