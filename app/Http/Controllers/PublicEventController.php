<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicEventController extends Controller
{
    public function program(Event $event)
    {
        if (!$event->is_repertoire_public) {
            abort(404, 'Ce répertoire n\'est pas public.');
        }

        $repertoire = DB::table('repertoire')
            ->leftJoin('chants', 'repertoire.chant_id', '=', 'chants.id')
            ->leftJoin('partie_events', 'repertoire.partie_event_id', '=', 'partie_events.id')
            ->where('repertoire.event_id', $event->id)
            ->select(
                'repertoire.id',
                'chants.id as chant_id',
                'chants.title as chant_title',
                'chants.composer',
                'partie_events.titre as partie_titre',
                'repertoire.ordre'
            )
            ->orderBy('repertoire.ordre')
            ->get();

        // On peut aussi charger les fichiers pour chaque chant
        foreach ($repertoire as $item) {
            $item->fichiers = DB::table('fichier_chants')
                ->where('chant_id', $item->chant_id)
                ->get();
        }

        return view('public.events.program', compact('event', 'repertoire'));
    }
}
