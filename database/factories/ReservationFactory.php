<?php

namespace Database\Factories;

use App\Models\Guest;
use App\Models\Location;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'location_id' => Location::factory(),
            'table_id' => null,
            'guest_id' => fake()->boolean(60) ? Guest::factory() : null,
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->numerify('(###) ###-####'),
            'date' => fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            'time' => fake()->randomElement(['17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30']),
            'party_size' => fake()->numberBetween(1, 10),
            'special_requests' => fake()->boolean(30) ? fake()->sentence() : null,
            'status' => fake()->randomElement(['pending', 'confirmed', 'pending']),
            'source' => fake()->randomElement(['online_form', 'phone', 'opentable', 'resy']),
            'notes' => null,
            'reminder_sent_at' => null,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(['status' => 'confirmed']);
    }

    public function cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }

    public function forDate(string $date): static
    {
        return $this->state(['date' => $date]);
    }
}
