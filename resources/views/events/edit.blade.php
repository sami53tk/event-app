{{-- resources/views/events/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Modifier l'événement</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="mt-1">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block font-semibold">Titre</label>
            <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}"
                   class="border border-gray-300 p-2 w-full rounded" required>
        </div>

        <div>
            <label for="banner" class="block font-semibold">Image (optionnel)</label>
            <input type="file" name="banner" id="banner" class="border border-gray-300 p-2 w-full rounded">
            @if($event->banner)
                <p class="mt-1 text-sm">Bannière actuelle : {{ $event->banner }}</p>
            @endif
        </div>

        <div>
            <label for="description" class="block font-semibold">Description</label>
            <textarea name="description" id="description" rows="4"
                      class="border border-gray-300 p-2 w-full rounded">{{ old('description', $event->description) }}</textarea>
        </div>

        <div>
            <label for="date" class="block font-semibold">Date</label>
            <input type="datetime-local" name="date" id="date"
                   value="{{ old('date', \Carbon\Carbon::parse($event->date)->format('Y-m-d\TH:i')) }}"
                   class="border border-gray-300 p-2 w-full rounded" required>
        </div>

        <div>
            <label for="location" class="block font-semibold">Lieu</label>
            <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}"
                   class="border border-gray-300 p-2 w-full rounded" required>
        </div>

        <div>
            <label for="price" class="block font-semibold">Prix (laissez vide pour un événement gratuit)</label>
            <input type="number" name="price" id="price" value="{{ old('price', $event->price) }}"
                   class="border border-gray-300 p-2 w-full rounded" min="0" step="0.01">
        </div>

        <div>
            <label for="currency" class="block font-semibold">Devise</label>
            <select name="currency" id="currency" class="border border-gray-300 p-2 w-full rounded">
                <option value="EUR" {{ old('currency', $event->currency) === 'EUR' ? 'selected' : '' }}>EUR</option>
                <option value="USD" {{ old('currency', $event->currency) === 'USD' ? 'selected' : '' }}>USD</option>
                <option value="GBP" {{ old('currency', $event->currency) === 'GBP' ? 'selected' : '' }}>GBP</option>
            </select>
        </div>

        <div>
            <label for="status" class="block font-semibold">Statut</label>
            <select name="status" id="status" class="border border-gray-300 p-2 w-full rounded" required>
                <option value="active" {{ $event->status === 'active' ? 'selected' : '' }}>Actif</option>
                <option value="annule" {{ $event->status === 'annule' ? 'selected' : '' }}>Annulé</option>
            </select>
        </div>

        <div>
            <label for="max_participants" class="block font-semibold">Max participants</label>
            <input type="number" name="max_participants" id="max_participants"
                   value="{{ old('max_participants', $event->max_participants) }}"
                   class="border border-gray-300 p-2 w-full rounded" min="1" required>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Mettre à jour
        </button>
    </form>
</div>
@endsection
