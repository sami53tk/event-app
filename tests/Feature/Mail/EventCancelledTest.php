<?php

use App\Mail\EventCancelled;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('event cancelled email has correct content', function () {
    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);
    
    // Créer un client
    $client = User::factory()->create(['role' => 'client']);
    
    // Créer un événement
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Test Event',
        'date' => now()->addDay(),
        'location' => 'Test Location',
        'status' => 'annule',
        'max_participants' => 10,
    ]);
    
    // Créer l'email
    $mailable = new EventCancelled($event, $client);
    
    // Vérifier que l'email a le bon sujet
    $mailable->assertHasSubject('Annulation de l\'événement');
    
    // Vérifier que l'email utilise la bonne vue
    $mailable->assertSeeInHtml($event->title);
    $mailable->assertSeeInHtml($client->name);
    
    // Vérifier que l'email contient les bonnes données
    expect($mailable->event->id)->toBe($event->id);
    expect($mailable->user->id)->toBe($client->id);
});

test('event cancelled email can be rendered', function () {
    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);
    
    // Créer un client
    $client = User::factory()->create(['role' => 'client']);
    
    // Créer un événement
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Test Event',
        'date' => now()->addDay(),
        'location' => 'Test Location',
        'status' => 'annule',
        'max_participants' => 10,
    ]);
    
    // Simuler l'envoi d'emails
    Mail::fake();
    
    // Envoyer l'email
    Mail::to($client->email)->send(new EventCancelled($event, $client));
    
    // Vérifier que l'email a été envoyé
    Mail::assertSent(EventCancelled::class, function ($mail) use ($client) {
        return $mail->hasTo($client->email);
    });
});
