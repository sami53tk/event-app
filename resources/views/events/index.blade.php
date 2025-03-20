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

    {{-- Bouton de création d'événement --}}
    <div class="mt-6">
        <a href="{{ route('events.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Créer un nouvel événement
        </a>
    </div>
</div>
@endsection
