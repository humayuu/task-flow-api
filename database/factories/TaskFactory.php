<?php

namespace Database\Factories;

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
            'user_id' => fake()->numberBetween(1, 99),
            'title' => fake()->text(20),
            'description' => fake()->text(30),
            'status' => fake()->randomElement(['pending', 'completed', 'in_progress']),
            'priority' => fake()->randomElement(['low', 'high', 'medium']),
            'due_date' => fake()->date('Y-m-d'),
        ];
    }
}