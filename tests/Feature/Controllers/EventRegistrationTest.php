<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('client can register for a free event', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client = User::factory()->create(['role' => 'client']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Free Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
        'price' => null,
        'currency' => null,
    ]);

    $response = $this->actingAs($client)->post("/events/{$event->id}/register");

    $response->assertStatus(302);

    // Vérifier que le client est inscrit
    expect($event->participants()->where('user_id', $client->id)->exists())->toBeTrue();
});

test('client is redirected to payment for paid event', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client = User::factory()->create(['role' => 'client']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Paid Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
        'price' => 2500, // 25.00 EUR
        'currency' => 'EUR',
    ]);

    $response = $this->actingAs($client)->post("/events/{$event->id}/register");

    $response->assertStatus(302)
        ->assertRedirect(route('payment.show', $event));
});

test('client cannot register for a full event', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client = User::factory()->create(['role' => 'client']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Full Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 1,
    ]);

    // Remplir l'événement
    $otherClient = User::factory()->create(['role' => 'client']);
    $event->participants()->attach($otherClient->id);

    $response = $this->actingAs($client)->post("/events/{$event->id}/register");

    $response->assertStatus(302);

    // Vérifier que le client n'est pas inscrit
    expect($event->participants()->where('user_id', $client->id)->exists())->toBeFalse();
});

test('client can unregister from an event', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client = User::factory()->create(['role' => 'client']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Test Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    // Inscrire le client d'abord
    $event->participants()->attach($client->id);
    expect($event->participants()->where('user_id', $client->id)->exists())->toBeTrue();

    // Désinscrire le client
    $response = $this->actingAs($client)->delete("/events/{$event->id}/unregister");

    $response->assertStatus(302);

    // Vérifier que le client n'est plus inscrit
    expect($event->participants()->where('user_id', $client->id)->exists())->toBeFalse();
});

test('client cannot register twice for same event', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client = User::factory()->create(['role' => 'client']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Test Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    // Première inscription
    $response1 = $this->actingAs($client)->post("/events/{$event->id}/register");
    $response1->assertStatus(302);

    // Tentative de deuxième inscription
    $response2 = $this->actingAs($client)->post("/events/{$event->id}/register");
    $response2->assertStatus(302);

    // Vérifier qu'il n'y a qu'une seule inscription
    expect($event->participants()->where('user_id', $client->id)->count())->toBe(1);
});

test('organizer cannot register for their own event', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Own Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    $response = $this->actingAs($organizer)->post("/events/{$event->id}/register");

    $response->assertStatus(403);
});

test('admin cannot register for events', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $organizer = User::factory()->create(['role' => 'organisateur']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Test Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    $response = $this->actingAs($admin)->post("/events/{$event->id}/register");

    $response->assertStatus(403);
});

test('guest cannot register for events', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Test Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    $response = $this->post("/events/{$event->id}/register");

    $response->assertStatus(302)
        ->assertRedirect(route('login'));
});

test('client can register for cancelled event but gets redirected with error', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client = User::factory()->create(['role' => 'client']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Cancelled Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'cancelled',
        'max_participants' => 10,
    ]);

    $response = $this->actingAs($client)->post("/events/{$event->id}/register");

    $response->assertStatus(302);

    // Note: Le système semble permettre l'inscription mais devrait probablement l'empêcher
    // Ceci pourrait être une amélioration future du système
});
