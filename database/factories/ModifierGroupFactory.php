<?php

namespace Database\Factories;

use App\Models\ModifierGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ModifierGroup>
 */
class ModifierGroupFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $groups = [
            ['name' => 'Cooking Temperature', 'required' => true, 'min' => 1, 'max' => 1],
            ['name' => 'Sauce Choice', 'required' => true, 'min' => 1, 'max' => 1],
            ['name' => 'Add-ons', 'required' => false, 'min' => 0, 'max' => 5],
            ['name' => 'Protein Choice', 'required' => true, 'min' => 1, 'max' => 1],
            ['name' => 'Side Substitution', 'required' => false, 'min' => 0, 'max' => 1],
            ['name' => 'Salad Dressing', 'required' => true, 'min' => 1, 'max' => 1],
            ['name' => 'Bread Choice', 'required' => false, 'min' => 0, 'max' => 1],
            ['name' => 'Spice Level', 'required' => false, 'min' => 0, 'max' => 1],
        ];

        $group = fake()->randomElement($groups);

        return [
            'name' => $group['name'],
            'required' => $group['required'],
            'min_selections' => $group['min'],
            'max_selections' => $group['max'],
        ];
    }
}
