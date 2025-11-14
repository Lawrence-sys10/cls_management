<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        $idTypes = ['ghanacard', 'passport', 'drivers_license', 'voters_id'];
        $genders = ['male', 'female', 'other'];

        return [
            'full_name' => $this->faker->name,
            'phone' => $this->faker->unique()->numerify('+233########'),
            'email' => $this->faker->unique()->safeEmail,
            'id_type' => $this->faker->randomElement($idTypes),
            'id_number' => $this->faker->unique()->numerify('GHA##########'),
            'address' => $this->faker->address,
            'occupation' => $this->faker->jobTitle,
            'date_of_birth' => $this->faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
            'gender' => $this->faker->randomElement($genders),
            'emergency_contact' => $this->faker->optional()->numerify('+233########'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function ghanaCard(): static
    {
        return $this->state(fn (array $attributes) => [
            'id_type' => 'ghanacard',
            'id_number' => 'GHA' . $this->faker->unique()->numerify('#########'),
        ]);
    }

    public function passport(): static
    {
        return $this->state(fn (array $attributes) => [
            'id_type' => 'passport',
            'id_number' => $this->faker->unique()->bothify('G#######'),
        ]);
    }
}