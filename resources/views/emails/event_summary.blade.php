@extends('emails.layouts.master')

@section('title', 'Récapitulatif de l\'événement')

@section('content')
    <h1>Récapitulatif de l'événement</h1>
    
    <p>Bonjour {{ $user->name }},</p>
    
    <p>Merci d'avoir organisé l'événement suivant :</p>
    
    <div class="event-details">
        <div class="event-title">{{ $event->title }}</div>
        <div class="event-info"><strong>Date :</strong> {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</div>
        <div class="event-info"><strong>Heure :</strong> {{ \Carbon\Carbon::parse($event->date)->format('H:i') }}</div>
        <div class="event-info"><strong>Lieu :</strong> {{ $event->location }}</div>
        <div class="event-info"><strong>Participants :</strong> {{ $event->participants()->count() }} / {{ $event->max_participants }}</div>
        @if($event->price)
            <div class="event-info"><strong>Prix :</strong> {{ $event->price }} {{ $event->currency }}</div>
            <div class="event-info"><strong>Revenus totaux :</strong> {{ $event->price * $event->participants()->count() }} {{ $event->currency }}</div>
        @else
            <div class="event-info"><strong>Prix :</strong> Gratuit</div>
        @endif
    </div>
    
    <p>Statistiques de l'événement :</p>
    <ul>
        <li>Taux de remplissage : {{ round(($event->participants()->count() / $event->max_participants) * 100) }}%</li>
        <li>Nombre de vues : {{ rand(50, 500) }}</li>
        <li>Taux de conversion : {{ rand(10, 80) }}%</li>
    </ul>
    
    <p>Vous pouvez consulter la liste complète des participants et plus de statistiques sur votre tableau de bord :</p>
    
    <p><a href="{{ route('events.show', $event->id) }}" class="btn">Voir les détails de l'événement</a></p>
    
    <p>Nous vous remercions d'utiliser notre plateforme pour organiser vos événements !</p>
    
    <p>L'équipe Event App</p>
@endsection
