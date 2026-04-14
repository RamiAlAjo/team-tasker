<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TaskStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskStatus>
 */
class TaskStatusFactory extends Factory
{
    protected $model = TaskStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = [
            ['name' => 'todo', 'color' => '#94a3b8', 'order' => 0],
            ['name' => 'in_progress', 'color' => '#f59e0b', 'order' => 1],
            ['name' => 'review', 'color' => '#f43f5e', 'order' => 2],
            ['name' => 'done', 'color' => '#10b981', 'order' => 3],
        ];

        $status = fake()->randomElement($statuses);

        return [
            'name' => $status['name'],
            'color' => $status['color'],
            'order' => $status['order'],
        ];
    }

    /**
     * Create a specific status.
     */
    public function status(string $name): static
    {
        $colors = [
            'todo' => '#94a3b8',
            'in_progress' => '#f59e0b',
            'review' => '#f43f5e',
            'done' => '#10b981',
        ];

        $orders = [
            'todo' => 0,
            'in_progress' => 1,
            'review' => 2,
            'done' => 3,
        ];

        return $this->state(fn (array $attributes) => [
            'name' => $name,
            'color' => $colors[$name] ?? '#6366f1',
            'order' => $orders[$name] ?? 0,
        ]);
    }
}