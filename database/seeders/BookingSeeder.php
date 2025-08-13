<?php
namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $properties = Property::all();

        foreach ($properties as $property) {
            // Créer quelques réservations pour chaque propriété
            for ($i = 0; $i < 3; $i++) {
                $startDate = Carbon::now()->addDays(rand(1, 30));
                $endDate = $startDate->copy()->addDays(rand(2, 7));
                
                Booking::create([
                    'user_id' => $users->random()->id,
                    'property_id' => $property->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'total_price' => $startDate->diffInDays($endDate) * $property->price_per_night,
                    'status' => collect(['PENDING', 'CONFIRMED'])->random(),
                    'special_requests' => rand(0, 1) ? 'Arrivée tardive possible' : null,
                ]);
            }
        }
    }
}