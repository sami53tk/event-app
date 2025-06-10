<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientDashboardSearchTest extends TestCase
{
    use RefreshDatabase;

    protected $client;

    protected $organizer;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer les utilisateurs
        $this->client = User::factory()->create(['role' => 'client']);
        $this->organizer = User::factory()->create(['role' => 'organisateur']);

        // Créer des événements pour les tests
        $event1 = Event::create([
            'user_id' => $this->organizer->id,
            'title' => 'Concert de Jazz',
            'description' => 'Un super concert de jazz',
            'date' => Carbon::now()->addDays(10),
            'location' => 'Paris',
            'status' => 'active',
            'max_participants' => 100,
            'price' => 25.00,
            'currency' => 'EUR',
        ]);

        $event2 = Event::create([
            'user_id' => $this->organizer->id,
            'title' => 'Exposition d\'Art',
            'description' => 'Une exposition d\'art contemporain',
            'date' => Carbon::now()->addDays(20),
            'location' => 'Lyon',
            'status' => 'active',
            'max_participants' => 50,
            'price' => 10.00,
            'currency' => 'EUR',
        ]);

        $event3 = Event::create([
            'user_id' => $this->organizer->id,
            'title' => 'Marathon',
            'description' => 'Course annuelle de marathon',
            'date' => Carbon::now()->addDays(30),
            'location' => 'Marseille',
            'status' => 'annule',
            'max_participants' => 200,
            'price' => 30.00,
            'currency' => 'EUR',
        ]);

        // Inscrire le client à certains événements
        $event1->participants()->attach($this->client->id);
        $event3->participants()->attach($this->client->id);
    }

    /** @test */
    public function client_can_search_registered_events_by_title()
    {
        $response = $this->actingAs($this->client)
            ->get(route('dashboard.client', ['search' => 'Jazz']));

        $response->assertStatus(200);
        $response->assertViewHas('events');
        $response->assertSee('Concert de Jazz');
        $response->assertDontSee('Marathon'); // Même si inscrit, ne correspond pas à la recherche
    }

    /** @test */
    public function client_can_filter_registered_events_by_status()
    {
        $response = $this->actingAs($this->client)
            ->get(route('dashboard.client', ['status' => 'annule']));

        $response->assertStatus(200);
        $response->assertViewHas('events');
        $response->assertSee('Marathon');
        $response->assertDontSee('Concert de Jazz');
    }

    /** @test */
    public function client_can_filter_registered_events_by_date()
    {
        $response = $this->actingAs($this->client)
            ->get(route('dashboard.client', [
                'date_start' => Carbon::now()->addDays(25)->format('Y-m-d'),
            ]));

        $response->assertStatus(200);
        $response->assertViewHas('events');
        $response->assertSee('Marathon');
        $response->assertDontSee('Concert de Jazz'); // Avant la date de début
    }

    /** @test */
    public function client_only_sees_events_they_are_registered_for()
    {
        // Créer un nouvel événement auquel le client n'est pas inscrit
        Event::create([
            'user_id' => $this->organizer->id,
            'title' => 'Atelier de Cuisine',
            'description' => 'Apprenez à cuisiner comme un chef',
            'date' => Carbon::now()->addDays(15),
            'location' => 'Paris',
            'status' => 'active',
            'max_participants' => 20,
            'price' => 50.00,
            'currency' => 'EUR',
        ]);

        $response = $this->actingAs($this->client)
            ->get(route('dashboard.client', ['location' => 'Paris']));

        $response->assertStatus(200);
        $response->assertViewHas('events');
        $response->assertSee('Concert de Jazz'); // Inscrit et à Paris
        $response->assertDontSee('Atelier de Cuisine'); // À Paris mais pas inscrit
    }

    /** @test */
    public function client_dashboard_search_returns_paginated_results()
    {
        // Créer plusieurs événements supplémentaires et y inscrire le client
        for ($i = 1; $i <= 15; $i++) {
            $event = Event::create([
                'user_id' => $this->organizer->id,
                'title' => "Événement Test $i",
                'description' => "Description de l'événement test $i",
                'date' => Carbon::now()->addDays($i),
                'location' => 'Test City',
                'status' => 'active',
                'max_participants' => 50,
                'price' => null,
                'currency' => null,
            ]);

            $event->participants()->attach($this->client->id);
        }

        $response = $this->actingAs($this->client)
            ->get(route('dashboard.client'));

        $response->assertStatus(200);
        $response->assertViewHas('events');

        // Vérifier que la pagination fonctionne
        $events = $response->viewData('events');
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $events);
    }
}
