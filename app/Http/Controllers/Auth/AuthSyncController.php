<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SupabaseService;

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
        \Log::info('Supabase user result', ['found' => (bool) $supabaseUser]);

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
        } elseif (empty($user->supabase_id)) {
            // Update existing user with supabase_id
            $user->update(['supabase_id' => $supabaseUser['id']]);
        }

        // Connecter l'utilisateur dans la session Laravel
        Auth::login($user, true);

        $role = strtolower($user->role->libelle ?? '');

        if ($user->must_change_password) {
            $redirect = route('password.change');
        } else {
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
}
