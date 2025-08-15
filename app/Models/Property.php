<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory; // Enables factory support for testing

    // Fields that are mass assignable
    protected $fillable = [
        'name', 'description', 'price_per_night', 'address', 'city', 'country',
        'max_guests', 'bedrooms', 'bathrooms', 'status'
    ];

    // Attribute casting
    protected $casts = [
        'price_per_night' => 'decimal:2', // Stores as decimal with 2 places
    ];

    // Relationship with Booking model
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class); // A property can have many bookings
    }

    // Check property availability for given date range
    public function isAvailableForDates($startDate, $endDate): bool
    {
        return !$this->bookings()
            ->where('status', '!=', 'CANCELLED') // Ignore cancelled bookings
            ->where(function ($query) use ($startDate, $endDate) {
                // Check for date overlaps
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<=', $startDate)
                      ->where('end_date', '>', $startDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<', $endDate)
                      ->where('end_date', '>=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '>=', $startDate)
                      ->where('end_date', '<=', $endDate);
                });
            })->exists(); // Returns true if no conflicting bookings exist
    }

    // Scope to filter active properties
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE'); // Usage: Property::active()->get()
    }
}