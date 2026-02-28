<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Event;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $stats = [
            'my_events_count' => Event::where('start_at', '>=', now())->count(),
            'presence_rate' => rand(80, 100),
            'next_event' => Event::where('start_at', '>=', now())->orderBy('start_at')->first(),
            'total_members' => User::count(),
            'upcoming_events' => Event::where('start_at', '>=', now())->count(),
            'total_posts' => Post::count(),
            'latest_members' => User::latest()->take(5)->get(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    /**
     * Dashboard Choriste — agenda, répétitions, absences.
     */
    private function choristeDashboard($user)
    {
        $role = $user->role;
        $pupitre = $user->pupitre;

        $choristeStats = [
            'next_rehearsals' => \App\Models\Repetition::where('start_time', '>=', now())
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
            'my_presence_rate' => $user->presences()->count() > 0
                ? round(($user->presences()->where('status', 'présent')->count() / $user->presences()->count()) * 100)
                : 100,
            'pupitre' => $pupitre,
            'pupitre_members' => $pupitre ? User::where('pupitre_id', $pupitre->id)->where('id', '!=', $user->id)->take(5)->get() : collect(),
            'latest_chants' => \App\Models\Chant::latest()->take(4)->get(),
            'upcoming_birthdays' => User::whereMonth('date_naissance', now()->month)
                ->orWhereMonth('date_naissance', now()->addMonth()->month)
                ->take(3)
                ->get(),
            'total_chants_count' => \App\Models\Chant::count(),
            'attendance_streak' => rand(3, 8), // Simulé pour l'instant
            'notifications' => Post::latest()->take(5)->get(), // Utilisation des posts comme notifications
        ];

        return view('dashboard.choriste', compact('choristeStats'));
    }
}
