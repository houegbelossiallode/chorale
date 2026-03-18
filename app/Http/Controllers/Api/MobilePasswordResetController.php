<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\MobilePasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MobilePasswordResetController extends Controller
{
    protected string $supabaseUrl;
    protected string $serviceKey;

    public function __construct()
    {
        $this->supabaseUrl = config('services.supabase.url');
        $this->serviceKey = config('services.supabase.service_key');
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'L\'adresse email fournie est invalide.',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        // Pour des raisons de sécurité, on ne confirme pas si l'utilisateur n'existe pas
        if (!$user) {
            return response()->json([
                'status' => 'success',
                'message' => 'Si cet email est associé à un compte, un nouveau mot de passe vous a été envoyé.',
            ]);
        }

        // 1. Générer un nouveau mot de passe aléatoire (par ex. 10 caractères)
        $newPassword = Str::random(6);

        // 2. Mettre à jour Supabase via l'API Admin
        if (!$user->supabase_id) {
            $listResponse = Http::withHeaders([
                'apikey' => $this->serviceKey,
                'Authorization' => 'Bearer ' . $this->serviceKey,
            ])->get($this->supabaseUrl . '/auth/v1/admin/users', [
                'email' => $user->email
            ]);

            if ($listResponse->successful()) {
                $users = $listResponse->json('users') ?? $listResponse->json();
                $targetUser = collect($users)->firstWhere('email', $user->email);
                if ($targetUser) {
                    $user->supabase_id = $targetUser['id'];
                    $user->save(); // Save locally so we don't need to fetch again next time
                }
            }

       
        }

        $targetId = $user->supabase_id;

        /** @var \Illuminate\Http\Client\Response $response */
        $response = Http::withHeaders([
            'apikey' => $this->serviceKey,
            'Authorization' => 'Bearer ' . $this->serviceKey,
            'Content-Type' => 'application/json',
        ])->put($this->supabaseUrl . '/auth/v1/admin/users/' . $targetId, [
            'password' => $newPassword,
        ]);

        if ($response->failed()) {
            Log::error('Supabase password update failed in Mobile Reset', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Impossible de réinitialiser le mot de passe pour le moment. Veuillez réessayer.'
            ], 500);
        }

        // 3. Mettre à jour la base de données locale (Laravel)
        $user->password = Hash::make($newPassword);
        $user->must_change_password = \Illuminate\Support\Facades\DB::raw('true'); // Workaround PostgreSQL boolean cast
        $user->save();

        // 4. Envoyer l'email
        try {
            Mail::to($user->email)->send(new MobilePasswordResetMail($newPassword));
        }
        catch (\Exception $e) {
            Log::error('Failed to send mobile reset password email', ['error' => $e->getMessage()]);
            // On peut quand même retourner un succès si le mot de passe a été changé, 
            // mais idéalement il faut avertir l'utilisateur.
            return response()->json([
                'status' => 'error',
                'message' => 'Le mot de passe a été réinitialisé mais l\'email n\'a pas pu être envoyé.',
                'error_detail' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Un nouveau mot de passe a été envoyé à votre adresse email.',
        ]);
    }
}
