<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can be created with factory', function () {
    $user = User::factory()->create([
        'role' => 'client',
    ]);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->not->toBeEmpty()
        ->and($user->email)->not->toBeEmpty()
        ->and($user->role)->toBe('client');
});

test('user factory creates different roles', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client = User::factory()->create(['role' => 'client']);

    expect($admin->role)->toBe('admin')
        ->and($organizer->role)->toBe('organisateur')
        ->and($client->role)->toBe('client');
});

test('user has events relationship', function () {
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

    expect($organizer->events)->toHaveCount(1)
        ->and($organizer->events->first()->title)->toBe('Test Event');
});

test('user has participated events relationship', function () {
    $client = User::factory()->create(['role' => 'client']);
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

    // Inscrire le client à l'événement
    $event->participants()->attach($client->id);

    expect($client->participatedEvents)->toHaveCount(1)
        ->and($client->participatedEvents->first()->title)->toBe('Test Event');
});

test('user email must be unique', function () {
    User::factory()->create(['email' => 'test@example.com']);

    expect(function () {
        User::factory()->create(['email' => 'test@example.com']);
    })->toThrow(\Illuminate\Database\QueryException::class);
});

test('user password is hashed', function () {
    $user = User::factory()->create([
        'password' => 'plaintext-password',
    ]);

    expect($user->password)->not->toBe('plaintext-password')
        ->and(strlen($user->password))->toBeGreaterThan(50);
});

test('user can have multiple events as organizer', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);

    Event::create([
        'user_id' => $organizer->id,
        'title' => 'Event 1',
        'description' => 'Description 1',
        'date' => now()->addDays(7),
        'location' => 'Location 1',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    Event::create([
        'user_id' => $organizer->id,
        'title' => 'Event 2',
        'description' => 'Description 2',
        'date' => now()->addDays(14),
        'location' => 'Location 2',
        'status' => 'active',
        'max_participants' => 20,
    ]);

    expect($organizer->events)->toHaveCount(2);
});

test('user can participate in multiple events', function () {
    $client = User::factory()->create(['role' => 'client']);
    $organizer = User::factory()->create(['role' => 'organisateur']);

    $event1 = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Event 1',
        'description' => 'Description 1',
        'date' => now()->addDays(7),
        'location' => 'Location 1',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    $event2 = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Event 2',
        'description' => 'Description 2',
        'date' => now()->addDays(14),
        'location' => 'Location 2',
        'status' => 'active',
        'max_participants' => 20,
    ]);

    // Inscrire le client aux deux événements
    $event1->participants()->attach($client->id);
    $event2->participants()->attach($client->id);

    expect($client->participatedEvents)->toHaveCount(2);
});
