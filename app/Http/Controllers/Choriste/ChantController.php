<?php

namespace App\Http\Controllers\Choriste;

use App\Http\Controllers\Controller;
use App\Models\Chant;
use App\Models\Enregistrement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CategorieChant;

class ChantController extends Controller
{
    /**
     * Display a listing of the chants (Musical Library).
     */
    public function index()
    {
        // On récupère tous les chants avec tous les fichiers et la catégorie
        $chants = Chant::where('actif', 'OUI')->with([
            'fichiers.pupitre',
            'categorieChant'
        ])->orderBy('updated_at', 'desc')->get();

        $categories = CategorieChant::orderBy('name')->get();

        return view('choriste.chants.index', compact('chants', 'categories'));
    }

    /**
     * Display the specified chant.
     */
    public function show(Chant $chant)
    {
        $chant->load([
            'fichiers.pupitre',
            'categorieChant'
        ]);

        return view('choriste.chants.show', compact('chant'));
    }
}
