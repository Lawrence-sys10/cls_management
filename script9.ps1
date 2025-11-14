# Step 9: Generate Database Seeders, Configuration Files and Deployment Scripts
# Save this as generate-seeders-config.ps1 and run from project root

# Create necessary directories
$seedersPath = "database/seeders"
$configPath = "config"
$deploymentPath = "deployment"

@($seedersPath, $configPath, $deploymentPath) | ForEach-Object {
    if (!(Test-Path $_)) {
        New-Item -ItemType Directory -Path $_ -Force
    }
}

# 1. Database Seeder
@'
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
        
        $this->command->info(''Database seeded successfully!'');
        $this->command->info(''Admin Login: admin@cls.com / password'');
    }

    private function createPermissions(): void
    {
        $permissions = [
            ''view dashboard'',
            
            // Land permissions
            ''view lands'', ''create lands'', ''edit lands'', ''delete lands'', ''export lands'',
            
            // Client permissions
            ''view clients'', ''create clients'', ''edit clients'', ''delete clients'', ''export clients'',
            
            // Allocation permissions
            ''view allocations'', ''create allocations'', ''edit allocations'', ''delete allocations'', ''approve allocations'',
            
            // Chief permissions
            ''view chiefs'', ''create chiefs'', ''edit chiefs'', ''delete chiefs'',
            
            // Report permissions
            ''view reports'', ''generate reports'',
            
            // User management permissions
            ''view users'', ''create users'', ''edit users'', ''delete users'',
            
            // System permissions
            ''manage settings'', ''view audit logs'',
        ];

        foreach ($permissions as $permission) {
            Permission::create([''name'' => $permission, ''guard_name'' => ''web'']);
        }
    }

    private function createRoles(): void
    {
        $adminRole = Role::create([''name'' => ''admin'', ''guard_name'' => ''web'']);
        $chiefRole = Role::create([''name'' => ''chief'', ''guard_name'' => ''web'']);
        $staffRole = Role::create([''name'' => ''staff'', ''guard_name'' => ''web'']);
        $registrarRole = Role::create([''name'' => ''registrar'', ''guard_name'' => ''web'']);
        $viewerRole = Role::create([''name'' => ''viewer'', ''guard_name'' => ''web'']);

        // Admin gets all permissions
        $adminRole->givePermissionTo(Permission::all());

        // Chief permissions
        $chiefRole->givePermissionTo([
            ''view dashboard'',
            ''view lands'', ''view clients'', ''view allocations'',
            ''approve allocations'',
        ]);

        // Staff permissions
        $staffRole->givePermissionTo([
            ''view dashboard'',
            ''view lands'', ''create lands'', ''edit lands'',
            ''view clients'', ''create clients'', ''edit clients'',
            ''view allocations'', ''create allocations'', ''edit allocations'',
            ''view chiefs'',
            ''view reports'',
        ]);

        // Registrar permissions
        $registrarRole->givePermissionTo([
            ''view dashboard'',
            ''view lands'', ''view clients'', ''view allocations'',
            ''approve allocations'', ''view reports'', ''generate reports'',
        ]);

        // Viewer permissions (read-only)
        $viewerRole->givePermissionTo([
            ''view dashboard'',
            ''view lands'', ''view clients'', ''view allocations'', ''view chiefs'',
            ''view reports'',
        ]);
    }

    private function createAdminUser(): void
    {
        $admin = User::create([
            ''name'' => ''System Administrator'',
            ''email'' => ''admin@cls.com'',
            ''phone'' => ''0205440495'',
            ''password'' => Hash::make(''password''),
            ''user_type'' => ''admin'',
            ''email_verified_at'' => now(),
        ]);

        $admin->assignRole(''admin'');

        // Create staff record for admin
        Staff::create([
            ''user_id'' => $admin->id,
            ''department'' => ''Administration'',
            ''phone'' => ''0205440495'',
            ''assigned_area'' => ''Headquarters'',
            ''employee_id'' => ''ADM001'',
            ''date_joined'' => now(),
        ]);
    }

    private function createChiefs(): void
    {
        $chiefs = [
            [
                ''name'' => ''Nana Kwame Ababio'',
                ''jurisdiction'' => ''Techiman Central'',
                ''phone'' => ''0201234567'',
                ''email'' => ''chief.techiman@example.com'',
                ''is_active'' => true,
            ],
            [
                ''name'' => ''Nana Akua Mensah'',
                ''jurisdiction'' => ''Tuobodom Area'',
                ''phone'' => ''0202345678'',
                ''email'' => ''chief.tuobodom@example.com'',
                ''is_active'' => true,
            ],
            [
                ''name'' => ''Nana Yaw Boateng'',
                ''jurisdiction'' => ''Krobo Division'',
                ''phone'' => ''0203456789'',
                ''email'' => ''chief.krobo@example.com'',
                ''is_active'' => true,
            ],
        ];

        foreach ($chiefs as $chiefData) {
            Chief::create($chiefData);
        }
    }

    private function createStaff(): void
    {
        $staffMembers = [
            [
                ''name'' => ''John Mensah'',
                ''email'' => ''john.mensah@cls.com'',
                ''phone'' => ''0204567890'',
                ''department'' => ''Land Registration'',
                ''employee_id'' => ''STAFF001'',
            ],
            [
                ''name'' => ''Akosua Asante'',
                ''email'' => ''akosua.asante@cls.com'',
                ''phone'' => ''0205678901'',
                ''department'' => ''Client Services'',
                ''employee_id'' => ''STAFF002'',
            ],
            [
                ''name'' => ''Kwame Osei'',
                ''email'' => ''kwame.osei@cls.com'',
                ''phone'' => ''0206789012'',
                ''department'' => ''Survey & Mapping'',
                ''employee_id'' => ''STAFF003'',
            ],
        ];

        foreach ($staffMembers as $staffData) {
            $user = User::create([
                ''name'' => $staffData[''name''],
                ''email'' => $staffData[''email''],
                ''phone'' => $staffData[''phone''],
                ''password'' => Hash::make(''password''),
                ''user_type'' => ''staff'',
                ''email_verified_at'' => now(),
            ]);

            $user->assignRole(''staff'');

            Staff::create([
                ''user_id'' => $user->id,
                ''department'' => $staffData[''department''],
                ''phone'' => $staffData[''phone''],
                ''assigned_area'' => ''Techiman Municipality'',
                ''employee_id'' => $staffData[''employee_id''],
                ''date_joined'' => now()->subMonths(rand(1, 24)),
            ]);
        }
    }

    private function createClients(): void
    {
        $clients = [
            [
                ''full_name'' => ''Michael Agyeman'',
                ''phone'' => ''0241234567'',
                ''email'' => ''michael.agyeman@example.com'',
                ''id_type'' => ''ghanacard'',
                ''id_number'' => ''GHA-123456789-0'',
                ''address'' => ''P.O. Box 123, Techiman'',
                ''occupation'' => ''Business Owner'',
                ''date_of_birth'' => ''1985-06-15'',
                ''gender'' => ''male'',
            ],
            [
                ''full_name'' => ''Grace Nyamekye'',
                ''phone'' => ''0242345678'',
                ''email'' => ''grace.nyamekye@example.com'',
                ''id_type'' => ''ghanacard'',
                ''id_number'' => ''GHA-234567890-1'',
                ''address'' => ''Tuobodom Road, Techiman'',
                ''occupation'' => ''Teacher'',
                ''date_of_birth'' => ''1990-03-22'',
                ''gender'' => ''female'',
            ],
            [
                ''full_name'' => ''Samuel Ofori'',
                ''phone'' => ''0243456789'',
                ''email'' => ''samuel.ofori@example.com'',
                ''id_type'' => ''passport'',
                ''id_number'' => ''G12345678'',
                ''address'' => ''Krobo Avenue, Techiman'',
                ''occupation'' => ''Farmer'',
                ''date_of_birth'' => ''1978-11-08'',
                ''gender'' => ''male'',
            ],
            [
                ''full_name'' => ''Comfort Asare'',
                ''phone'' => ''0244567890'',
                ''email'' => ''comfort.asare@example.com'',
                ''id_type'' => ''voters_id'',
                ''id_number'' => ''V123456789'',
                ''address'' => ''Central Market, Techiman'',
                ''occupation'' => ''Trader'',
                ''date_of_birth'' => ''1982-09-14'',
                ''gender'' => ''female'',
            ],
        ];

        foreach ($clients as $clientData) {
            Client::create($clientData);
        }
    }

    private function createLands(): void
    {
        $chiefs = Chief::all();
        
        $lands = [
            [
                ''plot_number'' => ''TCH/001/2024'',
                ''area_acres'' => 2.5,
                ''area_hectares'' => 1.01,
                ''location'' => ''Techiman Central, Near Central Market'',
                ''boundary_description'' => ''Bounded by Main Road to the North, River to the South'',
                ''latitude'' => 7.5860,
                ''longitude'' => -1.9550,
                ''ownership_status'' => ''allocated'',
                ''land_use'' => ''residential'',
                ''price'' => 15000.00,
                ''soil_type'' => ''Sandy Loam'',
                ''topography'' => ''Flat'',
                ''registration_date'' => now()->subMonths(6),
                ''is_verified'' => true,
            ],
            [
                ''plot_number'' => ''TCH/002/2024'',
                ''area_acres'' => 5.0,
                ''area_hectares'' => 2.02,
                ''location'' => ''Tuobodom Road, Opposite Police Station'',
                ''boundary_description'' => ''Adjacent to the main highway, with access road'',
                ''latitude'' => 7.5900,
                ''longitude'' => -1.9600,
                ''ownership_status'' => ''vacant'',
                ''land_use'' => ''commercial'',
                ''price'' => 35000.00,
                ''soil_type'' => ''Clay Loam'',
                ''topography'' => ''Gentle Slope'',
                ''registration_date'' => now()->subMonths(3),
                ''is_verified'' => true,
            ],
            [
                ''plot_number'' => ''TCH/003/2024'',
                ''area_acres'' => 10.0,
                ''area_hectares'' => 4.05,
                ''location'' => ''Krobo Division, Near Water Works'',
                ''boundary_description'' => ''Large plot suitable for agricultural purposes'',
                ''latitude'' => 7.5800,
                ''longitude'' => -1.9500,
                ''ownership_status'' => ''vacant'',
                ''land_use'' => ''agricultural'',
                ''price'' => 25000.00,
                ''soil_type'' => ''Sandy Clay'',
                ''topography'' => ''Flat'',
                ''registration_date'' => now()->subMonths(2),
                ''is_verified'' => true,
            ],
            [
                ''plot_number'' => ''TCH/004/2024'',
                ''area_acres'' => 1.5,
                ''area_hectares'' => 0.61,
                ''location'' => ''Techiman Central, Residential Area'',
                ''boundary_description'' => ''Corner plot with road access on two sides'',
                ''latitude'' => 7.5880,
                ''longitude'' => -1.9580,
                ''ownership_status'' => ''under_dispute'',
                ''land_use'' => ''residential'',
                ''price'' => 12000.00,
                ''soil_type'' => ''Loamy'',
                ''topography'' => ''Flat'',
                ''registration_date'' => now()->subMonths(8),
                ''is_verified'' => false,
            ],
        ];

        foreach ($lands as $index => $landData) {
            $landData[''chief_id''] = $chiefs[$index % count($chiefs)]->id;
            Land::create($landData);
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
                ''land_id'' => $lands[0]->id,
                ''client_id'' => $clients[0]->id,
                ''chief_id'' => $chiefs[0]->id,
                ''processed_by'' => $staff[0]->id,
                ''allocation_date'' => now()->subMonths(2),
                ''approval_status'' => ''approved'',
                ''chief_approval_date'' => now()->subMonths(2)->addDays(3),
                ''payment_status'' => ''paid'',
                ''payment_amount'' => 15000.00,
                ''payment_date'' => now()->subMonths(2)->addDays(1),
                ''is_finalized'' => true,
            ],
            [
                ''land_id'' => $lands[1]->id,
                ''client_id'' => $clients[1]->id,
                ''chief_id'' => $chiefs[1]->id,
                ''processed_by'' => $staff[1]->id,
                ''allocation_date'' => now()->subMonth(),
                ''approval_status'' => ''pending'',
                ''payment_status'' => ''partial'',
                ''payment_amount'' => 10000.00,
                ''is_finalized'' => false,
            ],
        ];

        foreach ($allocations as $allocationData) {
            Allocation::create($allocationData);
        }
    }
}
'@ | Out-File -FilePath "$seedersPath/DatabaseSeeder.php" -Encoding UTF8

