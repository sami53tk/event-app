{{-- resources/views/admin/users/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Détails de l'utilisateur</h1>

    <div class="bg-white rounded shadow p-4">
        <p><strong>ID :</strong> {{ $user->id }}</p>
        <p><strong>Nom :</strong> {{ $user->name }}</p>
        <p><strong>Email :</strong> {{ $user->email }}</p>
        <p><strong>Rôle :</strong> {{ $user->role }}</p>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">Retour à la liste</a>
    </div>
</div>
@endsection
