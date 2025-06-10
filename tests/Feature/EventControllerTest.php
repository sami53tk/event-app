<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view all events', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $organizer = User::factory()->create(['role' => 'organisateur']);

    $event1 = Event::create([
        'user_id' => $admin->id,
        'title' => 'Admin Event',
        'date' => now()->addDays(10),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    $event2 = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Organizer Event',
        'date' => now()->addDays(15),
        'location' => 'Test Location 2',
        'status' => 'active',
        'max_participants' => 20,
    ]);

    $response = $this->actingAs($admin)->get(route('events.index'));

    $response->assertStatus(200);
    $response->assertSee('Admin Event');
    $response->assertSee('Organizer Event');
});

test('organizer can only view their own events', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $organizer = User::factory()->create(['role' => 'organisateur']);

    $event1 = Event::create([
        'user_id' => $admin->id,
        'title' => 'Admin Event',
        'date' => now()->addDays(10),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    $event2 = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Organizer Event',
        'date' => now()->addDays(15),
        'location' => 'Test Location 2',
        'status' => 'active',
        'max_participants' => 20,
    ]);

    $response = $this->actingAs($organizer)->get(route('events.index'));

    $response->assertStatus(200);
    $response->assertDontSee('Admin Event');
    $response->assertSee('Organizer Event');
});

test('client cannot access events management', function () {
    $client = User::factory()->create(['role' => 'client']);

    $response = $this->actingAs($client)->get(route('events.index'));

    $response->assertStatus(403);
});

test('admin can create an event', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('events.create'));
    $response->assertStatus(200);

    $eventData = [
        'title' => 'New Admin Event',
        'date' => now()->addDays(20)->format('Y-m-d H:i:s'),
        'location' => 'New Location',
        'status' => 'active',
        'max_participants' => 30,
        'price' => 15.99,
        'currency' => 'EUR',
    ];

    $response = $this->actingAs($admin)->post(route('events.store'), $eventData);

    $response->assertRedirect(route('events.index'));
    $this->assertDatabaseHas('events', ['title' => 'New Admin Event']);
});

test('organizer can create an event', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);

    $response = $this->actingAs($organizer)->get(route('events.create'));
    $response->assertStatus(200);

    $eventData = [
        'title' => 'New Organizer Event',
        'date' => now()->addDays(25)->format('Y-m-d H:i:s'),
        'location' => 'New Location 2',
        'status' => 'active',
        'max_participants' => 40,
    ];

    $response = $this->actingAs($organizer)->post(route('events.store'), $eventData);

    $response->assertRedirect(route('events.index'));
    $this->assertDatabaseHas('events', ['title' => 'New Organizer Event']);
});

test('client can view public events', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client = User::factory()->create(['role' => 'client']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Public Event',
        'date' => now()->addDays(10),
        'location' => 'Public Location',
        'status' => 'active',
        'max_participants' => 50,
    ]);

    $response = $this->actingAs($client)->get(route('public.events.index'));

    $response->assertStatus(200);
    $response->assertSee('Public Event');
});
