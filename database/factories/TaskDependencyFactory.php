<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskDependency>
 */
class TaskDependencyFactory extends Factory
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
            'dependency_task_id' => Task::factory(),
            'dependency_type' => fake()->randomElement(['blocking', 'blocking_reverse']),
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Create a dependency between specific tasks.
     */
    public function betweenTasks(Task $task, Task $dependencyTask): static
    {
        return $this->state(fn (array $attributes) => [
            'task_id' => $task->id,
            'dependency_task_id' => $dependencyTask->id,
        ]);
    }

    /**
     * Create a blocking dependency.
     */
    public function blocking(): static
    {
        return $this->state(fn (array $attributes) => [
            'dependency_type' => 'blocking',
        ]);
    }

    /**
     * Create a reverse blocking dependency.
     */
    public function blockingReverse(): static
    {
        return $this->state(fn (array $attributes) => [
            'dependency_type' => 'blocking_reverse',
        ]);
    }
}