<?php

namespace Database\Seeders;

use App\Models\HappyHour;
use App\Models\Location;
use App\Models\LocationHour;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        // Primary location — Penn Quarter, DC
        $primary = Location::create([
            'name' => 'Lewis & Co. Kitchen — Penn Quarter',
            'address' => '701 Pennsylvania Ave NW',
            'city' => 'Washington',
            'state' => 'DC',
            'zip' => '20004',
            'phone' => '(202) 555-0180',
            'email' => 'pennquarter@lewishospitality.com',
            'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3105.0!2d-77.0229!3d38.8933',
            'latitude' => 38.8933,
            'longitude' => -77.0229,
            'is_primary' => true,
            'active' => true,
        ]);

        $this->seedHours($primary, [
            0 => ['11:00', '21:00'],  // Sun
            1 => ['11:00', '22:00'],  // Mon
            2 => ['11:00', '22:00'],  // Tue
            3 => ['11:00', '22:00'],  // Wed
            4 => ['11:00', '23:00'],  // Thu
            5 => ['11:00', '23:00'],  // Fri
            6 => ['10:00', '23:00'],  // Sat
        ]);

        $this->seedHappyHours($primary);

        // Second location — Bethesda, MD
        $bethesda = Location::create([
            'name' => 'Lewis & Co. Kitchen — Bethesda',
            'address' => '4922 Hampden Ln',
            'city' => 'Bethesda',
            'state' => 'MD',
            'zip' => '20814',
            'phone' => '(301) 555-0247',
            'email' => 'bethesda@lewishospitality.com',
            'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3103.0!2d-77.0948!3d38.9807',
            'latitude' => 38.9807,
            'longitude' => -77.0948,
            'is_primary' => false,
            'active' => true,
        ]);

        $this->seedHours($bethesda, [
            0 => null,                // Sun — closed
            1 => ['12:00', '21:00'],  // Mon
            2 => ['12:00', '21:00'],  // Tue
            3 => ['12:00', '22:00'],  // Wed
            4 => ['12:00', '22:00'],  // Thu
            5 => ['12:00', '23:00'],  // Fri
            6 => ['11:00', '23:00'],  // Sat
        ]);

        $this->seedHappyHours($bethesda);
    }

    /**
     * @param  array<int, array{0: string, 1: string}|null>  $schedule
     */
    private function seedHours(Location $location, array $schedule): void
    {
        foreach ($schedule as $day => $hours) {
            LocationHour::create([
                'location_id' => $location->id,
                'day_of_week' => $day,
                'opens_at' => $hours ? $hours[0].':00' : null,
                'closes_at' => $hours ? $hours[1].':00' : null,
                'closed' => $hours === null,
            ]);
        }
    }

    private function seedHappyHours(Location $location): void
    {
        $weekdays = [1, 2, 3, 4, 5]; // Mon–Fri

        foreach ($weekdays as $day) {
            HappyHour::create([
                'location_id' => $location->id,
                'day_of_week' => $day,
                'starts_at' => '16:00:00',
                'ends_at' => '19:00:00',
                'label' => 'Happy Hour',
                'active' => true,
            ]);
        }
    }
}
