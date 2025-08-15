<?php
namespace App\Livewire;

use App\Models\Property;
use App\Models\Booking;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingManager extends Component
{
    // Component public properties
    public Property $property; // The property being booked
    public $startDate; // Check-in date
    public $endDate; // Check-out date
    public $specialRequests = ''; // Optional guest requests
    public $totalPrice = 0; // Calculated total price
    public $numberOfNights = 0; // Calculated nights count
    public $isAvailable = null; // Property availability status

    // Validation rules
    protected $rules = [
        'startDate' => 'required|date|after:today', // Must be future date
        'endDate' => 'required|date|after:startDate', // Must be after start date
        'specialRequests' => 'nullable|string|max:500' // Optional, max 500 chars
    ];

    // Initialize component with property and default dates
    public function mount(Property $property)
    {
        $this->property = $property;
        // Set default dates (tomorrow and day after)
        $this->startDate = Carbon::now()->addDay()->format('Y-m-d');
        $this->endDate = Carbon::now()->addDays(2)->format('Y-m-d');
        $this->checkAvailability(); // Check availability immediately
    }

    // When start date changes
    public function updatedStartDate()
    {
        $this->checkAvailability();
    }

    // When end date changes
    public function updatedEndDate()
    {
        $this->checkAvailability();
    }

    // Check property availability for selected dates
    public function checkAvailability()
    {
        if ($this->startDate && $this->endDate && $this->startDate < $this->endDate) {
            // Use property's availability check method
            $this->isAvailable = $this->property->isAvailableForDates($this->startDate, $this->endDate);
            $this->calculatePrice(); // Recalculate price if dates change
        } else {
            // Reset if dates are invalid
            $this->isAvailable = null;
            $this->totalPrice = 0;
            $this->numberOfNights = 0;
        }
    }

    // Calculate total price and nights count
    public function calculatePrice()
    {
        if ($this->startDate && $this->endDate) {
            $start = Carbon::parse($this->startDate);
            $end = Carbon::parse($this->endDate);
            $this->numberOfNights = $start->diffInDays($end); // Calculate nights
            $this->totalPrice = $this->numberOfNights * $this->property->price_per_night; // Calculate total
        }
    }

    // Create new booking
    public function createBooking()
    {
        // Verify user is authenticated
        if (!Auth::check()) {
            session()->flash('error', 'Please login to make a booking.');
            return redirect()->route('login');
        }

        $this->validate(); // Validate form data

        // Verify property availability
        if (!$this->isAvailable) {
            session()->flash('error', 'Property not available for selected dates.');
            return;
        }

        // Create booking record
        $booking = Booking::create([
            'user_id' => Auth::id(), // Current user ID
            'property_id' => $this->property->id,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'total_price' => $this->totalPrice,
            'status' => 'CONFIRMED', // Default status
            'special_requests' => $this->specialRequests
        ]);

        // Success message and redirect
        session()->flash('success', 'Booking created successfully!');
        return redirect()->route('bookings.show', $booking);
    }

    // Render component view
    public function render()
    {
        return view('livewire.booking-manager');
    }
}