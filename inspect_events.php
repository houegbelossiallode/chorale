<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\Type;

echo "--- TYPES ---\n";
$types = Type::all();
foreach ($types as $type) {
    echo "ID: {$type->id} | Libelle: {$type->libelle} | Default Image: " . ($type->default_image ?: 'NULL') . "\n";
}

echo "\n--- EVENTS (Top 5) ---\n";
$events = Event::with('images')->latest()->take(5)->get();
foreach ($events as $event) {
    echo "ID: {$event->id} | Title: {$event->title} | Type: " . ($event->type->libelle ?? 'N/A') . " | Images Count: " . $event->images->count() . " | Thumbnail: " . $event->thumbnail . "\n";
}
