<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;

class AdminBookingController extends Controller
{
    // Display paginated list of bookings with optional status filter
    public function index()
    {
        $bookings = Booking::with(['user', 'property'])
            // Filter by status if provided in request
            ->when(request()->filled('status'), function($query) {
                return $query->where('status', request('status'));
            })
            ->paginate(15);

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'users' => User::all(), // All users for admin assignment
            'properties' => Property::all(), // All properties for selection
            'routes' => [
                'destroy' => route('admin.bookings.destroy', ['booking' => ':id'])
            ]
        ]);
    }

    // Create new booking from admin panel
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date|after:today', // Must be future date
            'end_date' => 'required|date|after:start_date', // Must be after start
            'status' => 'required|in:PENDING,CONFIRMED,CANCELLED',
            'special_requests' => 'nullable|string|max:500'
        ]);

        // Calculate pricing based on property and dates
        $property = Property::find($validated['property_id']);
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $numberOfNights = $startDate->diffInDays($endDate);
        $totalPrice = $numberOfNights * $property->price_per_night;

        $booking = Booking::create([
            ...$validated,
            'total_price' => $totalPrice,
            'number_of_nights' => $numberOfNights
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Réservation créée avec succès'
        ]);
    }

    // Update existing booking
    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:PENDING,CONFIRMED,CANCELLED',
            'special_requests' => 'nullable|string|max:500'
        ]);

        // Recalculate price if key details changed
        if ($validated['property_id'] !== $booking->property_id || 
            $validated['start_date'] !== $booking->start_date->format('Y-m-d') || 
            $validated['end_date'] !== $booking->end_date->format('Y-m-d')) {
            
            $property = Property::find($validated['property_id']);
            $startDate = Carbon::parse($validated['start_date']);
            $endDate = Carbon::parse($validated['end_date']);
            $numberOfNights = $startDate->diffInDays($endDate);
            $totalPrice = $numberOfNights * $property->price_per_night;

            $validated['total_price'] = $totalPrice;
            $validated['number_of_nights'] = $numberOfNights;
        }

        $booking->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Réservation mise à jour avec succès'
        ]);
    }

    // Delete booking
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Réservation supprimée avec succès'
        ]);
    }
}