<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$supabase = app(\App\Services\SupabaseService::class);
$user = \App\Models\User::first();
if (!$user) {
    die("No users found");
}
$email = $user->email;
echo "Testing with email: " . $email . "\n";

$token = $supabase->createResetToken($email);
echo "Extracted Token: " . ($token ?? 'null') . "\n";

$url = config('services.supabase.url');
$key = config('services.supabase.service_key');

$verifyResponse = \Illuminate\Support\Facades\Http::withHeaders([
    'apikey' => $key,
    'Content-Type' => 'application/json'
])->post($url . '/auth/v1/verify', [
    'type' => 'recovery',
    'token_hash' => $token,
]);

echo "Verify response status: " . $verifyResponse->status() . "\n";
print_r($verifyResponse->json());
