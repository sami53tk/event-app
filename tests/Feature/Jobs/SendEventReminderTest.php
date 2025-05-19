<?php

use App\Jobs\SendEventReminder;
use App\Models\Event;
use App\Models\User;
use App\Mail\EventReminder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('send event reminder job sends emails to participants of upcoming events', function () {
    // Simuler l'envoi d'emails
    Mail::fake();
    
    // Fixer la date actuelle pour les tests
    Carbon::setTestNow(Carbon::create(2023, 5, 15, 12, 0, 0));
    
    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);
    
    // Créer des clients
    $client1 = User::factory()->create(['role' => 'client']);
    $client2 = User::factory()->create(['role' => 'client']);
    
    // Créer un événement qui commence dans moins de 24 heures
    $upcomingEvent = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Upcoming Event',
        'date' => Carbon::now()->addHours(20),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);
    
    // Créer un événement qui commence dans plus de 24 heures
    $laterEvent = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Later Event',
        'date' => Carbon::now()->addDays(2),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);
    
    // Inscrire les clients aux événements
    $upcomingEvent->participants()->attach([$client1->id, $client2->id]);
    $laterEvent->participants()->attach([$client1->id]);
    
    // Exécuter le job
    (new SendEventReminder())->handle();
    
    // Vérifier que les emails ont été envoyés uniquement pour l'événement imminent
    Mail::assertSent(EventReminder::class, function ($mail) use ($client1, $upcomingEvent) {
        return $mail->hasTo($client1->email) && 
               $mail->event->id === $upcomingEvent->id &&
               $mail->user->id === $client1->id;
    });
    
    Mail::assertSent(EventReminder::class, function ($mail) use ($client2, $upcomingEvent) {
        return $mail->hasTo($client2->email) && 
               $mail->event->id === $upcomingEvent->id &&
               $mail->user->id === $client2->id;
    });
    
    // Vérifier qu'aucun email n'a été envoyé pour l'événement ultérieur
    Mail::assertNotSent(EventReminder::class, function ($mail) use ($laterEvent) {
        return $mail->event->id === $laterEvent->id;
    });
    
    // Vérifier le nombre total d'emails envoyés
    Mail::assertSent(EventReminder::class, 2);
    
    // Réinitialiser la date de test
    Carbon::setTestNow();
});
