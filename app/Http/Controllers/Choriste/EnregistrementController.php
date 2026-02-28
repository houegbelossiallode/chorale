<?php

namespace App\Http\Controllers\Choriste;

use App\Http\Controllers\Controller;
use App\Models\Enregistrement;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EnregistrementController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    /**
     * Store a new recording.
     */
    public function store(Request $request)
    {
        $request->validate([
            'chant_id' => 'required|exists:chants,id',
            'audio' => 'required|file|mimes:webm,mp3,wav',
        ]);

        $user = Auth::user();
        $file = $request->file('audio');

        // Path: recordings/user_{id}/chant_{id}/recording_{uniqid}.webm
        $filename = 'recording-' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = "recordings/user_{$user->id}/chant_{$request->chant_id}/{$filename}";

        $filePath = $this->supabase->uploadFile('imgs', $path, $file);

        if ($filePath) {
            Enregistrement::create([
                'user_id' => $user->id,
                'chant_id' => $request->chant_id,
                'file_path' => $filePath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Enregistrement sauvegardé avec succès.',
                'path' => $filePath
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Échec de l\'upload vers Supabase.'
        ], 500);
    }
}
