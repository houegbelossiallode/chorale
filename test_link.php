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

$response = \Illuminate\Support\Facades\Http::withHeaders([
    'apikey' => config('services.supabase.service_key'),
    'Authorization' => 'Bearer ' . config('services.supabase.service_key'),
])->post(config('services.supabase.url') . '/auth/v1/admin/generate_link', [
    'type' => 'recovery',
    'email' => $email,
    'redirect_to' => route('password.reset', ['token' => 'placeholder'])
]);
echo "Raw Action Link Response: \n";
print_r($response->json());

$actionLink = $response->json('action_link');
$urlParts = parse_url($actionLink);
print_r($urlParts);
parse_str($urlParts['query'] ?? '', $queryParts);
echo "Extracted Query Token: " . ($queryParts['token'] ?? 'null') . "\n";
