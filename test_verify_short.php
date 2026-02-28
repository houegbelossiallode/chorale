<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('SUPABASE_SERVICE_ROLE_KEY');
$url = env('SUPABASE_URL');

$usersResp = Illuminate\Support\Facades\Http::withHeaders(['apikey' => $key, 'Authorization' => 'Bearer ' . $key])->get($url . '/auth/v1/admin/users');
$email = $usersResp->json('users')[0]['email'];

$response = Illuminate\Support\Facades\Http::withHeaders(['apikey' => $key, 'Authorization' => 'Bearer ' . $key])->post($url . '/auth/v1/admin/generate_link', [
    'type' => 'recovery',
    'email' => $email,
    'redirect_to' => 'http://localhost:8000/password/reset'
]);

parse_str(parse_url($response->json('action_link'))['query'], $queryParts);
$token = $queryParts['token'];

$v1 = Illuminate\Support\Facades\Http::withHeaders(['apikey' => env('SUPABASE_ANON_KEY')])->post($url . '/auth/v1/verify', [
    'type' => 'recovery',
    'token_hash' => $token,
    'email' => $email
]);
echo "WITH email: " . $v1->status() . " " . $v1->body() . "\n";

$response2 = Illuminate\Support\Facades\Http::withHeaders(['apikey' => $key, 'Authorization' => 'Bearer ' . $key])->post($url . '/auth/v1/admin/generate_link', [
    'type' => 'recovery',
    'email' => $email,
    'redirect_to' => 'http://localhost:8000/password/reset'
]);
parse_str(parse_url($response2->json('action_link'))['query'], $queryParts2);
$token2 = $queryParts2['token'];

$v2 = Illuminate\Support\Facades\Http::withHeaders(['apikey' => env('SUPABASE_ANON_KEY')])->post($url . '/auth/v1/verify', [
    'type' => 'recovery',
    'token_hash' => $token2
]);
echo "WITHOUT email: " . $v2->status() . " " . $v2->body() . "\n";
