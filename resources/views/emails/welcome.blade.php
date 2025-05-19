@extends('emails.layouts.master')

@section('title', 'Bienvenue sur Event App')

@section('content')
    <h1>Bienvenue sur Event App !</h1>

    <p>Bonjour {{ $user->name }},</p>

    <div class="success">
        <p>Nous sommes ravis de vous accueillir sur Event App, la plateforme qui vous permet de découvrir et participer à des événements exceptionnels !</p>
    </div>

    <p>Votre compte a été créé avec succès. Voici vos informations :</p>

    <div class="event-details">
        <div class="event-info"><strong>Nom :</strong> {{ $user->name }}</div>
        <div class="event-info"><strong>Email :</strong> {{ $user->email }}</div>
        <div class="event-info"><strong>Rôle :</strong> {{ ucfirst($user->role) }}</div>
        <div class="event-info"><strong>Date d'inscription :</strong> {{ $user->created_at->format('d/m/Y') }}</div>
    </div>

    @if($user->role == 'client')
        <p>En tant que client, vous pouvez :</p>
        <ul>
            <li>Parcourir les événements disponibles</li>
            <li>Vous inscrire à des événements gratuits ou payants</li>
            <li>Gérer vos inscriptions depuis votre tableau de bord</li>
        </ul>

        <p><a href="{{ route('public.events.index') }}" class="btn">Découvrir les événements</a></p>
    @elseif($user->role == 'organisateur')
        <p>En tant qu'organisateur, vous pouvez :</p>
        <ul>
            <li>Créer et gérer vos propres événements</li>
            <li>Suivre les inscriptions à vos événements</li>
            <li>Consulter les statistiques de participation</li>
        </ul>

        <p><a href="{{ route('events.create') }}" class="btn">Créer votre premier événement</a></p>
    @else

        <p><a href="{{ route('admin.users.index') }}" class="btn">Accéder au tableau de bord</a></p>
    @endif

    <p>Si vous avez des questions ou besoin d'assistance, n'hésitez pas à nous contacter.</p>

    <p>Nous vous souhaitons une excellente expérience sur Event App !</p>

    <p>L'équipe Event App</p>
@endsection
