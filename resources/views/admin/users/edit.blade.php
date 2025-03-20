{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Modifier l'utilisateur</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="mt-1">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-semibold">Nom</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                   class="border border-gray-300 p-2 w-full rounded" required>
        </div>

        <div>
            <label for="email" class="block font-semibold">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                   class="border border-gray-300 p-2 w-full rounded" required>
        </div>

        <div>
            <label for="role" class="block font-semibold">Rôle</label>
            <select name="role" id="role" class="border border-gray-300 p-2 w-full rounded" required>
                <option value="client" {{ $user->role === 'client' ? 'selected' : '' }}>Client</option>
                <option value="organisateur" {{ $user->role === 'organisateur' ? 'selected' : '' }}>Organisateur</option>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div>
            <label for="password" class="block font-semibold">Nouveau mot de passe (optionnel)</label>
            <input type="password" name="password" id="password"
                   class="border border-gray-300 p-2 w-full rounded"
                   placeholder="Laissez vide pour ne pas changer">
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Mettre à jour
        </button>
    </form>
</div>
@endsection
