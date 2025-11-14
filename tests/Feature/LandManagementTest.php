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
