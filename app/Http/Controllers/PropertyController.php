<?php
namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a paginated list of available properties
     * Supports filtering by city and maximum price
     */
    public function index(Request $request)
    {
        // Start with active properties only
        $properties = Property::active()
            // Apply city filter if provided
            ->when($request->city, function($query) use ($request) {
                return $query->where('city', 'like', '%' . $request->city . '%');
            })
            // Apply price filter if provided
            ->when($request->max_price, function($query) use ($request) {
                return $query->where('price_per_night', '<=', $request->max_price);
            })
            ->paginate(12);

        return view('properties.index', compact('properties'));
    }

    // Display details of a specific property
    public function show(Property $property)
    {
        return view('properties.show', compact('property'));
    }
}