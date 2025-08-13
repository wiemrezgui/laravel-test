<?php

namespace App\Livewire;

use App\Models\Property;
use App\Models\Booking;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingManager extends Component
{
    // Propriétés publiques du composant
    public Property $property; // La propriété à réserver
    public $startDate; // Date de début de séjour
    public $endDate; // Date de fin de séjour
    public $specialRequests = ''; // Demandes spéciales
    public $totalPrice = 0; // Prix total calculé
    public $numberOfNights = 0; // Nombre de nuits calculé
    public $isAvailable = null; // Disponibilité de la propriété

    // Règles de validation
    protected $rules = [
        'startDate' => 'required|date|after:today', // Doit être une date future
        'endDate' => 'required|date|after:startDate', // Doit être après startDate
        'specialRequests' => 'nullable|string|max:500' // Optionnel, max 500 caractères
    ];

    // Méthode exécutée lors de l'initialisation du composant
    public function mount(Property $property)
    {
        $this->property = $property;
        // Définit des dates par défaut (demain et après-demain)
        $this->startDate = Carbon::now()->addDay()->format('Y-m-d');
        $this->endDate = Carbon::now()->addDays(2)->format('Y-m-d');
        $this->checkAvailability(); // Vérifie immédiatement la disponibilité
    }

    // Méthode appelée quand startDate est modifiée
    public function updatedStartDate()
    {
        $this->checkAvailability();
    }

    // Méthode appelée quand endDate est modifiée
    public function updatedEndDate()
    {
        $this->checkAvailability();
    }

    // Vérifie la disponibilité de la propriété pour les dates sélectionnées
    public function checkAvailability()
    {
        if ($this->startDate && $this->endDate && $this->startDate < $this->endDate) {
            // Utilise la méthode du modèle Property pour vérifier la disponibilité
            $this->isAvailable = $this->property->isAvailableForDates($this->startDate, $this->endDate);
            $this->calculatePrice(); // Recalcule le prix si les dates changent
        } else {
            // Réinitialise si les dates ne sont pas valides
            $this->isAvailable = null;
            $this->totalPrice = 0;
            $this->numberOfNights = 0;
        }
    }

    // Calcule le prix total et le nombre de nuitées
    public function calculatePrice()
    {
        if ($this->startDate && $this->endDate) {
            $start = Carbon::parse($this->startDate);
            $end = Carbon::parse($this->endDate);
            $this->numberOfNights = $start->diffInDays($end); // Calcul des nuitées
            $this->totalPrice = $this->numberOfNights * $this->property->price_per_night; // Calcul du prix
        }
    }

    // Crée une nouvelle réservation
    public function createBooking()
    {
        // Vérifie que l'utilisateur est connecté
        if (!Auth::check()) {
            session()->flash('error', 'Vous devez être connecté pour effectuer une réservation.');
            return redirect()->route('login');
        }

        $this->validate(); // Valide les données du formulaire

        // Vérifie la disponibilité
        if (!$this->isAvailable) {
            session()->flash('error', 'Cette propriété n\'est pas disponible pour ces dates.');
            return;
        }

        // Ajout de la réservation
        $booking = Booking::create([
            'user_id' => Auth::id(), // ID de l'utilisateur connecté
            'property_id' => $this->property->id,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'total_price' => $this->totalPrice,
            'status' => 'CONFIRMED', // Statut par défaut
            'special_requests' => $this->specialRequests
        ]);

        // Message de succès et redirection
        session()->flash('success', 'Réservation effectuée avec succès !');
        return redirect()->route('bookings.show', $booking);
    }

    // Méthode de rendu du composant
    public function render()
    {
        return view('livewire.booking-manager');
    }
}