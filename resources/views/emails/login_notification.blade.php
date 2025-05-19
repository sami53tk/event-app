@extends('emails.layouts.master')

@section('title', 'Connexion à votre compte')

@section('content')
    <h1>Nouvelle connexion à votre compte</h1>
    
    <p>Bonjour {{ $user->name }},</p>
    
    <p>Nous avons détecté une nouvelle connexion à votre compte Event App.</p>
    
    <div class="event-details">
        <div class="event-info"><strong>Date et heure :</strong> {{ now()->format('d/m/Y à H:i') }}</div>
        <div class="event-info"><strong>Adresse IP :</strong> {{ $ipAddress }}</div>
        <div class="event-info"><strong>Navigateur :</strong> {{ $browser }}</div>
    </div>
    
    <p>Si c'est bien vous qui venez de vous connecter, vous pouvez ignorer cet email.</p>
    
    <div class="alert">
        <p>Si vous n'êtes pas à l'origine de cette connexion, nous vous recommandons de :</p>
        <ol>
            <li>Changer immédiatement votre mot de passe</li>
            <li>Vérifier les activités récentes sur votre compte</li>
            <li>Contacter notre support si vous constatez des activités suspectes</li>
        </ol>
    </div>
    
    <p><a href="{{ route('profile.edit') }}" class="btn">Gérer mon compte</a></p>
    
    <p>La sécurité de votre compte est notre priorité.</p>
    
    <p>L'équipe Event App</p>
@endsection
