<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AllocationFactory extends Factory
{
    public function definition()
    {
        return [
            'land_id' => \App\Models\Land::factory(),
            'client_id' => \App\Models\Client::factory(),
            'chief_id' => \App\Models\Chief::factory(),
            'processed_by' => \App\Models\Staff::factory(), // Use Staff factory instead of User
            'allocation_date' => $this->faker->date(),
            'approval_status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'purpose' => $this->faker->sentence,
            'chief_approval_date' => $this->faker->optional()->date(),
            'registrar_approval_date' => $this->faker->optional()->date(),
            'allocation_letter_path' => $this->faker->optional()->filePath(),
            'payment_status' => $this->faker->randomElement(['pending', 'paid', 'partial']),
            'payment_amount' => $this->faker->randomFloat(2, 1000, 50000),
            'payment_date' => $this->faker->optional()->date(),
            'notes' => $this->faker->optional()->text,
            'is_finalized' => $this->faker->boolean,
        ];
    }

    // Add states for different approval statuses
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'approval_status' => 'pending',
                'chief_approval_date' => null,
                'registrar_approval_date' => null,
            ];
        });
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'approval_status' => 'approved',
                'chief_approval_date' => $this->faker->date(),
            ];
        });
    }

    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'approval_status' => 'rejected',
                'chief_approval_date' => $this->faker->date(),
            ];
        });
    }

    public function finalized()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_finalized' => true,
                'registrar_approval_date' => $this->faker->date(),
            ];
        });
    }
}