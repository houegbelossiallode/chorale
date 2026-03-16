<?php

namespace App\Http\Controllers\Choriste;

use App\Http\Controllers\Controller;
use App\Models\Sondage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SondageController extends Controller
{
    /**
     * Enregistre ou met à jour le choix d'un choriste pour une répétition ou un événement.
     */
    public function vote(Request $request)
    {
        $request->validate([
            'choix' => 'required|string|in:oui,non,peut-etre',
            'repetition_id' => 'nullable|exists:repetitions,id',
            'event_id' => 'nullable|exists:events,id',
        ]);

        if (!$request->repetition_id && !$request->event_id) {
            return response()->json(['message' => 'ID de répétition ou d\'événement requis'], 400);
        }

        $userId = Auth::id();

        $sondage = Sondage::updateOrCreate(
        [
            'user_id' => $userId,
            'repetition_id' => $request->repetition_id,
            'event_id' => $request->event_id,
        ],
        [
            'choix' => $request->choix,
        ]
        );

        return response()->json([
            'status' => 'success',
            'sondage' => $sondage,
            'message' => 'Votre choix a été enregistré.'
        ]);
    }
}