# 2. Spatie Permission Configuration
@'
<?php

return [

    ''models'' => [

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Spatie\Permission\Contracts\Permission` contract.
         */

        ''permission'' => Spatie\Permission\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Spatie\Permission\Contracts\Role` contract.
         */

        ''role'' => Spatie\Permission\Models\Role::class,

    ],

    ''table_names'' => [

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        ''roles'' => ''roles'',

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * table should be used to retrieve your permissions. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        ''permissions'' => ''permissions'',

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * table should be used to retrieve your models permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        ''model_has_permissions'' => ''model_has_permissions'',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your models roles. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        ''model_has_roles'' => ''model_has_roles'',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        ''role_has_permissions'' => ''role_has_permissions'',
    ],

    ''column_names'' => [
        /*
         * Change this if you want to name the related pivots other than defaults
         */
        ''role_pivot_key'' => null, //default ''role_id'',
        ''permission_pivot_key'' => null, //default ''permission_id'',

        /*
         * Change this if you want to name the related model primary key other than
         * `model_id`.
         *
         * For example, this would be nice if your primary keys are all UUIDs. In
         * that case, name this `model_uuid`.
         */

        ''model_morph_key'' => ''model_id'',

        /*
         * Change this if you want to use the teams feature and your related model's
         * foreign key is other than `team_id`.
         */

        ''team_foreign_key'' => ''team_id'',
    ],

    /*
     * When set to true, the package for teams will be registered and used.
     */

    ''teams'' => false,

    /*
     * When set to true, the required permission names are added to the exception
     * message. This could be considered an information leak in some contexts, so
     * the default setting is false here for optimum safety.
     */

    ''display_permission_in_exception'' => false,

    /*
     * When set to true, the required role names are added to the exception
     * message. This could be considered an information leak in some contexts, so
     * the default setting is false here for optimum safety.
     */

    ''display_role_in_exception'' => false,

    /*
     * By default wildcard permission lookups are disabled.
     */

    ''enable_wildcard_permission'' => false,

    ''cache'' => [

        /*
         * By default all permissions are cached for 24 hours to speed up performance.
         * When permissions or roles are updated the cache is flushed automatically.
         */

        ''expiration_time'' => \DateInterval::createFromDateString(''24 hours''),

        /*
         * The cache key used to store all permissions.
         */

        ''key'' => ''spatie.permission.cache'',

        /*
         * You may optionally indicate a specific cache driver to use for permission and
         * role caching using any of the `store` drivers listed in the cache.php config
         * file. Using ''default'' here means to use the `default` set in cache.php.
         */

        ''store'' => ''default'',
    ],
];
'@ | Out-File -FilePath "$configPath/permission.php" -Encoding UTF8

