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
            'latitude' => $this->faker->randomFloat(8, -90, 90), // Fixed: Use randomFloat with proper range
            'longitude' => $this->faker->randomFloat(8, -99.99999999, 99.99999999), // Fixed: longitude column is decimal(10,8)
            'polygon_boundaries' => $this->faker->optional()->text,
            'ownership_status' => 'vacant', // Fixed: Use exact ENUM value (lowercase)
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

    // Use the exact ENUM values that work
    public function vacant()
    {
        return $this->state(function (array $attributes) {
            return [
                'ownership_status' => 'vacant', // Fixed: lowercase
            ];
        });
    }

    public function allocated()
    {
        return $this->state(function (array $attributes) {
            return [
                'ownership_status' => 'allocated',
            ];
        });
    }

    public function underDispute()
    {
        return $this->state(function (array $attributes) {
            return [
                'ownership_status' => 'under_dispute', // Fixed: exact ENUM value
            ];
        });
    }

    public function reserved()
    {
        return $this->state(function (array $attributes) {
            return [
                'ownership_status' => 'reserved', // Fixed: exact ENUM value
            ];
        });
    }
}