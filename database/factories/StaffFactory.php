<?php

namespace Database\Factories;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    // In database/factories/StaffFactory.php
public function definition()
{
    return [
        'user_id' => \App\Models\User::factory(),
        'employee_id' => 'EMP' . rand(10000, 99999),
        'department' => $this->faker->word,
        'phone' => $this->faker->phoneNumber,
        'date_joined' => $this->faker->date(), // Add this required field
        'assigned_area' => $this->faker->city,
    ];
}
}