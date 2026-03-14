<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sondage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SondageController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'repetition_id' => 'nullable|exists:repetitions,id',
            'choix' => 'required|in:oui,non,peut-etre',
        ]);

        if (!($validated['event_id'] ?? null) && !($validated['repetition_id'] ?? null)) {
            return response()->json(['message' => 'Un identifiant d\'événement ou de répétition est requis'], 422);
        }

        $userId = Auth::id();

        $match = ['user_id' => $userId];
        if ($validated['event_id'] ?? null) {
            $match['event_id'] = $validated['event_id'];
        }
        else {
            $match['repetition_id'] = $validated['repetition_id'];
        }

        $sondage = Sondage::updateOrCreate(
            $match,
        ['choix' => $validated['choix']]
        );

        return response()->json([
            'status' => 'success',
            'data' => $sondage
        ]);
    }
}
