@extends('emails.layouts.master')

@section('title', 'Paiement confirmé')

@section('content')
    <h1>Paiement confirmé avec succès !</h1>
    
    <p>Bonjour {{ $user->name }},</p>
    
    <div class="success">
        <p>Nous vous confirmons que votre paiement a été traité avec succès pour l'événement suivant :</p>
    </div>
    
    <div class="event-details">
        <div class="event-title">{{ $event->title }}</div>
        <div class="event-info"><strong>Date :</strong> {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</div>
        <div class="event-info"><strong>Heure :</strong> {{ \Carbon\Carbon::parse($event->date)->format('H:i') }}</div>
        <div class="event-info"><strong>Lieu :</strong> {{ $event->location }}</div>
        <div class="event-info"><strong>Montant payé :</strong> {{ $event->price }} {{ $event->currency }}</div>
        <div class="event-info"><strong>Date de paiement :</strong> {{ now()->format('d/m/Y H:i') }}</div>
        <div class="event-info"><strong>Référence de transaction :</strong> {{ $paymentId ?? 'TRANS-'.time() }}</div>
    </div>
    
    <p>Votre place est maintenant confirmée ! Ce mail fait office de reçu de paiement et de confirmation d'inscription.</p>
    
    <p>Conseils pour l'événement :</p>
    <ul>
        <li>Arrivez 15 minutes avant le début de l'événement</li>
        <li>Conservez ce mail comme preuve de paiement</li>
        <li>Consultez les détails de l'événement sur votre tableau de bord pour toute mise à jour</li>
    </ul>
    
    <p><a href="{{ route('dashboard.client') }}" class="btn">Accéder à mon tableau de bord</a></p>
    
    <p>Nous vous remercions pour votre confiance et avons hâte de vous accueillir !</p>
    
    <p>L'équipe Event App</p>
@endsection
