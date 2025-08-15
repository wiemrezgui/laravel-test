<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Initialize controller instance with auth middleware
     * Ensures all booking routes require authentication
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a paginated list of the user's bookings
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Eager load properties with bookings to prevent N+1 queries
        $bookings = Auth::user()->bookings()
                       ->with('property')
                       ->latest()
                       ->paginate(10);
                       
        return view('bookings.index', compact('bookings'));
    }

    // Display details of a specific booking
    public function show(Booking $booking)
    {
        // Authorization check - user can only view their own bookings
        if (auth()->id() !== $booking->user_id) {
            abort(403); // Forbidden access
        }
        
        return view('bookings.show', compact('booking'));
    }

    // Show the form for cancelling a booking
    public function showCancelForm(Booking $booking)
    {
        // Verify booking ownership
        if (auth()->id() !== $booking->user_id) {
            abort(403);
        }
        
        // Business rule: Only confirmed bookings can be cancelled
        if ($booking->status !== 'CONFIRMED') {
            return redirect()->route('bookings.index')
                           ->with('error', 'Only confirmed bookings can be cancelled');
        }
        
        return view('bookings.cancel', compact('booking'));
    }

    // Process booking cancellation
    public function cancel(Request $request, Booking $booking)
    {
        // Reauthorize in case route is accessed directly
        if (auth()->id() !== $booking->user_id) {
            abort(403);
        }
        
        // Prevent duplicate cancellations
        if ($booking->status === 'CANCELLED') {
            return back()->with('error', 'This booking is already cancelled');
        }
        
        // Validate optional cancellation reason
        $validated = $request->validate([
            'cancellation_reason' => 'nullable|string|max:255'
        ]);
        
        // Update booking status and reason
        $booking->update([
            'status' => 'CANCELLED',
            'cancellation_reason' => $validated['cancellation_reason'] ?? null
        ]);
        
        return redirect()->route('bookings.index')
                       ->with('success', 'Booking cancelled successfully');
    }
}