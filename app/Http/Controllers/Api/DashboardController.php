<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getStats(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        // Chants appris
        $chantsAppris = \App\Models\Chant::count();

        // Taux de présence
        $totalPresences = $user->presences()->count();
        $presencesPresentes = $user->presences()->where('status', 'present')->count();
        
        $tauxPresence = $totalPresences > 0 
            ? round(($presencesPresentes / $totalPresences) * 100) 
            : 100;

        // Activité récente (Prochain événement)
        $nextEvent = \App\Models\Event::where('start_at', '>=', now())
            ->orderBy('start_at')
            ->first();

        // Chant du moment (aléatoire ou dernier ajouté)
        $chantDuMoment = \App\Models\Chant::inRandomOrder()->first();

        // Derniers chants ajoutés
        $derniersChants = \App\Models\Chant::orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($chant) {
                return [
                    'id' => $chant->id,
                    'title' => $chant->title,
                    'composer' => $chant->composer,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'chants_appris' => $chantsAppris,
                'taux_presence' => $tauxPresence,
                'activite_recente' => $nextEvent ? [
                    'titre' => $nextEvent->title,
                    'jour_heure' => \Carbon\Carbon::parse($nextEvent->start_at)->translatedFormat('l d F \• H:i'),
                    'lieu' => $nextEvent->location ?? 'Non défini',
                    'couleur' => '#7367F0' 
                ] : null,
                'chant_du_moment' => $chantDuMoment ? [
                    'id' => $chantDuMoment->id,
                    'title' => $chantDuMoment->title,
                    'composer' => $chantDuMoment->composer,
                ] : null,
                'derniers_chants' => $derniersChants,
            ]
        ]);
    }
}
