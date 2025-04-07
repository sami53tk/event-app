{{-- resources/views/events/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- Notifications --}}
    @if (session('success'))
        <div class="mb-4 flex items-center space-x-2 px-4 py-3 rounded-md bg-green-100 text-green-700">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 11l3 3L22 4"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 flex items-center space-x-2 px-4 py-3 rounded-md bg-red-100 text-red-700">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M10 14l2-2m0 0l2-2m-2 2l2 2m-2-2l-2 2M4 6h16"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header: Titre + bouton retour --}}
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-extrabold text-gray-800">
            Détails de l'événement
        </h1>

        {{-- Si l’admin veut revenir à la liste des événements, sinon le client retourne à l'accueil --}}
        @if(Auth::check() && Auth::user()->role === 'admin')
            <a href="{{ route('events.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md
                      hover:bg-gray-300 transition duration-200">
                ← Retour aux événements
            </a>
        @else
            <a href="{{ route('dashboard.client') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md
                      hover:bg-gray-300 transition duration-200">
                ← Retour à l'accueil
            </a>
        @endif
    </div>

    {{-- Carte principale --}}
    <div class="bg-white p-6 shadow-md rounded-md">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Section Image + Badge Statut --}}
            <div class="relative">

                {{-- Badge de statut dans un coin de l'image (ou ailleurs au choix) --}}
                @if($event->status === 'active')
                    <span class="absolute top-4 left-4 px-2 py-1 text-sm font-bold text-white
                               bg-green-600 rounded-md shadow-md z-10">
                        Actif
                    </span>
                @elseif($event->status === 'annule')
                    <span class="absolute top-4 left-4 px-2 py-1 text-sm font-bold text-white
                               bg-red-600 rounded-md shadow-md z-10">
                        Annulé
                    </span>
                @else
                    <span class="absolute top-4 left-4 px-2 py-1 text-sm font-bold text-white
                               bg-gray-600 rounded-md shadow-md z-10">
                        {{ $event->status }}
                    </span>
                @endif

                @if($event->banner)
                    <img
                        src="{{ asset('storage/' . $event->banner) }}"
                        alt="Bannière de {{ $event->title }}"
                        class="w-full h-auto rounded-md object-cover
                               transition-transform duration-300 hover:scale-105"
                        loading="lazy"
                    >
                @else
                    <img
                        src="{{ asset('images/placeholder-banner.jpg') }}"
                        alt="Image par défaut"
                        class="w-full h-auto rounded-md object-cover
                               transition-transform duration-300 hover:scale-105"
                        loading="lazy"
                    >
                @endif
            </div>

            {{-- Section Détails de l'événement --}}
            <div class="flex flex-col justify-center">

                {{-- Titre de l'événement --}}
                <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                    {{ $event->title }}
                </h2>

                {{-- Comptage de jours restants (optionnel) --}}
                @php
                    $today = now();
                    $eventDate = \Carbon\Carbon::parse($event->date);
                    $daysLeft = $today->diffInDays($eventDate, false);
                    // false => pour un diff négatif si date passée
                @endphp
                @if($daysLeft > 0)
                    <p class="text-sm text-gray-500 mb-2">
                        L'événement aura lieu dans <strong>{{ $daysLeft }} jours</strong>.
                    </p>
                @elseif($daysLeft === 0)
                    <p class="text-sm text-red-500 font-semibold mb-2">
                        L'événement a lieu aujourd'hui !
                    </p>
                @else
                    <p class="text-sm text-gray-500 mb-2">
                        L'événement est passé (il y a {{ abs($daysLeft) }} jours).
                    </p>
                @endif

                {{-- Date et lieu --}}
                <div class="flex items-center text-gray-600 text-sm space-x-4 mb-4">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7V3m8 4V3m-9 8h10l1 10H6l1-10zm4 5h.01"></path>
                        </svg>
                        <span>{{ $eventDate->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17.657 16.657L13.414 12m0 0l-2.121-2.121a4.001 4.001 0 015.657-5.657l2.121 2.121a4.001
                                   4.001 0 010 5.657L13.414 12z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.889 13.889a3 3 0 11-4.242-4.242"></path>
                        </svg>
                        <span>{{ $event->location }}</span>
                    </div>
                </div>

                {{-- Description encadrée --}}
                <div class="bg-gray-50 p-3 rounded-md mb-3">
                    <p class="text-gray-700 text-sm">
                        <span class="font-medium">Description : </span>
                        {{ $event->description }}
                    </p>
                </div>

                {{-- Autres infos (ID, max participants, déjà inscrits) --}}
                <div class="text-gray-700 text-sm space-y-1">
                    <p>
                        <strong>ID :</strong> {{ $event->id }}
                    </p>
                    <p>
                        <strong>Places max :</strong> {{ $event->max_participants }}
                    </p>
                    <p>
                        <strong>Prix :</strong>
                        @if($event->price && $event->price > 0)
                            {{ $event->price }} {{ $event->currency }}
                        @else
                            Gratuit
                        @endif
                    </p>
                    @if(method_exists($event, 'participants'))
                        @php
                            $current = $event->participants()->count();
                            $max = $event->max_participants;
                            $percentage = $max > 0 ? min(($current / $max) * 100, 100) : 0;
                        @endphp
                        <p>
                            <strong>Déjà inscrits :</strong> {{ $current }}
                        </p>

                        {{-- Barre de progression (facultative) --}}
                        <div class="w-full bg-gray-200 h-2 rounded-full mt-2 mb-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    @endif
                </div>

                {{-- Boutons d'action (S'inscrire / Se désinscrire) --}}
                <div class="mt-6 flex flex-wrap items-center space-x-4">
                    @if(Auth::check() && Auth::user()->role === 'client')
                        @if(!$event->participants()->where('user_id', Auth::id())->exists())
                            <form action="{{ route('events.register', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="bg-green-600 text-white px-4 py-2 rounded-md
                                               hover:bg-green-700 transition duration-200">
                                    S'inscrire
                                </button>
                            </form>
                        @else
                            <form action="{{ route('events.unregister', $event->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 text-white px-4 py-2 rounded-md
                                               hover:bg-red-700 transition duration-200">
                                    Se désinscrire
                                </button>
                            </form>
                        @endif
                    @else
                        @if(!Auth::check())
                            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
                                Se connecter pour s'inscrire
                            </a>
                        @endif
                    @endif
                </div>

                {{-- Bouton d'édition réservé à l'admin (ou owner) --}}
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <div class="mt-6 flex items-center space-x-4">
                        <a href="{{ route('events.edit', $event->id) }}"
                           class="inline-flex items-center text-yellow-600 hover:text-yellow-800 transition duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 20h9"></path>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                            Modifier l'événement
                        </a>
                    </div>
                @endif

            </div> {{-- Fin col détails --}}
        </div> {{-- Fin grid --}}
    </div> {{-- Fin carte --}}
</div>
@endsection
