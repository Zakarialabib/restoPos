<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard
            'access_dashboard',
            'view_pos',

            // Products
            'view_products',
            'create_products',
            'edit_products',
            'delete_products',
            'manage_composable_products',

            // Categories
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',

            // Inventory
            'view_inventory',
            'manage_ingredients',
            'manage_recipes',
            'manage_waste',

            // Orders
            'view_orders',
            'create_orders',
            'edit_orders',
            'delete_orders',
            'manage_kitchen_orders',

            // Finance
            'manage_cash_register',
            'view_purchases',
            'create_purchases',
            'edit_purchases',
            'delete_purchases',
            'manage_expenses',

            // Suppliers
            'view_suppliers',
            'create_suppliers',
            'edit_suppliers',
            'delete_suppliers',

            // Kitchen
            'access_kitchen',
            'view_kitchen_display',
            'view_kitchen_dashboard',

            // Settings
            'manage_languages',
            'manage_settings',
            'manage_menu',

            // Customer permissions
            'place_orders',
            'view_own_orders',
            'edit_own_orders',
            'cancel_own_orders',
        ];

        // Create permissions for both guards
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }

        // Create admin roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::where('guard_name', 'web')->get());

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'admin']);
        $admin->givePermissionTo(Permission::where('guard_name', 'admin')->get());

        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'admin']);
        $manager->givePermissionTo([
            'access_dashboard',
            'view_pos',
            'view_products',
            'create_products',
            'edit_products',
            'view_categories',
            'view_inventory',
            'manage_ingredients',
            'view_orders',
            'create_orders',
            'edit_orders',
            'manage_cash_register',
            'view_purchases',
            'create_purchases',
            'edit_purchases',
            'manage_expenses',
            'view_suppliers',
            'create_suppliers',
            'edit_suppliers',
            'access_kitchen',
            'view_kitchen_display',
            'view_kitchen_dashboard',
            'manage_languages',
            'manage_settings',
            'manage_menu',
        ]);

        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'admin']);
        $staff->givePermissionTo([
            'access_dashboard',
            'view_pos',
            'view_products',
            'view_categories',
            'view_inventory',
            'view_orders',
            'create_orders',
            'edit_orders',
            'access_kitchen',
            'view_kitchen_display',
        ]);

        // Create customer role and assign permissions
        $customer = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        $customer->givePermissionTo([
            'place_orders',
            'view_own_orders',
            'edit_own_orders',
            'cancel_own_orders',
        ]);

    }
}
