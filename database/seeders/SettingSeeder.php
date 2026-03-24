<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // About page settings
        Setting::set('about.origin_story', '<p>Founded in 2004, Lewis Hospitality Group began with a simple vision: to create exceptional dining experiences that bring people together. What started as a single restaurant in Penn Quarter has grown into a beloved collection of establishments across the Washington, DC metropolitan area.</p><p>Our founder, inspired by travels through Europe and a passion for locally-sourced ingredients, set out to create spaces where food, atmosphere, and service come together seamlessly. Today, we continue that tradition, serving thousands of guests each week while maintaining the personal touch that made us successful.</p>');

        Setting::set('about.philosophy', '<p>We believe great food starts with great ingredients. That\'s why we partner with local farms and artisans to source the freshest seasonal produce, sustainable seafood, and humanely-raised meats.</p><p>But it\'s not just about the food. We\'re committed to creating warm, welcoming spaces where memories are made—whether you\'re celebrating a special occasion, enjoying a casual dinner with friends, or grabbing a quick lunch.</p><p>Our team members are the heart of everything we do. We invest in their growth, celebrate their creativity, and empower them to deliver exceptional hospitality every day.</p>');

        Setting::set('about.show_values', true);
        Setting::set('about.show_awards', true);
        Setting::set('about.show_team', false);

        Setting::set('about.awards_list', [
            [
                'title' => 'Best New Restaurant',
                'year' => '2005',
                'description' => 'Washington Post Dining Guide',
            ],
            [
                'title' => 'Michelin Bib Gourmand',
                'year' => '2020-2024',
                'description' => 'Recognized for exceptional value',
            ],
            [
                'title' => 'Top 100 Restaurants',
                'year' => '2023',
                'description' => 'Washingtonian Magazine',
            ],
        ]);
    }
}
