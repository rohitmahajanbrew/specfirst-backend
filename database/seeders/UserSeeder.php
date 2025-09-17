<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'full_name' => 'Admin User',
            'email' => 'admin@specfirst.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'phone_number' => '+1234567890',
            'company_name' => 'SpecFirst',
            'role' => 'admin',
            'onboarding_completed' => true,
            'preferred_project_types' => ['web-development', 'mobile-app', 'ai-ml', 'ecommerce'],
            'completed_step' => 'onboarding_complete',
            'next_step' => 'project_creation',
            'last_login_at' => now(),
            'device_type' => 'web',
        ]);

        // Assign admin role
        $admin->assignRole('admin');

        // Create a test manager user (using admin role since manager is not in enum)
        $manager = User::create([
            'name' => 'Manager User',
            'full_name' => 'Manager User',
            'email' => 'manager@specfirst.com',
            'password' => Hash::make('manager123'),
            'email_verified_at' => now(),
            'phone_number' => '+1234567891',
            'company_name' => 'SpecFirst',
            'role' => 'admin',
            'onboarding_completed' => true,
            'preferred_project_types' => ['web-development', 'mobile-app'],
            'completed_step' => 'onboarding_complete',
            'next_step' => 'project_creation',
            'last_login_at' => now(),
            'device_type' => 'web',
        ]);

        // Assign manager role (using Spatie roles, not the enum role column)
        $manager->assignRole('manager');

        // Create a test regular user
        $user = User::create([
            'name' => 'Regular User',
            'full_name' => 'Regular User',
            'email' => 'user@specfirst.com',
            'password' => Hash::make('user123'),
            'email_verified_at' => now(),
            'phone_number' => '+1234567892',
            'company_name' => 'Test Company',
            'role' => 'user',
            'onboarding_completed' => false,
            'preferred_project_types' => ['web-development'],
            'completed_step' => 'profile_setup',
            'next_step' => 'project_type_selection',
            'last_login_at' => now(),
            'device_type' => 'web',
        ]);

        // Assign user role
        $user->assignRole('user');

        // Create a test vendor user
        $vendor = User::create([
            'name' => 'Vendor User',
            'full_name' => 'Vendor User',
            'email' => 'vendor@specfirst.com',
            'password' => Hash::make('vendor123'),
            'email_verified_at' => now(),
            'phone_number' => '+1234567893',
            'company_name' => 'Vendor Solutions Inc',
            'role' => 'vendor',
            'onboarding_completed' => true,
            'preferred_project_types' => ['ai-ml', 'data-analytics'],
            'completed_step' => 'vendor_verification',
            'next_step' => 'portfolio_setup',
            'last_login_at' => now(),
            'device_type' => 'web',
        ]);

        // Assign vendor role
        $vendor->assignRole('vendor');

        $this->command->info('Users created successfully:');
        $this->command->info('- Admin: admin@specfirst.com');
        $this->command->info('- Manager: manager@specfirst.com');
        $this->command->info('- User: user@specfirst.com');
        $this->command->info('- Vendor: vendor@specfirst.com');
    }
}