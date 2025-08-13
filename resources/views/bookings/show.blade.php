@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Détails de la réservation</h1>
            <span class="px-3 py-1 rounded-full text-sm
                @if($booking->status === 'CONFIRMED') bg-green-100 text-green-800
                @elseif($booking->status === 'PENDING') bg-yellow-100 text-yellow-800
                @else bg-red-100 text-red-800 @endif">
                {{ $booking->status }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-lg font-semibold mb-4">Informations de la propriété</h3>
                <div class="space-y-2">
                    <p><strong>Nom :</strong> {{ $booking->property->name }}</p>
                    <p><strong>Adresse :</strong> {{ $booking->property->address }}</p>
                    <p><strong>Ville :</strong> {{ $booking->property->city }}, {{ $booking->property->country }}</p>
                    <p><strong>Capacité :</strong> {{ $booking->property->max_guests }} invités</p>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Détails de la réservation</h3>
                <div class="space-y-2">
                    <p><strong>Date d'arrivée :</strong> {{ $booking->start_date->format('d/m/Y') }}</p>
                    <p><strong>Date de départ :</strong> {{ $booking->end_date->format('d/m/Y') }}</p>
                    <p><strong>Nombre de nuits :</strong> {{ $booking->number_of_nights }}</p>
                    <p><strong>Prix total :</strong> <span class="text-xl font-bold text-primary">{{ number_format($booking->total_price, 2) }}€</span></p>
                </div>
            </div>
        </div>

        @if($booking->special_requests)
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-2">Demandes spéciales</h3>
                <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $booking->special_requests }}</p>
            </div>
        @endif

        <div class="mt-8 flex space-x-4">
            <a href="{{ route('bookings.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600">
                Retour à mes réservations
            </a>
            <a href="{{ route('properties.show', $booking->property) }}" class="bg-primary text-white px-6 py-2 rounded-md hover:bg-secondary">
                Voir la propriété
            </a>
        </div>
    </div>
</div>
@endsection