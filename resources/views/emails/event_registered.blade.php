@extends('emails.layouts.master')

@section('title', 'Inscription confirmée')

@section('content')
    <h1>Inscription confirmée !</h1>

    <p>Bonjour {{ $user->name ?? 'cher participant' }},</p>

    <p>Nous sommes ravis de vous confirmer votre inscription à l'événement suivant :</p>

    <div class="event-details">
        <div class="event-title">{{ $event->title }}</div>
        <div class="event-info"><strong>Date :</strong> {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</div>
        <div class="event-info"><strong>Heure :</strong> {{ \Carbon\Carbon::parse($event->date)->format('H:i') }}</div>
        <div class="event-info"><strong>Lieu :</strong> {{ $event->location }}</div>
        @if($event->price)
            <div class="event-info"><strong>Prix :</strong> {{ $event->price }} {{ $event->currency }}</div>
        @else
            <div class="event-info"><strong>Prix :</strong> Gratuit</div>
        @endif
    </div>

    <div class="success">
        <p>Votre place est réservée ! Nous avons hâte de vous y retrouver.</p>
    </div>

    <p>Vous pouvez consulter les détails de cet événement et gérer vos inscriptions à tout moment depuis votre tableau de bord.</p>

    <p><a href="{{ route('dashboard.client') }}" class="btn">Accéder à mon tableau de bord</a></p>

    <p>Merci de votre participation et à très bientôt !</p>

    <p>L'équipe Event App</p>
@endsection
