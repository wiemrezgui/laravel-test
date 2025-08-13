@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-12">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Annuler la réservation</h1>
        
        <div class="mb-8">
            <h3 class="text-lg font-semibold">{{ $booking->property->name }}</h3>
            <p class="text-gray-600">{{ $booking->property->city }}, {{ $booking->property->country }}</p>
            
            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Dates</label>
                    <p>{{ $booking->start_date->format('d/m/Y') }} - {{ $booking->end_date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Prix total</label>
                    <p class="font-bold">{{ number_format($booking->total_price, 2) }}€</p>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
            @csrf
            @method('PATCH')
            
            <div class="mb-6">
                <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Raison d'annulation (optionnelle)
                </label>
                <textarea id="cancellation_reason" name="cancellation_reason" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"></textarea>
            </div>
            
            <div class="flex justify-end space-x-4">
                <a href="{{ route('bookings.index') }}" 
                   class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600">
                    Retour
                </a>
                <button type="submit" 
                        class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700">
                    Confirmer l'annulation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection