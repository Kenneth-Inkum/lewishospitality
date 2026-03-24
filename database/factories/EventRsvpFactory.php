<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventRsvp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EventRsvp>
 */
class EventRsvpFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory()->withRsvp(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'party_size' => fake()->numberBetween(1, 8),
        ];
    }
}
