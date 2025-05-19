@extends('emails.layouts.master')

@section('title', 'Désinscription confirmée')

@section('content')
    <h1>Désinscription confirmée</h1>
    
    <p>Bonjour {{ $user->name }},</p>
    
    <p>Nous confirmons votre désinscription de l'événement suivant :</p>
    
    <div class="event-details">
        <div class="event-title">{{ $event->title }}</div>
        <div class="event-info"><strong>Date :</strong> {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</div>
        <div class="event-info"><strong>Heure :</strong> {{ \Carbon\Carbon::parse($event->date)->format('H:i') }}</div>
        <div class="event-info"><strong>Lieu :</strong> {{ $event->location }}</div>
    </div>
    
    @if($event->price && $event->price > 0)
        <div class="alert">
            <p><strong>Information concernant votre paiement :</strong></p>
            <p>Si vous avez effectué un paiement pour cet événement et que vous vous désinscrivez plus de 48 heures avant la date de l'événement, un remboursement sera traité dans les 5 à 10 jours ouvrables.</p>
            <p>Pour toute question concernant votre remboursement, veuillez contacter notre service client.</p>
        </div>
    @endif
    
    <p>Nous espérons vous revoir bientôt sur d'autres événements !</p>
    
    <p>Voici quelques événements qui pourraient vous intéresser :</p>
    
    <p><a href="{{ route('public.events.index') }}" class="btn">Découvrir d'autres événements</a></p>
    
    <p>L'équipe Event App</p>
@endsection
