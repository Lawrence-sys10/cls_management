<?php

namespace Database\Factories;

use App\Models\Allocation;
use App\Models\Land;
use App\Models\Client;
use App\Models\User;
use App\Models\Chief;
use Illuminate\Database\Eloquent\Factories\Factory;

class AllocationFactory extends Factory
{
    protected $model = Allocation::class;

    public function definition()
    {
        return [
            'land_id' => Land::factory(),
            'client_id' => Client::factory(),
            'chief_id' => Chief::factory(),
            'processed_by' => User::factory(),
            'allocation_date' => $this->faker->date(),
            'purpose' => $this->faker->sentence,
            'approval_status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'payment_status' => $this->faker->randomElement(['pending', 'paid', 'partial']),
            'payment_amount' => $this->faker->randomFloat(2, 100, 10000),
            'notes' => $this->faker->optional()->paragraph,
            'is_finalized' => $this->faker->boolean,
        ];
    }
}