@extends('emails.layouts.master')

@section('title', 'Annulation d\'événement')

@section('content')
    <h1>Annulation d'événement</h1>

    <p>Bonjour {{ $user->name }},</p>

    <div class="alert">
        <p>Nous sommes au regret de vous informer que l'événement suivant a été <strong>annulé</strong> :</p>
    </div>

    <div class="event-details">
        <div class="event-title">{{ $event->title }}</div>
        <div class="event-info"><strong>Date prévue :</strong> {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</div>
        <div class="event-info"><strong>Heure prévue :</strong> {{ \Carbon\Carbon::parse($event->date)->format('H:i') }}</div>
        <div class="event-info"><strong>Lieu :</strong> {{ $event->location }}</div>
    </div>

    <p>Nous sommes sincèrement désolés pour la gêne occasionnée par cette annulation.</p>

    @if($event->price)
    <p>Si vous avez effectué un paiement pour cet événement, un remboursement complet sera traité automatiquement dans les 5 à 10 jours ouvrables.</p>
    @endif

    <p>Nous vous invitons à découvrir d'autres événements qui pourraient vous intéresser :</p>

    <p><a href="{{ route('public.events.index') }}" class="btn">Découvrir d'autres événements</a></p>

    <p>Nous vous remercions pour votre compréhension et espérons vous revoir très bientôt lors d'un prochain événement.</p>

    <p>L'équipe Event App</p>
@endsection
