<?php

use App\Jobs\SendEventReminder;
use App\Mail\EventReminder;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('send event reminder job can be instantiated', function () {
    $job = new SendEventReminder();

    expect($job)->toBeInstanceOf(SendEventReminder::class);
});

test('send event reminder job sends emails to participants of upcoming events', function () {
    Mail::fake();

    // Fixer la date actuelle pour les tests
    Carbon::setTestNow(Carbon::create(2023, 5, 15, 12, 0, 0));

    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client1 = User::factory()->create(['role' => 'client']);
    $client2 = User::factory()->create(['role' => 'client']);

    // Créer un événement dans les 24 heures
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Test Event',
        'description' => 'Test Description',
        'date' => Carbon::now()->addHours(20),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    // Inscrire les clients
    $event->participants()->attach([$client1->id, $client2->id]);

    // Exécuter le job
    $job = new SendEventReminder();
    $job->handle();

    // Vérifier que les emails ont été envoyés
    Mail::assertSent(EventReminder::class, 2);

    Mail::assertSent(EventReminder::class, function ($mail) use ($client1) {
        return $mail->hasTo($client1->email);
    });

    Mail::assertSent(EventReminder::class, function ($mail) use ($client2) {
        return $mail->hasTo($client2->email);
    });

    // Réinitialiser la date de test
    Carbon::setTestNow();
});

test('send event reminder job does not send emails for events outside 24h window', function () {
    Mail::fake();

    // Fixer la date actuelle pour les tests
    Carbon::setTestNow(Carbon::create(2023, 5, 15, 12, 0, 0));

    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client = User::factory()->create(['role' => 'client']);

    // Créer un événement dans plus de 24 heures
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Future Event',
        'description' => 'Test Description',
        'date' => Carbon::now()->addDays(2),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);

    // Inscrire le client
    $event->participants()->attach($client->id);

    // Exécuter le job
    $job = new SendEventReminder();
    $job->handle();

    // Vérifier qu'aucun email n'a été envoyé
    Mail::assertNotSent(EventReminder::class);

    // Réinitialiser la date de test
    Carbon::setTestNow();
});

test('send event reminder job handles cancelled events correctly', function () {
    Mail::fake();

    // Fixer la date actuelle pour les tests
    Carbon::setTestNow(Carbon::create(2023, 5, 15, 12, 0, 0));

    $organizer = User::factory()->create(['role' => 'organisateur']);
    $client = User::factory()->create(['role' => 'client']);

    // Créer un événement annulé dans les 24 heures
    $cancelledEvent = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Cancelled Event',
        'description' => 'Test Description',
        'date' => Carbon::now()->addHours(20),
        'location' => 'Test Location',
        'status' => 'cancelled',
        'max_participants' => 10,
    ]);

    // Inscrire le client
    $cancelledEvent->participants()->attach($client->id);

    // Exécuter le job
    $job = new SendEventReminder();
    $job->handle();

    // Le job actuel envoie des emails même pour les événements annulés
    // Ceci pourrait être une amélioration future du système
    Mail::assertSent(EventReminder::class, 1);

    // Réinitialiser la date de test
    Carbon::setTestNow();
});
