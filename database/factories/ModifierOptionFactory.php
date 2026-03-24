<?php

namespace Database\Factories;

use App\Models\ModifierGroup;
use App\Models\ModifierOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ModifierOption>
 */
class ModifierOptionFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $options = [
            'Rare', 'Medium Rare', 'Medium', 'Medium Well', 'Well Done',
            'Béarnaise', 'Peppercorn', 'Chimichurri', 'Garlic Butter',
            'Grilled Shrimp +$4', 'Sautéed Mushrooms', 'Caramelized Onions',
            'Extra Cheese', 'Bacon', 'Avocado',
            'Truffle Oil', 'Crispy Shallots', 'House-Made Hot Sauce',
        ];

        return [
            'modifier_group_id' => ModifierGroup::factory(),
            'name' => fake()->randomElement($options),
            'price_adjustment' => fake()->randomElement([0.00, 0.00, 0.00, 1.50, 2.00, 2.50, 3.00, 4.00]),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
