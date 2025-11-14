# Step 10: Generate Testing Files, Documentation and Project Summary
# Save this as generate-testing-docs.ps1 and run from project root

# Create necessary directories
$testsPath = "tests"
$featurePath = "$testsPath/Feature"
$unitPath = "$testsPath/Unit"
$docsPath = "docs"
$resourcesPath = "resources"

@($testsPath, $featurePath, $unitPath, $docsPath) | ForEach-Object {
    if (!(Test-Path $_)) {
        New-Item -ItemType Directory -Path $_ -Force | Out-Null
    }
}

Write-Host "Creating test files and documentation..." -ForegroundColor Green

# 1. Land Management Test
$landManagementTest = @'
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Land;
use App\Models\Chief;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class LandManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $staff;
    protected $chiefUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $staffRole = Role::create(['name' => 'staff']);
        $chiefRole = Role::create(['name' => 'chief']);

        // Create users
        $this->admin = User::factory()->create();
        $this->admin->assignRole($adminRole);

        $this->staff = User::factory()->create();
        $this->staff->assignRole($staffRole);

        $this->chiefUser = User::factory()->create();
        $this->chiefUser->assignRole($chiefRole);

        // Create chief
        $this->chief = Chief::factory()->create(['user_id' => $this->chiefUser->id]);
    }

    /** @test */
    public function admin_can_view_lands_index()
    {
        $response = $this->actingAs($this->admin)->get('/lands');

        $response->assertStatus(200);
        $response->assertSee('Land Management');
    }

    /** @test */
    public function staff_can_view_lands_index()
    {
        $response = $this->actingAs($this->staff)->get('/lands');

        $response->assertStatus(200);
        $response->assertSee('Land Management');
    }

    /** @test */
    public function chief_cannot_view_lands_index()
    {
        $response = $this->actingAs($this->chiefUser)->get('/lands');

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_create_land()
    {
        $landData = [
            'plot_number' => 'TCH/TEST/001',
            'area_acres' => 2.5,
            'area_hectares' => 1.01,
            'location' => 'Test Location',
            'ownership_status' => 'vacant',
            'chief_id' => $this->chief->id,
            'land_use' => 'residential',
            'registration_date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->admin)
            ->post('/lands', $landData);

        $response->assertRedirect();
        $this->assertDatabaseHas('lands', ['plot_number' => 'TCH/TEST/001']);
    }

    /** @test */
    public function staff_can_create_land()
    {
        $landData = [
            'plot_number' => 'TCH/TEST/002',
            'area_acres' => 3.0,
            'area_hectares' => 1.21,
            'location' => 'Staff Test Location',
            'ownership_status' => 'vacant',
            'chief_id' => $this->chief->id,
            'land_use' => 'commercial',
            'registration_date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->staff)
            ->post('/lands', $landData);

        $response->assertRedirect();
        $this->assertDatabaseHas('lands', ['plot_number' => 'TCH/TEST/002']);
    }

    /** @test */
    public function land_requires_unique_plot_number()
    {
        Land::factory()->create(['plot_number' => 'EXISTING_PLOT']);

        $landData = [
            'plot_number' => 'EXISTING_PLOT', // Duplicate
            'area_acres' => 2.5,
            'area_hectares' => 1.01,
            'location' => 'Test Location',
            'ownership_status' => 'vacant',
            'chief_id' => $this->chief->id,
            'land_use' => 'residential',
            'registration_date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->admin)
            ->post('/lands', $landData);

        $response->assertSessionHasErrors('plot_number');
    }

    /** @test */
    public function admin_can_update_land()
    {
        $land = Land::factory()->create(['chief_id' => $this->chief->id]);

        $updateData = [
            'plot_number' => $land->plot_number, // Keep same plot number
            'area_acres' => 5.0,
            'area_hectares' => 2.02,
            'location' => 'Updated Location',
            'ownership_status' => 'allocated',
            'chief_id' => $this->chief->id,
            'land_use' => 'commercial',
            'registration_date' => $land->registration_date->format('Y-m-d'),
        ];

        $response = $this->actingAs($this->admin)
            ->put("/lands/{$land->id}", $updateData);

        $response->assertRedirect();
        $this->assertDatabaseHas('lands', [
            'id' => $land->id,
            'location' => 'Updated Location',
            'area_acres' => 5.0
        ]);
    }

    /** @test */
    public function admin_can_delete_land()
    {
        $land = Land::factory()->create(['chief_id' => $this->chief->id]);

        $response = $this->actingAs($this->admin)
            ->delete("/lands/{$land->id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('lands', ['id' => $land->id]);
    }

    /** @test */
    public function staff_cannot_delete_land()
    {
        $land = Land::factory()->create(['chief_id' => $this->chief->id]);

        $response = $this->actingAs($this->staff)
            ->delete("/lands/{$land->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function land_export_works()
    {
        Land::factory()->count(5)->create(['chief_id' => $this->chief->id]);

        $response = $this->actingAs($this->admin)
            ->get('/lands/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** @test */
    public function land_geojson_api_returns_data()
    {
        Land::factory()->create([
            'chief_id' => $this->chief->id,
            'latitude' => 7.5860,
            'longitude' => -1.9550
        ]);

        $response = $this->get('/api/lands/geojson');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'type',
            'features' => [
                '*' => [
                    'type',
                    'geometry',
                    'properties'
                ]
            ]
        ]);
    }
}
'@

$landManagementTest | Out-File -FilePath "$featurePath/LandManagementTest.php" -Encoding UTF8
Write-Host "✓ Created LandManagementTest.php" -ForegroundColor Green

# 2. Client Management Test
$clientManagementTest = @'
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $staff;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::create(['name' => 'admin']);
        $staffRole = Role::create(['name' => 'staff']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole($adminRole);

        $this->staff = User::factory()->create();
        $this->staff->assignRole($staffRole);
    }

    /** @test */
    public function admin_can_view_clients_index()
    {
        Client::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get('/clients');

        $response->assertStatus(200);
        $response->assertSee('Client Management');
    }

    /** @test */
    public function staff_can_create_client()
    {
        $clientData = [
            'full_name' => 'Test Client',
            'phone' => '0241234567',
            'email' => 'test@example.com',
            'id_type' => 'ghanacard',
            'id_number' => 'GHA-123456789-0',
            'address' => 'Test Address, Techiman',
            'occupation' => 'Test Occupation',
        ];

        $response = $this->actingAs($this->staff)
            ->post('/clients', $clientData);

        $response->assertRedirect();
        $this->assertDatabaseHas('clients', ['full_name' => 'Test Client']);
    }

    /** @test */
    public function client_requires_unique_phone_and_id_number()
    {
        Client::factory()->create([
            'phone' => '0241111111',
            'id_number' => 'EXISTING_ID'
        ]);

        $clientData = [
            'full_name' => 'Test Client',
            'phone' => '0241111111', // Duplicate
            'email' => 'test@example.com',
            'id_type' => 'ghanacard',
            'id_number' => 'EXISTING_ID', // Duplicate
            'address' => 'Test Address',
            'occupation' => 'Test Occupation',
        ];

        $response = $this->actingAs($this->admin)
            ->post('/clients', $clientData);

        $response->assertSessionHasErrors(['phone', 'id_number']);
    }

    /** @test */
    public function client_export_works()
    {
        Client::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)
            ->get('/clients/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
'@

$clientManagementTest | Out-File -FilePath "$featurePath/ClientManagementTest.php" -Encoding UTF8
Write-Host "✓ Created ClientManagementTest.php" -ForegroundColor Green

# 3. Allocation Workflow Test
$allocationWorkflowTest = @'
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Land;
use App\Models\Client;
use App\Models\Chief;
use App\Models\Allocation;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class AllocationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $staff;
    protected $chiefUser;
    protected $land;
    protected $client;
    protected $chief;
    protected $staffMember;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $staffRole = Role::create(['name' => 'staff']);
        $chiefRole = Role::create(['name' => 'chief']);

        // Create users
        $this->admin = User::factory()->create();
        $this->admin->assignRole($adminRole);

        $this->staff = User::factory()->create();
        $this->staff->assignRole($staffRole);

        $this->chiefUser = User::factory()->create();
        $this->chiefUser->assignRole($chiefRole);

        // Create entities
        $this->chief = Chief::factory()->create(['user_id' => $this->chiefUser->id]);
        $this->land = Land::factory()->create([
            'chief_id' => $this->chief->id,
            'ownership_status' => 'vacant'
        ]);
        $this->client = Client::factory()->create();
        
        $this->staffMember = Staff::factory()->create(['user_id' => $this->staff->id]);
    }

    /** @test */
    public function staff_can_create_allocation()
    {
        $allocationData = [
            'land_id' => $this->land->id,
            'client_id' => $this->client->id,
            'chief_id' => $this->chief->id,
            'processed_by' => $this->staffMember->id,
            'allocation_date' => now()->format('Y-m-d'),
            'approval_status' => 'pending',
            'payment_status' => 'pending',
        ];

        $response = $this->actingAs($this->staff)
            ->post('/allocations', $allocationData);

        $response->assertRedirect();
        $this->assertDatabaseHas('allocations', [
            'land_id' => $this->land->id,
            'client_id' => $this->client->id
        ]);

        // Verify land status updated
        $this->assertDatabaseHas('lands', [
            'id' => $this->land->id,
            'ownership_status' => 'allocated'
        ]);
    }

    /** @test */
    public function cannot_allocate_already_allocated_land()
    {
        // Create an existing allocation for the land
        Allocation::factory()->create([
            'land_id' => $this->land->id,
            'approval_status' => 'approved'
        ]);

        $allocationData = [
            'land_id' => $this->land->id, // Already allocated
            'client_id' => $this->client->id,
            'chief_id' => $this->chief->id,
            'processed_by' => $this->staffMember->id,
            'allocation_date' => now()->format('Y-m-d'),
            'approval_status' => 'pending',
            'payment_status' => 'pending',
        ];

        $response = $this->actingAs($this->staff)
            ->post('/allocations', $allocationData);

        $response->assertSessionHasErrors('land_id');
    }

    /** @test */
    public function chief_can_approve_allocation()
    {
        $allocation = Allocation::factory()->create([
            'land_id' => $this->land->id,
            'client_id' => $this->client->id,
            'chief_id' => $this->chief->id,
            'approval_status' => 'pending'
        ]);

        $response = $this->actingAs($this->chiefUser)
            ->post("/allocations/{$allocation->id}/approve");

        $response->assertRedirect();
        $this->assertDatabaseHas('allocations', [
            'id' => $allocation->id,
            'approval_status' => 'approved',
            'chief_approval_date' => now()
        ]);
    }

    /** @test */
    public function chief_can_reject_allocation()
    {
        $allocation = Allocation::factory()->create([
            'land_id' => $this->land->id,
            'client_id' => $this->client->id,
            'chief_id' => $this->chief->id,
            'approval_status' => 'pending'
        ]);

        $response = $this->actingAs($this->chiefUser)
            ->post("/allocations/{$allocation->id}/reject");

        $response->assertRedirect();
        $this->assertDatabaseHas('allocations', [
            'id' => $allocation->id,
            'approval_status' => 'rejected'
        ]);

        // Verify land status reset to vacant
        $this->assertDatabaseHas('lands', [
            'id' => $this->land->id,
            'ownership_status' => 'vacant'
        ]);
    }

    /** @test */
    public function allocation_letter_can_be_generated()
    {
        $allocation = Allocation::factory()->create([
            'land_id' => $this->land->id,
            'client_id' => $this->client->id,
            'chief_id' => $this->chief->id,
            'approval_status' => 'approved'
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/allocations/{$allocation->id}/allocation-letter");

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
'@

$allocationWorkflowTest | Out-File -FilePath "$featurePath/AllocationWorkflowTest.php" -Encoding UTF8
Write-Host "✓ Created AllocationWorkflowTest.php" -ForegroundColor Green

# 4. Model Unit Tests
$modelRelationshipsTest = @'
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Land;
use App\Models\Client;
use App\Models\Allocation;
use App\Models\Chief;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function land_belongs_to_chief()
    {
        $chief = Chief::factory()->create();
        $land = Land::factory()->create(['chief_id' => $chief->id]);

        $this->assertInstanceOf(Chief::class, $land->chief);
        $this->assertEquals($chief->id, $land->chief->id);
    }

    /** @test */
    public function land_has_one_allocation()
    {
        $land = Land::factory()->create();
        $allocation = Allocation::factory()->create(['land_id' => $land->id]);

        $this->assertInstanceOf(Allocation::class, $land->allocation);
        $this->assertEquals($allocation->id, $land->allocation->id);
    }

    /** @test */
    public function client_has_many_allocations()
    {
        $client = Client::factory()->create();
        $allocations = Allocation::factory()->count(3)->create(['client_id' => $client->id]);

        $this->assertCount(3, $client->allocations);
        $this->assertInstanceOf(Allocation::class, $client->allocations->first());
    }

    /** @test */
    public function allocation_belongs_to_land_and_client()
    {
        $land = Land::factory()->create();
        $client = Client::factory()->create();
        $allocation = Allocation::factory()->create([
            'land_id' => $land->id,
            'client_id' => $client->id
        ]);

        $this->assertInstanceOf(Land::class, $allocation->land);
        $this->assertInstanceOf(Client::class, $allocation->client);
        $this->assertEquals($land->id, $allocation->land->id);
        $this->assertEquals($client->id, $allocation->client->id);
    }

    /** @test */
    public function chief_has_many_lands()
    {
        $chief = Chief::factory()->create();
        $lands = Land::factory()->count(2)->create(['chief_id' => $chief->id]);

        $this->assertCount(2, $chief->lands);
        $this->assertInstanceOf(Land::class, $chief->lands->first());
    }

    /** @test */
    public function user_can_have_staff_record()
    {
        $user = User::factory()->create();
        $staff = \App\Models\Staff::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(\App\Models\Staff::class, $user->staff);
        $this->assertEquals($staff->id, $user->staff->id);
    }

    /** @test */
    public function land_ownership_status_scopes_work()
    {
        Land::factory()->create(['ownership_status' => 'vacant']);
        Land::factory()->create(['ownership_status' => 'allocated']);
        Land::factory()->create(['ownership_status' => 'under_dispute']);

        $this->assertCount(1, Land::vacant()->get());
        $this->assertCount(1, Land::allocated()->get());
        $this->assertCount(1, Land::underDispute()->get());
    }

    /** @test */
    public function allocation_approval_status_scopes_work()
    {
        Allocation::factory()->create(['approval_status' => 'pending']);
        Allocation::factory()->create(['approval_status' => 'approved']);
        Allocation::factory()->create(['approval_status' => 'rejected']);

        $this->assertCount(1, Allocation::pending()->get());
        $this->assertCount(1, Allocation::approved()->get());
        $this->assertCount(1, Allocation::rejected()->get());
    }
}
'@

$modelRelationshipsTest | Out-File -FilePath "$unitPath/ModelRelationshipsTest.php" -Encoding UTF8
Write-Host "✓ Created ModelRelationshipsTest.php" -ForegroundColor Green

# 5. Project Documentation
$projectDocumentation = @'
# Techiman Customary Lands Secretariat (CLS) Management System

## 📋 Project Overview

A comprehensive web-based platform for managing customary land administration in the Techiman Traditional Council area. The system digitizes land registration, allocation, and management processes with GIS integration and advanced reporting capabilities.

**Developed by:** Geky Dev - Directorate under GEKY MEDIA GHANA  
**Contact:** 0205440495 | info@gekymedia.com

## 🎯 System Features

### Core Modules
- **Land Management** - Plot registration with GIS coordinates
- **Client Management** - Applicant registration and verification
- **Allocation Workflow** - Digital approval process with chiefs
- **Chief Management** - Chief jurisdiction and approval system
- **Document Management** - Secure file storage and verification
- **Reporting & Analytics** - Comprehensive reports and dashboards
- **GIS Integration** - Interactive maps and spatial data

### Advanced Features
- Role-based access control (5 user roles)
- Excel import/export functionality
- PDF certificate generation
- SMS and email notifications
- Audit trail and activity logging
- Mobile-responsive design
- Production-ready deployment

## 🛠 Technical Stack

### Backend
- **Framework:** Laravel 11.x
- **Database:** MySQL 8.0+
- **Authentication:** Laravel Breeze
- **PDF Generation:** DomPDF
- **Excel Handling:** Laravel Excel

### Frontend
- **Styling:** Tailwind CSS
- **Icons:** Font Awesome
- **Maps:** Leaflet.js
- **Tables:** DataTables
- **Interactivity:** Alpine.js

### Key Packages
- `spatie/laravel-permission` - Role management
- `maatwebsite/excel` - Excel import/export
- `barryvdh/laravel-dompdf` - PDF generation
- `laravel/breeze` - Authentication

## 🗄 Database Schema

### Core Tables
- `users` - System users and authentication
- `roles`, `permissions` - Access control
- `chiefs` - Traditional chiefs and jurisdictions
- `staff` - Secretariat staff members
- `clients` - Land applicants
- `lands` - Land plots with spatial data
- `allocations` - Land allocation records
- `documents` - File uploads and management
- `activity_logs` - Audit trail
- `reports` - Generated reports

## 👥 User Roles

| Role | Description | Permissions |
|------|-------------|-------------|
| **Admin** | System administrator | Full system access |
| **Chief** | Traditional authority | View and approve allocations |
| **Staff** | Secretariat personnel | Data entry and management |
| **Registrar** | Approval authority | Finalize allocations |
| **Viewer** | Read-only access | View data and reports |

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.1+
- MySQL 8.0+
- Composer
- Node.js & NPM

### Development Setup
1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env`
4. Generate application key: `php artisan key:generate`
5. Configure database in `.env`
6. Run migrations: `php artisan migrate`
7. Seed database: `php artisan db:seed`
8. Install frontend dependencies: `npm install && npm run build`
9. Start development server: `php artisan serve`

### Production Deployment
Use the provided deployment script:
```bash
chmod +x deployment/deploy-ubuntu.sh
sudo ./deployment/deploy-ubuntu.sh
'@