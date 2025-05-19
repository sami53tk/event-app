{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Liste des utilisateurs</h1>

    {{-- Message flash --}}
    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Formulaire de recherche --}}
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                       placeholder="Nom ou email..."
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
            </div>

            <div class="w-full sm:w-auto">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                <select name="role" id="role"
                        class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">Tous les rôles</option>
                    <option value="admin" {{ ($role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="organisateur" {{ ($role ?? '') == 'organisateur' ? 'selected' : '' }}>Organisateur</option>
                    <option value="client" {{ ($role ?? '') == 'client' ? 'selected' : '' }}>Client</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition-colors">
                    Rechercher
                </button>

                <a href="{{ route('admin.users.index') }}"
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
                    <th class="px-4 py-2 border">Nom</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Rôle</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ $user->id }}</td>
                    <td class="px-4 py-2 border">{{ $user->name }}</td>
                    <td class="px-4 py-2 border">{{ $user->email }}</td>
                    <td class="px-4 py-2 border">{{ $user->role }}</td>
                    <td class="px-4 py-2 border">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-600 hover:underline mr-2">Voir</a>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-green-600 hover:underline mr-2">Modifier</a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline"
                                onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?')">
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
        {{ $users->appends(request()->query())->links() }}
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer un nouvel utilisateur</a>
    </div>
</div>
@endsection
