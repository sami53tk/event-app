@extends('emails.layouts.master')

@section('title', 'Événement créé avec succès')

@section('content')
    <h1>Votre événement a été créé avec succès !</h1>
    
    <p>Bonjour {{ $user->name }},</p>
    
    <div class="success">
        <p>Félicitations ! Votre événement a été créé et publié avec succès sur Event App.</p>
    </div>
    
    <p>Voici les détails de votre événement :</p>
    
    <div class="event-details">
        <div class="event-title">{{ $event->title }}</div>
        <div class="event-info"><strong>Date :</strong> {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</div>
        <div class="event-info"><strong>Heure :</strong> {{ \Carbon\Carbon::parse($event->date)->format('H:i') }}</div>
        <div class="event-info"><strong>Lieu :</strong> {{ $event->location }}</div>
        <div class="event-info"><strong>Capacité :</strong> {{ $event->max_participants }} participants</div>
        @if($event->price)
            <div class="event-info"><strong>Prix :</strong> {{ $event->price }} {{ $event->currency }}</div>
        @else
            <div class="event-info"><strong>Prix :</strong> Gratuit</div>
        @endif
        <div class="event-info"><strong>Statut :</strong> {{ ucfirst($event->status) }}</div>
    </div>
    
    <p>Voici quelques conseils pour promouvoir votre événement :</p>
    <ul>
        <li>Partagez le lien de votre événement sur les réseaux sociaux</li>
        <li>Envoyez des invitations personnalisées à votre réseau</li>
        <li>Mettez à jour régulièrement les informations de votre événement</li>
        <li>Répondez rapidement aux questions des participants potentiels</li>
    </ul>
    
    <p>Vous pouvez gérer votre événement à tout moment depuis votre tableau de bord :</p>
    
    <p><a href="{{ route('events.show', $event->id) }}" class="btn">Gérer mon événement</a></p>
    
    <p>Nous vous souhaitons un grand succès pour votre événement !</p>
    
    <p>L'équipe Event App</p>
@endsection
