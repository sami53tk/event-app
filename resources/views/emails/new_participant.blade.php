@extends('emails.layouts.master')

@section('title', 'Nouvelle inscription à votre événement')

@section('content')
    <h1>Nouvelle inscription à votre événement</h1>
    
    <p>Bonjour {{ $organizer->name }},</p>
    
    <div class="success">
        <p>Bonne nouvelle ! Un nouveau participant vient de s'inscrire à votre événement.</p>
    </div>
    
    <p>Détails de l'événement :</p>
    
    <div class="event-details">
        <div class="event-title">{{ $event->title }}</div>
        <div class="event-info"><strong>Date :</strong> {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</div>
        <div class="event-info"><strong>Heure :</strong> {{ \Carbon\Carbon::parse($event->date)->format('H:i') }}</div>
        <div class="event-info"><strong>Lieu :</strong> {{ $event->location }}</div>
        <div class="event-info"><strong>Participants :</strong> {{ $event->participants()->count() }} / {{ $event->max_participants }}</div>
        <div class="event-info"><strong>Taux de remplissage :</strong> {{ round(($event->participants()->count() / $event->max_participants) * 100) }}%</div>
    </div>
    
    <p>Informations sur le nouveau participant :</p>
    
    <div class="event-details">
        <div class="event-info"><strong>Nom :</strong> {{ $participant->name }}</div>
        <div class="event-info"><strong>Email :</strong> {{ $participant->email }}</div>
        <div class="event-info"><strong>Date d'inscription :</strong> {{ now()->format('d/m/Y à H:i') }}</div>
        @if($event->price && $event->price > 0)
            <div class="event-info"><strong>Statut du paiement :</strong> <span class="highlight">Payé</span></div>
        @endif
    </div>
    
    <p>Vous pouvez consulter la liste complète des participants depuis votre tableau de bord :</p>
    
    <p><a href="{{ route('events.show', $event->id) }}" class="btn">Gérer mon événement</a></p>
    
    <p>Nous vous souhaitons un grand succès pour votre événement !</p>
    
    <p>L'équipe Event App</p>
@endsection
