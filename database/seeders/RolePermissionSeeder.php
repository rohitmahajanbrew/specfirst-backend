<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Project permissions
            'view-projects',
            'create-projects',
            'edit-projects',
            'delete-projects',
            'manage-projects',
            
            // User permissions
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'manage-users',
            
            // Vendor permissions
            'view-vendors',
            'create-vendors',
            'edit-vendors',
            'delete-vendors',
            'manage-vendors',
            
            // Interview permissions
            'view-interviews',
            'create-interviews',
            'edit-interviews',
            'delete-interviews',
            'manage-interviews',
            
            // Analytics permissions
            'view-analytics',
            'export-analytics',
            'manage-analytics',
            
            // System permissions
            'view-system-settings',
            'manage-system-settings',
            'view-logs',
            'manage-logs',
            
            // Admin permissions
            'access-admin-panel',
            'manage-roles',
            'manage-permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $managerRole = Role::create(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'view-projects',
            'create-projects',
            'edit-projects',
            'view-users',
            'view-vendors',
            'create-vendors',
            'edit-vendors',
            'view-interviews',
            'create-interviews',
            'edit-interviews',
            'view-analytics',
            'export-analytics',
            'access-admin-panel',
        ]);

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view-projects',
            'create-projects',
            'edit-projects',
            'view-interviews',
            'create-interviews',
            'view-analytics',
        ]);

        $vendorRole = Role::create(['name' => 'vendor']);
        $vendorRole->givePermissionTo([
            'view-projects',
            'view-interviews',
            'view-analytics',
        ]);
    }
}