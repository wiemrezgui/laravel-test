@props(['property'])
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <div class="p-6">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ $property->name }}</h3>
            <span class="px-2 py-1 text-xs rounded-full 
                @if($property->status === 'ACTIVE') bg-green-100 text-green-800
                @elseif($property->status === 'INACTIVE') bg-red-100 text-red-800
                @else bg-yellow-100 text-yellow-800 @endif">
                {{ $property->status }}
            </span>
        </div>
        <p class="text-gray-600 mb-4 line-clamp-3">{{ $property->description }}</p>
        <div class="space-y-2 mb-4">
            <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ $property->city }}, {{ $property->country }}
            </div>
            <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                {{ $property->max_guests }} invités • {{ $property->bedrooms }} chambres • {{ $property->bathrooms }} sdb
            </div>
        </div>
        <div class="flex items-center justify-between">
            <div class="text-xl font-bold text-primary">
                {{ number_format($property->price_per_night, 2) }}€
                <span class="text-sm font-normal text-gray-500">/ nuit</span>
            </div>
            <a href="{{ route('properties.show', $property) }}" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-secondary transition-colors">
                Réserver
            </a>
        </div>
    </div>
</div>
