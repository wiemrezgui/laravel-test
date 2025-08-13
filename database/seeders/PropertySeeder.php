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
            ],[
                'name' => 'Tiny house Alsace',
                'description' => 'Petite maison écologique au cœur des vignobles alsaciens.',
                'price_per_night' => 92.00,
                'address' => 'Route des Vins',
                'city' => 'Riquewihr',
                'country' => 'France',
                'max_guests' => 2,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Manoir historique Normandie',
                'description' => 'Manoir du 18ème siècle avec parc et étang privatif.',
                'price_per_night' => 310.00,
                'address' => 'Route du Manoir',
                'city' => 'Honfleur',
                'country' => 'France',
                'max_guests' => 12,
                'bedrooms' => 6,
                'bathrooms' => 4,
                'status' => 'INACTIVE',
            ],
            [
                'name' => 'Gîte rural Auvergne',
                'description' => 'Ancienne ferme rénovée avec vue sur les volcans.',
                'price_per_night' => 75.00,
                'address' => 'Hameau de la Volcane',
                'city' => 'Clermont-Ferrand',
                'country' => 'France',
                'max_guests' => 5,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'status' => 'MAINTENANCE',
            ],
            [
                'name' => 'Cabane sur l\'eau Sologne',
                'description' => 'Cabane flottante avec ponton privé pour pêche et détente.',
                'price_per_night' => 115.00,
                'address' => 'Étang des Saules',
                'city' => 'Romorantin-Lanthenay',
                'country' => 'France',
                'max_guests' => 2,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Maison troglodyte Loire',
                'description' => 'Habitation insolite creusée dans la roche avec piscine naturelle.',
                'price_per_night' => 145.00,
                'address' => 'Route des Troglos',
                'city' => 'Doué-la-Fontaine',
                'country' => 'France',
                'max_guests' => 4,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Appartement design Bordeaux',
                'description' => 'Appartement moderne à deux pas des quais et des vignobles.',
                'price_per_night' => 69.00,
                'address' => '22 Cours de l\'Intendance',
                'city' => 'Bordeaux',
                'country' => 'France',
                'max_guests' => 4,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Studio cosy Montmartre',
                'description' => 'Charmant studio typique avec vue sur le Sacré-Cœur.',
                'price_per_night' => 85.00,
                'address' => '22 Rue Lepic',
                'city' => 'Paris',
                'country' => 'France',
                'max_guests' => 2,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Roulotte romantique Normandie',
                'description' => 'Roulotte aménagée avec spa et vue sur les pommiers.',
                'price_per_night' => 89.00,
                'address' => 'La Ferme du Vieux Chêne',
                'city' => 'Deauville',
                'country' => 'France',
                'max_guests' => 2,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'status' => 'ACTIVE',
            ]
        ];

        foreach ($properties as $property) {
            Property::create($property);
        }
    }
}