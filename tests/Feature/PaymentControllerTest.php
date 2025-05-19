<?php

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Mockery\MockInterface;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->stripe = $this->mock('Stripe\Stripe');
    $this->session = $this->mock('Stripe\Checkout\Session');
});

test('client can view checkout page for paid event', function () {
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
    
    $response = $this->actingAs($client)->get(route('payment.show', $event->id));
    
    $response->assertStatus(200);
    $response->assertViewIs('payments.checkout');
    $response->assertViewHas('event', $event);
});

test('client is redirected from checkout page for free event', function () {
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
    
    $response = $this->actingAs($client)->get(route('payment.show', $event->id));
    
    $response->assertRedirect(route('events.show', $event->id));
    $response->assertSessionHas('error');
});

test('client is registered after successful payment', function () {
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
    
    $response = $this->actingAs($client)->get(route('payment.success', $event->id));
    
    $response->assertRedirect(route('events.show', $event->id));
    $response->assertSessionHas('success');
    
    $this->assertDatabaseHas('event_user', [
        'event_id' => $event->id,
        'user_id' => $client->id,
    ]);
});

test('client is not registered after cancelled payment', function () {
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
    
    $response = $this->actingAs($client)->get(route('payment.cancel', $event->id));
    
    $response->assertRedirect(route('events.show', $event->id));
    $response->assertSessionHas('error');
    
    $this->assertDatabaseMissing('event_user', [
        'event_id' => $event->id,
        'user_id' => $client->id,
    ]);
});
