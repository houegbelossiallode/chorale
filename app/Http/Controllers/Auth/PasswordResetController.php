<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class PasswordResetController extends Controller
{
    protected string $supabaseUrl;
    protected string $serviceKey;
    protected string $anonKey;

    public function __construct()
    {
        $this->supabaseUrl = config('services.supabase.url');
        $this->serviceKey = config('services.supabase.service_key');
        $this->anonKey = config('services.supabase.anon_key');
    }

    // Show form to request password reset
    public function showRequestForm()
    {
        return view('auth.password_reset.request');
    }

    // Handle form submission, send reset email via Supabase generate_link
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $email = $request->input('email');

        // Use Supabase Admin API to generate a recovery link
        $response = Http::withHeaders([
            'apikey' => $this->serviceKey,
            'Authorization' => 'Bearer ' . $this->serviceKey,
            'Content-Type' => 'application/json',
        ])->post($this->supabaseUrl . '/auth/v1/admin/generate_link', [
            'type' => 'recovery',
            'email' => $email,
            // Redirect to our own reset page after Supabase exchanges the code
            'redirect_to' => url('/password/reset/confirm'),
        ]);

        if ($response->failed()) {
            Log::error('Supabase generate_link error', ['body' => $response->json()]);
            // Show generic success to avoid user enumeration
            return redirect()->back()->with('status', 'Si cette adresse email est connue, vous recevrez un lien de réinitialisation.');
        }

        $link = $response->json('action_link');

        if (!$link) {
            Log::error('Supabase: action_link missing', ['body' => $response->json()]);
            return redirect()->back()->with('status', 'Si cette adresse email est connue, vous recevrez un lien de réinitialisation.');
        }

        // Send our own custom email with the Supabase link
        Mail::to($email)->send(new PasswordResetMail($link));

        return redirect()->back()->with('status', 'Un email de réinitialisation a été envoyé. Vérifiez votre boîte mail.');
    }

    // Show form to set new password — the access_token arrives via URL hash fragment (#)
    // The JavaScript in the view extracts it and injects it into the hidden field.
    public function showResetForm()
    {
        return view('auth.password_reset.reset');
    }

    // Update password using the access_token extracted by the client-side JS
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required|string',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $accessToken = $request->input('access_token');
        $newPassword = $request->input('password');

        // Call Supabase REST API as the authenticated user using their own access_token
        $response = Http::withHeaders([
            'apikey' => $this->anonKey,
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->put($this->supabaseUrl . '/auth/v1/user', [
            'password' => $newPassword,
        ]);

        if ($response->failed()) {
            Log::error('Supabase reset password error', ['status' => $response->status(), 'body' => $response->json()]);
            return redirect()->back()
                ->with('error', 'Le lien de réinitialisation est invalide ou a expiré. Veuillez en demander un nouveau.')
                ->withInput();
        }

        return redirect()->route('login')->with('success', 'Bravo ! Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');
    }
}
