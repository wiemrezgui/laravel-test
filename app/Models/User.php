<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Provides factory and notification features

    /**
     * The attributes that are mass assignable
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password', // Automatically hashed
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token', // Used for "remember me" functionality
    ];

    /**
     * The attributes that should be cast
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Converts to Carbon instance
            'password' => 'hashed', // Ensures automatic password hashing
        ];
    }

    // Relationship with Booking model
    public function bookings()
    {
        return $this->hasMany(Booking::class); // A user can have many bookings
    }

    // Check if user is admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Check if user is regular user
    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}