<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    /**
     * Show the password change form.
     */
    public function show()
    {
        return view('auth.password-change');
    }

    /**
     * Update the user's password in both Supabase and Local DB.
     */
    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();
        \Log::info('Password change attempt for user', ['email' => $user->email]);

        // 1. Mise à jour dans Supabase Auth
        $supabaseSuccess = $this->supabase->updateUserPasswordByEmail($user->email, $request->password);

        if (!$supabaseSuccess) {
            \Log::error('Supabase password update failed for user', ['email' => $user->email]);
            return back()->withErrors(['password' => 'Une erreur est survenue lors de la mise à jour de votre compte sur le serveur de sécurité. Veuillez réessayer.']);
        }

        \Log::info('Supabase password update successful', ['email' => $user->email]);

        // 2. Mise à jour locale
        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        \Log::info('Local password update successful', [
            'email' => $user->email,
            'must_change_password' => $user->must_change_password
        ]);

        // 3. Déconnexion forcée pour ré-authentification
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        \Log::info('User logged out after password change, redirecting to login', ['email' => $user->email]);

        return redirect()->route('login')->with('success', 'Votre mot de passe a été mis à jour avec succès. Veuillez vous connecter avec vos nouveaux accès.');
    }
}
