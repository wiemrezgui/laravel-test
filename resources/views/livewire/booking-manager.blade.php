<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold mb-6">Réserver cette propriété</h3>
    
    <form wire:submit="createBooking">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date d'arrivée</label>
                <input type="date" wire:model.live="startDate" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                @error('startDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date de départ</label>
                <input type="date" wire:model.live="endDate" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                @error('endDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        @if($isAvailable !== null)
            <div class="mb-4 p-3 rounded-md {{ $isAvailable ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $isAvailable ? '✓ Disponible pour ces dates' : '✗ Non disponible pour ces dates' }}
            </div>
        @endif

        @if($numberOfNights > 0)
            <div class="bg-gray-50 p-4 rounded-md mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span>{{ number_format($property->price_per_night, 2) }}€ × {{ $numberOfNights }} nuits</span>
                    <span>{{ number_format($totalPrice, 2) }}€</span>
                </div>
                <hr class="my-2">
                <div class="flex justify-between items-center font-semibold">
                    <span>Total</span>
                    <span class="text-lg text-primary">{{ number_format($totalPrice, 2) }}€</span>
                </div>
            </div>
        @endif

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Demandes spéciales (optionnel)</label>
            <textarea wire:model="specialRequests" rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                      placeholder="Toute demande particulière..."></textarea>
        </div>

        <button type="submit" 
                class="w-full bg-primary text-white py-3 px-4 rounded-md hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary transition-colors duration-200 font-medium disabled:opacity-50"
                @disabled(!$isAvailable || !$numberOfNights)
                wire:loading.attr="disabled">
            <span wire:loading.remove>Réserver maintenant</span>
            <span wire:loading>Réservation...</span>
        </button>
    </form>
</div>