<?php

namespace Database\Factories;

use App\Models\SeoSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SeoSetting>
 */
class SeoSettingFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $page = fake()->unique()->randomElement(['home', 'menu', 'events', 'gallery', 'about', 'contact']);
        $title = ucfirst($page).' | Lewis Hospitality';

        return [
            'page' => $page,
            'meta_title' => $title,
            'meta_description' => fake()->sentence(12),
            'og_title' => $title,
            'og_description' => fake()->sentence(10),
            'og_image' => 'https://lewishospitality.com/images/og/'.$page.'.jpg',
        ];
    }
}
