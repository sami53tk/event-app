<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('event can be created', function () {
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

    expect($event)->toBeInstanceOf(Event::class)
        ->and($event->title)->toBe('Test Event')
        ->and($event->status)->toBe('active')
        ->and($event->max_participants)->toBe(10);
});

test('event belongs to user', function () {
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

    expect($event->user)->toBeInstanceOf(User::class)
        ->and($event->user->id)->toBe($organizer->id)
        ->and($event->user->role)->toBe('organisateur');
});

test('event can have participants', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client1 = User::factory()->create(['role' => 'client']);
    $client2 = User::factory()->create(['role' => 'client']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Test Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    // Ajouter des participants
    $event->participants()->attach([$client1->id, $client2->id]);

    expect($event->participants)->toHaveCount(2)
        ->and($event->participants->pluck('id')->toArray())->toContain($client1->id)
        ->and($event->participants->pluck('id')->toArray())->toContain($client2->id);
});

test('event can be created with price', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Paid Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
        'price' => 2500, // 25.00 EUR en centimes
        'currency' => 'EUR',
    ]);

    expect($event->price)->toBe(2500)
        ->and($event->currency)->toBe('EUR');
});

test('event can be created without price (free)', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);

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

    expect($event->price)->toBeNull()
        ->and($event->currency)->toBeNull();
});

test('event status can be updated', function () {
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

    $event->update(['status' => 'cancelled']);

    expect($event->fresh()->status)->toBe('cancelled');
});

test('event date is cast to carbon instance', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $futureDate = now()->addDays(7);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Test Event',
        'description' => 'Test Description',
        'date' => $futureDate,
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    expect($event->date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('event can have banner', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);

    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Test Event',
        'description' => 'Test Description',
        'date' => now()->addDays(7),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
        'banner' => 'banners/test-banner.jpg',
    ]);

    expect($event->banner)->toBe('banners/test-banner.jpg');
});

test('event requires user_id', function () {
    expect(function () {
        Event::create([
            'title' => 'Test Event',
            'description' => 'Test Description',
            'date' => now()->addDays(7),
            'location' => 'Test Location',
            'status' => 'active',
            'max_participants' => 10,
        ]);
    })->toThrow(\Illuminate\Database\QueryException::class);
});
