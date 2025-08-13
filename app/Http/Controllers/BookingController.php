<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $bookings = Auth::user()->bookings()->with('property')->latest()->paginate(10);
        return view('bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
         if (auth()->id() !== $booking->user_id) {
        abort(403);
        }
        return view('bookings.show', compact('booking'));
    }
    public function showCancelForm(Booking $booking)
{
    // Vérification que l'utilisateur est bien le propriétaire
    if (auth()->id() !== $booking->user_id) {
        abort(403);
    }
    
    // Vérification que la réservation peut être annulée
    if ($booking->status !== 'CONFIRMED') {
        return redirect()->route('bookings.index')
                       ->with('error', 'Seules les réservations confirmées peuvent être annulées');
    }
    
    return view('bookings.cancel', compact('booking'));
}
   public function cancel(Request $request, Booking $booking)
{
    if (auth()->id() !== $booking->user_id) {
        abort(403);
    }
    
    // Empêche l'annulation si déjà annulée
    if ($booking->status === 'CANCELLED') {
        return back()->with('error', 'Cette réservation est déjà annulée');
    }
    
    $validated = $request->validate([
        'cancellation_reason' => 'nullable|string|max:255'
    ]);
    
    $booking->update([
        'status' => 'CANCELLED',
        'cancellation_reason' => $validated['cancellation_reason'] ?? null
    ]);
    
    return redirect()->route('bookings.index')
                   ->with('success', 'Réservation annulée avec succès');
}
}