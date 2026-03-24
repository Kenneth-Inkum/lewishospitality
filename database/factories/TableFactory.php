<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Table>
 */
class TableFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        static $counter = 1;

        return [
            'location_id' => Location::factory(),
            'name' => 'Table '.$counter++,
            'capacity' => fake()->randomElement([2, 2, 4, 4, 4, 6, 8]),
            'section' => fake()->randomElement(['Main Dining', 'Patio', 'Bar', 'Private Room']),
            'pos_x' => fake()->randomFloat(2, 0, 100),
            'pos_y' => fake()->randomFloat(2, 0, 100),
            'shape' => fake()->randomElement(['round', 'square', 'rectangle']),
            'status' => 'available',
            'active' => true,
        ];
    }

    public function occupied(): static
    {
        return $this->state(['status' => 'occupied']);
    }

    public function reserved(): static
    {
        return $this->state(['status' => 'reserved']);
    }
}
