<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'amount'      => $this->faker->randomFloat(2, 10, 500),
            'status'      => $this->faker->randomElement(['pending', 'paid', 'failed', 'cancelled']),
        ];
    }
}
