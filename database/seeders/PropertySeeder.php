<?php
namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $properties = [
            [
                'name' => 'Appartement moderne Paris',
                'description' => 'Magnifique appartement au cœur de Paris, proche des monuments.',
                'price_per_night' => 120.00,
                'address' => '123 Rue de Rivoli',
                'city' => 'Paris',
                'country' => 'France',
                'max_guests' => 4,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Villa avec piscine Nice',
                'description' => 'Villa luxueuse avec piscine privée et vue sur la mer.',
                'price_per_night' => 250.00,
                'address' => '456 Promenade des Anglais',
                'city' => 'Nice',
                'country' => 'France',
                'max_guests' => 8,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Chalet montagne Chamonix',
                'description' => 'Chalet traditionnel avec vue sur le Mont-Blanc.',
                'price_per_night' => 180.00,
                'address' => '789 Route du Mont-Blanc',
                'city' => 'Chamonix',
                'country' => 'France',
                'max_guests' => 6,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'status' => 'ACTIVE',
            ]
        ];

        foreach ($properties as $property) {
            Property::create($property);
        }
    }
}