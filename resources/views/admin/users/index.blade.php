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

    <div class="mt-6">
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer un nouvel utilisateur</a>
    </div>
</div>
@endsection
