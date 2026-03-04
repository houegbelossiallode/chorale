<?php

namespace App\Http\Controllers\Choriste;

use App\Http\Controllers\Controller;
use App\Models\Repetition;
use App\Models\Repertoire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepetitionController extends Controller
{
    /**
     * Display a listing of rehearsals for choristers.
     */
    public function index()
    {
        $repetitions = Repetition::with(['event.repertoireEntries.chant', 'event.repertoireEntries.partieEvent', 'chants'])
            ->withCount('presences')
            ->orderBy('start_time', 'desc')
            ->paginate(6);

        return view('choriste.repetitions.index', compact('repetitions'));
    }

    /**
     * Display the specific repertoire for a rehearsal.
     */
    public function repertoire(Repetition $repetition)
    {
        // 1. Charger le répertoire de l'événement lié s'il existe
        $repertoire = collect();
        if ($repetition->event_id) {
            $repertoire = Repertoire::with([
                'chant.fichiers',
                'partieEvent',
                'enregistrements' => function ($q) {
                    $q->where('user_id', Auth::id());
                }
            ])
                ->where('repertoire.event_id', $repetition->event_id)
                ->leftJoin('partie_events', 'repertoire.partie_event_id', '=', 'partie_events.id')
                ->select('repertoire.*')
                ->orderBy('partie_events.ordre')
                ->get();
        }

        // 2. Charger les chants spécifiques à la répétition (ceux qui ne sont pas dans l'agenda)
        $agendaChantIds = $repertoire->pluck('chant_id')->toArray();
        $extraChants = $repetition->chants()
            ->with([
                'fichiers',
                'enregistrements' => function ($q) {
                    $q->where('user_id', Auth::id())
                        ->whereNull('repertoire_id'); // Pour ne pas mélanger avec les enregistrements d'agenda
                }
            ])
            ->whereNotIn('chants.id', $agendaChantIds)
            ->get();

        $pupitres = \App\Models\Pupitre::with('users')->get();

        return view('choriste.repetitions.repertoire', compact('repetition', 'repertoire', 'extraChants', 'pupitres'));
    }
}
