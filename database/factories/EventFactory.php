<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $title = fake()->randomElement([
            'Jazz & Cocktails Night', 'Wine Pairing Dinner', 'Chef\'s Table Experience',
            'Trivia Night', 'Live Music Showcase', 'Cooking Masterclass',
            'New Year\'s Eve Gala', 'Valentine\'s Day Dinner', 'Whiskey Tasting',
            'Farm-to-Table Brunch', 'Rooftop Summer Party', 'Holiday Cookie Exchange',
        ]).' '.fake()->year();

        $startsAt = fake()->dateTimeBetween('now', '+3 months');

        return [
            'location_id' => fake()->boolean(70) ? Location::factory() : null,
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 9999),
            'description' => fake()->paragraphs(3, true),
            'starts_at' => $startsAt,
            'ends_at' => fake()->boolean(60)
                ? (clone $startsAt)->modify('+3 hours')
                : null,
            'cta_type' => fake()->randomElement(['none', 'rsvp', 'external_link']),
            'cta_label' => fake()->randomElement(['Reserve Your Seat', 'Get Tickets', 'Learn More', null]),
            'cta_url' => fake()->boolean(50) ? fake()->url() : null,
            'rsvp_enabled' => fake()->boolean(40),
            'published' => true,
        ];
    }

    public function withRsvp(): static
    {
        return $this->state([
            'cta_type' => 'rsvp',
            'cta_label' => 'Reserve Your Seat',
            'rsvp_enabled' => true,
        ]);
    }

    public function unpublished(): static
    {
        return $this->state(['published' => false]);
    }

    public function past(): static
    {
        return $this->state(function () {
            $startsAt = fake()->dateTimeBetween('-3 months', '-1 day');

            return [
                'starts_at' => $startsAt,
                'ends_at' => (clone $startsAt)->modify('+3 hours'),
            ];
        });
    }
}
