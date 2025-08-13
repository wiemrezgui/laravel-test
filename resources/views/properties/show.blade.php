@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $property->name }}</h1>
            <div class="flex items-center text-gray-500 mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ $property->address }}, {{ $property->city }}, {{ $property->country }}
            </div>
            
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-primary">{{ $property->max_guests }}</div>
                    <div class="text-sm text-gray-600">Invités</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-primary">{{ $property->bedrooms }}</div>
                    <div class="text-sm text-gray-600">Chambres</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-primary">{{ $property->bathrooms }}</div>
                    <div class="text-sm text-gray-600">Salles de bain</div>
                </div>
            </div>
            
            <h3 class="text-xl font-semibold mb-4">Description</h3>
            <p class="text-gray-700 leading-relaxed">{{ $property->description }}</p>
        </div>
    </div>
    
    <div class="lg:col-span-1">
        @livewire('booking-manager', ['property' => $property])
    </div>
</div>
@endsection