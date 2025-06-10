<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed the application with demo data.
     */
    public function run(): void
    {
        // Création des utilisateurs avec des rôles spécifiques
        $this->createUsers();

        // Création des événements
        $this->createEvents();

        // Inscription des clients aux événements
        $this->registerClientsToEvents();
    }

    /**
     * Crée les utilisateurs de démonstration.
     */
    private function createUsers(): void
    {
        // Création d'un administrateur
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'remember_token' => Str::random(10),
        ]);

        // Création de 3 organisateurs
        $organizerNames = [
            'Association Culturelle de Paris',
            'Club Sportif Marseillais',
            'Conférences Tech Lyon',
        ];

        foreach ($organizerNames as $index => $name) {
            User::create([
                'name' => $name,
                'email' => 'organisateur'.($index + 1).'@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'organisateur',
                'remember_token' => Str::random(10),
            ]);
        }

        // Création de 10 clients
        $clientNames = [
            'Sophie Martin',
            'Thomas Dubois',
            'Emma Bernard',
            'Lucas Petit',
            'Chloé Leroy',
            'Hugo Moreau',
            'Léa Roux',
            'Nathan Fournier',
            'Camille Girard',
            'Maxime Lambert',
        ];

        foreach ($clientNames as $index => $name) {
            User::create([
                'name' => $name,
                'email' => 'client'.($index + 1).'@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => 'client',
                'remember_token' => Str::random(10),
            ]);
        }
    }

    /**
     * Crée les événements de démonstration.
     */
    private function createEvents(): void
    {
        $organizers = User::where('role', 'organisateur')->get();

        // Événements culturels (organisateur 1)
        $culturalEvents = [
            [
                'title' => 'Exposition d\'Art Contemporain',
                'description' => 'Découvrez les œuvres de jeunes artistes émergents dans cette exposition unique qui mêle peinture, sculpture et art numérique.',
                'date' => Carbon::now()->addDays(15)->setHour(10)->setMinute(0),
                'location' => 'Galerie d\'Art Moderne, Paris',
                'status' => 'active',
                'max_participants' => 100,
                'price' => 12.50,
                'currency' => 'EUR',
            ],
            [
                'title' => 'Concert de Jazz',
                'description' => 'Une soirée exceptionnelle avec le Quartet de Jazz de Paris qui interprètera les grands classiques et leurs compositions originales.',
                'date' => Carbon::now()->addDays(7)->setHour(20)->setMinute(30),
                'location' => 'Salle Pleyel, Paris',
                'status' => 'active',
                'max_participants' => 200,
                'price' => 25.00,
                'currency' => 'EUR',
            ],
            [
                'title' => 'Atelier d\'Écriture Créative',
                'description' => 'Développez votre créativité et votre style d\'écriture avec cet atelier animé par l\'auteur renommé Jean Dupont.',
                'date' => Carbon::now()->addDays(21)->setHour(14)->setMinute(0),
                'location' => 'Bibliothèque Nationale, Paris',
                'status' => 'active',
                'max_participants' => 15,
                'price' => 8.00,
                'currency' => 'EUR',
            ],
            [
                'title' => 'Visite Guidée du Vieux Paris',
                'description' => 'Explorez les quartiers historiques de Paris avec un guide expert qui vous racontera les secrets et anecdotes de la ville lumière.',
                'date' => Carbon::now()->addDays(10)->setHour(9)->setMinute(30),
                'location' => 'Place Saint-Michel, Paris',
                'status' => 'active',
                'max_participants' => 20,
                'price' => null,
                'currency' => null,
            ],
            [
                'title' => 'Festival de Cinéma en Plein Air',
                'description' => 'Projection de films indépendants sous les étoiles. Apportez vos chaises et couvertures pour une expérience cinématographique unique.',
                'date' => Carbon::now()->addDays(30)->setHour(21)->setMinute(0),
                'location' => 'Parc de la Villette, Paris',
                'status' => 'active',
                'max_participants' => 300,
                'price' => 5.00,
                'currency' => 'EUR',
            ],
        ];

        // Événements sportifs (organisateur 2)
        $sportEvents = [
            [
                'title' => 'Marathon de Marseille',
                'description' => 'Participez au marathon annuel de Marseille qui vous fera découvrir les plus beaux paysages de la cité phocéenne.',
                'date' => Carbon::now()->addDays(45)->setHour(8)->setMinute(0),
                'location' => 'Vieux Port, Marseille',
                'status' => 'active',
                'max_participants' => 1000,
                'price' => 30.00,
                'currency' => 'EUR',
            ],
            [
                'title' => 'Tournoi de Beach Volley',
                'description' => 'Compétition amicale de beach volley ouverte à tous les niveaux. Formez votre équipe de 2 et venez vous amuser sur les plages du Prado.',
                'date' => Carbon::now()->addDays(14)->setHour(10)->setMinute(0),
                'location' => 'Plages du Prado, Marseille',
                'status' => 'active',
                'max_participants' => 32,
                'price' => 15.00,
                'currency' => 'EUR',
            ],
            [
                'title' => 'Cours de Yoga en Plein Air',
                'description' => 'Séance de yoga tous niveaux face à la mer. Apportez votre tapis et venez vous ressourcer dans un cadre exceptionnel.',
                'date' => Carbon::now()->addDays(5)->setHour(9)->setMinute(0),
                'location' => 'Parc Borély, Marseille',
                'status' => 'active',
                'max_participants' => 25,
                'price' => null,
                'currency' => null,
            ],
            [
                'title' => 'Randonnée dans les Calanques',
                'description' => 'Randonnée guidée dans le Parc National des Calanques. Découvrez des paysages à couper le souffle entre mer et montagne.',
                'date' => Carbon::now()->addDays(12)->setHour(8)->setMinute(30),
                'location' => 'Calanques de Marseille',
                'status' => 'active',
                'max_participants' => 15,
                'price' => 10.00,
                'currency' => 'EUR',
            ],
            [
                'title' => 'Tournoi de Pétanque',
                'description' => 'Tournoi convivial de pétanque ouvert à tous. Venez tester votre adresse et partager un moment de convivialité typiquement marseillais.',
                'date' => Carbon::now()->addDays(-5)->setHour(14)->setMinute(0),
                'location' => 'Place des Moulins, Marseille',
                'status' => 'annule',
                'max_participants' => 50,
                'price' => 5.00,
                'currency' => 'EUR',
            ],
        ];

        // Événements tech (organisateur 3)
        $techEvents = [
            [
                'title' => 'Conférence sur l\'Intelligence Artificielle',
                'description' => 'Experts et chercheurs présenteront les dernières avancées en matière d\'IA et leurs applications dans notre quotidien.',
                'date' => Carbon::now()->addDays(20)->setHour(9)->setMinute(0),
                'location' => 'Centre de Congrès, Lyon',
                'status' => 'active',
                'max_participants' => 200,
                'price' => 50.00,
                'currency' => 'EUR',
            ],
            [
                'title' => 'Hackathon Développement Durable',
                'description' => '48h pour développer des solutions technologiques innovantes répondant aux enjeux environnementaux actuels.',
                'date' => Carbon::now()->addDays(30)->setHour(9)->setMinute(0),
                'location' => 'Campus Numérique, Lyon',
                'status' => 'active',
                'max_participants' => 100,
                'price' => null,
                'currency' => null,
            ],
            [
                'title' => 'Workshop UX/UI Design',
                'description' => 'Atelier pratique pour apprendre les fondamentaux du design d\'interface utilisateur et de l\'expérience utilisateur.',
                'date' => Carbon::now()->addDays(8)->setHour(14)->setMinute(0),
                'location' => 'École de Design, Lyon',
                'status' => 'active',
                'max_participants' => 20,
                'price' => 35.00,
                'currency' => 'EUR',
            ],
            [
                'title' => 'Meetup Blockchain et Cryptomonnaies',
                'description' => 'Rencontre et échanges autour des technologies blockchain et de leurs applications dans divers secteurs.',
                'date' => Carbon::now()->addDays(15)->setHour(18)->setMinute(30),
                'location' => 'Incubateur H7, Lyon',
                'status' => 'active',
                'max_participants' => 50,
                'price' => 5.00,
                'currency' => 'EUR',
            ],
            [
                'title' => 'Formation Développement Web',
                'description' => 'Initiation aux technologies web modernes: HTML5, CSS3, JavaScript et introduction aux frameworks populaires.',
                'date' => Carbon::now()->addDays(-10)->setHour(9)->setMinute(0),
                'location' => 'Digital Campus, Lyon',
                'status' => 'annule',
                'max_participants' => 15,
                'price' => 120.00,
                'currency' => 'EUR',
            ],
        ];

        // Création des événements pour chaque organisateur
        $this->createEventsForOrganizer($organizers[0], $culturalEvents);
        $this->createEventsForOrganizer($organizers[1], $sportEvents);
        $this->createEventsForOrganizer($organizers[2], $techEvents);
    }

    /**
     * Crée des événements pour un organisateur spécifique.
     */
    private function createEventsForOrganizer(User $organizer, array $events): void
    {
        foreach ($events as $eventData) {
            Event::create([
                'user_id' => $organizer->id,
                'title' => $eventData['title'],
                'description' => $eventData['description'],
                'date' => $eventData['date'],
                'location' => $eventData['location'],
                'status' => $eventData['status'],
                'max_participants' => $eventData['max_participants'],
                'price' => $eventData['price'],
                'currency' => $eventData['currency'],
            ]);
        }
    }

    /**
     * Inscrit des clients à des événements.
     */
    private function registerClientsToEvents(): void
    {
        $clients = User::where('role', 'client')->get();
        $events = Event::where('status', 'active')->get();

        // Pour chaque client, on l'inscrit à 2-5 événements aléatoires
        foreach ($clients as $client) {
            $randomEvents = $events->random(rand(2, 5));

            foreach ($randomEvents as $event) {
                // On vérifie qu'il reste de la place
                if ($event->participants()->count() < $event->max_participants) {
                    $event->participants()->attach($client->id);
                }
            }
        }
    }
}
