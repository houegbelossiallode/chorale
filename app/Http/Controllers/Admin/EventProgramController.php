<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PartieEvent;
use App\Models\Chant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventProgramController extends Controller
{
    public function index(Event $event)
    {
        // On récupère le répertoire de l'événement avec les chants et les parties associées
        $repertoire = DB::table('repertoire')
            ->leftJoin('chants', 'repertoire.chant_id', '=', 'chants.id')
            ->leftJoin('partie_events', 'repertoire.partie_event_id', '=', 'partie_events.id')
            ->where('repertoire.event_id', $event->id)
            ->select('repertoire.id', 'chants.title as chant_title', 'partie_events.titre as partie_titre', 'repertoire.ordre')
            ->orderBy('repertoire.ordre')
            ->get();

        $allChants = Chant::all();
        $allParties = PartieEvent::orderBy('ordre')->get();

        return view('admin.events.program.index', compact('event', 'repertoire', 'allChants', 'allParties'));
    }

    public function storeRepertoire(Request $request, Event $event)
    {
        $validated = $request->validate([
            'chant_id' => [
                'required',
                'exists:chants,id',
                function ($attribute, $value, $fail) use ($event) {
                    if (DB::table('repertoire')->where('event_id', $event->id)->where('chant_id', $value)->exists()) {
                        $fail('Ce chant est déjà présent dans le programme de cet événement.');
                    }
                },
            ],
            'partie_event_id' => [
                'required',
                'exists:partie_events,id',
                function ($attribute, $value, $fail) use ($event) {
                    if (DB::table('repertoire')->where('event_id', $event->id)->where('partie_event_id', $value)->exists()) {
                        $fail('Cette partie est déjà utilisée dans le programme de cet événement.');
                    }
                },
            ],
        ]);

        $lastOrder = DB::table('repertoire')
            ->where('event_id', $event->id)
            ->max('ordre') ?? 0;

        DB::table('repertoire')->insert([
            'event_id' => $event->id,
            'chant_id' => $validated['chant_id'],
            'partie_event_id' => $validated['partie_event_id'],
            'ordre' => $lastOrder + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Chant ajouté au répertoire.');
    }

    public function toggleVisibility(Event $event)
    {
        $event->update([
            'is_repertoire_public' => (bool) !$event->is_repertoire_public
        ]);

        return back()->with('success', 'Visibilité du répertoire mise à jour.');
    }

    public function destroyRepertoire($id)
    {
        DB::table('repertoire')->where('id', $id)->delete();
        return back()->with('success', 'Élément retiré du répertoire.');
    }
}
