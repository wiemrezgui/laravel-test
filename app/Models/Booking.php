<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory; // Enables factory support for testing

    // Fields that are mass assignable
    protected $fillable = [
        'user_id', 'property_id', 'start_date', 'end_date',
        'total_price', 'status', 'special_requests', 'cancellation_reason'
    ];

    // Attribute casting
    protected $casts = [
        'start_date' => 'date', // Converts to Carbon instance
        'end_date' => 'date',   // Converts to Carbon instance
        'total_price' => 'decimal:2', // Stores as decimal with 2 places
    ];

    // Relationship with User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // Each booking belongs to one user
    }

    // Relationship with Property model
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class); // Each booking belongs to one property
    }

    // Accessor for number of nights (computed attribute)
    public function getNumberOfNightsAttribute(): int
    {
        return Carbon::parse($this->start_date)->diffInDays($this->end_date);
    }

    // Calculate total price based on property rate and duration
    public function calculateTotalPrice(): float
    {
        return $this->property ? $this->number_of_nights * $this->property->price_per_night : 0;
    }
}