<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthSyncController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    /**
     * Synchronise la connexion après une authentification réussie sur Supabase JS
     */
    public function login(Request $request)
    {
        \Log::info('Login sync attempt', ['token' => substr($request->access_token, 0, 10) . '...']);
        $request->validate([
            'access_token' => 'required|string',
        ]);

        // On peut vérifier le token auprès de Supabase si on veut une sécurité maximale
        $supabaseUser = $this->supabase->getUser($request->access_token);
        \Log::info('Supabase user result', ['found' => (bool)$supabaseUser]);

        if (!$supabaseUser) {
            return response()->json(['message' => 'Token invalide'], 401);
        }

        $email = $supabaseUser['email'];
        $user = User::where('email', $email)->orWhere('supabase_id', $supabaseUser['id'])->first();

        if (!$user) {
            // Si l'utilisateur n'existe pas localement, on le crée
            // Supabase stocke souvent les métadonnées sous 'user_metadata'
            $metadata = $supabaseUser['user_metadata'] ?? [];

            $user = User::create([
                'first_name' => $metadata['first_name'] ?? explode('@', $email)[0],
                'last_name' => $metadata['last_name'] ?? '',
                'email' => $email,
                'password' => bcrypt(str()->random(16)),
                'slug' => str(($metadata['first_name'] ?? '') . ' ' . ($metadata['last_name'] ?? ''))->slug() ?: str($email)->slug(),
                'supabase_id' => $supabaseUser['id'],
                'role' => 'choriste',
                'is_active' => true,
            ]);
        }
        elseif (empty($user->supabase_id)) {
            // Update existing user with supabase_id
            $user->update(['supabase_id' => $supabaseUser['id']]);
        }

        // Connecter l'utilisateur dans la session Laravel
        Auth::login($user, true);

        $role = strtolower($user->role->libelle ?? '');

        if ($user->must_change_password) {
            $redirect = route('password.change');
        }
        else {
            $redirect = str_contains($role, 'admin') || str_contains($role, 'administrateur')
                ? route('admin.dashboard')
                : route('choriste.dashboard');
        }

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'redirect' => $redirect
        ]);
    }

    /**
     * Crée le profil local après une inscription Supabase JS
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt(str()->random(16)), // Laravel a besoin d'un mot de passe technique
            'slug' => str($request->first_name . ' ' . $request->last_name)->slug(),
            'supabase_id' => $request->supabase_id ?? null,
            'role' => 'choriste', // Par défaut
            'is_active' => true,
        ]);

        Auth::login($user);

        return response()->json(['status' => 'success', 'user' => $user]);
    }

    /**
     * Synchronise les informations du profil depuis le mobile
     */
    public function syncProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        // On ne met à jour que les champs fournis
        $data = $request->only([
            'first_name',
            'last_name',
            'activite',
            'hobbie',
            'citation',
            'love_choir',
            'date_naissance',
            'photo_url',
            'pupitre_id'
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $publicUrl = $this->supabase->uploadFile('imgs', 'avatars/' . $user->id . '_' . $fileName, $file);
            if ($publicUrl) {
                $data['photo_url'] = $publicUrl;
            }
        }

        $user->update($data);

        return response()->json([
            'status' => 'success',
            'user' => $user
        ]);
    }

    public function getProfile(Request $request)
    {
        $user = $request->user()->load('pupitre');
        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        $data = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'activite' => $user->activite,
            'hobbie' => $user->hobbie,
            'citation' => $user->citation,
            'love_choir' => $user->love_choir,
            'date_naissance' => $user->date_naissance,
            'photo_url' => $user->photo_url,
            'pupitres' => $user->pupitre ? ['name' => $user->pupitre->name] : null,
        ];

        return response()->json([
            'status' => 'success',
            'user' => $data
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function updateFcmToken(Request $request)
    {
        $user = Auth::user();

        // If not authenticated via session, try finding user by email or supabase_id
        if (!$user && ($request->has('email') || $request->has('supabase_id'))) {
            $user = User::where('email', $request->email)
                ->orWhere('supabase_id', $request->supabase_id)
                ->first();
        }

        if (!$user) {
            \Log::warning('FCM Token update: User not found/authenticated', [
                'email' => $request->email,
                'supabase_id' => $request->supabase_id
            ]);
            return response()->json(['message' => 'Utilisateur non identifié'], 401);
        }

        \Log::info('FCM Token update attempt', [
            'user_id' => $user->id,
            'token_received' => (bool)$request->fcm_token
        ]);

        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user->update([
            'fcm_token' => $request->fcm_token
        ]);

        \Log::info('FCM Token updated successfully for user ' . $user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'FCM token updated successfully'
        ]);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        \Log::info('Mobile password change attempt for user', ['email' => $user->email]);

        // 1. Mise à jour dans Supabase Auth
        $supabaseSuccess = $this->supabase->updateUserPasswordByEmail($user->email, $request->password);

        if (!$supabaseSuccess) {
            \Log::error('Supabase password update failed for mobile user', ['email' => $user->email]);
            return response()->json([
                'status' => 'error',
                'message' => 'Une erreur est survenue lors de la mise à jour sur Supabase.'
            ], 500);
        }

        // 2. Mise à jour locale
        \Illuminate\Support\Facades\DB::table('users')
            ->where('id', $user->id)
            ->update([
            'password' => Hash::make($request->password),
            'must_change_password' => \Illuminate\Support\Facades\DB::raw('false'),
            'updated_at' => now(),
        ]);

        \Log::info('Local password update successful for mobile user', ['email' => $user->email]);

        return response()->json([
            'status' => 'success',
            'message' => 'Votre mot de passe a été mis à jour avec succès.'
        ]);
    }
}
