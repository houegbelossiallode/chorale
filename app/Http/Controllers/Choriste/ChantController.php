<?php

namespace App\Http\Controllers\Choriste;

use App\Http\Controllers\Controller;
use App\Models\Chant;
use App\Models\Enregistrement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChantController extends Controller
{
    /**
     * Display a listing of the chants (Musical Library).
     */
    public function index()
    {
        $user = Auth::user();
        $pupitreId = $user->pupitre_id;

        // On récupère tous les chants avec les fichiers filtrés par pupitre
        $chants = Chant::with([
            'fichiers' => function ($query) use ($pupitreId) {
                $query->where(function ($q) use ($pupitreId) {
                    $q->whereNull('pupitre_id') // Ressources pour tous
                        ->orWhere('pupitre_id', $pupitreId); // Ressources pour son pupitre
                });
            }
        ])->get();

        return view('choriste.chants.index', compact('chants'));
    }

    /**
     * Display the specified chant.
     */
    public function show(Chant $chant)
    {
        $user = Auth::user();
        $pupitreId = $user->pupitre_id;

        $chant->load([
            'fichiers' => function ($query) use ($pupitreId) {
                $query->with('pupitre')->where(function ($q) use ($pupitreId) {
                    $q->whereNull('pupitre_id')
                        ->orWhere('pupitre_id', $pupitreId);
                });
            }
        ]);

        return view('choriste.chants.show', compact('chant'));
    }
}
