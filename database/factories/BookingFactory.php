<?php
namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+3 months');
        $endDate = Carbon::instance($startDate)->addDays($this->faker->numberBetween(1, 14));
        
        return [
            'user_id' => User::factory(),
            'property_id' => Property::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_price' => $this->faker->randomFloat(2, 100, 2000),
            'status' => $this->faker->randomElement(['PENDING', 'CONFIRMED', 'CANCELLED']),
            'special_requests' => $this->faker->optional()->sentence(),
        ];
    }
}