<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$service = app(App\Services\SupabaseService::class);
$email = 'krystoam2@gmail.com';
$response = Illuminate\Support\Facades\Http::withHeaders([
    'apikey' => env('SUPABASE_SERVICE_KEY'),
    'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
])->post(env('SUPABASE_URL') . '/auth/v1/admin/generate_link', [
            'type' => 'recovery',
            'email' => $email,
            'redirect_to' => route('password.reset', ['token' => 'placeholder']) // Le redirect_to est requis mais non utilisé pour l'email
        ]);
print_r($response->json());
