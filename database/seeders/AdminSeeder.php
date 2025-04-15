<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create super admin
        $superAdmin = Admin::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'full_name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        // Create manager
        $manager = Admin::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'full_name' => 'Manager User',
                'password' => Hash::make('password'),
            ]
        );

        // Create staff
        $staff = Admin::updateOrCreate(
            ['email' => 'staff@example.com'],
            [
                'full_name' => 'Staff User',
                'password' => Hash::make('password'),
            ]
        );

        // Assign roles with correct guard
        $superAdmin->assignRole('admin');
        $manager->assignRole('manager');
        $staff->assignRole('staff');
    }
} 