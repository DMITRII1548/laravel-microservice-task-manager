<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'surname' => fake()->word(),
            'patronymic' => fake()->word(),
            'image' => fake()->imageUrl(),
            'age' => random_int(1, 100),
            'user_id' => User::factory()->create()->id,
        ];
    }
}
