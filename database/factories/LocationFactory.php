<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Location>
 */
class LocationFactory extends Factory
{
    /** @var array<string> */
    private static array $dcCities = [
        'Washington', 'Arlington', 'Alexandria', 'Bethesda',
        'Silver Spring', 'Rockville', 'Tysons', 'Chevy Chase',
    ];

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $city = fake()->randomElement(self::$dcCities);
        $state = $city === 'Washington' ? 'DC' : fake()->randomElement(['VA', 'MD']);

        return [
            'name' => fake()->randomElement([
                'The Grille at Penn Quarter', 'Lewis & Co. Kitchen', 'Capitol Brasserie',
                'The Dupont Table', 'Georgetown Hearth', 'Navy Yard Social',
                'Shaw Street Bistro', 'Foggy Bottom Tavern',
            ]),
            'address' => fake()->streetAddress(),
            'city' => $city,
            'state' => $state,
            'zip' => fake()->numerify($state === 'DC' ? '200##' : '2####'),
            'phone' => fake()->numerify('(###) ###-####'),
            'email' => fake()->companyEmail(),
            'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3105.'.fake()->numerify('######'),
            'latitude' => fake()->latitude(38.8, 39.0),
            'longitude' => fake()->longitude(-77.2, -76.9),
            'is_primary' => false,
            'active' => true,
        ];
    }

    public function primary(): static
    {
        return $this->state(['is_primary' => true]);
    }

    public function inactive(): static
    {
        return $this->state(['active' => false]);
    }
}
