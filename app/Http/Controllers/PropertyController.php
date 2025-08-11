<?php
namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $properties = Property::active()
            ->when($request->city, fn($query) => $query->where('city', 'like', '%' . $request->city . '%'))
            ->when($request->max_price, fn($query) => $query->where('price_per_night', '<=', $request->max_price))
            ->paginate(12);

        return view('properties.index', compact('properties'));
    }

    public function show(Property $property)
    {
        return view('properties.show', compact('property'));
    }
}