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
        // On récupère tous les chants avec tous les fichiers
        $chants = Chant::with([
            'fichiers' => function ($query) {
                $query->with('pupitre');
            }
        ])->get();

        return view('choriste.chants.index', compact('chants'));
    }

    /**
     * Display the specified chant.
     */
    public function show(Chant $chant)
    {
        $chant->load([
            'fichiers' => function ($query) {
                $query->with('pupitre');
            }
        ]);

        return view('choriste.chants.show', compact('chant'));
    }
}
