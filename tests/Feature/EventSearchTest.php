<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class EventSearchTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $organizer;
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer les utilisateurs
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->organizer = User::factory()->create(['role' => 'organisateur']);
        $this->client = User::factory()->create(['role' => 'client']);
        
        // Créer des événements pour les tests
        Event::create([
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
        
        Event::create([
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
        
        Event::create([
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
    }

    /** @test */
    public function admin_can_search_events_by_title()
    {
        $response = $this->actingAs($this->admin)
                         ->get(route('events.index', ['search' => 'Jazz']));
        
        $response->assertStatus(200);
        $response->assertViewHas('events');
        $response->assertSee('Concert de Jazz');
        $response->assertDontSee('Exposition d\'Art');
        $response->assertDontSee('Marathon');
    }

    /** @test */
    public function admin_can_search_events_by_location()
    {
        $response = $this->actingAs($this->admin)
                         ->get(route('events.index', ['location' => 'Lyon']));
        
        $response->assertStatus(200);
        $response->assertViewHas('events');
        $response->assertSee('Exposition d\'Art');
        $response->assertDontSee('Concert de Jazz');
        $response->assertDontSee('Marathon');
    }

    /** @test */
    public function admin_can_filter_events_by_status()
    {
        $response = $this->actingAs($this->admin)
                         ->get(route('events.index', ['status' => 'annule']));
        
        $response->assertStatus(200);
        $response->assertViewHas('events');
        $response->assertSee('Marathon');
        $response->assertDontSee('Concert de Jazz');
        $response->assertDontSee('Exposition d\'Art');
    }

    /** @test */
    public function admin_can_filter_events_by_date_range()
    {
        $response = $this->actingAs($this->admin)
                         ->get(route('events.index', [
                             'date_start' => Carbon::now()->addDays(15)->format('Y-m-d'),
                             'date_end' => Carbon::now()->addDays(25)->format('Y-m-d'),
                         ]));
        
        $response->assertStatus(200);
        $response->assertViewHas('events');
        $response->assertSee('Exposition d\'Art');
        $response->assertDontSee('Concert de Jazz'); // Avant la date de début
        $response->assertDontSee('Marathon'); // Après la date de fin
    }

    /** @test */
    public function organizer_can_only_search_their_own_events()
    {
        // Créer un autre organisateur avec ses propres événements
        $otherOrganizer = User::factory()->create(['role' => 'organisateur']);
        
        Event::create([
            'user_id' => $otherOrganizer->id,
            'title' => 'Concert de Rock',
            'description' => 'Un super concert de rock',
            'date' => Carbon::now()->addDays(15),
            'location' => 'Paris',
            'status' => 'active',
            'max_participants' => 100,
            'price' => 25.00,
            'currency' => 'EUR',
        ]);
        
        // L'organisateur ne devrait voir que ses propres événements
        $response = $this->actingAs($this->organizer)
                         ->get(route('events.index', ['location' => 'Paris']));
        
        $response->assertStatus(200);
        $response->assertViewHas('events');
        $response->assertSee('Concert de Jazz'); // Son événement
        $response->assertDontSee('Concert de Rock'); // Événement d'un autre organisateur
    }

    /** @test */
    public function client_cannot_access_event_management_search()
    {
        $response = $this->actingAs($this->client)
                         ->get(route('events.index', ['search' => 'Jazz']));
        
        $response->assertStatus(403);
    }
}
