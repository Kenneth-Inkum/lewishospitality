<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Location;
use App\Models\Promotion;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $primary = Location::where('is_primary', true)->first();

        // Upcoming event with RSVP
        $event1 = Event::create([
            'location_id' => $primary?->id,
            'title' => 'Chef\'s Table Wine Pairing Dinner',
            'slug' => 'chefs-table-wine-pairing-dinner',
            'description' => '<p>Join us for an exclusive five-course tasting menu curated by Executive Chef Marcus Webb, paired with selections from our award-winning wine cellar.</p><p>Each course is thoughtfully matched with a wine that elevates both the food and the glass. Guests will enjoy a behind-the-scenes look at our kitchen and a direct conversation with the chef throughout the evening.</p><p>Seating is strictly limited to 20 guests. Dietary accommodations available upon request at time of RSVP.</p>',
            'starts_at' => now()->addDays(18)->setTime(18, 30),
            'ends_at' => now()->addDays(18)->setTime(22, 0),
            'cta_type' => 'rsvp',
            'cta_label' => 'Reserve Your Seat',
            'cta_url' => null,
            'rsvp_enabled' => true,
            'published' => true,
        ]);

        try {
            $event1->addMediaFromUrl('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&q=80')
                ->toMediaCollection('images');
        } catch (\Exception $e) {
            // Skip if image fails
        }

        // Upcoming event with external ticket link
        $event2 = Event::create([
            'location_id' => $primary?->id,
            'title' => 'Jazz & Craft Cocktails Night',
            'slug' => 'jazz-craft-cocktails-night',
            'description' => "<p>Every last Friday of the month, the Lewis & Co. bar transforms into a live jazz lounge. Enjoy expertly crafted cocktails alongside performances from some of DC's finest jazz musicians.</p><p>No cover charge. Walk-ins welcome. Bar menu available all evening.</p>",
            'starts_at' => now()->addDays(10)->setTime(20, 0),
            'ends_at' => now()->addDays(10)->setTime(23, 30),
            'cta_type' => 'external_link',
            'cta_label' => 'Learn More',
            'cta_url' => 'https://lewishospitality.com/jazz-night',
            'rsvp_enabled' => false,
            'published' => true,
        ]);

        try {
            $event2->addMediaFromUrl('https://images.unsplash.com/photo-1511192336575-5a79af67a629?w=800&q=80')
                ->toMediaCollection('images');
        } catch (\Exception $e) {
            // Skip if image fails
        }

        // A third event further out
        $event3 = Event::create([
            'location_id' => null,
            'title' => 'Farm-to-Table Harvest Brunch',
            'slug' => 'farm-to-table-harvest-brunch',
            'description' => '<p>A special Sunday brunch celebrating the fall harvest. All ingredients are sourced from local Virginia and Maryland farms within 50 miles of our kitchen.</p><p>Menu features seasonal produce, pastured eggs, heritage pork, and house-baked breads. Live acoustic music from 11am.</p>',
            'starts_at' => now()->addDays(35)->setTime(11, 0),
            'ends_at' => now()->addDays(35)->setTime(15, 0),
            'cta_type' => 'rsvp',
            'cta_label' => 'Reserve a Table',
            'cta_url' => null,
            'rsvp_enabled' => true,
            'published' => true,
        ]);

        try {
            $event3->addMediaFromUrl('https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=800&q=80')
                ->toMediaCollection('images');
        } catch (\Exception $e) {
            // Skip if image fails
        }

        // Active promotion
        Promotion::create([
            'location_id' => $primary?->id,
            'title' => 'Happy Hour — 20% Off Cocktails',
            'description' => 'Every Monday through Friday from 4–7pm, enjoy 20% off our full cocktail menu. Available at the bar and patio seating only.',
            'type' => 'percentage',
            'discount_value' => 20,
            'free_item_id' => null,
            'applicable_to' => 'all',
            'starts_at' => now()->startOfMonth()->format('Y-m-d'),
            'ends_at' => now()->endOfYear()->format('Y-m-d'),
            'show_on_homepage' => true,
            'active' => true,
        ]);

        // A second promotion for loyal members
        Promotion::create([
            'location_id' => null,
            'title' => 'Loyalty Member — Complimentary Dessert',
            'description' => 'Lewis Rewards members receive one complimentary dessert per visit on their birthday month. Show your membership card to redeem.',
            'type' => 'free_item',
            'discount_value' => null,
            'free_item_id' => null,
            'applicable_to' => 'loyalty_members',
            'starts_at' => now()->startOfYear()->format('Y-m-d'),
            'ends_at' => now()->endOfYear()->format('Y-m-d'),
            'show_on_homepage' => false,
            'active' => true,
        ]);
    }
}
