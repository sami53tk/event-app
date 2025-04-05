@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-4">Tableau de bord Organisateur</h1>
    <p class="mb-6">Bienvenue, {{ Auth::user()->name }} !</p>

    <div class="grid grid-cols-2 gap-6">
        <a href="{{ route('events.index') }}"
           class="p-6 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700">
            Mes événements
        </a>
        <a href="{{ route('profile.edit') }}"
           class="p-6 bg-gray-600 text-white rounded-lg shadow-md hover:bg-gray-700">
            Mon profil
        </a>
    </div>
</div>
@endsection
