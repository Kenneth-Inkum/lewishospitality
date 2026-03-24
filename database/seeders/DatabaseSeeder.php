<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            LocationSeeder::class,
            MenuSeeder::class,
            EventSeeder::class,
        ]);

        // Super admin user
        $admin = User::factory()->create([
            'name' => 'Lewis Admin',
            'email' => 'admin@lewishospitality.com',
        ]);
        $admin->assignRole('super_admin');

        // Dev test user
        $dev = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $dev->assignRole('restaurant_manager');
    }
}
