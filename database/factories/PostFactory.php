<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title'   => $this->faker->sentence(),
            'content'    => $this->faker->paragraph(5),
            'published' => $this->faker->boolean(80), // 80% chance of being true
        ];
    }
}
