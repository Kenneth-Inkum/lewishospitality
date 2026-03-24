<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            ['caption' => 'Signature dish presentation', 'collection' => 'food', 'url' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=800&q=80'],
            ['caption' => 'Fresh seasonal ingredients', 'collection' => 'food', 'url' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=800&q=80'],
            ['caption' => 'Artisan bread selection', 'collection' => 'food', 'url' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800&q=80'],
            ['caption' => 'Handcrafted desserts', 'collection' => 'food', 'url' => 'https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?w=800&q=80'],
            
            ['caption' => 'Main dining room', 'collection' => 'ambience', 'url' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&q=80'],
            ['caption' => 'Private dining area', 'collection' => 'ambience', 'url' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=800&q=80'],
            ['caption' => 'Bar and lounge', 'collection' => 'ambience', 'url' => 'https://images.unsplash.com/photo-1572116469696-31de0f17cc34?w=800&q=80'],
            ['caption' => 'Outdoor patio seating', 'collection' => 'ambience', 'url' => 'https://images.unsplash.com/photo-1552566626-52f8b828add9?w=800&q=80'],
            
            ['caption' => 'Wine pairing dinner', 'collection' => 'events', 'url' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&q=80'],
            ['caption' => 'Live jazz night', 'collection' => 'events', 'url' => 'https://images.unsplash.com/photo-1511192336575-5a79af67a629?w=800&q=80'],
            ['caption' => 'Chef demonstration', 'collection' => 'events', 'url' => 'https://images.unsplash.com/photo-1556910103-1c02745aae4d?w=800&q=80'],
            
            ['caption' => 'Chef at work', 'collection' => 'behind_the_scenes', 'url' => 'https://images.unsplash.com/photo-1577219491135-ce391730fb2c?w=800&q=80'],
            ['caption' => 'Kitchen prep', 'collection' => 'behind_the_scenes', 'url' => 'https://images.unsplash.com/photo-1556910096-6f5e72db6803?w=800&q=80'],
            ['caption' => 'Plating perfection', 'collection' => 'behind_the_scenes', 'url' => 'https://images.unsplash.com/photo-1600565193348-f74bd3c7ccdf?w=800&q=80'],
        ];

        foreach ($images as $imageData) {
            $galleryImage = GalleryImage::create([
                'caption' => $imageData['caption'],
                'active' => true,
            ]);

            try {
                $galleryImage->addMediaFromUrl($imageData['url'])
                    ->toMediaCollection($imageData['collection']);
            } catch (\Exception $e) {
                // Skip if image fails to download
            }
        }
    }
}
