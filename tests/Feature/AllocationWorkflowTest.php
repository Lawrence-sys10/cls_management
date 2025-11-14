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
