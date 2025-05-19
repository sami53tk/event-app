@extends('emails.layouts.master')

@section('title', 'Rappel : votre événement approche')

@section('content')
    <h1>Rappel : votre événement approche</h1>
    
    <p>Bonjour {{ $organizer->name }},</p>
    
    <p>Nous vous rappelons que votre événement aura lieu <span class="highlight">demain</span> :</p>
    
    <div class="event-details">
        <div class="event-title">{{ $event->title }}</div>
        <div class="event-info"><strong>Date :</strong> {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</div>
        <div class="event-info"><strong>Heure :</strong> {{ \Carbon\Carbon::parse($event->date)->format('H:i') }}</div>
        <div class="event-info"><strong>Lieu :</strong> {{ $event->location }}</div>
        <div class="event-info"><strong>Participants :</strong> {{ $event->participants()->count() }} / {{ $event->max_participants }}</div>
    </div>
    
    <p>Voici une liste de vérification pour vous aider à préparer votre événement :</p>
    <ul>
        <li>Confirmez les détails avec le lieu de l'événement</li>
        <li>Préparez la liste des participants pour l'enregistrement</li>
        <li>Vérifiez que tout le matériel nécessaire est prêt</li>
        <li>Préparez la signalétique et les badges si nécessaire</li>
        <li>Testez les équipements techniques (son, vidéo, etc.)</li>
        <li>Prévoyez une solution de secours en cas d'imprévu</li>
    </ul>
    
    <p>Vous pouvez consulter et télécharger la liste complète des participants depuis votre tableau de bord :</p>
    
    <p><a href="{{ route('events.show', $event->id) }}" class="btn">Gérer mon événement</a></p>
    
    <p>Nous vous souhaitons un excellent événement !</p>
    
    <p>L'équipe Event App</p>
@endsection
