<?php

namespace App\Livewire;

use App\Models\Property;
use App\Models\Booking;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingManager extends Component
{
    public Property $property;
    public $startDate;
    public $endDate;
    public $specialRequests = '';
    public $totalPrice = 0;
    public $numberOfNights = 0;
    public $isAvailable = null;

    protected $rules = [
        'startDate' => 'required|date|after:today',
        'endDate' => 'required|date|after:startDate',
        'specialRequests' => 'nullable|string|max:500'
    ];

    public function mount(Property $property)
    {
        $this->property = $property;
        $this->startDate = Carbon::now()->addDay()->format('Y-m-d');
        $this->endDate = Carbon::now()->addDays(2)->format('Y-m-d');
        $this->checkAvailability();
    }

    public function updatedStartDate()
    {
        $this->checkAvailability();
    }

    public function updatedEndDate()
    {
        $this->checkAvailability();
    }

    public function checkAvailability()
    {
        if ($this->startDate && $this->endDate && $this->startDate < $this->endDate) {
            $this->isAvailable = $this->property->isAvailableForDates($this->startDate, $this->endDate);
            $this->calculatePrice();
        } else {
            $this->isAvailable = null;
            $this->totalPrice = 0;
            $this->numberOfNights = 0;
        }
    }

    public function calculatePrice()
    {
        if ($this->startDate && $this->endDate) {
            $start = Carbon::parse($this->startDate);
            $end = Carbon::parse($this->endDate);
            $this->numberOfNights = $start->diffInDays($end);
            $this->totalPrice = $this->numberOfNights * $this->property->price_per_night;
        }
    }

    public function createBooking()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Vous devez être connecté pour effectuer une réservation.');
            return redirect()->route('login');
        }

        $this->validate();

        if (!$this->isAvailable) {
            session()->flash('error', 'Cette propriété n\'est pas disponible pour ces dates.');
            return;
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'property_id' => $this->property->id,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'total_price' => $this->totalPrice,
            'status' => 'CONFIRMED',
            'special_requests' => $this->specialRequests
        ]);

        session()->flash('success', 'Réservation effectuée avec succès !');
        return redirect()->route('bookings.show', $booking);
    }

    public function render()
    {
        return view('livewire.booking-manager');
    }
}
