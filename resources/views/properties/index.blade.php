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

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($properties as $property)
        <x-property-card :property="$property" />
    @endforeach
</div>

<div class="mt-8">
    {{ $properties->links() }}
</div>
@endsection