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
   public function test_client_export_works()
    {
    // Create some test clients
    Client::factory()->count(3)->create();

    $response = $this->actingAs($this->admin)
        ->get('/clients/export');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}
