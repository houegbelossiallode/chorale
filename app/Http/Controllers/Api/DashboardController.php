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

        // Taux de présence : (Nombre de fois présent / Nombre total de répétitions) * 100
        $totalRepetitions = \App\Models\Repetition::count();
        $presencesPresentes = $user->presences()->where('status', 'present')->count();

        $tauxPresence = $totalRepetitions > 0
            ? round(($presencesPresentes / $totalRepetitions) * 100)
            : 0; // S'il n'y a aucune répétition, le taux est de 0% (ou 100% selon la politique, mais 0% est plus rigoureux)

        // Activité récente (Prochain événement)
        $currentDate = date('Y-m-d H:i:s');
        $nextEvent = \App\Models\Event::where('start_at', '>=', $currentDate)
            ->where('actif', 'OUI')
            ->orderBy('start_at')
            ->first();

        // Prochaine répétition
        $nextRepetition = \App\Models\Repetition::where('start_time', '>=', $currentDate)
            ->where('actif', 'OUI')
            ->orderBy('start_time')
            ->first();

        // Chant du moment (recommandation du jour, change chaque jour)
        $count = \App\Models\Chant::count();
        $chantDuMoment = null;
        if ($count > 0) {
            $dayOfYear = date('z'); // 0 to 365
            $chantDuMoment = \App\Models\Chant::orderBy('id')->skip($dayOfYear % $count)->first();
        }

        // Derniers chants ajoutés
        $derniersChants = \App\Models\Chant::orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($chant) {
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
                'prochain_evenement' => $nextEvent ? [
                    'titre' => $nextEvent->title,
                    'jour_heure' => \Carbon\Carbon::parse($nextEvent->start_at)->translatedFormat('l d F \• H:i'),
                    'lieu' => $nextEvent->location ?? 'Non défini',
                    'couleur' => '#EA5455'
                ] : null,
                'prochaine_repetition' => $nextRepetition ? [
                    'titre' => $nextRepetition->titre,
                    'jour_heure' => \Carbon\Carbon::parse($nextRepetition->start_time)->translatedFormat('l d F \• H:i'),
                    'lieu' => $nextRepetition->lieu ?? 'Non défini',
                    'couleur' => '#00CFE8'
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
