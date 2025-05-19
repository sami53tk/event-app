@extends('emails.layouts.master')

@section('title', 'Rappel d\'événement')

@section('content')
    <h1>Rappel : Votre événement approche !</h1>

    <p>Bonjour {{ $user->name }},</p>

    <p>Nous vous rappelons que vous êtes inscrit(e) à l'événement suivant qui aura lieu <span class="highlight">demain</span> :</p>

    <div class="event-details">
        <div class="event-title">{{ $event->title }}</div>
        <div class="event-info"><strong>Date :</strong> {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</div>
        <div class="event-info"><strong>Heure :</strong> {{ \Carbon\Carbon::parse($event->date)->format('H:i') }}</div>
        <div class="event-info"><strong>Lieu :</strong> {{ $event->location }}</div>
        @if($event->description)
            <div class="event-info"><strong>Description :</strong> {{ Str::limit($event->description, 150) }}</div>
        @endif
    </div>

    <p>N'oubliez pas de prendre avec vous :</p>
    <ul>
        <li>Une pièce d'identité</li>
        <li>Votre confirmation d'inscription (ce mail peut servir de justificatif)</li>
        @if($event->price)
            <li>Votre reçu de paiement</li>
        @endif
    </ul>

    <p><a href="{{ route('dashboard.client') }}" class="btn">Voir les détails de l'événement</a></p>

    <p>Nous sommes impatients de vous accueillir !</p>

    <p>L'équipe Event App</p>
@endsection
