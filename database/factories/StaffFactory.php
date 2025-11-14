<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'employee_id' => 'EMP' . rand(10000, 99999),
            'department' => $this->faker->word,
            'phone' => $this->faker->phoneNumber,
            'assigned_area' => $this->faker->city,
            'date_joined' => $this->faker->date(),
        ];
    }
}