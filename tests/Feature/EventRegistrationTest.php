<?php

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventRegistered;

uses(RefreshDatabase::class);

test('client can register for a free event', function () {
    Mail::fake();
    
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client = User::factory()->create(['role' => 'client']);
    
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Free Event',
        'date' => now()->addDays(10),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
        'price' => null,
    ]);
    
    $response = $this->actingAs($client)->post(route('events.register', $event->id));
    
    $response->assertRedirect();
    $response->assertSessionHas('success');
    
    $this->assertDatabaseHas('event_user', [
        'event_id' => $event->id,
        'user_id' => $client->id,
    ]);
    
    Mail::assertSent(EventRegistered::class, function ($mail) use ($client) {
        return $mail->hasTo($client->email);
    });
});

test('client is redirected to payment for paid event', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client = User::factory()->create(['role' => 'client']);
    
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Paid Event',
        'date' => now()->addDays(10),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
        'price' => 25.99,
        'currency' => 'EUR',
    ]);
    
    $response = $this->actingAs($client)->post(route('events.register', $event->id));
    
    $response->assertRedirect(route('payment.show', $event->id));
    
    $this->assertDatabaseMissing('event_user', [
        'event_id' => $event->id,
        'user_id' => $client->id,
    ]);
});

test('client cannot register for a full event', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client1 = User::factory()->create(['role' => 'client']);
    $client2 = User::factory()->create(['role' => 'client']);
    
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Limited Event',
        'date' => now()->addDays(10),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 1,
        'price' => null,
    ]);
    
    // First client registers
    $this->actingAs($client1)->post(route('events.register', $event->id));
    
    // Second client tries to register
    $response = $this->actingAs($client2)->post(route('events.register', $event->id));
    
    $response->assertRedirect();
    $response->assertSessionHas('error');
    
    $this->assertDatabaseMissing('event_user', [
        'event_id' => $event->id,
        'user_id' => $client2->id,
    ]);
});

test('client can unregister from an event', function () {
    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client = User::factory()->create(['role' => 'client']);
    
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Test Event',
        'date' => now()->addDays(10),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);
    
    // Register the client
    $event->participants()->attach($client->id);
    
    // Unregister the client
    $response = $this->actingAs($client)->delete(route('events.unregister', $event->id));
    
    $response->assertRedirect();
    $response->assertSessionHas('success');
    
    $this->assertDatabaseMissing('event_user', [
        'event_id' => $event->id,
        'user_id' => $client->id,
    ]);
});
