{{-- resources/views/events/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-2xl font-bold mb-4">Détails de l'événement</h1>

    <div class="bg-white p-4 shadow rounded">
        <p><strong>ID :</strong> {{ $event->id }}</p>
        <p><strong>Titre :</strong> {{ $event->title }}</p>
        <p><strong>Date :</strong> {{ $event->date }}</p>
        <p><strong>Lieu :</strong> {{ $event->location }}</p>
        <p><strong>Statut :</strong> {{ $event->status }}</p>
        <p><strong>Description :</strong> {{ $event->description }}</p>

        @if($event->banner)
            <div class="mt-4">
                <img src="{{ asset('storage/'.$event->banner) }}" alt="Bannière" class="max-w-sm">
            </div>
        @endif

        <p class="mt-4"><strong>Max participants :</strong> {{ $event->max_participants }}</p>
        @if (method_exists($event, 'participants'))
            <p><strong>Déjà inscrits :</strong> {{ $event->participants()->count() }}</p>
        @endif
    </div>

    {{-- Si le user est "client", on pourrait afficher un bouton "S'inscrire" --}}
    @if(Auth::check() && Auth::user()->role === 'client')
        <form action="{{ route('events.register', $event->id) }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                S'inscrire
            </button>
        </form>
    @endif

    <div class="mt-4">
        <a href="{{ route('events.index') }}" class="text-blue-600 hover:underline">Retour à la liste</a>
    </div>
</div>
@endsection
