<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repetition;
use App\Models\User;
use App\Models\Presence;
use App\Models\Chant;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\Mail;
use App\Mail\RepetitionReminderMail;

class RepetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $repetitions = Repetition::orderBy('updated_at', 'desc')->with(['repertoires.chant', 'repertoires.partieEvent'])
            ->withCount('presences')
            ->latest()
            ->paginate(10);
        return view('admin.suivi.repetitions.index', compact('repetitions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'lieu' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Repetition::create($validated);

        return back()->with('success', 'Répétition programmée.');
    }

    public function show(Repetition $repetition)
    {
        // Get all members who should be present
        $members = User::whereHas('role', function ($q) {
            $q->where('libelle', '!=', 'Donateur');
        })->with([
                    'role',
                    'presences' => function ($q) use ($repetition) {
                        $q->where('repetition_id', $repetition->id);
                    }
                ])->get();

        $allChants = Chant::orderBy('title')->get();
        $repetition->load(['repertoires.chant']);

        $events = \App\Models\Event::with(['repertoireEntries.chant', 'repertoireEntries.partieEvent'])
            ->orderBy('start_at', 'desc')
            ->take(30)
            ->get()
            ->map(function ($e) {
                return [
                    'id' => $e->id,
                    'title' => $e->title . ' (' . ($e->start_at ? $e->start_at->format('d/m/Y') : 'Date inconnue') . ')',
                    'repertoire' => $e->repertoireEntries->groupBy(function ($r) {
                        return $r->partieEvent->titre ?? 'Sans partie';
                    })->map(function ($items) {
                        return $items->map(function ($r) {
                            return [
                                'id' => $r->id, // Repertoire ID
                                'chant_id' => $r->chant_id,
                                'title' => $r->chant->title,
                                'composer' => $r->chant->composer
                            ];
                        });
                    })
                ];
            });

        $presences = $members->mapWithKeys(function ($m) use ($repetition) {
            $presence = $m->presences->first(); // On a déjà filtré par repetition_id dans le with
            return [
                $m->id => [
                    'status' => $presence?->status,
                    'motif' => $presence?->motif,
                ],
            ];
        });

        return view('admin.suivi.repetitions.show', compact('repetition', 'members', 'allChants', 'events', 'presences'));
    }

    public function syncChants(Request $request, Repetition $repetition)
    {
        $repetition->repertoires()->sync($request->input('repertoire_ids', []));

        return back()->with('success', 'Programme musical mis à jour.');
    }

    public function update(Request $request, Repetition $repetition)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'lieu' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $repetition->update($validated);

        return back()->with('success', 'Répétition mise à jour.');
    }

    public function destroy(Repetition $repetition)
    {
        $repetition->delete();
        return back()->with('success', 'Répétition annulée.');
    }

    public function automate(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2024',
            'day_of_week' => 'required|integer|between:0,6', // 0: Sunday, 1: Monday, ...
            'start_time' => 'required|string', // HH:mm
            'end_time' => 'required|string', // HH:mm
            'titre' => 'required|string|max:255',
            'lieu' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $startOfMonth = Carbon::createFromDate($validated['year'], $validated['month'], 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $count = 0;
        $currentDate = $startOfMonth->copy();

        while ($currentDate <= $endOfMonth) {
            if ($currentDate->dayOfWeek === (int) $validated['day_of_week']) {
                $start = $currentDate->copy()->setTimeFromTimeString($validated['start_time']);
                $end = $currentDate->copy()->setTimeFromTimeString($validated['end_time']);

                Repetition::create([
                    'titre' => $validated['titre'],
                    'description' => $validated['description'],
                    'lieu' => $validated['lieu'],
                    'start_time' => $start,
                    'end_time' => $end,
                ]);
                $count++;
            }
            $currentDate->addDay();
        }

        return back()->with('success', "$count répétitions ont été programmées pour le mois.");
    }

    public function sendReminder(Repetition $repetition)
    {
        // Récupérer tous les choristes actifs (ceux qui ont un rôle choriste)
        $choristes = User::whereHas('role', function ($query) {
            $query->where('libelle', 'like', '%choriste%');
        })->get();

        if ($choristes->isEmpty()) {
            return back()->with('error', 'Aucun choriste actif trouvé pour recevoir la relance.');
        }

        // Charger les relations nécessaires pour l'email
        $repetition->load(['repertoires.chant', 'repertoires.partieEvent']);

        try {
            // Envoi des mails
            foreach ($choristes as $choriste) {
                if ($choriste->email) {
                    Mail::to($choriste->email)->send(new RepetitionReminderMail($repetition));
                }
            }
            return back()->with('success', 'Relance envoyée avec succès à ' . $choristes->count() . ' chorist(e)s.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi de la relance : ' . $e->getMessage());
        }
    }
}
