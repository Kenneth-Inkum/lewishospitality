<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\LocationHour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LocationHour>
 */
class LocationHourFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'location_id' => Location::factory(),
            'day_of_week' => fake()->numberBetween(0, 6),
            'opens_at' => fake()->randomElement(['11:00:00', '11:30:00', '12:00:00']),
            'closes_at' => fake()->randomElement(['21:00:00', '22:00:00', '23:00:00']),
            'closed' => false,
        ];
    }

    public function closed(): static
    {
        return $this->state(['closed' => true, 'opens_at' => null, 'closes_at' => null]);
    }
}
