<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enregistrement;
use Illuminate\Http\Request;

class EnregistrementController extends Controller
{
    /**
     * Display a listing of all recordings.
     */
    public function index()
    {
        // On récupère les événements qui ont au moins un enregistrement via leur répertoire
        $events = \App\Models\Event::whereHas('repertoireEntries.enregistrements')
            ->with(['repertoireEntries.enregistrements.user.pupitre', 'repertoireEntries.chant', 'repertoireEntries.partieEvent'])
            ->orderBy('start_at', 'desc')
            ->get();

        // On peut aussi récupérer les enregistrements orphelins (sans répertoire) si nécessaire
        // Mais selon la nouvelle logique, ils devraient tous avoir un repertoire_id
        $orphanEnregistrements = Enregistrement::whereNull('repertoire_id')
            ->with(['user.pupitre', 'chant'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.enregistrements.index', compact('events', 'orphanEnregistrements'));
    }

    /**
     * Add chef feedback to a recording.
     */
    public function feedback(Request $request, Enregistrement $enregistrement)
    {
        $request->validate([
            'chef_comment' => 'required|string',
        ]);

        $enregistrement->update([
            'chef_comment' => $request->chef_comment,
        ]);

        return back()->with('success', 'Commentaire ajouté avec succès.');
    }
}
