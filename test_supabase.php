<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('SUPABASE_SERVICE_ROLE_KEY');
$url = env('SUPABASE_URL');

// 1. Get first user
$usersResp = Illuminate\Support\Facades\Http::withHeaders([
    'apikey' => $key,
    'Authorization' => 'Bearer ' . $key,
])->get($url . '/auth/v1/admin/users');

$users = $usersResp->json('users');
if (empty($users)) {
    echo "No users found in Supabase.\n";
    exit;
}
$email = $users[0]['email'];
echo "Testing generate_link for: $email\n";

// 2. Generate link
$response = Illuminate\Support\Facades\Http::withHeaders([
    'apikey' => $key,
    'Authorization' => 'Bearer ' . $key,
])->post($url . '/auth/v1/admin/generate_link', [
            'type' => 'recovery',
            'email' => $email,
            'redirect_to' => 'http://localhost:8000/password/reset'
        ]);

$linkData = $response->json();
$actionLink = $linkData['action_link'];

echo "Action link: " . $actionLink . "\n";

// Parse token from action_link
$urlParts = parse_url($actionLink);
parse_str($urlParts['query'], $queryParts);
$token = $queryParts['token'];

echo "Extracted Token: " . $token . "\n";

// 3. Verify Token
$verifyResponse = Illuminate\Support\Facades\Http::withHeaders([
    'apikey' => env('SUPABASE_ANON_KEY')
])->post($url . '/auth/v1/verify', [
            'type' => 'recovery',
            'token_hash' => $token
        ]);

echo "Verify Status: " . $verifyResponse->status() . "\n";
echo "Verify Body: " . substr($verifyResponse->body(), 0, 300) . "\n";
