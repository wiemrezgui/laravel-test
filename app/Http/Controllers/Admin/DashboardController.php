<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Booking;
use App\Models\User;

class DashboardController extends Controller
{
    // Display admin dashboard with key metrics
    public function index()
    {
        $stats = [
            'total_properties' => Property::count(),
            'total_bookings' => Booking::count(),
            'total_users' => User::where('role', 'user')->count(), // Only count regular users
            'total_revenue' => Booking::where('status', 'CONFIRMED')->sum('total_price'), // Only confirmed bookings
            'recent_bookings' => Booking::with(['user', 'property'])
                ->latest()
                ->limit(5) // Last 5 bookings
                ->get(),
            'active_properties' => Property::where('status', 'ACTIVE')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}