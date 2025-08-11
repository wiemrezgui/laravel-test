<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price_per_night', 'address', 'city', 'country',
        'max_guests', 'bedrooms', 'bathrooms', 'status'
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isAvailableForDates($startDate, $endDate): bool
    {
        return !$this->bookings()
            ->where('status', '!=', 'CANCELLED')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<=', $startDate)->where('end_date', '>', $startDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<', $endDate)->where('end_date', '>=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '>=', $startDate)->where('end_date', '<=', $endDate);
                });
            })->exists();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }
}
