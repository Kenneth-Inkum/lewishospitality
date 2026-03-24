<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Promotion>
 */
class PromotionFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $type = fake()->randomElement(['percentage', 'fixed_amount', 'bogo']);
        $startsAt = fake()->dateTimeBetween('-1 week', 'now');
        $endsAt = fake()->dateTimeBetween('+2 weeks', '+3 months');

        return [
            'location_id' => fake()->boolean(60) ? Location::factory() : null,
            'title' => fake()->randomElement([
                'Happy Hour Special', 'Date Night Deal', 'Weekend Brunch Offer',
                'Loyalty Member Exclusive', 'Summer Sip & Save', 'Early Bird Discount',
            ]),
            'description' => fake()->sentence(10),
            'type' => $type,
            'discount_value' => match ($type) {
                'percentage' => fake()->randomElement([10, 15, 20, 25]),
                'fixed_amount' => fake()->randomElement([5, 10, 15, 20]),
                default => null,
            },
            'free_item_id' => null,
            'applicable_to' => fake()->randomElement(['all', 'loyalty_members']),
            'starts_at' => $startsAt->format('Y-m-d'),
            'ends_at' => $endsAt->format('Y-m-d'),
            'show_on_homepage' => fake()->boolean(40),
            'active' => true,
        ];
    }

    public function active(): static
    {
        return $this->state([
            'active' => true,
            'starts_at' => now()->subDay()->format('Y-m-d'),
            'ends_at' => now()->addMonth()->format('Y-m-d'),
        ]);
    }

    public function expired(): static
    {
        return $this->state([
            'starts_at' => now()->subMonths(2)->format('Y-m-d'),
            'ends_at' => now()->subDay()->format('Y-m-d'),
        ]);
    }
}
