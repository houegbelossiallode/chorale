<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\Type;

$eventsWithoutImages = Event::doesntHave('images')->with('type')->get();

echo "--- EVENTS WITHOUT IMAGES (" . $eventsWithoutImages->count() . ") ---\n";
foreach ($eventsWithoutImages as $event) {
    echo "ID: {$event->id} | Title: {$event->title} | Type: " . ($event->type->libelle ?? 'N/A') . " | Default Type Image: " . ($event->type->default_image ?? 'NONE') . " | Thumbnail: " . $event->thumbnail . "\n";
}
