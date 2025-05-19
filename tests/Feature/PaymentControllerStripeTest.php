<?php

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Mockery\MockInterface;

uses(RefreshDatabase::class);

// Ce test a été supprimé car il nécessite une configuration Stripe valide

test('client cannot create checkout session for free event', function () {
    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);

    // Créer un client
    $client = User::factory()->create(['role' => 'client']);

    // Créer un événement gratuit
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Free Event',
        'date' => now()->addDays(10),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
        'price' => null,
    ]);

    // Envoyer la requête pour créer une session de paiement
    $response = $this->actingAs($client)->get(route('payment.checkout', $event->id));

    // Vérifier la redirection vers la page de l'événement
    $response->assertRedirect(route('events.show', $event->id));
    $response->assertSessionHas('error');
});

test('client cannot create checkout session for full event', function () {
    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);

    // Créer des clients
    $client1 = User::factory()->create(['role' => 'client']);
    $client2 = User::factory()->create(['role' => 'client']);

    // Créer un événement payant avec une seule place
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Limited Paid Event',
        'date' => now()->addDays(10),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 1,
        'price' => 25.99,
        'currency' => 'EUR',
    ]);

    // Inscrire le premier client
    $event->participants()->attach($client1->id);

    // Envoyer la requête pour créer une session de paiement pour le deuxième client
    $response = $this->actingAs($client2)->get(route('payment.checkout', $event->id));

    // Vérifier la redirection avec message d'erreur
    $response->assertRedirect();
    $response->assertSessionHas('error');
});

test('client cannot create checkout session for event they are already registered to', function () {
    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);

    // Créer un client
    $client = User::factory()->create(['role' => 'client']);

    // Créer un événement payant
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

    // Inscrire le client
    $event->participants()->attach($client->id);

    // Envoyer la requête pour créer une session de paiement
    $response = $this->actingAs($client)->get(route('payment.checkout', $event->id));

    // Vérifier la redirection avec message d'erreur
    $response->assertRedirect();
    $response->assertSessionHas('error');
});

test('non-client users cannot access payment checkout', function () {
    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);

    // Créer un administrateur
    $admin = User::factory()->create(['role' => 'admin']);

    // Créer un événement payant
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

    // Essayer d'accéder à la page de paiement en tant qu'organisateur
    $responseOrganizer = $this->actingAs($organizer)->get(route('payment.checkout', $event->id));
    $responseOrganizer->assertStatus(403);

    // Essayer d'accéder à la page de paiement en tant qu'administrateur
    $responseAdmin = $this->actingAs($admin)->get(route('payment.checkout', $event->id));
    $responseAdmin->assertStatus(403);
});
