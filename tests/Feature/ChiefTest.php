<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Chief;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChiefTest extends TestCase
{
    use RefreshDatabase;

    public function test_chief_can_be_created(): void
    {
        $chief = Chief::factory()->create();

        $this->assertDatabaseHas('chiefs', [
            'id' => $chief->id,
            'name' => $chief->name,
            'jurisdiction' => $chief->jurisdiction,
            'is_active' => true,
        ]);
    }

    public function test_chief_has_user_relationship(): void
    {
        $chief = Chief::factory()->create();

        $this->assertInstanceOf(User::class, $chief->user);
    }

    public function test_chief_can_have_lands(): void
    {
        $chief = Chief::factory()->create();

        $this->assertCount(0, $chief->lands);
    }

    public function test_chief_can_be_inactive(): void
    {
        $chief = Chief::factory()->inactive()->create();

        $this->assertFalse($chief->is_active);
    }
}