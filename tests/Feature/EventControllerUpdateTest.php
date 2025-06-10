<?php

use App\Mail\EventCancelled;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('admin can update an event', function () {
    // Simuler l'envoi d'emails
    Mail::fake();

    // Créer un administrateur
    $admin = User::factory()->create(['role' => 'admin']);

    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);

    // Créer un événement
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Original Event',
        'date' => now()->addDays(10),
        'location' => 'Original Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    // Données pour la mise à jour
    $updatedData = [
        'title' => 'Updated Event',
        'date' => now()->addDays(15)->format('Y-m-d H:i:s'),
        'location' => 'Updated Location',
        'status' => 'active',
        'max_participants' => 20,
        'price' => 25.99,
        'currency' => 'EUR',
    ];

    // Envoyer la requête de mise à jour
    $response = $this->actingAs($admin)->put(route('events.update', $event->id), $updatedData);

    // Vérifier la redirection
    $response->assertRedirect(route('events.index'));
    $response->assertSessionHas('success');

    // Vérifier que l'événement a été mis à jour en base de données
    $this->assertDatabaseHas('events', [
        'id' => $event->id,
        'title' => 'Updated Event',
        'location' => 'Updated Location',
        'max_participants' => 20,
        'price' => 25.99,
        'currency' => 'EUR',
    ]);

    // Vérifier qu'aucun email n'a été envoyé (car le statut n'a pas changé)
    Mail::assertNothingSent();
});

test('organizer can update their own event', function () {
    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);

    // Créer un événement
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Original Event',
        'date' => now()->addDays(10),
        'location' => 'Original Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    // Données pour la mise à jour
    $updatedData = [
        'title' => 'Updated Event',
        'date' => now()->addDays(15)->format('Y-m-d H:i:s'),
        'location' => 'Updated Location',
        'status' => 'active',
        'max_participants' => 20,
    ];

    // Envoyer la requête de mise à jour
    $response = $this->actingAs($organizer)->put(route('events.update', $event->id), $updatedData);

    // Vérifier la redirection
    $response->assertRedirect(route('events.index'));
    $response->assertSessionHas('success');

    // Vérifier que l'événement a été mis à jour en base de données
    $this->assertDatabaseHas('events', [
        'id' => $event->id,
        'title' => 'Updated Event',
        'location' => 'Updated Location',
        'max_participants' => 20,
    ]);
});

test('organizer cannot update events they do not own', function () {
    // Créer deux organisateurs
    $organizer1 = User::factory()->create(['role' => 'organisateur']);
    $organizer2 = User::factory()->create(['role' => 'organisateur']);

    // Créer un événement appartenant à organizer1
    $event = Event::create([
        'user_id' => $organizer1->id,
        'title' => 'Original Event',
        'date' => now()->addDays(10),
        'location' => 'Original Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    // Données pour la mise à jour
    $updatedData = [
        'title' => 'Updated Event',
        'date' => now()->addDays(15)->format('Y-m-d H:i:s'),
        'location' => 'Updated Location',
        'status' => 'active',
        'max_participants' => 20,
    ];

    // Envoyer la requête de mise à jour en tant qu'organizer2
    $response = $this->actingAs($organizer2)->put(route('events.update', $event->id), $updatedData);

    // Vérifier que l'accès est refusé
    $response->assertStatus(403);

    // Vérifier que l'événement n'a pas été mis à jour
    $this->assertDatabaseHas('events', [
        'id' => $event->id,
        'title' => 'Original Event',
        'location' => 'Original Location',
        'max_participants' => 10,
    ]);
});

test('emails are sent when event is cancelled', function () {
    // Simuler l'envoi d'emails
    Mail::fake();

    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);

    // Créer des clients
    $client1 = User::factory()->create(['role' => 'client']);
    $client2 = User::factory()->create(['role' => 'client']);

    // Créer un événement
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Original Event',
        'date' => now()->addDays(10),
        'location' => 'Original Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    // Inscrire les clients à l'événement
    $event->participants()->attach([$client1->id, $client2->id]);

    // Données pour la mise à jour (annulation)
    $updatedData = [
        'title' => 'Original Event',
        'date' => now()->addDays(10)->format('Y-m-d H:i:s'),
        'location' => 'Original Location',
        'status' => 'annule',
        'max_participants' => 10,
    ];

    // Envoyer la requête de mise à jour
    $response = $this->actingAs($organizer)->put(route('events.update', $event->id), $updatedData);

    // Vérifier la redirection
    $response->assertRedirect(route('events.index'));
    $response->assertSessionHas('success');

    // Vérifier que l'événement a été mis à jour en base de données
    $this->assertDatabaseHas('events', [
        'id' => $event->id,
        'status' => 'annule',
    ]);

    // Vérifier que les emails ont été envoyés aux participants
    Mail::assertSent(EventCancelled::class, function ($mail) use ($client1) {
        return $mail->hasTo($client1->email);
    });

    Mail::assertSent(EventCancelled::class, function ($mail) use ($client2) {
        return $mail->hasTo($client2->email);
    });

    // Vérifier le nombre total d'emails envoyés
    Mail::assertSent(EventCancelled::class, 2);
});
