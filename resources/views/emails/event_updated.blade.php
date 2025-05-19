@extends('emails.layouts.master')

@section('title', 'Modification d\'événement')

@section('content')
    <h1>Modification d'un événement</h1>
    
    <p>Bonjour {{ $user->name }},</p>
    
    <p>Nous vous informons que l'événement auquel vous êtes inscrit(e) a été modifié :</p>
    
    <div class="event-details">
        <div class="event-title">{{ $event->title }}</div>
        
        @if(isset($changes['date']))
            <div class="event-info">
                <strong>Date :</strong> 
                <span style="text-decoration: line-through;">{{ \Carbon\Carbon::parse($changes['date']['old'])->format('d/m/Y') }}</span> → 
                <span class="highlight">{{ \Carbon\Carbon::parse($changes['date']['new'])->format('d/m/Y') }}</span>
            </div>
        @else
            <div class="event-info"><strong>Date :</strong> {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</div>
        @endif
        
        @if(isset($changes['time']))
            <div class="event-info">
                <strong>Heure :</strong> 
                <span style="text-decoration: line-through;">{{ \Carbon\Carbon::parse($changes['time']['old'])->format('H:i') }}</span> → 
                <span class="highlight">{{ \Carbon\Carbon::parse($changes['time']['new'])->format('H:i') }}</span>
            </div>
        @else
            <div class="event-info"><strong>Heure :</strong> {{ \Carbon\Carbon::parse($event->date)->format('H:i') }}</div>
        @endif
        
        @if(isset($changes['location']))
            <div class="event-info">
                <strong>Lieu :</strong> 
                <span style="text-decoration: line-through;">{{ $changes['location']['old'] }}</span> → 
                <span class="highlight">{{ $changes['location']['new'] }}</span>
            </div>
        @else
            <div class="event-info"><strong>Lieu :</strong> {{ $event->location }}</div>
        @endif
        
        @if(isset($changes['price']))
            <div class="event-info">
                <strong>Prix :</strong> 
                <span style="text-decoration: line-through;">
                    @if($changes['price']['old'])
                        {{ $changes['price']['old'] }} {{ $event->currency }}
                    @else
                        Gratuit
                    @endif
                </span> → 
                <span class="highlight">
                    @if($changes['price']['new'])
                        {{ $changes['price']['new'] }} {{ $event->currency }}
                    @else
                        Gratuit
                    @endif
                </span>
            </div>
        @elseif($event->price)
            <div class="event-info"><strong>Prix :</strong> {{ $event->price }} {{ $event->currency }}</div>
        @else
            <div class="event-info"><strong>Prix :</strong> Gratuit</div>
        @endif
    </div>
    
    @if(count($changes) > 0)
        <div class="alert">
            <p>Si ces modifications ne vous conviennent pas, vous pouvez vous désinscrire de cet événement depuis votre tableau de bord.</p>
        </div>
    @endif
    
    <p>Pour plus de détails sur cet événement :</p>
    
    <p><a href="{{ route('events.show', $event->id) }}" class="btn">Voir les détails de l'événement</a></p>
    
    <p>Nous vous remercions pour votre compréhension.</p>
    
    <p>L'équipe Event App</p>
@endsection
