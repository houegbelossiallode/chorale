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
        $enregistrements = Enregistrement::with(['user.pupitre', 'chant'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.enregistrements.index', compact('enregistrements'));
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
