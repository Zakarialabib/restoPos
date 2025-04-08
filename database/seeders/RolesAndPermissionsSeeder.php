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

        // Create roles
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        // You could create a 'user' role here too if needed for the demo user
        // Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // Example of creating a permission (optional for now)
        // Permission::firstOrCreate(['name' => 'manage settings', 'guard_name' => 'web']);

        // Example of assigning a permission to a role (optional for now)
        // $adminRole = Role::findByName('admin');
        // if ($adminRole) {
        //     $adminRole->givePermissionTo('manage settings');
        // }
    }
}
