<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
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
            // Menu Management
            'view menu',
            'create menu',
            'edit menu',
            'delete menu',
            
            // Order Management
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
            
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Role & Permission Management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            
            // Dashboard & Reports
            'view dashboard',
            'view reports',
            
            // Settings
            'manage settings',
            
            // Customer specific permissions
            'place orders',
            'view own orders',
            'edit own orders',
            'cancel own orders',
        ];

        // Create permissions for both guards
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
            Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }

        // Create admin roles and assign permissions
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'admin']);
        $admin->givePermissionTo(Permission::where('guard_name', 'admin')->get());

        $manager = Role::create(['name' => 'manager', 'guard_name' => 'admin']);
        $manager->givePermissionTo([
            'view menu',
            'create menu',
            'edit menu',
            'view orders',
            'create orders',
            'edit orders',
            'view users',
            'view dashboard',
            'view reports',
        ]);

        $staff = Role::create(['name' => 'staff', 'guard_name' => 'admin']);
        $staff->givePermissionTo([
            'view menu',
            'view orders',
            'create orders',
            'edit orders',
            'view dashboard',
        ]);

        // Create customer role and assign permissions
        $customer = Role::create(['name' => 'customer', 'guard_name' => 'web']);
        $customer->givePermissionTo([
            'view menu',
            'place orders',
            'view own orders',
            'edit own orders',
            'cancel own orders',
        ]);
    }
}
