<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class AdminPropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('bookings')
            ->withCount('bookings')
            ->paginate(15);

        return view('admin.properties.index', [
            'properties' => $properties,
            'routes' => [
                'update' => route('admin.properties.update', ['property' => ':id']),
                'store' => route('admin.properties.store'),
                'destroy' => route('admin.properties.destroy', ['property' => ':id'])
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'max_guests' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:1',
            'status' => 'required|in:ACTIVE,INACTIVE,MAINTENANCE'
        ]);

        Property::create($validated);

        return response()->json(['success' => true, 'message' => 'Propriété crée avec succès']);
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'max_guests' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:1',
            'status' => 'required|in:ACTIVE,INACTIVE,MAINTENANCE'
        ]);

        $property->update($validated);

        return response()->json(['success' => true, 'message' => 'Propriété mise à jour avec succès']);
    }

    public function destroy(Property $property)
    {
        $property->delete();
        return response()->json(['success' => true, 'message' => 'Propriété supprimée avec succès']);
    }
}