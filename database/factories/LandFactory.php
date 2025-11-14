<?php

namespace Database\Factories;

use App\Models\Land;
use App\Models\Chief;
use Illuminate\Database\Eloquent\Factories\Factory;

class LandFactory extends Factory
{
    protected $model = Land::class;

    // In database/factories/LandFactory.php
    public function definition()
    {
        return [
        'plot_number' => 'PLOT' . rand(10000, 99999),
        'location' => $this->faker->address,
        'size' => $this->faker->randomFloat(2, 1, 100),
        'status' => 'available',
        'chief_id' => \App\Models\Chief::factory(),
        // Remove 'coordinates' if it doesn't exist in your database
        // 'coordinates' => $this->faker->latitude . ',' . $this->faker->longitude,
        ];
    }
}