<?php

namespace App\Http\Controllers\Choriste;

use App\Http\Controllers\Controller;
use App\Models\Repetition;
use App\Models\Repertoire;
use App\Models\Pupitre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepetitionController extends Controller
{
    /**
     * Display a listing of rehearsals for choristers.
     */
    public function index()
    {
        $repetitions = Repetition::orderBy('updated_at', 'desc')->with(['repertoires.chant', 'repertoires.partieEvent'])
            ->withCount('presences')
            // ->where('start_time', '>=', now()->startOfDay())
            // ->orderBy('start_time', 'desc')
            ->paginate(6);

        return view('choriste.repetitions.index', compact('repetitions'));
    }

    /**
     * Display the specific repertoire for a rehearsal.
     */
    public function repertoire(Repetition $repetition)
    {

        // 1. Charger les répertoires liés directement à cette répétition
        $repertoire = $repetition->repertoires()->with([
            'chant.fichiers',
            'partieEvent',
            'enregistrements' => function ($q) {
            $q->where('user_id', Auth::id());
        }
        ])
            ->get();

        // Determine if all chants in the repertoire are fully recorded by the user
        $isFullyRecorded = true;
        foreach ($repertoire as $item) {
            if ($item->chant && $item->chant->fichiers->count() > 0) {
                $recordedCount = $item->enregistrements->count();
                $expectedCount = $item->chant->fichiers->count();
                if ($recordedCount < $expectedCount) {
                    $isFullyRecorded = false;
                    break;
                }
            }
            else {
            // If a chant has no files, it's considered "fully recorded" for this check
            // Or if the repertoire item doesn't have a chant, it's not relevant for recording status
            }
        }

        // Fetch pupitres for the chorale composition section
        $pupitres = Pupitre::with('users')->get();

        // Get current user's poll choice
        $userSondage = \App\Models\Sondage::where('user_id', Auth::id())
            ->where('repetition_id', $repetition->id)
            ->first();

        return view('choriste.repetitions.repertoire', compact('repetition', 'repertoire', 'isFullyRecorded', 'pupitres', 'userSondage'));
    }
}
