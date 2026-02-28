<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    protected SupabaseService $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    // Show form to request password reset
    public function showRequestForm()
    {
        return view('auth.password_reset.request');
    }

    // Handle form submission, send reset email
    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $email = $request->input('email');
        // Generate a reset token via Supabase admin API
        $token = $this->supabase->createResetToken($email);
        if (!$token) {
            return redirect()->back()->with('error', 'Unable to generate reset token.');
        }
        $resetUrl = url('/password/reset/' . urlencode($token));
        Mail::to($email)->send(new PasswordResetMail($resetUrl));
        return redirect()->back()->with('status', 'Password reset link sent.');
    }

    // Show form to set new password (token in URL)
    public function showResetForm(Request $request, $token)
    {
        // Verify token via Supabase (optional, can be done in resetPassword)
        return view('auth.password_reset.reset', ['token' => $token]);
    }

    // Update password using token
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $token = $request->input('token');
        $email = $request->input('email');
        $newPassword = $request->input('password');
        // Use Supabase admin endpoint to update password
        $result = $this->supabase->resetUserPassword($token, $newPassword);
        if ($result) {
            return redirect()->route('login')->with('status', 'Password has been reset.');
        }
        return redirect()->back()->with('error', 'Invalid or expired token.');
    }
}
