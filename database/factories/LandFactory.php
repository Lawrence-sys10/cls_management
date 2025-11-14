<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LandFactory extends Factory
{
    public function definition()
    {
        return [
            'plot_number' => 'PLOT' . rand(10000, 99999),
            'area_acres' => $this->faker->randomFloat(2, 1, 100),
            'area_hectares' => $this->faker->randomFloat(2, 1, 100),
            'location' => $this->faker->address,
            'status' => $this->faker->randomElement(['available', 'allocated', 'reserved']),
            'size' => $this->faker->randomFloat(2, 1, 100),
            'boundary_description' => $this->faker->text(200),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'polygon_boundaries' => $this->faker->optional()->text,
            'ownership_status' => $this->faker->randomElement(['vacant', 'occupied', 'disputed']),
            'chief_id' => \App\Models\Chief::factory(),
            'price' => $this->faker->randomFloat(2, 1000, 100000),
            'land_use' => $this->faker->randomElement(['agricultural', 'residential', 'commercial']),
            'soil_type' => $this->faker->word,
            'topography' => $this->faker->word,
            'access_roads' => $this->faker->boolean,
            'utilities' => $this->faker->boolean,
            'registration_date' => $this->faker->date(),
            'is_verified' => $this->faker->boolean,
        ];
    }
}