<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\Type;

echo "--- VERIFICATION DES THUMBNAILS ---\n";
$events = Event::with('type', 'images')->latest()->get();

foreach ($events as $event) {
    echo "ID: {$event->id}\n";
    echo "Title: {$event->title}\n";
    echo "Type: " . ($event->type->libelle ?? 'N/A') . "\n";
    echo "Default Type Image: " . ($event->type->default_image ?? 'NONE') . "\n";
    echo "Images count: " . $event->images->count() . "\n";
    echo "Thumbnail calculated: " . $event->thumbnail . "\n";
    echo "-----------------------------------\n";
}
