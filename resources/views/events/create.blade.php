{{-- resources/views/events/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Créer un événement</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="mt-1">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label for="title" class="block font-semibold">Titre</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}"
                   class="border border-gray-300 p-2 w-full rounded" required>
        </div>

        <div>
            <label for="banner" class="block font-semibold">Image (optionnel)</label>
            <input type="file" name="banner" id="banner" class="border border-gray-300 p-2 w-full rounded">
        </div>

        <div>
            <label for="description" class="block font-semibold">Description</label>
            <textarea name="description" id="description" rows="4"
                      class="border border-gray-300 p-2 w-full rounded">{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="date" class="block font-semibold">Date</label>
            <input type="datetime-local" name="date" id="date" value="{{ old('date') }}"
                   class="border border-gray-300 p-2 w-full rounded" required>
        </div>

        <div>
            <label for="location" class="block font-semibold">Lieu</label>
            <input type="text" name="location" id="location" value="{{ old('location') }}"
                   class="border border-gray-300 p-2 w-full rounded" required>
        </div>

        <div>
            <label for="status" class="block font-semibold">Statut</label>
            <select name="status" id="status" class="border border-gray-300 p-2 w-full rounded" required>
                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Actif</option>
                <option value="annule" {{ old('status') === 'annule' ? 'selected' : '' }}>Annulé</option>
            </select>
        </div>

        <div>
            <label for="max_participants" class="block font-semibold">Max participants</label>
            <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants') }}"
                   class="border border-gray-300 p-2 w-full rounded" min="1" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Créer
        </button>
    </form>
</div>
@endsection
