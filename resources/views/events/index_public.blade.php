{{-- resources/views/events/index_public.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- Notifications (succès, erreur) --}}
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

    {{-- Titre --}}
    <h1 class="text-3xl font-extrabold mb-6 text-gray-800">
        Liste des événements
    </h1>

    {{-- Formulaire de recherche / filtres --}}
    <div class="mb-8 bg-white rounded-md shadow p-4">
        <form method="GET" action="{{ route('public.events.index') }}">
            <div class="flex flex-wrap gap-4 items-end">

                {{-- Champ de recherche texte --}}
                <div>
                    <label for="search" class="block font-semibold mb-1 text-gray-700">
                        Recherche
                    </label>
                    <div class="relative">
                        {{-- Icone search à l'intérieur du champ --}}
                        <svg class="w-5 h-5 text-gray-400 absolute left-2 top-1/2 transform -translate-y-1/2"
                             fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M11 19a8 8 0 100-16 8 8 0 000 16z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-4.35-4.35"></path>
                        </svg>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               class="border border-gray-300 pl-9 pr-3 py-2 rounded w-72
                                      focus:outline-none focus:ring-2 focus:ring-blue-600"
                               placeholder="Rechercher par titre ou description...">
                    </div>
                </div>

                {{-- Filtre location --}}
                <div>
                    <label for="location" class="block font-semibold mb-1 text-gray-700">
                        Lieu
                    </label>
                    <div class="relative">
                        <svg class="w-5 h-5 text-gray-400 absolute left-2 top-1/2 transform -translate-y-1/2"
                             fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17.657 16.657L13.414 12m0 0l-2.121-2.121a4.001
                                     4.001 0 015.657-5.657l2.121 2.121a4.001 4.001 0
                                     010 5.657L13.414 12z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.889 13.889a3 3 0 11-4.242-4.242"></path>
                        </svg>
                        <input type="text" name="location" id="location" value="{{ request('location') }}"
                               class="border border-gray-300 pl-9 pr-3 py-2 rounded w-48
                                      focus:outline-none focus:ring-2 focus:ring-blue-600"
                               placeholder="Ville, salle...">
                    </div>
                </div>

                {{-- Filtre statut (optionnel) --}}
                <div>
                    <label for="status" class="block font-semibold mb-1 text-gray-700">
                        Statut
                    </label>
                    <select name="status" id="status"
                            class="border border-gray-300 p-2 rounded w-48 focus:outline-none
                                   focus:ring-2 focus:ring-blue-600">
                        <option value="">-- Tous --</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="annule" {{ request('status') == 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>

                {{-- Bouton filtrer --}}
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded
                               hover:bg-blue-700 transition duration-200">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    {{-- Liste paginée --}}
    @if ($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="bg-white rounded shadow-md overflow-hidden
                            hover:shadow-lg transition-shadow duration-300">

                    {{-- Affichage éventuel de la bannière (optionnel, si besoin) --}}
                    @if($event->banner)
                        <img src="{{ asset('storage/' . $event->banner) }}"
                             alt="{{ $event->title }}"
                             class="w-full h-40 object-cover
                                    transition-transform duration-300 hover:scale-105">
                    @endif

                    <div class="p-4">
                        {{-- Titre & Statut (badge) --}}
                        <div class="flex items-center justify-between mb-2">
                            <h2 class="text-lg font-semibold text-gray-800">
                                {{ $event->title }}
                            </h2>
                            {{-- Badge statut --}}
                            @if($event->status === 'active')
                                <span class="px-2 py-1 text-xs font-bold text-white bg-green-600 rounded">
                                    Actif
                                </span>
                            @elseif($event->status === 'annule')
                                <span class="px-2 py-1 text-xs font-bold text-white bg-red-600 rounded">
                                    Annulé
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-bold text-white bg-gray-600 rounded">
                                    {{ $event->status }}
                                </span>
                            @endif
                        </div>

                        {{-- Description courte --}}
                        <p class="text-gray-700 mb-2">
                            {{ Str::limit($event->description, 80) }}
                        </p>

                        {{-- Date + lieu (formaté) --}}
                        <p class="text-sm text-gray-600 mb-1">
                            <strong>Date :</strong>
                            {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-sm text-gray-600 mb-1">
                            <strong>Lieu :</strong> {{ $event->location }}
                        </p>
                        <p class="text-sm text-gray-600 mb-1">
                            <strong>Prix :</strong>
                            @if($event->price && $event->price > 0)
                                {{ $event->price }} {{ $event->currency }}
                            @else
                                Gratuit
                            @endif
                        </p>

                        {{-- Nombre de participants si la relation existe --}}
                        @if(method_exists($event, 'participants'))
                            <p class="text-sm text-gray-600 mb-1">
                                <strong>Participants :</strong>
                                {{ $event->participants()->count() }} / {{ $event->max_participants }}
                            </p>
                        @endif

                        <div class="mt-4 flex items-center justify-between">
                            {{-- Lien détails --}}
                            <a href="{{ route('events.show', $event->id) }}"
                               class="text-blue-600 text-sm font-semibold hover:underline">
                                Détails
                            </a>

                            {{-- Bouton S'inscrire / Se désinscrire si client --}}
                            @if(Auth::check() && Auth::user()->role === 'client')
                                @if(!$event->participants()->where('user_id', Auth::id())->exists())
                                    <form action="{{ route('events.register', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="bg-green-600 text-white px-3 py-1 text-sm rounded
                                                       hover:bg-green-700 transition duration-200">
                                            S'inscrire
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('events.unregister', $event->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-600 text-white px-3 py-1 text-sm rounded
                                                       hover:bg-red-700 transition duration-200">
                                            Se désinscrire
                                        </button>
                                    </form>
                                @endif
                            @else
                                @if(!Auth::check())
                                    <a href="{{ route('login') }}"
                                       class="text-blue-600 text-sm hover:underline">
                                        Se connecter pour s'inscrire
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $events->links() }}
        </div>
    @else
        <p class="text-gray-700">Aucun événement trouvé.</p>
    @endif
</div>
@endsection
