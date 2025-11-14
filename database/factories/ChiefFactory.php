<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ChiefFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'jurisdiction' => $this->faker->city,
            'phone' => $this->faker->unique()->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'area_boundaries' => [
                'north' => $this->faker->latitude,
                'south' => $this->faker->latitude,
                'east' => $this->faker->longitude,
                'west' => $this->faker->longitude,
            ],
            'user_id' => User::factory(),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}