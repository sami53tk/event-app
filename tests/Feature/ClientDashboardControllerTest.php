<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('client dashboard displays registered events', function () {
    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);

    // Créer un client
    $client = User::factory()->create(['role' => 'client']);

    // Créer deux événements
    $event1 = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Event 1',
        'date' => now()->addDays(5),
        'location' => 'Location 1',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    $event2 = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Event 2',
        'date' => now()->addDays(10),
        'location' => 'Location 2',
        'status' => 'active',
        'max_participants' => 20,
    ]);

    // Inscrire le client aux événements
    $event1->participants()->attach($client->id);
    $event2->participants()->attach($client->id);

    // Accéder au tableau de bord client
    $response = $this->actingAs($client)->get(route('dashboard.client'));

    // Vérifier que la réponse est OK
    $response->assertStatus(200);

    // Vérifier que la vue correcte est chargée
    $response->assertViewIs('dashboard.client');

    // Vérifier que les événements sont passés à la vue
    $response->assertViewHas('events', function ($events) use ($event1, $event2) {
        return $events->contains($event1) && $events->contains($event2);
    });

    // Vérifier que les événements sont affichés dans la vue
    $response->assertSee('Event 1');
    $response->assertSee('Event 2');
});

test('non-client users see empty event list in client dashboard', function () {
    // Créer un administrateur
    $admin = User::factory()->create(['role' => 'admin']);

    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);

    // Accéder au tableau de bord client en tant qu'admin
    $responseAdmin = $this->actingAs($admin)->get(route('dashboard.client'));
    $responseAdmin->assertStatus(200);
    $responseAdmin->assertViewHas('events', function ($events) {
        return $events->isEmpty();
    });

    // Accéder au tableau de bord client en tant qu'organisateur
    $responseOrganizer = $this->actingAs($organizer)->get(route('dashboard.client'));
    $responseOrganizer->assertStatus(200);
    $responseOrganizer->assertViewHas('events', function ($events) {
        return $events->isEmpty();
    });
});
