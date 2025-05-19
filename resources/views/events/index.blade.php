{{-- resources/views/events/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Liste des événements</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Formulaire de recherche --}}
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <form action="{{ route('events.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                           placeholder="Titre ou description..."
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                    <input type="text" name="location" id="location" value="{{ $location ?? '' }}"
                           placeholder="Lieu de l'événement"
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

                <a href="{{ route('events.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md transition-colors">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto w-full">
        <table class="min-w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Titre</th>
                    <th class="px-4 py-2 border">Date</th>
                    <th class="px-4 py-2 border">Lieu</th>
                    <th class="px-4 py-2 border">Statut</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ $event->id }}</td>
                    <td class="px-4 py-2 border">{{ $event->title }}</td>
                    <td class="px-4 py-2 border">{{ $event->date }}</td>
                    <td class="px-4 py-2 border">{{ $event->location }}</td>
                    <td class="px-4 py-2 border">{{ $event->status }}</td>
                    <td class="px-4 py-2 border">
                        <a href="{{ route('events.show', $event->id) }}" class="text-blue-600 hover:underline mr-2">Voir</a>
                        <a href="{{ route('events.edit', $event->id) }}" class="text-green-600 hover:underline mr-2">Modifier</a>
                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline"
                                onclick="return confirm('Voulez-vous vraiment supprimer cet événement ?')">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $events->appends(request()->query())->links() }}
    </div>

    {{-- Bouton de création d'événement --}}
    <div class="mt-6">
        <a href="{{ route('events.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Créer un nouvel événement
        </a>
    </div>
</div>
@endsection
