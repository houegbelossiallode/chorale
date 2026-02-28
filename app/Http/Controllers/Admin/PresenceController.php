<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'repetition_id' => 'required|exists:repetitions,id',
            'status' => 'required|in:present,absent,justifie',
            'motif' => 'nullable|string',
        ]);

        Presence::updateOrCreate(
            ['user_id' => $validated['user_id'], 'repetition_id' => $validated['repetition_id']],
            ['status' => $validated['status'], 'motif' => $validated['motif']]
        );

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Présence mise à jour.');
    }
}
