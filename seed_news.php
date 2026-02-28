<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

$user = User::first();
$user_id = $user ? $user->id : 1;

$news = [
    [
        'title' => 'Répétition Générale : En route pour le Gala',
        'excerpt' => 'Une soirée intense de travail vocal pour préparer notre grand gala annuel.',
        'content' => 'Nos choristes se sont réunis hier soir pour une répétition marathon. L\'énergie était palpable alors que nous affinions les dernières harmonies pour le Gala de la Foi qui se tiendra le mois prochain.',
        'image_path' => '/uploads/posts/rehearsal.png'
    ],
    [
        'title' => 'Concert de Pâques : Une soirée inoubliable',
        'excerpt' => 'Retour sur la célébration musicale qui a ému toute la paroisse le week-end dernier.',
        'content' => 'La nef de l\'église était comble pour notre concert de Pâques. Entre chants classiques et créations contemporaines, la soirée a été un véritable moment de partage et de recueillement.',
        'image_path' => '/uploads/posts/concert.png'
    ],
    [
        'title' => 'Atelier Vocal : Découvrir sa voix',
        'excerpt' => 'Un atelier ouvert à tous pour explorer les bases du chant choral et de la respiration.',
        'content' => 'Nous avons accueilli ce samedi une dizaine de nouveaux visages pour un atelier découverte. Une belle occasion de partager notre passion et d\'expliquer aux plus timides que tout le monde peut chanter.',
        'image_path' => '/uploads/posts/workshop.png'
    ]
];

foreach ($news as $item) {
    Post::create(array_merge($item, [
        'author_id' => $user_id,
        'type' => 'news',
        'slug' => Str::slug($item['title']),
        'published_at' => now()
    ]));
}

echo "3 posts created successfully\n";
