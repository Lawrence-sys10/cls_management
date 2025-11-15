<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'user.view',
            'user.create', 
            'user.edit',
            'user.delete',
            'user.activate',
            'user.impersonate',
            
            // Land management
            'land.view',
            'land.create',
            'land.edit', 
            'land.delete',
            'land.verify',
            'land.export',
            'land.import',
            
            // Client management
            'client.view',
            'client.create',
            'client.edit',
            'client.delete',
            'client.export',
            'client.import',
            
            // Allocation management
            'allocation.view',
            'allocation.create',
            'allocation.edit',
            'allocation.delete',
            'allocation.approve',
            'allocation.reject',
            'allocation.export',
            'allocation.import',
            
            // Chief management
            'chief.view',
            'chief.create',
            'chief.edit',
            'chief.delete',
            'chief.export',
            'chief.import',
            
            // Document management
            'document.view',
            'document.create',
            'document.edit',
            'document.delete',
            'document.verify',
            'document.download',
            
            // Report management
            'report.view',
            'report.generate',
            'report.export',
            'report.download',
            
            // System management
            'settings.manage',
            'backup.manage',
            'logs.view',
            'system.health',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $chief = Role::firstOrCreate(['name' => 'chief']);

        // Assign all permissions to admin
        $admin->givePermissionTo(Permission::all());

        // Assign permissions to staff
        $staff->givePermissionTo([
            'user.view',
            'land.view', 'land.create', 'land.edit',
            'client.view', 'client.create', 'client.edit',
            'allocation.view', 'allocation.create', 'allocation.edit',
            'chief.view', 'chief.create', 'chief.edit',
            'document.view', 'document.create', 'document.edit', 'document.download',
            'report.view', 'report.generate', 'report.export',
        ]);

        // Assign permissions to chief (limited access)
        $chief->givePermissionTo([
            'land.view',
            'client.view',
            'allocation.view',
            'document.view', 'document.download',
            'report.view',
        ]);

        // Create initial admin user if doesn't exist
        $this->createAdminUser();
    }

    protected function createAdminUser(): void
    {
        $userModel = config('auth.providers.users.model');
        $admin = $userModel::firstOrCreate(
            ['email' => 'admin@landallocation.com'],
            [
                'name' => 'System Administrator',
                'password' => bcrypt('admin123'),
                'phone' => '+1234567890',
                'user_type' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');
    }
}