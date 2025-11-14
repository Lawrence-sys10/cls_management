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
