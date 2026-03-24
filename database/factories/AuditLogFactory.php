<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLog>
 */
class AuditLogFactory extends Factory
{
    /** @var array<string> */
    private static array $actions = [
        'menu_item.created', 'menu_item.updated', 'menu_item.deleted',
        'event.published', 'event.unpublished',
        'promotion.activated', 'promotion.deactivated',
        'reservation.confirmed', 'reservation.cancelled',
        'contact_submission.read', 'contact_submission.archived',
        'user.login', 'user.password_changed',
    ];

    /** @var array<string> */
    private static array $modelTypes = [
        'App\\Models\\MenuItem', 'App\\Models\\Event',
        'App\\Models\\Promotion', 'App\\Models\\Reservation',
        'App\\Models\\ContactSubmission',
    ];

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'user_id' => fake()->boolean(80) ? User::factory() : null,
            'action' => fake()->randomElement(self::$actions),
            'model_type' => fake()->randomElement(self::$modelTypes),
            'model_id' => fake()->numberBetween(1, 500),
            'old_values' => ['name' => fake()->word(), 'active' => true],
            'new_values' => ['name' => fake()->word(), 'active' => false],
            'ip_address' => fake()->ipv4(),
        ];
    }
}
