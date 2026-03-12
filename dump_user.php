<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'elyseehouegbelossi@gmail.com';
$user = App\Models\User::whereRaw('LOWER(email) = ?', [strtolower($email)])->first();

if ($user) {
    echo "USER FOUND: " . $user->email . "\n";
    echo "ID: " . $user->id . "\n";
    echo "Supabase ID: " . ($user->supabase_id ?? 'NULL') . "\n";
    foreach ($user->getAttributes() as $k => $v) {
        echo "[$k]: " . ($v ?? 'NULL') . "\n";
    }
} else {
    echo "USER NOT FOUND: " . $email . "\n";
}
