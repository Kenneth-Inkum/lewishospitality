<?php

namespace Database\Factories;

use App\Models\Guest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guest>
 */
class GuestFactory extends Factory
{
    /** @var array<string> */
    private static array $dietaryOptions = [
        'vegetarian', 'vegan', 'gluten_free', 'halal', 'kosher',
        'nut_allergy', 'shellfish_allergy', 'dairy_free',
    ];

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('(###) ###-####'),
            'dietary_preferences' => fake()->boolean(50)
                ? fake()->randomElements(self::$dietaryOptions, fake()->numberBetween(1, 3))
                : null,
            'notes' => fake()->boolean(30) ? fake()->sentence() : null,
            'loyalty_points' => fake()->numberBetween(0, 2500),
        ];
    }

    public function loyaltyMember(): static
    {
        return $this->state(['loyalty_points' => fake()->numberBetween(500, 5000)]);
    }
}
