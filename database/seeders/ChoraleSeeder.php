<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pupitre;
use App\Models\Post;
use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ChoraleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer les Pupitres
        $pupitres = [
            ['name' => 'Soprano', 'description' => 'Voix aiguës féminines'],
            ['name' => 'Alto', 'description' => 'Voix graves féminines'],
            ['name' => 'Ténor', 'description' => 'Voix aiguës masculines'],
            ['name' => 'Basse', 'description' => 'Voix graves masculines'],
        ];

        foreach ($pupitres as $p) {
            Pupitre::create($p);
        }

        // 2. Créer un Admin et un Chef de Chœur
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Chorale',
            'email' => 'admin@chorale.fr',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $chef = User::create([
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'slug' => 'jean-dupont',
            'email' => 'chef@chorale.fr',
            'password' => Hash::make('password'),
            'role' => 'choir_master',
            'bio' => 'La musique est le souffle de mon âme.',
            'why_choir' => 'Pour porter la prière par l\'harmonie.',
            'hobbies' => 'Violoncelle, Randonnée',
            'activities' => 'Professeur de Musique',
            'citation' => 'Chanter, c\'est prier deux fois.',
            'photo_url' => 'https://i.pravatar.cc/150?u=chef',
        ]);

        // 3. Créer des membres pour le trombinoscope
        $sections = Pupitre::with('users')->get();
        $names = [
            ['Marie', 'Lumière'], ['Paul', 'Grâce'], ['Sophie', 'Joie'],
            ['Thomas', 'Paix'], ['Julie', 'Espérance'], ['Marc', 'Foi'],
            ['Lucie', 'Clarté'], ['Antoine', 'Sagesse'], ['Clara', 'Douceur'],
            ['David', 'Force'], ['Sarah', 'Bonté'], ['Jean', 'Vérité']
        ];

        foreach ($sections as $index => $section) {
            foreach (range(0, 2) as $i) {
                $namePair = $names[($index * 3) + $i];
                $firstName = $namePair[0];
                $lastName = $namePair[1];
                User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'slug' => Str::slug($firstName . ' ' . $lastName),
                    'email' => strtolower($firstName . '.' . $lastName) . "@example.com",
                    'password' => Hash::make('password'),
                    'pupitre_id' => $section->id,
                    'role' => 'choriste',
                    'bio' => "Le chant m'apporte une paix profonde chaque dimanche.",
                    'why_choir' => "Pour la beauté de la liturgie et l'esprit de famille.",
                    'hobbies' => 'Jardinage, Chant choral, Lecture spirituelle',
                    'activities' => 'Étudiant en Théologie / Salarié',
                    'citation' => "Que ma voix soit un humble instrument de Ta paix.",
                    'photo_url' => "https://i.pravatar.cc/150?u=" . Str::slug($firstName . $lastName),
                    'likes_count' => rand(5, 50),
                ]);
            }
        }

        // 4. Créer des Articles (Parole aux Prêtres)
        Post::create([
            'title' => 'La musique au service de la Foi',
            'slug' => 'la-musique-au-service-de-la-foi',
            'content' => 'Le chant est une double prière. En cette saison, nous devons nous concentrer sur...',
            'type' => 'priest_word',
            'author_id' => $admin->id,
            'published_at' => now(),
            'image_path' => 'https://images.unsplash.com/photo-1515516089376-88db1e26e9c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        ]);

        Post::create([
            'title' => 'Récital de Printemps : Un succès !',
            'slug' => 'recital-printemps-succes',
            'content' => 'Merci à tous les paroissiens pour leur présence lors de notre dernier concert.',
            'type' => 'news',
            'author_id' => $admin->id,
            'published_at' => now(),
            'image_path' => 'https://images.unsplash.com/photo-1459749411177-042180cefb44?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
        ]);

        // 5. Créer des Événements
        $e1 = Event::create([
            'title' => 'Répétition Générale',
            'slug' => 'repetition-generale-paques',
            'description' => 'Préparation intensive pour la messe de Pâques. Tous les pupitres sont attendus pour caler les harmonisations.',
            'start_at' => now()->addDays(2)->setHour(18)->setMinute(0),
            'location' => 'Salle Paroissiale',
            'type' => 'rehearsal',
            'is_public' => false,
        ]);

        $e2 = Event::create([
            'title' => 'Messe Solennelle',
            'slug' => 'messe-solennelle-pentecote',
            'description' => 'Célébration animée par la chorale d\'élite. Venez partager ce moment de grâce avec nous.',
            'start_at' => now()->addDays(5)->setHour(10)->setMinute(30),
            'location' => 'Cathédrale Saint-Pierre',
            'type' => 'mass',
            'is_public' => true,
        ]);

        // 6. Ajouter des images à la galerie
        EventImage::create([
            'event_id' => $e2->id,
            'image_path' => 'https://images.unsplash.com/photo-1544256718-3bcf237f3974?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'caption' => 'Répétition dans le chœur'
        ]);
        EventImage::create([
            'event_id' => $e2->id,
            'image_path' => 'https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'caption' => 'Moment de concentration'
        ]);
        EventImage::create([
            'event_id' => $e2->id,
            'image_path' => 'https://images.unsplash.com/photo-1459749411177-042180cefb44?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            'caption' => 'La chorale au complet'
        ]);

        // 7. Créer des Témoignages
        Post::create([
            'title' => 'Marie L.',
            'slug' => 'temoignage-marie-l',
            'content' => 'Intégrer cette chorale a été pour moi une révélation. J\'y ai trouvé une famille et une nouvelle façon de prier.',
            'type' => 'testimony',
            'author_id' => $admin->id,
            'published_at' => now(),
        ]);

        Post::create([
            'title' => 'Paul G.',
            'slug' => 'temoignage-paul-g',
            'content' => 'Le niveau d\'exigence musicale nous tire vers le haut, mais c\'est l\'amour du Christ qui nous unit vraiment.',
            'type' => 'testimony',
            'author_id' => $admin->id,
            'published_at' => now(),
        ]);
    }
}
