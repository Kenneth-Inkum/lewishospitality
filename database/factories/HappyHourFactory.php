<?php

namespace Database\Factories;

use App\Models\HappyHour;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HappyHour>
 */
class HappyHourFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'location_id' => Location::factory(),
            'day_of_week' => fake()->numberBetween(1, 5), // Mon–Fri
            'starts_at' => fake()->randomElement(['15:00:00', '16:00:00', '16:30:00']),
            'ends_at' => fake()->randomElement(['18:00:00', '19:00:00', '19:30:00']),
            'label' => fake()->randomElement(['Happy Hour', 'Cocktail Hour', 'Sunset Specials']),
            'active' => true,
        ];
    }
}
