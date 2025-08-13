<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory; // Utilise le système de factories pour les tests

    // Champs autorisés
    protected $fillable = [
        'user_id', 'property_id', 'start_date', 'end_date',
        'total_price', 'status', 'special_requests','cancellation_reason'
    ];

    // Conversion automatique des types de données
    protected $casts = [
        'start_date' => 'date', // Convertit en objet Carbon
        'end_date' => 'date',   // Convertit en objet Carbon
        'total_price' => 'decimal:2', // Format décimal avec 2 chiffres après la virgule
    ];

    // Relation avec le modèle User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // Une réservation appartient à un utilisateur
    }

    // Relation avec le modèle Property
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class); // Une réservation appartient à une propriété
    }

    // Méthode pour calculer le nombre de nuitées
    public function getNumberOfNightsAttribute(): int
    {
        return Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date));
    }

    // Méthode pour calculer le prix total
    public function calculateTotalPrice(): float
    {
        return $this->property ? $this->number_of_nights * $this->property->price_per_night : 0;
    }
}