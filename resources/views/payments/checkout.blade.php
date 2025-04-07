@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Paiement pour l'événement</h1>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">{{ $event->title }}</h2>

            <div class="mb-4">
                <p class="text-gray-700"><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y H:i') }}</p>
                <p class="text-gray-700"><strong>Lieu:</strong> {{ $event->location }}</p>
                <p class="text-gray-700"><strong>Prix:</strong> {{ $event->price }} {{ $event->currency }}</p>
            </div>

            <div class="border-t pt-4 mt-4">
                <p class="text-gray-600 mb-4">Vous allez être redirigé vers Stripe pour effectuer votre paiement en toute sécurité.</p>

                <a href="{{ route('payment.checkout', $event->id) }}" class="block w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition duration-200 text-center">
                    Procéder au paiement
                </a>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('events.show', $event->id) }}" class="text-blue-600 hover:underline">
                Retour aux détails de l'événement
            </a>
        </div>
    </div>
</div>
@endsection
