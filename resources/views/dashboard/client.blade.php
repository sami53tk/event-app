{{-- resources/views/dashboard/client.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Titre principal + message d’accueil --}}
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold mb-2 text-gray-800">Tableau de bord Client</h1>
        <p class="text-gray-600">
            Bienvenue, <span class="font-semibold">{{ Auth::user()->name }}</span> !
        </p>
    </div>

    {{-- Section de raccourcis (explorer événements, profil, etc.) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-12">
        <a href="{{ route('public.events.index') }}"
           class="flex items-center p-6 rounded-lg shadow-md bg-blue-600 text-white
                  hover:bg-blue-700 transition duration-200 transform hover:scale-105">
            {{-- Icône (Heroicon: globe-alt) --}}
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 4C6.477 4 2 8.477 2 14s4.477 10 10 10
                      10-4.477 10-10S17.523 4 12 4z"></path>
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2 14h20"></path>
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 4c1.958 2.392 3.095 5.636 3.095 10
                      0 4.364-1.137 7.608-3.095 10"></path>
            </svg>
            <span class="text-lg font-semibold">Explorer les événements</span>
        </a>

        <a href="{{ route('profile.edit') }}"
           class="flex items-center p-6 rounded-lg shadow-md bg-gray-600 text-white
                  hover:bg-gray-700 transition duration-200 transform hover:scale-105">
            {{-- Icône (Heroicon: user) --}}
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M5.121 17.804A7 7 0 0112 14c1.933 0 3.681.784 4.879
                      2.047M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span class="text-lg font-semibold">Mon profil</span>
        </a>
    </div>

    {{-- Formulaire de recherche --}}
    <div class="bg-white p-4 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-semibold mb-4">Rechercher mes événements</h2>
        <form action="{{ route('dashboard.client') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                           placeholder="Titre, description ou lieu..."
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" id="status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ ($status ?? '') == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="annule" {{ ($status ?? '') == 'annule' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>

                <div>
                    <label for="date_start" class="block text-sm font-medium text-gray-700 mb-1">Date de début</label>
                    <input type="date" name="date_start" id="date_start" value="{{ $dateStart ?? '' }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="date_end" class="block text-sm font-medium text-gray-700 mb-1">Date de fin</label>
                    <input type="date" name="date_end" id="date_end" value="{{ $dateEnd ?? '' }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition-colors">
                    Rechercher
                </button>

                <a href="{{ route('dashboard.client') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md transition-colors">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- Liste des événements inscrits --}}
    <h2 class="text-2xl font-bold mb-4">Mes événements inscrits</h2>
    @if($events->isEmpty())
        <p class="text-gray-600">Vous n'êtes inscrit à aucun événement pour le moment.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="bg-white rounded-lg shadow-md overflow-hidden
                            hover:shadow-lg transition-shadow duration-300">
                    @if($event->banner)
                        <img
                            src="{{ asset('storage/' . $event->banner) }}"
                            alt="{{ $event->title }}"
                            class="w-full h-48 object-cover
                                   transition-transform duration-300 hover:scale-105"
                        >
                    @else
                        {{-- Image placeholder si pas de bannière --}}
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4.354a4 4 0 110 7.292
                                    M12 0C5.372 0 0 5.372 0 12
                                    c0 6.627 5.372 12 12 12
                                    s12-5.373 12-12C24 5.372 18.628 0 12 0z">
                                </path>
                            </svg>
                        </div>
                    @endif

                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2 text-gray-800">
                            {{ $event->title }}
                        </h3>
                        <p class="text-gray-700 mb-2">
                            {{ Str::limit($event->description, 100) }}
                        </p>
                        <p class="text-gray-500 text-sm mb-2">
                            {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y H:i') }}
                            - {{ $event->location }}
                        </p>
                        <a href="{{ route('events.show', $event->id) }}"
                           class="inline-block mt-2 text-blue-600 hover:underline">
                            Voir les détails
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $events->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
