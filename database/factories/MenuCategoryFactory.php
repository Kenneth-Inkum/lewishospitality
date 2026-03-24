<?php

namespace Database\Factories;

use App\Models\MenuCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MenuCategory>
 */
class MenuCategoryFactory extends Factory
{
    /** @var array<string> */
    private static array $categories = [
        'Appetizers', 'Soups & Salads', 'Entrees', 'Burgers & Sandwiches',
        'Seafood', 'Steaks & Chops', 'Pasta & Risotto', 'Sides',
        'Desserts', 'Brunch', 'Small Plates', 'Flatbreads',
    ];

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(self::$categories);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'sort_order' => fake()->numberBetween(0, 20),
            'active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['active' => false]);
    }
}
