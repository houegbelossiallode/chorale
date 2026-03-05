<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;
use App\Models\Type;

$eventId = 7; // L'ID mentionné dans les logs précédents ou un autre événement récent
$event = Event::find($eventId);

if (!$event) {
    echo "Event not found. Getting the latest one instead.\n";
    $event = Event::latest()->first();
}

if ($event) {
    echo "--- EVENT DETAILS ---\n";
    echo "ID: {$event->id}\n";
    echo "Title: {$event->title}\n";
    echo "Type ID: {$event->type_id}\n";

    $type = $event->type;
    if ($type) {
        echo "Type Libelle: {$type->libelle}\n";
        echo "Type Default Image: '" . $type->default_image . "'\n";
    } else {
        echo "Type: NULL\n";
    }

    $images = $event->images;
    echo "Images Count: " . $images->count() . "\n";
    foreach ($images as $img) {
        echo "- Image ID: {$img->id}, Path: {$img->image_path}, Principal: " . ($img->is_principal ? 'YES' : 'NO') . "\n";
    }

    echo "\n--- ACCESSOR TEST ---\n";
    echo "Thumbnail: " . $event->thumbnail . "\n";
} else {
    echo "No events found.\n";
}
