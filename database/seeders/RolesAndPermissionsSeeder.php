<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- Permissions ---
        $permissions = [
            // Locations
            'view locations', 'create locations', 'edit locations', 'delete locations',

            // Menu
            'view menu', 'create menu items', 'edit menu items', 'delete menu items',
            'manage menu categories', 'manage modifier groups',

            // Events
            'view events', 'create events', 'edit events', 'delete events', 'publish events',

            // Promotions
            'view promotions', 'create promotions', 'edit promotions', 'delete promotions',

            // Reservations
            'view reservations', 'create reservations', 'edit reservations', 'cancel reservations',
            'seat guests', 'manage tables',

            // Guests / CRM
            'view guests', 'edit guests', 'manage loyalty',

            // Contact & Inbox
            'view contact submissions', 'manage contact submissions',

            // Reports & Analytics
            'view reports',

            // Settings & Admin
            'manage settings', 'manage seo', 'view audit logs',

            // User Management
            'view users', 'create users', 'edit users', 'delete users', 'assign roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // --- Roles ---

        /** Super Admin — full access, bypasses all gates */
        Role::firstOrCreate(['name' => 'super_admin']);

        /** Area Manager — oversees multiple locations, no user/role management */
        $areaManager = Role::firstOrCreate(['name' => 'area_manager']);
        $areaManager->syncPermissions([
            'view locations', 'edit locations',
            'view menu', 'create menu items', 'edit menu items', 'delete menu items',
            'manage menu categories', 'manage modifier groups',
            'view events', 'create events', 'edit events', 'delete events', 'publish events',
            'view promotions', 'create promotions', 'edit promotions', 'delete promotions',
            'view reservations', 'create reservations', 'edit reservations', 'cancel reservations',
            'seat guests', 'manage tables',
            'view guests', 'edit guests', 'manage loyalty',
            'view contact submissions', 'manage contact submissions',
            'view reports',
            'manage settings', 'manage seo', 'view audit logs',
        ]);

        /** Restaurant Manager — single-location operator */
        $restaurantManager = Role::firstOrCreate(['name' => 'restaurant_manager']);
        $restaurantManager->syncPermissions([
            'view locations',
            'view menu', 'create menu items', 'edit menu items',
            'manage menu categories',
            'view events', 'create events', 'edit events', 'publish events',
            'view promotions', 'create promotions', 'edit promotions',
            'view reservations', 'create reservations', 'edit reservations', 'cancel reservations',
            'seat guests', 'manage tables',
            'view guests', 'edit guests',
            'view contact submissions', 'manage contact submissions',
            'view reports',
        ]);

        /** Content Editor — manages public-facing content only */
        $contentEditor = Role::firstOrCreate(['name' => 'content_editor']);
        $contentEditor->syncPermissions([
            'view menu', 'edit menu items',
            'view events', 'create events', 'edit events',
            'view promotions', 'create promotions', 'edit promotions',
            'manage seo',
        ]);

        /** Server — front-of-house access */
        $server = Role::firstOrCreate(['name' => 'server']);
        $server->syncPermissions([
            'view menu',
            'view reservations', 'create reservations', 'edit reservations',
            'seat guests',
            'view guests',
        ]);

        /** Kitchen Staff — read-only menu + order visibility */
        $kitchenStaff = Role::firstOrCreate(['name' => 'kitchen_staff']);
        $kitchenStaff->syncPermissions([
            'view menu',
        ]);

        /** Loyalty Member — guest-facing portal (Phase 3) */
        Role::firstOrCreate(['name' => 'loyalty_member']);
    }
}
