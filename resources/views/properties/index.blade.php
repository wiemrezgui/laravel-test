@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-4">Propriétés disponibles</h1>
    
    <!-- Filtres -->
    <form method="GET" class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="city" placeholder="Ville..." value="{{ request('city') }}" 
                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
            <input type="number" name="max_price" placeholder="Prix max..." value="{{ request('max_price') }}" 
                   class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-secondary">
                Filtrer
            </button>
        </div>
    </form>
</div>

@if($properties->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($properties as $property)
            <x-property-card :property="$property" />
        @endforeach
    </div>

    <div class="mt-8">
        {{ $properties->links() }}
    </div>
@else
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">Aucune propriété trouvée</h3>
        <p class="mt-2 text-gray-600">
            @if(request()->has('city') || request()->has('max_price'))
                Aucune propriété ne correspond à vos critères de recherche.
                <a href="{{ route('properties.index') }}" class="text-primary hover:underline">Réinitialiser les filtres</a>
            @else
                Aucune propriété disponible pour le moment.
            @endif
        </p>
    </div>
@endif
@endsection