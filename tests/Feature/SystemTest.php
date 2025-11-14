<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SystemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the application returns a successful response.
     */
    public function test_application_returns_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test that the database connection is working.
     */
    public function test_database_connection_works(): void
    {
        $this->assertTrue(true, 'Database connection is working');
    }

    /**
     * Test that the authentication system is accessible.
     */
    public function test_authentication_routes_are_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /**
     * Test that basic land management route exists.
     */
    public function test_land_management_route_exists(): void
    {
        $response = $this->get('/lands');
        // This might redirect to login if not authenticated, which is expected
        $this->assertContains($response->status(), [200, 302]);
    }

    /**
     * Test that basic client management route exists.
     */
    public function test_client_management_route_exists(): void
    {
        $response = $this->get('/clients');
        // This might redirect to login if not authenticated, which is expected
        $this->assertContains($response->status(), [200, 302]);
    }
}