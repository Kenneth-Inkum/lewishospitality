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
                    ['name' => 'Crispy Calamari', 'price' => 16.00, 'tags' => ['contains_nuts'], 'featured' => false, 'desc' => 'Lightly breaded, flash-fried, served with marinara and lemon aioli.', 'image' => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=800&q=80'],
                    ['name' => 'Burrata & Heirloom Tomato', 'price' => 18.00, 'tags' => ['vegetarian', 'gluten_free'], 'featured' => true, 'desc' => 'Fresh burrata, heirloom tomatoes, basil oil, aged balsamic.', 'image' => 'https://images.unsplash.com/photo-1608897013039-887f21d8c804?w=800&q=80'],
                    ['name' => 'Shrimp Cocktail', 'price' => 19.00, 'tags' => ['gluten_free'], 'featured' => false, 'desc' => 'Chilled jumbo shrimp, house cocktail sauce, fresh horseradish.', 'image' => 'https://images.unsplash.com/photo-1565680018434-b513d5e5fd47?w=800&q=80'],
                    ['name' => 'Wagyu Beef Sliders', 'price' => 22.00, 'tags' => [], 'featured' => true, 'desc' => 'Three mini wagyu burgers on brioche, white cheddar, truffle aioli.', 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=800&q=80'],
                    ['name' => 'Truffle Arancini', 'price' => 15.00, 'tags' => ['vegetarian'], 'featured' => false, 'desc' => 'Crispy risotto balls filled with black truffle and fontina.', 'image' => 'https://images.unsplash.com/photo-1633504581786-316c8002b1b2?w=800&q=80'],
                ],
            ],
            [
                'name' => 'Soups & Salads',
                'sort_order' => 2,
                'items' => [
                    ['name' => 'French Onion Soup', 'price' => 14.00, 'tags' => ['vegetarian'], 'featured' => false, 'desc' => 'Caramelized onion broth, gruyère crouton, baked tableside.', 'image' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=800&q=80'],
                    ['name' => 'Lobster Bisque', 'price' => 17.00, 'tags' => ['gluten_free'], 'featured' => true, 'desc' => 'Rich cream bisque, Maine lobster, cognac, chive oil.', 'image' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=800&q=80'],
                    ['name' => 'Caesar Salad', 'price' => 15.00, 'tags' => [], 'featured' => false, 'desc' => 'Romaine, house Caesar dressing, shaved parmesan, house croutons.', 'image' => 'https://images.unsplash.com/photo-1546793665-c74683f339c1?w=800&q=80'],
                    ['name' => 'Roasted Beet Salad', 'price' => 16.00, 'tags' => ['vegetarian', 'gluten_free'], 'featured' => false, 'desc' => 'Golden and red beets, goat cheese, candied walnuts, citrus vinaigrette.', 'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800&q=80'],
                    ['name' => 'Wedge Salad', 'price' => 14.00, 'tags' => ['gluten_free'], 'featured' => false, 'desc' => 'Iceberg wedge, Maytag blue cheese, bacon, cherry tomatoes, red onion.', 'image' => 'https://images.unsplash.com/photo-1505253716362-afaea1d3d1af?w=800&q=80'],
                ],
            ],
            [
                'name' => 'Entrees',
                'sort_order' => 3,
                'items' => [
                    ['name' => 'Pan-Seared Salmon', 'price' => 38.00, 'tags' => ['gluten_free'], 'featured' => true, 'desc' => 'Atlantic salmon, lemon beurre blanc, charred asparagus, fingerling potatoes.', 'image' => 'https://images.unsplash.com/photo-1485921325833-c519f76c4927?w=800&q=80'],
                    ['name' => 'Grilled Filet Mignon', 'price' => 58.00, 'tags' => ['gluten_free'], 'featured' => true, 'desc' => '8oz USDA Prime filet, roasted garlic butter, seasonal vegetables.', 'image' => 'https://images.unsplash.com/photo-1558030006-450675393462?w=800&q=80'],
                    ['name' => 'Rack of Lamb', 'price' => 52.00, 'tags' => ['gluten_free'], 'featured' => false, 'desc' => 'New Zealand lamb, herb crust, rosemary jus, roasted root vegetables.', 'image' => 'https://images.unsplash.com/photo-1529692236671-f1f6cf9683ba?w=800&q=80'],
                    ['name' => 'Truffle Mushroom Risotto', 'price' => 32.00, 'tags' => ['vegetarian', 'gluten_free'], 'featured' => false, 'desc' => 'Arborio rice, wild mushroom medley, black truffle, aged parmesan.', 'image' => 'https://images.unsplash.com/photo-1476124369491-c4ca3d6c0b7e?w=800&q=80'],
                    ['name' => 'Chicken Marsala', 'price' => 34.00, 'tags' => [], 'featured' => false, 'desc' => 'Pan-roasted airline breast, marsala wine sauce, wild mushrooms, whipped potatoes.', 'image' => 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=800&q=80'],
                    ['name' => 'Lobster Ravioli', 'price' => 42.00, 'tags' => [], 'featured' => true, 'desc' => 'Hand-made pasta, Maine lobster filling, champagne cream, caviar.', 'image' => 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=800&q=80'],
                ],
            ],
            [
                'name' => 'Desserts',
                'sort_order' => 4,
                'items' => [
                    ['name' => 'Chocolate Lava Cake', 'price' => 13.00, 'tags' => ['contains_nuts'], 'featured' => true, 'desc' => 'Warm valrhona chocolate cake, vanilla bean ice cream, raspberry coulis.', 'image' => 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51?w=800&q=80'],
                    ['name' => 'Crème Brûlée', 'price' => 11.00, 'tags' => ['gluten_free'], 'featured' => false, 'desc' => 'Classic vanilla custard, caramelized sugar crust, fresh berries.', 'image' => 'https://images.unsplash.com/photo-1470124182917-cc6e71b22ecc?w=800&q=80'],
                    ['name' => 'New York Cheesecake', 'price' => 12.00, 'tags' => [], 'featured' => false, 'desc' => 'Dense New York-style, graham crust, seasonal berry compote.', 'image' => 'https://images.unsplash.com/photo-1533134486753-c833f0ed4866?w=800&q=80'],
                    ['name' => 'Seasonal Sorbet', 'price' => 9.00, 'tags' => ['vegan', 'gluten_free'], 'featured' => false, 'desc' => 'Three scoops of house-made sorbet — ask your server for today\'s flavors.', 'image' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=800&q=80'],
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
                $item = MenuItem::create([
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

                // Add images to all items
                if (isset($itemData['image'])) {
                    try {
                        $item->addMediaFromUrl($itemData['image'])
                            ->toMediaCollection('images');
                    } catch (\Exception $e) {
                        // Skip if image fails to download
                    }
                }
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
