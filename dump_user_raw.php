<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'isgostage@gmail.com';
$results = Illuminate\Support\Facades\DB::select('SELECT * FROM users WHERE email = ?', [$email]);

if (!empty($results)) {
    $user = (array)$results[0];
    echo "USER FOUND: " . $user['email'] . "\n";
    foreach ($user as $k => $v) {
        echo "[$k]: " . ($v ?? 'NULL') . "\n";
    }
} else {
    echo "USER NOT FOUND: " . $email . "\n";
}
