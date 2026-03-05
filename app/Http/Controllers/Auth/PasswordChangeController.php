<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
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

        // 1. Mise à jour dans Supabase Auth
        $supabaseSuccess = $this->supabase->updateUserPasswordByEmail($user->email, $request->password);

        if (!$supabaseSuccess) {
            return back()->withErrors(['password' => 'Une erreur est survenue lors de la mise à jour de votre compte sur le serveur de sécurité. Veuillez réessayer.']);
        }

        // 2. Mise à jour locale
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('choriste.dashboard')->with('success', 'Votre mot de passe a été mis à jour avec succès.');
    }
}
