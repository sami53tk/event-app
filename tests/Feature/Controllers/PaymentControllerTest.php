<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('client can view checkout page for paid event', function () {
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

    $response = $this->actingAs($client)->get("/payment/{$event->id}/show");

    $response->assertStatus(200);
});

test('client is redirected from checkout page for free event', function () {
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

    $response = $this->actingAs($client)->get("/payment/{$event->id}/show");

    $response->assertStatus(302);
});

test('client can access payment success page', function () {
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
        'price' => 2500,
        'currency' => 'EUR',
    ]);

    $response = $this->actingAs($client)->get("/payment/{$event->id}/success");

    // La page peut rediriger après traitement
    expect($response->status())->toBeIn([200, 302]);
});

test('client can access payment cancel page', function () {
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
        'price' => 2500,
        'currency' => 'EUR',
    ]);

    $response = $this->actingAs($client)->get("/payment/{$event->id}/cancel");

    // La page peut rediriger après traitement
    expect($response->status())->toBeIn([200, 302]);
});

test('non-client users cannot access payment pages', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $admin = User::factory()->create(['role' => 'admin']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Paid Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
        'price' => 2500,
        'currency' => 'EUR',
    ]);

    // Test avec admin
    $response = $this->actingAs($admin)->get("/payment/{$event->id}/show");
    $response->assertStatus(403);

    // Test avec organisateur
    $response = $this->actingAs($organizer)->get("/payment/{$event->id}/show");
    $response->assertStatus(403);
});

test('guest cannot access payment pages', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Paid Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
        'price' => 2500,
        'currency' => 'EUR',
    ]);

    $response = $this->get("/payment/{$event->id}/show");

    $response->assertStatus(302)
        ->assertRedirect(route('login'));
});

test('client cannot access payment for full event', function () {
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
        'price' => 2500,
        'currency' => 'EUR',
    ]);

    // Remplir l'événement
    $otherClient = User::factory()->create(['role' => 'client']);
    $event->participants()->attach($otherClient->id);

    $response = $this->actingAs($client)->get("/payment/{$event->id}/show");

    $response->assertStatus(302);
});

test('client cannot access payment if already registered', function () {
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
        'price' => 2500,
        'currency' => 'EUR',
    ]);

    // Inscrire le client d'abord
    $event->participants()->attach($client->id);

    $response = $this->actingAs($client)->get("/payment/{$event->id}/show");

    $response->assertStatus(302);
});

test('payment success registers client to event', function () {
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
        'price' => 2500,
        'currency' => 'EUR',
    ]);

    // Simuler un succès de paiement
    $response = $this->actingAs($client)->get("/payment/{$event->id}/success");

    // La page peut rediriger après inscription
    expect($response->status())->toBeIn([200, 302]);

    // Vérifier que le client est maintenant inscrit
    expect($event->participants()->where('user_id', $client->id)->exists())->toBeTrue();
});

test('payment cancel does not register client to event', function () {
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
        'price' => 2500,
        'currency' => 'EUR',
    ]);

    // Simuler une annulation de paiement
    $response = $this->actingAs($client)->get("/payment/{$event->id}/cancel");

    // La page peut rediriger après traitement
    expect($response->status())->toBeIn([200, 302]);

    // Vérifier que le client n'est pas inscrit
    expect($event->participants()->where('user_id', $client->id)->exists())->toBeFalse();
});
