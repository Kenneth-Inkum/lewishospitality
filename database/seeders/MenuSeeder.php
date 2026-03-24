<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\ModifierGroup;
use App\Models\ModifierOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Appetizers',
                'sort_order' => 1,
                'items' => [
                    ['name' => 'Crispy Calamari', 'price' => 16.00, 'tags' => ['contains_nuts'], 'featured' => false, 'desc' => 'Lightly breaded, flash-fried, served with marinara and lemon aioli.'],
                    ['name' => 'Burrata & Heirloom Tomato', 'price' => 18.00, 'tags' => ['vegetarian', 'gluten_free'], 'featured' => true, 'desc' => 'Fresh burrata, heirloom tomatoes, basil oil, aged balsamic.'],
                    ['name' => 'Shrimp Cocktail', 'price' => 19.00, 'tags' => ['gluten_free'], 'featured' => false, 'desc' => 'Chilled jumbo shrimp, house cocktail sauce, fresh horseradish.'],
                    ['name' => 'Wagyu Beef Sliders', 'price' => 22.00, 'tags' => [], 'featured' => true, 'desc' => 'Three mini wagyu burgers on brioche, white cheddar, truffle aioli.'],
                    ['name' => 'Truffle Arancini', 'price' => 15.00, 'tags' => ['vegetarian'], 'featured' => false, 'desc' => 'Crispy risotto balls filled with black truffle and fontina.'],
                ],
            ],
            [
                'name' => 'Soups & Salads',
                'sort_order' => 2,
                'items' => [
                    ['name' => 'French Onion Soup', 'price' => 14.00, 'tags' => ['vegetarian'], 'featured' => false, 'desc' => 'Caramelized onion broth, gruyère crouton, baked tableside.'],
                    ['name' => 'Lobster Bisque', 'price' => 17.00, 'tags' => ['gluten_free'], 'featured' => true, 'desc' => 'Rich cream bisque, Maine lobster, cognac, chive oil.'],
                    ['name' => 'Caesar Salad', 'price' => 15.00, 'tags' => [], 'featured' => false, 'desc' => 'Romaine, house Caesar dressing, shaved parmesan, house croutons.'],
                    ['name' => 'Roasted Beet Salad', 'price' => 16.00, 'tags' => ['vegetarian', 'gluten_free'], 'featured' => false, 'desc' => 'Golden and red beets, goat cheese, candied walnuts, citrus vinaigrette.'],
                    ['name' => 'Wedge Salad', 'price' => 14.00, 'tags' => ['gluten_free'], 'featured' => false, 'desc' => 'Iceberg wedge, Maytag blue cheese, bacon, cherry tomatoes, red onion.'],
                ],
            ],
            [
                'name' => 'Entrees',
                'sort_order' => 3,
                'items' => [
                    ['name' => 'Pan-Seared Salmon', 'price' => 38.00, 'tags' => ['gluten_free'], 'featured' => true, 'desc' => 'Atlantic salmon, lemon beurre blanc, charred asparagus, fingerling potatoes.'],
                    ['name' => 'Grilled Filet Mignon', 'price' => 58.00, 'tags' => ['gluten_free'], 'featured' => true, 'desc' => '8oz USDA Prime filet, roasted garlic butter, seasonal vegetables.'],
                    ['name' => 'Rack of Lamb', 'price' => 52.00, 'tags' => ['gluten_free'], 'featured' => false, 'desc' => 'New Zealand lamb, herb crust, rosemary jus, roasted root vegetables.'],
                    ['name' => 'Truffle Mushroom Risotto', 'price' => 32.00, 'tags' => ['vegetarian', 'gluten_free'], 'featured' => false, 'desc' => 'Arborio rice, wild mushroom medley, black truffle, aged parmesan.'],
                    ['name' => 'Chicken Marsala', 'price' => 34.00, 'tags' => [], 'featured' => false, 'desc' => 'Pan-roasted airline breast, marsala wine sauce, wild mushrooms, whipped potatoes.'],
                    ['name' => 'Lobster Ravioli', 'price' => 42.00, 'tags' => [], 'featured' => true, 'desc' => 'Hand-made pasta, Maine lobster filling, champagne cream, caviar.'],
                ],
            ],
            [
                'name' => 'Desserts',
                'sort_order' => 4,
                'items' => [
                    ['name' => 'Chocolate Lava Cake', 'price' => 13.00, 'tags' => ['contains_nuts'], 'featured' => true, 'desc' => 'Warm valrhona chocolate cake, vanilla bean ice cream, raspberry coulis.'],
                    ['name' => 'Crème Brûlée', 'price' => 11.00, 'tags' => ['gluten_free'], 'featured' => false, 'desc' => 'Classic vanilla custard, caramelized sugar crust, fresh berries.'],
                    ['name' => 'New York Cheesecake', 'price' => 12.00, 'tags' => [], 'featured' => false, 'desc' => 'Dense New York-style, graham crust, seasonal berry compote.'],
                    ['name' => 'Seasonal Sorbet', 'price' => 9.00, 'tags' => ['vegan', 'gluten_free'], 'featured' => false, 'desc' => 'Three scoops of house-made sorbet — ask your server for today\'s flavors.'],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = MenuCategory::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'sort_order' => $categoryData['sort_order'],
                'active' => true,
            ]);

            foreach ($categoryData['items'] as $itemData) {
                MenuItem::create([
                    'menu_category_id' => $category->id,
                    'name' => $itemData['name'],
                    'slug' => Str::slug($itemData['name']),
                    'description' => $itemData['desc'],
                    'price' => $itemData['price'],
                    'dietary_tags' => empty($itemData['tags']) ? null : $itemData['tags'],
                    'featured' => $itemData['featured'],
                    'available_always' => true,
                    'sort_order' => 0,
                    'active' => true,
                ]);
            }
        }

        // Modifier groups attached to steaks/entrees
        $steakTemps = ModifierGroup::create([
            'name' => 'Cooking Temperature',
            'required' => true,
            'min_selections' => 1,
            'max_selections' => 1,
        ]);

        foreach (['Rare', 'Medium Rare', 'Medium', 'Medium Well', 'Well Done'] as $i => $temp) {
            ModifierOption::create([
                'modifier_group_id' => $steakTemps->id,
                'name' => $temp,
                'price_adjustment' => 0.00,
                'sort_order' => $i,
            ]);
        }

        $sauces = ModifierGroup::create([
            'name' => 'Sauce Choice',
            'required' => false,
            'min_selections' => 0,
            'max_selections' => 1,
        ]);

        foreach (['Béarnaise', 'Peppercorn Sauce', 'Chimichurri', 'Garlic Herb Butter', 'Red Wine Reduction'] as $i => $sauce) {
            ModifierOption::create([
                'modifier_group_id' => $sauces->id,
                'name' => $sauce,
                'price_adjustment' => 0.00,
                'sort_order' => $i,
            ]);
        }

        $addOns = ModifierGroup::create([
            'name' => 'Add-ons',
            'required' => false,
            'min_selections' => 0,
            'max_selections' => 5,
        ]);

        $addOnItems = [
            'Sautéed Mushrooms' => 3.00,
            'Caramelized Onions' => 2.00,
            'Grilled Shrimp' => 8.00,
            'Truffle Butter' => 4.00,
            'Foie Gras' => 14.00,
        ];

        $sortOrder = 0;
        foreach ($addOnItems as $addOn => $price) {
            ModifierOption::create([
                'modifier_group_id' => $addOns->id,
                'name' => $addOn,
                'price_adjustment' => $price,
                'sort_order' => $sortOrder++,
            ]);
        }

        // Attach cooking temp + sauce + add-ons to the filet
        $filet = MenuItem::where('slug', 'grilled-filet-mignon')->first();
        if ($filet) {
            $filet->modifierGroups()->attach([$steakTemps->id, $sauces->id, $addOns->id]);
        }

        // Attach cooking temp to rack of lamb
        $lamb = MenuItem::where('slug', 'rack-of-lamb')->first();
        if ($lamb) {
            $lamb->modifierGroups()->attach([$steakTemps->id, $sauces->id]);
        }
    }
}
