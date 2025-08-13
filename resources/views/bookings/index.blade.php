@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Mes Réservations</h1>
</div>

<div class="space-y-6">
    @forelse($bookings as $booking)
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold">{{ $booking->property->name }}</h3>
                    <p class="text-gray-600">{{ $booking->property->city }}, {{ $booking->property->country }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm
                    @if($booking->status === 'CONFIRMED') bg-green-100 text-green-800
                    @elseif($booking->status === 'PENDING') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ $booking->status }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Arrivée</label>
                    <div class="text-lg">{{ $booking->start_date->format('d/m/Y') }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Départ</label>
                    <div class="text-lg">{{ $booking->end_date->format('d/m/Y') }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Prix total</label>
                    <div class="text-lg font-bold text-primary">{{ number_format($booking->total_price, 2) }}€</div>
                </div>
            </div>
            
            <div class="mt-4 text-right">
                <a href="{{ route('bookings.show', $booking) }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-secondary">
                    Voir détails
                </a>
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">Aucune réservation trouvée</p>
            <a href="{{ route('properties.index') }}" class="mt-4 inline-block bg-primary text-white px-6 py-3 rounded-md hover:bg-secondary">
                Découvrir les propriétés
            </a>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $bookings->links() }}
</div>
@endsection