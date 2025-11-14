<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class StaffFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'department' => $this->faker->word,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}