# 3. Excel Configuration
@'
<?php

return [
    ''exports'' => [

        /*
        |--------------------------------------------------------------------------
        | Chunk size
        |--------------------------------------------------------------------------
        |
        | When using FromQuery, the query is automatically chunked.
        | Here you can specify how big the chunk should be.
        |
        */
        ''chunk_size''             => 1000,

        /*
        |--------------------------------------------------------------------------
        | Pre-calculate formulas during export
        |--------------------------------------------------------------------------
        */
        ''pre_calculate_formulas'' => false,

        /*
        |--------------------------------------------------------------------------
        | Enable strict null comparison
        |--------------------------------------------------------------------------
        |
        | When enabling strict null comparison empty cells ('''') will
        | be added to the sheet.
        */
        ''strict_null_comparison'' => false,

        /*
        |--------------------------------------------------------------------------
        | CSV Settings
        |--------------------------------------------------------------------------
        |
        | Configure e.g. delimiter, enclosure and line ending for CSV exports.
        |
        */
        ''csv''                    => [
            ''delimiter''              => '','',
            ''enclosure''              => ''"'',
            ''line_ending''            => PHP_EOL,
            ''use_bom''                => false,
            ''include_separator_line'' => false,
            ''excel_compatibility''    => false,
            ''output_encoding''        => '''',
        ],

        /*
        |--------------------------------------------------------------------------
        | Worksheet properties
        |--------------------------------------------------------------------------
        |
        | Configure e.g. default title, creator, subject,...
        |
        */
        ''properties''             => [
            ''creator''        => ''Techiman CLS'',
            ''lastModifiedBy'' => ''Techiman CLS'',
            ''title''          => ''CLS Export'',
            ''description''    => ''Customary Lands Secretariat Export'',
            ''subject''        => ''Lands Data'',
            ''keywords''       => ''lands,clients,allocations,export'',
            ''category''       => ''Land Management'',
            ''manager''        => ''Techiman CLS'',
            ''company''        => ''Techiman Traditional Council'',
        ],
    ],

    ''imports''            => [

        /*
        |--------------------------------------------------------------------------
        | Read Only
        |--------------------------------------------------------------------------
        |
        | When dealing with imports, you might only be interested in the
        | data that the sheet exists. By default we ignore all styles,
        | however if you want to do some logic based on style data
        | you can enable it by setting read_only to false.
        |
        */
        ''read_only'' => true,

        /*
        |--------------------------------------------------------------------------
        | Ignore Empty
        |--------------------------------------------------------------------------
        |
        | When dealing with imports, you might be interested in ignoring
        | rows that have null values or empty strings. By default rows
        | containing empty strings or empty values are not ignored but can be
        | ignored by enabling the following setting.
        |
        */
        ''ignore_empty'' => false,

        /*
        |--------------------------------------------------------------------------
        | Heading Row Formatter
        |--------------------------------------------------------------------------
        |
        | Configure the heading row formatter.
        | Available options: none|slug|custom
        |
        */
        ''heading_row'' => [
            ''formatter'' => ''slug'',
        ],

        /*
        |--------------------------------------------------------------------------
        | CSV Settings
        |--------------------------------------------------------------------------
        |
        | Configure e.g. delimiter, enclosure and line ending for CSV imports.
        |
        */
        ''csv''         => [
            ''delimiter''        => '','',
            ''enclosure''        => ''"'',
            ''escape_character'' => ''\\'',
            ''contiguous''       => false,
            ''input_encoding''   => ''UTF-8'',
        ],

        /*
        |--------------------------------------------------------------------------
        | Worksheet properties
        |--------------------------------------------------------------------------
        |
        | Configure e.g. default title, creator, subject,...
        |
        */
        ''properties''  => [
            ''creator''        => ''Techiman CLS'',
            ''lastModifiedBy'' => ''Techiman CLS'',
            ''title''          => ''CLS Import'',
            ''description''    => ''Customary Lands Secretariat Import'',
            ''subject''        => ''Lands Data'',
            ''keywords''       => ''lands,clients,allocations,import'',
            ''category''       => ''Land Management'',
            ''manager''        => ''Techiman CLS'',
            ''company''        => ''Techiman Traditional Council'',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Extension detector
    |--------------------------------------------------------------------------
    |
    | Configure here which writer type should be used when
    | the package needs to guess the writer type based on
    | the file extension.
    |
    */
    ''extension_detector'' => [
        ''xlsx''     => \Maatwebsite\Excel\Excel::XLSX,
        ''xlsm''     => \Maatwebsite\Excel\Excel::XLSX,
        ''xltx''     => \Maatwebsite\Excel\Excel::XLSX,
        ''xltm''     => \Maatwebsite\Excel\Excel::XLSX,
        ''xls''      => \Maatwebsite\Excel\Excel::XLS,
        ''xlt''      => \Maatwebsite\Excel\Excel::XLS,
        ''ods''      => \Maatwebsite\Excel\Excel::ODS,
        ''ots''      => \Maatwebsite\Excel\Excel::ODS,
        ''slk''      => \Maatwebsite\Excel\Excel::SLK,
        ''xml''      => \Maatwebsite\Excel\Excel::XML,
        ''gnumeric'' => \Maatwebsite\Excel\Excel::GNUMERIC,
        ''htm''      => \Maatwebsite\Excel\Excel::HTML,
        ''html''     => \Maatwebsite\Excel\Excel::HTML,
        ''csv''      => \Maatwebsite\Excel\Excel::CSV,
        ''tsv''      => \Maatwebsite\Excel\Excel::TSV,

        /*
        |--------------------------------------------------------------------------
        | PDF Extension
        |--------------------------------------------------------------------------
        |
        | Configure here which Pdf driver should be used by default.
        | Available options: Excel::MPDF | Excel::TCPDF | Excel::DOMPDF
        |
        */
        ''pdf''      => \Maatwebsite\Excel\Excel::DOMPDF,
    ],

    /*
    |--------------------------------------------------------------------------
    | Value Binder
    |--------------------------------------------------------------------------
    |
    | PhpSpreadsheet offers a way to hook into the process of a value being
    | written to a cell. In there some assumptions are made on how the
    | value should be formatted. If you want to change those defaults,
    | you can implement your own default value binder.
    |
    | Possible value binders:
    |
    | [x] Maatwebsite\Excel\DefaultValueBinder::class
    | [x] PhpOffice\PhpSpreadsheet\Cell\StringValueBinder::class
    | [x] PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder::class
    |
    */
    ''value_binder'' => [
        ''default'' => Maatwebsite\Excel\DefaultValueBinder::class,
    ],

    ''cache'' => [
        /*
        |--------------------------------------------------------------------------
        | Default cell caching driver
        |--------------------------------------------------------------------------
        |
        | By default PhpSpreadsheet keeps all cell values in memory, however when
        | dealing with large files, this might result into memory issues. If you
        | want to mitigate that, you can configure a cell caching driver here.
        | When using the illuminate driver, it will store each value in the
        | cache store. This can slow down the process, because it needs to
        | store each value. You can use the "batch" store if you want to
        | only persist to the store when the memory limit is reached.
        |
        | Drivers: memory|illuminate|batch
        |
        */
        ''driver''     => ''memory'',

        /*
        |--------------------------------------------------------------------------
        | Batch memory caching
        |--------------------------------------------------------------------------
        |
        | When dealing with the "batch" caching driver, it will only
        | persist to the store when the memory limit is reached.
        | Here you can tweak the memory limit to your liking.
        |
        */
        ''batch''     => [
            ''memory_limit'' => 60000,
        ],

        /*
        |--------------------------------------------------------------------------
        | Illuminate cache
        |--------------------------------------------------------------------------
        |
        | When using the "illuminate" caching driver, it will automatically use
        | your default cache store. However if you prefer to have the cell
        | cache on a separate store, you can configure the store name here.
        | You can use any store defined in your cache config. When leaving
        | at "null" it will use the default store.
        |
        */
        ''illuminate'' => [
            ''store'' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Transaction Handler
    |--------------------------------------------------------------------------
    |
    | By default the import is wrapped in a transaction. This is useful
    | for when an import may fail and you want to retry it. With the
    | transactions, the previous import gets rolled-back.
    |
    | You can disable the transaction handler by setting this to null.
    | Or you can choose a custom made transaction handler here.
    |
    | Supported handlers: null|db
    |
    */
    ''transactions'' => [
        ''handler'' => ''db'',
        ''db''      => [
            ''connection'' => null,
        ],
    ],

    ''temporary_files'' => [

        /*
        |--------------------------------------------------------------------------
        | Local Temporary Path
        |--------------------------------------------------------------------------
        |
        | When exporting and importing files, we use a temporary file, before
        | storing reading or downloading. Here you can customize that path.
        |
        */
        ''local_path''          => storage_path(''framework/cache/laravel-excel''),

        /*
        |--------------------------------------------------------------------------
        | Remote Temporary Disk
        |--------------------------------------------------------------------------
        |
        | When dealing with a multi disk setup, here you can configure which
        | disk should be used to store temporary files.
        |
        */
        ''remote_disk''         => null,
        ''remote_prefix''       => null,

        /*
        |--------------------------------------------------------------------------
        | Force Resync
        |--------------------------------------------------------------------------
        |
        | When dealing with a multi disk setup, here you can configure after
        | how many seconds the remote should resync the temporary file.
        |
        */
        ''force_resync_after''  => null,
    ],
];
'@ | Out-File -FilePath "$configPath/excel.php" -Encoding UTF8

# 4. Deployment Script for Ubuntu Server
@'
#!/bin/bash

# CLS Management System Deployment Script
# Ubuntu 20.04+ Server Deployment

set -e

echo "🚀 Starting CLS Management System Deployment..."
echo "=============================================="

# Colors for output
RED=''\033[0;31m''
GREEN=''\033[0;32m''
YELLOW=''\033[1;33m''
NC=''\033[0m'' # No Color

# Configuration
DB_NAME="cls_management"
DB_USER="cls_user"
DB_PASS=$(openssl rand -base64 32)
APP_URL="your-domain.com" # Change this to your domain
APP_NAME="Techiman CLS"

# Function to print colored output
print_status() {
    echo -e "${GREEN}[✓]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[!]${NC} $1"
}

print_error() {
    echo -e "${RED}[✗]${NC} $1"
}

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    print_error "Please run as root"
    exit 1
fi

# Update system
print_status "Updating system packages..."
apt update && apt upgrade -y

# Install required packages
print_status "Installing required packages..."
apt install -y nginx mysql-server php8.1 php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl php8.1-zip php8.1-gd php8.1-mbstring php8.1-bcmath php8.1-common

# Install Composer
print_status "Installing Composer..."
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Configure MySQL
print_status "Configuring MySQL database..."
mysql -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS ''$DB_USER''@''localhost'' IDENTIFIED BY ''$DB_PASS'';"
mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO ''$DB_USER''@''localhost'';"
mysql -e "FLUSH PRIVILEGES;"

# Create application directory
print_status "Creating application directory..."
mkdir -p /var/www/cls
chown -R www-data:www-data /var/www/cls

# Clone or upload your Laravel application here
# For this script, we assume the application is already in /var/www/cls

print_warning "Please upload your Laravel application to /var/www/cls before continuing"
read -p "Press Enter to continue after uploading the application..."

# Set proper permissions
print_status "Setting file permissions..."
cd /var/www/cls
chown -R www-data:www-data .
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Install Laravel dependencies
print_status "Installing Laravel dependencies..."
sudo -u www-data composer install --no-dev --optimize-autoloader

# Create environment file
print_status "Creating environment configuration..."
cp .env.example .env

# Generate application key
sudo -u www-data php artisan key:generate

# Configure environment
print_status "Configuring environment variables..."
sed -i "s/APP_NAME=.*/APP_NAME=\"$APP_NAME\"/" .env
sed -i "s/APP_URL=.*/APP_URL=https:\/\/$APP_URL/" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASS/" .env

# Set production environment
sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" .env
sed -i "s/APP_ENV=.*/APP_ENV=production/" .env

# Run database migrations and seed
print_status "Running database setup..."
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan db:seed --force

# Generate storage link
sudo -u www-data php artisan storage:link

# Optimize application
print_status "Optimizing application..."
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache

# Configure Nginx
print_status "Configuring Nginx..."
cat > /etc/nginx/sites-available/cls << EOF
server {
    listen 80;
    server_name $APP_URL;
    root /var/www/cls/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    location / {
        try_files \\$uri \\$uri/ /index.php?\\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php\\$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \\$realpath_root\\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\\.(?!well-known).* {
        deny all;
    }

    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
}
EOF

# Enable site
ln -sf /etc/nginx/sites-available/cls /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test Nginx configuration
nginx -t

# Restart services
print_status "Restarting services..."
systemctl restart nginx
systemctl restart php8.1-fpm
systemctl enable nginx
systemctl enable php8.1-fpm

# Configure firewall
print_status "Configuring firewall..."
ufw allow ''Nginx Full''
ufw allow OpenSSH
ufw --force enable

# Setup SSL with Let''s Encrypt (optional)
print_warning "Would you like to setup SSL with Let''s Encrypt? (y/n)"
read -r setup_ssl
if [[ \\$setup_ssl =~ ^[Yy]\\$ ]]; then
    apt install -y certbot python3-certbot-nginx
    certbot --nginx -d \\$APP_URL
    # Auto-renewal
    (crontab -l 2>/dev/null; echo "0 12 * * * /usr/bin/certbot renew --quiet") | crontab -
fi

# Create backup script
print_status "Creating backup script..."
cat > /usr/local/bin/backup-cls.sh << ''EOF''
#!/bin/bash
# Backup script for CLS Management System

BACKUP_DIR="/var/backups/cls"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="cls_management"

mkdir -p \\$BACKUP_DIR

# Backup database
mysqldump \\$DB_NAME > \\$BACKUP_DIR/cls_db_\\$DATE.sql
gzip \\$BACKUP_DIR/cls_db_\\$DATE.sql

# Backup application files
tar -czf \\$BACKUP_DIR/cls_files_\\$DATE.tar.gz /var/www/cls

# Cleanup old backups (keep last 30 days)
find \\$BACKUP_DIR -name "*.gz" -type f -mtime +30 -delete

echo "Backup completed: \\$BACKUP_DIR/cls_db_\\$DATE.sql.gz"
echo "Backup completed: \\$BACKUP_DIR/cls_files_\\$DATE.tar.gz"
EOF

chmod +x /usr/local/bin/backup-cls.sh

# Setup daily backups
echo "0 2 * * * root /usr/local/bin/backup-cls.sh" > /etc/cron.d/cls-backup

# Create logrotate configuration
cat > /etc/logrotate.d/cls << EOF
/var/www/cls/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    copytruncate
}
EOF

print_status "🎉 Deployment completed successfully!"
echo ""
print_warning "Important Information:"
echo "  Database Name: \\$DB_NAME"
echo "  Database User: \\$DB_USER"
echo "  Database Password: \\$DB_PASS"
echo "  Application URL: https://\\$APP_URL"
echo ""
print_warning "Next steps:"
echo "  1. Configure your DNS to point to this server"
echo "  2. Access the application at https://\\$APP_URL"
echo "  3. Login with: admin@cls.com / password"
echo "  4. Review and configure additional settings in the admin panel"
echo ""
print_status "Backup script location: /usr/local/bin/backup-cls.sh"
print_status "Backups run daily at 2 AM and are stored in /var/backups/cls/"
'@ | Out-File -FilePath "$deploymentPath/deploy-ubuntu.sh" -Encoding UTF8

# 5. Environment Configuration Template
@'
APP_NAME="Techiman Customary Lands Secretariat"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cls_management
DB_USERNAME=cls_user
DB_PASSWORD=your_secure_password_here

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@cls.techiman.gov.gh"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# Custom CLS Configuration
CLS_SYSTEM_NAME="Customary Lands Secretariat"
CLS_SYSTEM_VERSION="1.0.0"
CLS_CONTACT_PHONE="0205440495"
CLS_CONTACT_EMAIL="info@gekymedia.com"

# GIS Configuration
MAP_PROVIDER=openstreetmap
DEFAULT_LATITUDE=7.5860
DEFAULT_LONGITUDE=-1.9550
DEFAULT_ZOOM_LEVEL=12

# Notification Configuration
SEND_SMS_NOTIFICATIONS=false
SEND_EMAIL_NOTIFICATIONS=true
SMS_PROVIDER=twilio

# File Upload Configuration
MAX_FILE_SIZE=10240
ALLOWED_FILE_TYPES=pdf,jpg,jpeg,png,doc,docx
'@ | Out-File -FilePath "$deploymentPath/.env.production" -Encoding UTF8

Write-Host "✅ Database seeders, configuration files, and deployment scripts generated successfully!" -ForegroundColor Green
Write-Host "📁 Files created:" -ForegroundColor Cyan
Write-Host "   - database/seeders/DatabaseSeeder.php" -ForegroundColor White
Write-Host "   - config/permission.php" -ForegroundColor White
Write-Host "   - config/excel.php" -ForegroundColor White
Write-Host "   - deployment/deploy-ubuntu.sh" -ForegroundColor White
Write-Host "   - deployment/.env.production" -ForegroundColor White
Write-Host "🚀 Next step: We'll create the final step with testing, documentation, and project summary" -ForegroundColor Yellow