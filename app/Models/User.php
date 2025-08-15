<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // Utilisation des traits pour les fonctionnalités de base
    use HasFactory, Notifiable;

    /**
     * Champs autorisés
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password', // Stocké de manière sécurisée 
        'role'
    ];

    /**
     * Champs cachés lors de la sérialisation
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token', // Pour la fonctionnalité "se souvenir de moi"
    ];

    /**
     * Conversion automatique des types de données
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Conversion en objet Carbon
            'password' => 'hashed', // Hashage automatique du mot de passe
        ];
    }

    // Relation avec le modèle Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class); // Un utilisateur peut avoir plusieurs réservations
    }

    // Vérifier un admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Vérifier un simple utilisateur
    public function isUser()
    {
        return $this->role === 'user';
    }
}