<?php

namespace Database\Factories;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MenuItem>
 */
class MenuItemFactory extends Factory
{
    /** @var array<string> */
    private static array $dietaryTags = [
        'vegetarian', 'vegan', 'gluten_free', 'halal', 'spicy', 'contains_nuts',
    ];

    /** @var array<string> */
    private static array $dishes = [
        'Crispy Calamari', 'Shrimp Cocktail', 'Burrata & Heirloom Tomato',
        'Caesar Salad', 'French Onion Soup', 'Lobster Bisque',
        'Pan-Seared Salmon', 'Grilled Filet Mignon', 'Rack of Lamb',
        'Truffle Mushroom Risotto', 'Lobster Ravioli', 'Chicken Marsala',
        'Wagyu Beef Burger', 'Smoked Turkey Club', 'Crab Cake Sandwich',
        'Chocolate Lava Cake', 'New York Cheesecake', 'Crème Brûlée',
        'Truffle Fries', 'Roasted Brussels Sprouts', 'Mac & Cheese',
        'Avocado Toast', 'Eggs Benedict', 'Smoked Salmon Bagel',
    ];

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement(self::$dishes);
        $tags = fake()->boolean(70)
            ? fake()->randomElements(self::$dietaryTags, fake()->numberBetween(1, 3))
            : null;

        return [
            'menu_category_id' => MenuCategory::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(12),
            'price' => fake()->randomFloat(2, 9, 48),
            'dietary_tags' => $tags,
            'featured' => fake()->boolean(25),
            'available_always' => true,
            'available_from' => null,
            'available_until' => null,
            'available_days' => null,
            'sort_order' => fake()->numberBetween(0, 20),
            'active' => true,
            'pos_id' => null,
        ];
    }

    public function featured(): static
    {
        return $this->state(['featured' => true]);
    }

    public function seasonal(string $from, string $until): static
    {
        return $this->state([
            'available_always' => false,
            'available_from' => $from,
            'available_until' => $until,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(['active' => false]);
    }
}
