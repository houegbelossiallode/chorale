<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Event;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = strtolower($user->role->libelle ?? '');

        return match (true) {
                str_contains($role, 'admin') || str_contains($role, 'administrateur') => $this->adminDashboard(),
                str_contains($role, 'choriste') => $this->choristeDashboard($user),
                default => $this->choristeDashboard($user),
            };
    }

    /**
     * Dashboard Admin — stats globales.
     */
    private function adminDashboard()
    {
        $totalRecettes = \App\Models\TransactionFinanciere::where('type', 'recette')->sum('montant');
        $totalDepenses = \App\Models\TransactionFinanciere::where('type', 'depense')->sum('montant');

        // Calcul du taux de présence Global (Réel)
        $presenceStats = \App\Models\Presence::selectRaw('COUNT(*) as total, SUM(CASE WHEN status = \'present\' THEN 1 ELSE 0 END) as presentes')->first();
        $presenceRate = $presenceStats->total > 0 ? round(($presenceStats->presentes / $presenceStats->total) * 100) : 100;

        $stats = [
            'my_events_count' => Event::where('start_at', '>=', now())->count(),
            'presence_rate' => $presenceRate,
            'next_event' => Event::where('start_at', '>=', now())->orderBy('start_at')->first(),
            'total_members' => User::count(),
            'upcoming_events' => Event::where('start_at', '>=', now())->count(),
            'total_posts' => Post::count(),
            'latest_members' => User::latest()->take(5)->get(),
            'total_recettes' => $totalRecettes,
            'total_depenses' => $totalDepenses,
            'solde' => ($caisse = \App\Models\Caisse::where('nom', 'Caisse Principale')->first()) ? $caisse->solde : ($totalRecettes - $totalDepenses),
        ];

        // Distribution des présences pour le graphique (Remplace les finances)
        $presenceDist = \App\Models\Presence::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderByRaw("CASE WHEN status = 'present' THEN 1 WHEN status = 'absent' THEN 2 ELSE 3 END")
            ->get();

        $chartData = [
            'labels' => $presenceDist->map(fn($p) => ucfirst($p->status)),
            'data' => $presenceDist->map(fn($p) => $p->total),
        ];

        return view('dashboard.admin', compact('stats', 'chartData'));
    }

    /**
     * Dashboard Choriste — agenda, répétitions, absences.
     */
    private function choristeDashboard($user)
    {
        $role = $user->role;
        $pupitre = $user->pupitre;

        // Taux de présence (Logique identique au Mobile)
        $presences = $user->presences()
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN status = \'present\' THEN 1 ELSE 0 END) as presentes')
            ->first();

        $tauxPresence = ($presences && $presences->total > 0)
            ? round(($presences->presentes / $presences->total) * 100)
            : 100;

        // Dernière présence (Remplace la série simulée)
        $lastPresence = $user->presences()
            ->join('repetitions', 'presences.repetition_id', '=', 'repetitions.id')
            ->orderBy('repetitions.start_time', 'desc')
            ->select('presences.*')
            ->first();

        $choristeStats = [
            'next_rehearsals' => \App\Models\Repetition::with(['repertoires.chant', 'repertoires.partieEvent'])
            ->where('start_time', '>=', now())
            ->orderBy('start_time')
            ->take(3)
            ->get(),
            'monthly_events' => Event::whereMonth('start_at', now()->month)
            ->whereYear('start_at', now()->year)
            ->orderBy('start_at')
            ->get(),
            'recent_announcements' => Post::latest()
            ->take(5)
            ->get(),
            'my_presence_rate' => $tauxPresence,
            'pupitre' => $pupitre,
            'pupitre_members' => $pupitre ?User::where('pupitre_id', $pupitre->id)->where('id', '!=', $user->id)->take(5)->get() : collect(),
            'latest_chants' => \App\Models\Chant::latest()->take(4)->get(),
            'upcoming_birthdays' => User::whereMonth('date_naissance', now()->month)
            ->orWhereMonth('date_naissance', now()->addMonth()->month)
            ->take(3)
            ->get(),
            'total_chants_count' => \App\Models\Chant::count(),
            'last_presence' => $lastPresence,
            'notifications' => $user->notifications->take(5),
        ];

        return view('dashboard.choriste', compact('choristeStats'));
    }
}
