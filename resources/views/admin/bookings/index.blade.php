@extends('layouts.admin')

@section('page-title', 'Gestion des Réservations')

@section('content')
<div x-data="bookingsManager(@js($routes))">
    <!-- Header with filters -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Réservations</h2>
        <div class="flex space-x-2">
            <div class="relative">
                <select x-model="statusFilter" @change="filterBookings()" class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">Tous les statuts</option>
                    <option value="PENDING">En attente</option>
                    <option value="CONFIRMED">Confirmée</option>
                    <option value="CANCELLED">Annulée</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propriété</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->property->name }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->property->city }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $booking->start_date->format('d/m/Y') }} - {{ $booking->end_date->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($booking->total_price, 2) }}€
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($booking->status === 'CONFIRMED') bg-green-100 text-green-800
                                    @elseif($booking->status === 'PENDING') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    @if($booking->status === 'CONFIRMED') Confirmée
                                    @elseif($booking->status === 'PENDING') En attente
                                    @else Annulée @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <!-- View Button -->
                                    <button @click="viewBooking({{ $booking->toJson() }})" 
                                            class="text-blue-600 hover:text-blue-900 p-1 rounded" 
                                            title="Voir les détails">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    <button @click="confirmDelete({{ $booking->id }}, '{{ $booking->user->name }}')" 
                                            class="text-red-600 hover:text-red-900 p-1 rounded" 
                                            title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>

    <!-- View Modal -->
    <div x-show="showViewModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeViewModal()"></div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Détails de la réservation</h3>
                        <button @click="closeViewModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div x-show="viewingBooking" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Informations client</h4>
                            <div class="space-y-2">
                                <p><strong>Nom:</strong> <span x-text="viewingBooking?.user?.name"></span></p>
                                <p><strong>Email:</strong> <span x-text="viewingBooking?.user?.email"></span></p>
                            </div>
                            
                            <h4 class="font-semibold text-gray-900 mt-4 mb-2">Informations de paiement</h4>
                            <div class="space-y-2">
                                <p><strong>Prix total:</strong> <span x-text="viewingBooking?.total_price"></span>€</p>
                            </div>
                        </div>
                        
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Détails de la réservation</h4>
                            <div class="space-y-2">
                                <p><strong>Propriété:</strong> <span x-text="viewingBooking?.property?.name"></span></p>
                                <p><strong>Localisation:</strong> <span x-text="`${viewingBooking?.property?.city}, ${viewingBooking?.property?.country}`"></span></p>
                                <p><strong>Dates:</strong> <span x-text="`${formatDate(viewingBooking?.start_date)} - ${formatDate(viewingBooking?.end_date)}`"></span></p>
                                <p><strong>Statut:</strong> 
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          :class="getStatusClass(viewingBooking?.status)"
                                          x-text="viewingBooking?.status">
                                    </span>
                                </p>
                            </div>
                            
                            <h4 class="font-semibold text-gray-900 mt-4 mb-2">Demandes spéciales</h4>
                            <div class="space-y-2">
                                <p x-text="viewingBooking?.special_requests || 'Aucune demande particulière'"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="closeViewModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="showFormModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeFormModal()"></div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form @submit.prevent="submitForm()">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="isEditing ? 'Modifier la réservation' : 'Créer une réservation'"></h3>
                            <button type="button" @click="closeFormModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                                    <select x-model="formData.user_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="">Sélectionner un client</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Propriété</label>
                                    <select x-model="formData.property_id" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        <option value="">Sélectionner une propriété</option>
                                        @foreach($properties as $property)
                                            <option value="{{ $property->id }}">{{ $property->name }} ({{ $property->city }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date d'arrivée</label>
                                    <input type="date" x-model="formData.start_date" required
                                           @change="calculateTotalPrice()"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date de départ</label>
                                    <input type="date" x-model="formData.end_date" required
                                           @change="calculateTotalPrice()"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nuitées</label>
                                    <input type="number" x-model="formData.number_of_nights" readonly
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix/nuit (€)</label>
                                    <input type="number" step="0.01" x-model="formData.price_per_night" readonly
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix total (€)</label>
                                    <input type="number" step="0.01" x-model="formData.total_price" readonly
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                                <select x-model="formData.status" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    <option value="PENDING">En attente</option>
                                    <option value="CONFIRMED">Confirmée</option>
                                    <option value="CANCELLED">Annulée</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Demandes spéciales</label>
                                <textarea x-model="formData.special_requests" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" 
                                :disabled="loading"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                            <span x-show="!loading" x-text="isEditing ? 'Mettre à jour' : 'Créer'"></span>
                            <span x-show="loading">Traitement...</span>
                        </button>
                        <button type="button" @click="closeFormModal()" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeDeleteModal()"></div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Confirmer la suppression</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir supprimer la réservation de "<span x-text="deleteBookingName" class="font-medium"></span>" ? 
                                    Cette action ne peut pas être annulée.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="deleteBooking()" 
                            :disabled="loading"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                        <span x-show="!loading">Supprimer</span>
                        <span x-show="loading">Suppression...</span>
                    </button>
                    <button @click="closeDeleteModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function bookingsManager(routes) {
    return {
        // State variables
        showViewModal: false,
        showFormModal: false,
        showDeleteModal: false,
        viewingBooking: null,
        isEditing: false,
        loading: false,
        deleteBookingId: null,
        deleteBookingName: '',
        statusFilter: new URL(window.location.href).searchParams.get('status') || '',
        
        // Form data
        formData: {
            user_id: '',
            property_id: '',
            start_date: '',
            end_date: '',
            number_of_nights: 0,
            price_per_night: 0,
            total_price: 0,
            status: 'CONFIRMED',
            special_requests: ''
        },

        // Methods
        viewBooking(booking) {
            this.viewingBooking = booking;
            this.showViewModal = true;
        },

        closeViewModal() {
            this.showViewModal = false;
            this.viewingBooking = null;
        },

        openCreateModal() {
            this.isEditing = false;
            this.resetFormData();
            this.showFormModal = true;
        },

        editBooking(booking) {
            this.isEditing = true;
            this.formData = { 
                ...booking,
                id: booking.id,
                user_id: booking.user?.id,
                property_id: booking.property?.id,
                price_per_night: booking.property?.price_per_night || 0,
                start_date: this.formatDateForInput(booking.start_date),
                end_date: this.formatDateForInput(booking.end_date)
            };
            this.showFormModal = true;
        },

        closeFormModal() {
            this.showFormModal = false;
            this.resetFormData();
        },

        confirmDelete(id, name) {
            this.deleteBookingId = id;
            this.deleteBookingName = name;
            this.showDeleteModal = true;
        },

        closeDeleteModal() {
            this.showDeleteModal = false;
            this.deleteBookingId = null;
            this.deleteBookingName = '';
        },

        async submitForm() {
            this.loading = true;
            
            const url = this.isEditing 
                ? routes.update.replace(':id', this.formData.id)
                : routes.store;
            
            const method = this.isEditing ? 'PUT' : 'POST';
            
            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.closeFormModal();
                    window.location.reload();
                } else {
                    const errorMsg = data.errors 
                        ? Object.values(data.errors).flat().join('\n')
                        : data.message || 'Une erreur est survenue';
                    this.showNotification(errorMsg, 'error');
                }
            } catch (error) {
                this.showNotification('Erreur réseau: ' + error.message, 'error');
            } finally {
                this.loading = false;
            }
        },

        async deleteBooking() {
            this.loading = true;
            
            try {
                const url = routes.destroy.replace(':id', this.deleteBookingId);
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    this.closeDeleteModal();
                    window.location.reload();
                } else {
                    this.showNotification(data.message || 'Une erreur est survenue', 'error');
                }
            } catch (error) {
                this.showNotification('Erreur réseau', 'error');
            } finally {
                this.loading = false;
            }
        },

        async calculateTotalPrice() {
            if (!this.formData.property_id || !this.formData.start_date || !this.formData.end_date) {
                return;
            }

            try {
                // Get property price
                const propertyResponse = await fetch(`/api/properties/${this.formData.property_id}`);
                const property = await propertyResponse.json();
                this.formData.price_per_night = property.price_per_night;

                // Calculate number of nights
                const startDate = new Date(this.formData.start_date);
                const endDate = new Date(this.formData.end_date);
                const diffTime = Math.abs(endDate - startDate);
                this.formData.number_of_nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                // Calculate total price
                this.formData.total_price = this.formData.number_of_nights * this.formData.price_per_night;
            } catch (error) {
                console.error('Error calculating price:', error);
            }
        },

       filterBookings() {
            const url = new URL(window.location.href);
            if (!this.statusFilter) {
                // Remove status parameter when "Tous les statuts" is selected
                url.searchParams.delete('status');
            } else {
                // Set status parameter when a specific status is selected
                url.searchParams.set('status', this.statusFilter);
            }
            
            // Reset page to 1 when filtering to avoid empty pages
            url.searchParams.set('page', '1');
            
            window.location.href = url.toString();
        },

        resetFormData() {
            this.formData = {
                user_id: '',
                property_id: '',
                start_date: '',
                end_date: '',
                number_of_nights: 0,
                price_per_night: 0,
                total_price: 0,
                status: 'CONFIRMED',
                special_requests: ''
            };
        },

        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR');
        },

        formatDateForInput(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toISOString().split('T')[0];
        },

        getStatusClass(status) {
            const classes = {
                'CONFIRMED': 'bg-green-100 text-green-800',
                'PENDING': 'bg-yellow-100 text-yellow-800',
                'CANCELLED': 'bg-red-100 text-red-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        },

        showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => notification.remove(), 3000);
        }
    }
}
</script>
@endsection