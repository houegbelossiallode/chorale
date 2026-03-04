<?php

namespace App\Http\Controllers\Choriste;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Repertoire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index()
    {
        // On affiche les événements publics futurs
        $events = Event::where('is_public', DB::raw('true'))
            ->where('start_at', '>=', now()->subDays(7))
            ->orderBy('start_at', 'asc')
            ->get();

        return view('choriste.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $repertoire = Repertoire::with([
            'chant.fichiers',
            'partieEvent',
            'enregistrements' => function ($q) {
                $q->where('user_id', Auth::id());
            }
        ])
            ->where('repertoire.event_id', $event->id)
            ->leftJoin('partie_events', 'repertoire.partie_event_id', '=', 'partie_events.id')
            ->select('repertoire.*')
            ->orderBy('partie_events.ordre')
            ->get();

        $pupitres = \App\Models\Pupitre::with('users')->get();

        return view('choriste.events.repertoire', compact('event', 'repertoire', 'pupitres'));
    }
}