<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('SUPABASE_SERVICE_ROLE_KEY');
$url = env('SUPABASE_URL');

$usersResp = Illuminate\Support\Facades\Http::withHeaders(['apikey' => $key, 'Authorization' => 'Bearer ' . $key])->get($url . '/auth/v1/admin/users');
$email = $usersResp->json('users')[0]['email'];

$out = "Testing for email: $email\n";

// Case 1: WITH email
$response1 = Illuminate\Support\Facades\Http::withHeaders(['apikey' => $key, 'Authorization' => 'Bearer ' . $key])->post($url . '/auth/v1/admin/generate_link', [
    'type' => 'recovery',
    'email' => $email
]);
parse_str(parse_url($response1->json('action_link'))['query'], $q1);
$token1 = $q1['token'];

$v1 = Illuminate\Support\Facades\Http::withHeaders(['apikey' => env('SUPABASE_ANON_KEY')])->post($url . '/auth/v1/verify', [
    'type' => 'recovery',
    'token_hash' => $token1,
    'email' => $email
]);
$out .= "WITH email Result: " . $v1->status() . " " . $v1->body() . "\n\n";

// Case 2: WITHOUT email
$response2 = Illuminate\Support\Facades\Http::withHeaders(['apikey' => $key, 'Authorization' => 'Bearer ' . $key])->post($url . '/auth/v1/admin/generate_link', [
    'type' => 'recovery',
    'email' => $email
]);
parse_str(parse_url($response2->json('action_link'))['query'], $q2);
$token2 = $q2['token'];

$v2 = Illuminate\Support\Facades\Http::withHeaders(['apikey' => env('SUPABASE_ANON_KEY')])->post($url . '/auth/v1/verify', [
    'type' => 'recovery',
    'token_hash' => $token2
]);
$out .= "WITHOUT email Result: " . $v2->status() . " " . $v2->body() . "\n";

file_put_contents('test_results_final.txt', $out);
echo "Results written to test_results_final.txt\n";
