<?php

namespace Database\Factories;

use App\Models\ContactSubmission;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactSubmission>
 */
class ContactSubmissionFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'location_id' => fake()->boolean(70) ? Location::factory() : null,
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'subject' => fake()->randomElement([
                'Reservation Inquiry', 'Private Event Request', 'Catering Question',
                'Feedback', 'Menu Allergy Question', 'Gift Card Inquiry',
            ]),
            'message' => fake()->paragraph(3),
            'read_at' => null,
            'archived_at' => null,
        ];
    }

    public function read(): static
    {
        return $this->state(['read_at' => now()]);
    }

    public function archived(): static
    {
        return $this->state(['read_at' => now()->subDay(), 'archived_at' => now()]);
    }
}
