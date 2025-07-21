<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = \App\Models\Admin::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'full_name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('admin');

        // // Create manager user
        // $manager = User::updateOrCreate(
        //     ['email' => 'manager@example.com'],
        //     [
        //         'name' => 'Manager User',
        //         'password' => Hash::make('password'),
        //     ]
        // );
        // $manager->assignRole('manager');

        // // Create staff user
        // $staff = User::updateOrCreate(
        //     ['email' => 'staff@example.com'],
        //     [
        //         'name' => 'Staff User',
        //         'password' => Hash::make('password'),
        //     ]
        // );
        // $staff->assignRole('staff');

        // Create demo customer
        $demoUser = User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password'),
            ]
        );
        $demoUser->assignRole('customer');
    }
}
