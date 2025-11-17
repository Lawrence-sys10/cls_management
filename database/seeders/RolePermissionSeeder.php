<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Land permissions
            'land.view', 'land.create', 'land.edit', 'land.delete', 'land.export', 'land.import',
            
            // Client permissions
            'client.view', 'client.create', 'client.edit', 'client.delete', 'client.export', 'client.import',
            
            // Allocation permissions
            'allocation.view', 'allocation.create', 'allocation.edit', 'allocation.delete', 'allocation.approve', 'allocation.export',
            
            // Chief permissions
            'chief.view', 'chief.create', 'chief.edit', 'chief.delete', 'chief.export',
            
            // Document permissions
            'document.view', 'document.create', 'document.edit', 'document.delete', 'document.verify',
            
            // Report permissions
            'report.view', 'report.generate', 'report.export',
            
            // User management permissions
            'user.view', 'user.create', 'user.edit', 'user.delete', 'user.impersonate',
            
            // System permissions
            'settings.manage', 'backup.manage', 'logs.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->givePermissionTo([
            'land.view', 'land.create', 'land.edit', 'land.export', 'land.import',
            'client.view', 'client.create', 'client.edit', 'client.export', 'client.import',
            'allocation.view', 'allocation.create', 'allocation.edit', 'allocation.approve', 'allocation.export',
            'chief.view', 'chief.create', 'chief.edit', 'chief.export',
            'document.view', 'document.create', 'document.edit',
            'report.view', 'report.generate', 'report.export',
            'user.view',
        ]);

        $chief = Role::firstOrCreate(['name' => 'chief']);
        $chief->givePermissionTo([
            'land.view',
            'client.view',
            'allocation.view',
            'document.view',
            'report.view',
        ]);

        // Assign admin role to first user
        $firstUser = User::first();
        if ($firstUser && !$firstUser->hasRole('admin')) {
            $firstUser->assignRole('admin');
        }
    }
}