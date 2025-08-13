<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory; // Utilise le système de factories pour les tests

    // Champs autorisés
    protected $fillable = [
        'name', 'description', 'price_per_night', 'address', 'city', 'country',
        'max_guests', 'bedrooms', 'bathrooms', 'status'
    ];

    // Conversion automatique des types de données
    protected $casts = [
        'price_per_night' => 'decimal:2', // Format décimal avec 2 chiffres après la virgule
    ];

    // Relation avec le modèle Booking
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class); // Une propriété peut avoir plusieurs réservations
    }

    // Vérifie si la propriété est disponible pour une période donnée
    public function isAvailableForDates($startDate, $endDate): bool
    {
        return !$this->bookings()
            ->where('status', '!=', 'CANCELLED') // Exclut les réservations annulées
            ->where(function ($query) use ($startDate, $endDate) {
                // Vérifie les chevauchements de dates
                $query->where(function ($q) use ($startDate, $endDate) {
                    // Réservation commence avant et se termine après la date de début demandée
                    $q->where('start_date', '<=', $startDate)->where('end_date', '>', $startDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    // Réservation commence avant la date de fin demandée et se termine après
                    $q->where('start_date', '<', $endDate)->where('end_date', '>=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    // Réservation est complètement incluse dans la période demandée
                    $q->where('start_date', '>=', $startDate)->where('end_date', '<=', $endDate);
                });
            })->exists(); // Retourne true si aucune réservation conflictuelle n'existe
    }

    //filtrer les propriétés actives
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE'); // Permet Property::active()->get()
    }
}