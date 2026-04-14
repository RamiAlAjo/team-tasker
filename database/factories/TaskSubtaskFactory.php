<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskSubtask>
 */
class TaskSubtaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'title' => fake()->sentence(3),
            'completed' => fake()->boolean(30), // 30% chance of being completed
            'created_at' => fake()->dateTimeBetween('-2 weeks', 'now'),
            'updated_at' => fake()->dateTimeBetween('-2 weeks', 'now'),
        ];
    }

    /**
     * Create a subtask for a specific task.
     */
    public function forTask(Task $task): static
    {
        return $this->state(fn (array $attributes) => [
            'task_id' => $task->id,
        ]);
    }

    /**
     * Indicate that the subtask is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed' => true,
        ]);
    }

    /**
     * Indicate that the subtask is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed' => false,
        ]);
    }

    /**
     * Create a short subtask title.
     */
    public function short(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => fake()->words(2, true),
        ]);
    }

    /**
     * Create a long subtask title.
     */
    public function long(): static
    {
        return $this->state(fn (array $attributes) => [
            'title' => fake()->sentence(6),
        ]);
    }
}