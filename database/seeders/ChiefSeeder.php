<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chief;
use App\Models\User;

class ChiefSeeder extends Seeder
{
    public function run(): void
    {
        // Create chief users
        $chiefUsers = User::factory(5)->create();

        foreach ($chiefUsers as $user) {
            Chief::factory()->create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
            
            // Assign chief role
            $user->assignRole('chief');
        }

        // Or create specific chiefs
        Chief::factory()->create([
            'name' => 'Chief Administrator',
            'jurisdiction' => 'Central District',
            'phone' => '+255123456789',
            'email' => 'chief@example.com',
            'user_id' => User::factory()->create(['email' => 'chief@example.com'])->id,
            'is_active' => true,
        ]);
    }
}