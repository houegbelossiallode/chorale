<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$response = Http::withHeaders([
    'Content-Type' => 'application/json',
    'Accept' => 'application/json',
])->post('https://romero-38dc.onrender.com/api/mobile/password/reset', [
    'email' => 'isgostage@gmail.com'
]);

file_put_contents('error.json', $response->body());
