<?php

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can delete any event', function () {
    // Créer un administrateur
    $admin = User::factory()->create(['role' => 'admin']);
    
    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);
    
    // Créer un événement
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Test Event',
        'date' => now()->addDays(10),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);
    
    // Envoyer la requête de suppression
    $response = $this->actingAs($admin)->delete(route('events.destroy', $event->id));
    
    // Vérifier la redirection
    $response->assertRedirect(route('events.index'));
    $response->assertSessionHas('success');
    
    // Vérifier que l'événement a été supprimé de la base de données
    $this->assertDatabaseMissing('events', ['id' => $event->id]);
});

test('organizer can delete their own event', function () {
    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);
    
    // Créer un événement
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Test Event',
        'date' => now()->addDays(10),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);
    
    // Envoyer la requête de suppression
    $response = $this->actingAs($organizer)->delete(route('events.destroy', $event->id));
    
    // Vérifier la redirection
    $response->assertRedirect(route('events.index'));
    $response->assertSessionHas('success');
    
    // Vérifier que l'événement a été supprimé de la base de données
    $this->assertDatabaseMissing('events', ['id' => $event->id]);
});

test('organizer cannot delete events they do not own', function () {
    // Créer deux organisateurs
    $organizer1 = User::factory()->create(['role' => 'organisateur']);
    $organizer2 = User::factory()->create(['role' => 'organisateur']);
    
    // Créer un événement appartenant à organizer1
    $event = Event::create([
        'user_id' => $organizer1->id,
        'title' => 'Test Event',
        'date' => now()->addDays(10),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);
    
    // Envoyer la requête de suppression en tant qu'organizer2
    $response = $this->actingAs($organizer2)->delete(route('events.destroy', $event->id));
    
    // Vérifier que l'accès est refusé
    $response->assertStatus(403);
    
    // Vérifier que l'événement n'a pas été supprimé
    $this->assertDatabaseHas('events', ['id' => $event->id]);
});

test('client cannot delete events', function () {
    // Créer un organisateur
    $organizer = User::factory()->create(['role' => 'organisateur']);
    
    // Créer un client
    $client = User::factory()->create(['role' => 'client']);
    
    // Créer un événement
    $event = Event::create([
        'user_id' => $organizer->id,
        'title' => 'Test Event',
        'date' => now()->addDays(10),
        'location' => 'Test Location',
        'status' => 'active',
        'max_participants' => 10,
    ]);
    
    // Envoyer la requête de suppression en tant que client
    $response = $this->actingAs($client)->delete(route('events.destroy', $event->id));
    
    // Vérifier que l'accès est refusé
    $response->assertStatus(403);
    
    // Vérifier que l'événement n'a pas été supprimé
    $this->assertDatabaseHas('events', ['id' => $event->id]);
});
