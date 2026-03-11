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
        $presencesPresentes = $user->presences()->where('status', 'présent')->count();
        
        $tauxPresence = $totalPresences > 0 
            ? round(($presencesPresentes / $totalPresences) * 100) 
            : 100;

        // Activité récente (Prochain événement)
        $nextEvent = \App\Models\Event::where('start_at', '>=', now())
            ->orderBy('start_at')
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'chants_appris' => $chantsAppris,
                'taux_presence' => $tauxPresence,
                'activite_recente' => $nextEvent ? [
                    'titre' => $nextEvent->title,
                    'jour_heure' => \Carbon\Carbon::parse($nextEvent->start_at)->translatedFormat('l \• H:i'),
                    'lieu' => $nextEvent->location ?? 'Non défini',
                    'couleur' => '#C9A84C' // Peut être dynamique selon le type
                ] : null,
            ]
        ]);
    }
}
