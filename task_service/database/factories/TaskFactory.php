<?php

namespace Database\Factories;

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'content' => fake()->text(1000),
            'tags' => fake()->boolean(70)
                ? fake()->words(random_int(1, 10))
                : null,
        ];
    }
}
