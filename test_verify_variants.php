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
echo "Testing for: $email\n";

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
$urlParts = parse_url($actionLink);
parse_str($urlParts['query'], $queryParts);
$token = $queryParts['token'];

echo "Extracted Token: " . $token . "\n";

// 3. Verify Token WITH email
$verifyWithEmail = Illuminate\Support\Facades\Http::withHeaders([
    'apikey' => env('SUPABASE_ANON_KEY')
])->post($url . '/auth/v1/verify', [
            'type' => 'recovery',
            'token_hash' => $token,
            'email' => $email
        ]);

echo "Verify WITH email Status: " . $verifyWithEmail->status() . "\n";
echo "Verify WITH email Body: " . $verifyWithEmail->body() . "\n";

// 4. Verify Token WITHOUT email (Generating a new one to avoid consumption issues, though verify usually doesn't consume unless it's a login)
// Actually, verify DOES consume the token if it's successful. So I need a new one.

$response2 = Illuminate\Support\Facades\Http::withHeaders([
    'apikey' => $key,
    'Authorization' => 'Bearer ' . $key,
])->post($url . '/auth/v1/admin/generate_link', [
            'type' => 'recovery',
            'email' => $email,
            'redirect_to' => 'http://localhost:8000/password/reset'
        ]);
$token2 = $queryParts['token']; // Wait, I should re-parse
$linkData2 = $response2->json();
parse_str(parse_url($linkData2['action_link'])['query'], $queryParts2);
$token2 = $queryParts2['token'];

$verifyWithoutEmail = Illuminate\Support\Facades\Http::withHeaders([
    'apikey' => env('SUPABASE_ANON_KEY')
])->post($url . '/auth/v1/verify', [
            'type' => 'recovery',
            'token_hash' => $token2
        ]);

echo "Verify WITHOUT email Status: " . $verifyWithoutEmail->status() . "\n";
echo "Verify WITHOUT email Body: " . $verifyWithoutEmail->body() . "\n";
