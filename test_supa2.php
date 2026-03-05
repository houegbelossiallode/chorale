<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$response = Illuminate\Support\Facades\Http::withHeaders([
    'apikey' => env('SUPABASE_SERVICE_KEY'),
    'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
])->post(env('SUPABASE_URL') . '/auth/v1/admin/generate_link', [
            'type' => 'recovery',
            'email' => 'krystoam2@gmail.com',
            'redirect_to' => 'http://localhost/reset'
        ]);

$data = $response->json();
print_r($data);

if (isset($data['action_link'])) {
    $urlParts = parse_url($data['action_link']);
    parse_str($urlParts['query'] ?? '', $queryParts);
    $fragment = $urlParts['fragment'] ?? '';
    parse_str($fragment, $fragmentParts);

    echo "Query Token: " . ($queryParts['token'] ?? 'None') . "\n";
    echo "Fragment Access Token: " . ($fragmentParts['access_token'] ?? 'None') . "\n";
}
