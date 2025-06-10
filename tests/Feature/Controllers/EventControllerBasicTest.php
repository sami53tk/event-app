<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest can view public events page', function () {
    $response = $this->get('/public-events');

    $response->assertStatus(200);
});

test('authenticated user can view public events page', function () {
    $user = User::factory()->create(['role' => 'client']);

    $response = $this->actingAs($user)->get('/public-events');

    $response->assertStatus(200);
});

test('public events page shows active events', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);

    // Créer un événement actif
    $activeEvent = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Active Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    // Créer un événement annulé
    $cancelledEvent = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Cancelled Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'cancelled',
        'max_participants' => 10,
    ]);

    $response = $this->get('/public-events');

    $response->assertStatus(200)
        ->assertSee('Active Event')
        ->assertDontSee('Cancelled Event');
});

test('admin can access events management', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/events');

    $response->assertStatus(200);
});

test('organizer can access events management', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);

    $response = $this->actingAs($organizer)->get('/events');

    $response->assertStatus(200);
});

test('client cannot access events management', function () {
    $client = User::factory()->create(['role' => 'client']);

    $response = $this->actingAs($client)->get('/events');

    $response->assertStatus(403);
});

test('guest cannot access events management', function () {
    $response = $this->get('/events');

    $response->assertStatus(302)
        ->assertRedirect(route('login'));
});

test('admin can view event creation form', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/events/create');

    $response->assertStatus(200);
});

test('organizer can view event creation form', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);

    $response = $this->actingAs($organizer)->get('/events/create');

    $response->assertStatus(200);
});

test('client cannot view event creation form', function () {
    $client = User::factory()->create(['role' => 'client']);

    $response = $this->actingAs($client)->get('/events/create');

    $response->assertStatus(403);
});

test('admin can create event', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $eventData = [
        'title' => 'New Test Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7)->format('Y-m-d H:i:s'),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 15,
    ];

    $response = $this->actingAs($admin)->post('/events', $eventData);

    $response->assertStatus(302);

    $this->assertDatabaseHas('events', [
        'title' => 'New Test Event',
        'user_id' => $admin->id,
        'status' => 'active',
        'max_participants' => 15,
    ]);
});

test('organizer can create event', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);

    $eventData = [
        'title' => 'Organizer Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7)->format('Y-m-d H:i:s'),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 20,
    ];

    $response = $this->actingAs($organizer)->post('/events', $eventData);

    $response->assertStatus(302);

    $this->assertDatabaseHas('events', [
        'title' => 'Organizer Event',
        'user_id' => $organizer->id,
        'status' => 'active',
        'max_participants' => 20,
    ]);
});

test('client cannot create event', function () {
    $client = User::factory()->create(['role' => 'client']);

    $eventData = [
        'title' => 'Client Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7)->format('Y-m-d H:i:s'),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ];

    $response = $this->actingAs($client)->post('/events', $eventData);

    $response->assertStatus(403);

    $this->assertDatabaseMissing('events', [
        'title' => 'Client Event',
    ]);
});
