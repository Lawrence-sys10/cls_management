<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Chief;
use App\Models\Staff;
use App\Models\Client;
use App\Models\Land;
use App\Models\Allocation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $this->createPermissions();
        
        // Create roles
        $this->createRoles();
        
        // Create admin user
        $this->createAdminUser();
        
        // Create sample chiefs
        $this->createChiefs();
        
        // Create sample staff
        $this->createStaff();
        
        // Create sample clients
        $this->createClients();
        
        // Create sample lands
        $this->createLands();
        
        // Create sample allocations
        $this->createAllocations();
        
        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin Login: admin@cls.com / password');
    }

    private function createPermissions(): void
    {
        $permissions = [
            'view dashboard',
            
            // Land permissions
            'view lands', 'create lands', 'edit lands', 'delete lands', 'export lands',
            
            // Client permissions
            'view clients', 'create clients', 'edit clients', 'delete clients', 'export clients',
            
            // Allocation permissions
            'view allocations', 'create allocations', 'edit allocations', 'delete allocations', 'approve allocations',
            
            // Chief permissions
            'view chiefs', 'create chiefs', 'edit chiefs', 'delete chiefs',
            
            // Report permissions
            'view reports', 'generate reports',
            
            // User management permissions
            'view users', 'create users', 'edit users', 'delete users',
            
            // System permissions
            'manage settings', 'view audit logs',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }

    private function createRoles(): void
    {
        $roles = [
            'admin' => Permission::all(),
            'chief' => [
                'view dashboard',
                'view lands', 'view clients', 'view allocations',
                'approve allocations',
            ],
            'staff' => [
                'view dashboard',
                'view lands', 'create lands', 'edit lands',
                'view clients', 'create clients', 'edit clients',
                'view allocations', 'create allocations', 'edit allocations',
                'view chiefs',
                'view reports',
            ],
            'registrar' => [
                'view dashboard',
                'view lands', 'view clients', 'view allocations',
                'approve allocations', 'view reports', 'generate reports',
            ],
            'viewer' => [
                'view dashboard',
                'view lands', 'view clients', 'view allocations', 'view chiefs',
                'view reports',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            
            if (is_array($permissions)) {
                $role->syncPermissions($permissions);
            } else {
                // For admin, give all permissions
                $role->syncPermissions(Permission::all());
            }
        }
    }

    private function createAdminUser(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@cls.com'],
            [
                'name' => 'System Administrator',
                'phone' => '0205440495',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['admin']);

        // Create staff record for admin if it doesn't exist
        Staff::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'department' => 'Administration',
                'phone' => '0205440495',
                'assigned_area' => 'Headquarters',
                'employee_id' => 'ADM001',
                'date_joined' => now(),
            ]
        );
    }

    private function createChiefs(): void
    {
        $chiefs = [
            [
                'name' => 'Nana Kwame Ababio',
                'jurisdiction' => 'Techiman Central',
                'phone' => '0201234567',
                'email' => 'chief.techiman@example.com',
                'is_active' => true,
            ],
            [
                'name' => 'Nana Akua Mensah',
                'jurisdiction' => 'Tuobodom Area',
                'phone' => '0202345678',
                'email' => 'chief.tuobodom@example.com',
                'is_active' => true,
            ],
            [
                'name' => 'Nana Yaw Boateng',
                'jurisdiction' => 'Krobo Division',
                'phone' => '0203456789',
                'email' => 'chief.krobo@example.com',
                'is_active' => true,
            ],
        ];

        foreach ($chiefs as $chiefData) {
            Chief::firstOrCreate(
                ['email' => $chiefData['email']],
                $chiefData
            );
        }
    }

    private function createStaff(): void
    {
        $staffMembers = [
            [
                'name' => 'John Mensah',
                'email' => 'john.mensah@cls.com',
                'phone' => '0204567890',
                'department' => 'Land Registration',
                'employee_id' => 'STAFF001',
            ],
            [
                'name' => 'Akosua Asante',
                'email' => 'akosua.asante@cls.com',
                'phone' => '0205678901',
                'department' => 'Client Services',
                'employee_id' => 'STAFF002',
            ],
            [
                'name' => 'Kwame Osei',
                'email' => 'kwame.osei@cls.com',
                'phone' => '0206789012',
                'department' => 'Survey & Mapping',
                'employee_id' => 'STAFF003',
            ],
        ];

        foreach ($staffMembers as $staffData) {
            $user = User::firstOrCreate(
                ['email' => $staffData['email']],
                [
                    'name' => $staffData['name'],
                    'phone' => $staffData['phone'],
                    'password' => Hash::make('password'),
                    'user_type' => 'staff',
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles(['staff']);

            Staff::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'department' => $staffData['department'],
                    'phone' => $staffData['phone'],
                    'assigned_area' => 'Techiman Municipality',
                    'employee_id' => $staffData['employee_id'],
                    'date_joined' => now()->subMonths(rand(1, 24)),
                ]
            );
        }
    }

    private function createClients(): void
    {
        $clients = [
            [
                'full_name' => 'Michael Agyeman',
                'phone' => '0241234567',
                'email' => 'michael.agyeman@example.com',
                'id_type' => 'ghanacard',
                'id_number' => 'GHA-123456789-0',
                'address' => 'P.O. Box 123, Techiman',
                'occupation' => 'Business Owner',
                'date_of_birth' => '1985-06-15',
                'gender' => 'male',
            ],
            [
                'full_name' => 'Grace Nyamekye',
                'phone' => '0242345678',
                'email' => 'grace.nyamekye@example.com',
                'id_type' => 'ghanacard',
                'id_number' => 'GHA-234567890-1',
                'address' => 'Tuobodom Road, Techiman',
                'occupation' => 'Teacher',
                'date_of_birth' => '1990-03-22',
                'gender' => 'female',
            ],
            [
                'full_name' => 'Samuel Ofori',
                'phone' => '0243456789',
                'email' => 'samuel.ofori@example.com',
                'id_type' => 'passport',
                'id_number' => 'G12345678',
                'address' => 'Krobo Avenue, Techiman',
                'occupation' => 'Farmer',
                'date_of_birth' => '1978-11-08',
                'gender' => 'male',
            ],
            [
                'full_name' => 'Comfort Asare',
                'phone' => '0244567890',
                'email' => 'comfort.asare@example.com',
                'id_type' => 'voters_id',
                'id_number' => 'V123456789',
                'address' => 'Central Market, Techiman',
                'occupation' => 'Trader',
                'date_of_birth' => '1982-09-14',
                'gender' => 'female',
            ],
        ];

        foreach ($clients as $clientData) {
            Client::firstOrCreate(
                ['email' => $clientData['email']],
                $clientData
            );
        }
    }

    private function createLands(): void
    {
        $chiefs = Chief::all();
        
        $lands = [
            [
                'plot_number' => 'TCH/001/2024',
                'area_acres' => 2.5,
                'area_hectares' => 1.01,
                'location' => 'Techiman Central, Near Central Market',
                'boundary_description' => 'Bounded by Main Road to the North, River to the South',
                'latitude' => 7.5860,
                'longitude' => -1.9550,
                'ownership_status' => 'allocated',
                'land_use' => 'residential',
                'price' => 15000.00,
                'soil_type' => 'Sandy Loam',
                'topography' => 'Flat',
                'registration_date' => now()->subMonths(6),
                'is_verified' => true,
            ],
            [
                'plot_number' => 'TCH/002/2024',
                'area_acres' => 5.0,
                'area_hectares' => 2.02,
                'location' => 'Tuobodom Road, Opposite Police Station',
                'boundary_description' => 'Adjacent to the main highway, with access road',
                'latitude' => 7.5900,
                'longitude' => -1.9600,
                'ownership_status' => 'vacant',
                'land_use' => 'commercial',
                'price' => 35000.00,
                'soil_type' => 'Clay Loam',
                'topography' => 'Gentle Slope',
                'registration_date' => now()->subMonths(3),
                'is_verified' => true,
            ],
            [
                'plot_number' => 'TCH/003/2024',
                'area_acres' => 10.0,
                'area_hectares' => 4.05,
                'location' => 'Krobo Division, Near Water Works',
                'boundary_description' => 'Large plot suitable for agricultural purposes',
                'latitude' => 7.5800,
                'longitude' => -1.9500,
                'ownership_status' => 'vacant',
                'land_use' => 'agricultural',
                'price' => 25000.00,
                'soil_type' => 'Sandy Clay',
                'topography' => 'Flat',
                'registration_date' => now()->subMonths(2),
                'is_verified' => true,
            ],
            [
                'plot_number' => 'TCH/004/2024',
                'area_acres' => 1.5,
                'area_hectares' => 0.61,
                'location' => 'Techiman Central, Residential Area',
                'boundary_description' => 'Corner plot with road access on two sides',
                'latitude' => 7.5880,
                'longitude' => -1.9580,
                'ownership_status' => 'under_dispute',
                'land_use' => 'residential',
                'price' => 12000.00,
                'soil_type' => 'Loamy',
                'topography' => 'Flat',
                'registration_date' => now()->subMonths(8),
                'is_verified' => false,
            ],
        ];

        foreach ($lands as $index => $landData) {
            $landData['chief_id'] = $chiefs[$index % count($chiefs)]->id;
            Land::firstOrCreate(
                ['plot_number' => $landData['plot_number']],
                $landData
            );
        }
    }

    private function createAllocations(): void
    {
        $clients = Client::all();
        $lands = Land::all();
        $chiefs = Chief::all();
        $staff = Staff::all();

        $allocations = [
            [
                'land_id' => $lands[0]->id,
                'client_id' => $clients[0]->id,
                'chief_id' => $chiefs[0]->id,
                'processed_by' => $staff[0]->id,
                'allocation_date' => now()->subMonths(2),
                'approval_status' => 'approved',
                'chief_approval_date' => now()->subMonths(2)->addDays(3),
                'payment_status' => 'paid',
                'payment_amount' => 15000.00,
                'payment_date' => now()->subMonths(2)->addDays(1),
                'is_finalized' => true,
            ],
            [
                'land_id' => $lands[1]->id,
                'client_id' => $clients[1]->id,
                'chief_id' => $chiefs[1]->id,
                'processed_by' => $staff[1]->id,
                'allocation_date' => now()->subMonth(),
                'approval_status' => 'pending',
                'payment_status' => 'partial',
                'payment_amount' => 10000.00,
                'is_finalized' => false,
            ],
        ];

        foreach ($allocations as $allocationData) {
            // Use firstOrCreate with unique combination
            Allocation::firstOrCreate(
                [
                    'land_id' => $allocationData['land_id'],
                    'client_id' => $allocationData['client_id']
                ],
                $allocationData
            );
        }
    }
}