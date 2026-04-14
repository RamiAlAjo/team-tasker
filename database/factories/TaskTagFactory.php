<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskTag>
 */
class TaskTagFactory extends Factory
{
    protected $tags = [
        ['name' => 'Frontend', 'color' => '#3b82f6'],
        ['name' => 'Backend', 'color' => '#10b981'],
        ['name' => 'Design', 'color' => '#f59e0b'],
        ['name' => 'Testing', 'color' => '#ef4444'],
        ['name' => 'Documentation', 'color' => '#8b5cf6'],
        ['name' => 'Bug Fix', 'color' => '#dc2626'],
        ['name' => 'Feature', 'color' => '#059669'],
        ['name' => 'Enhancement', 'color' => '#0ea5e9'],
        ['name' => 'Security', 'color' => '#7c2d12'],
        ['name' => 'Performance', 'color' => '#c2410c'],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tag = fake()->randomElement($this->tags);

        return [
            'name' => $tag['name'],
            'color' => $tag['color'],
            'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'updated_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }

    /**
     * Create a specific tag.
     */
    public function tag(string $name): static
    {
        $colors = [
            'Frontend' => '#3b82f6',
            'Backend' => '#10b981',
            'Design' => '#f59e0b',
            'Testing' => '#ef4444',
            'Documentation' => '#8b5cf6',
            'Bug Fix' => '#dc2626',
            'Feature' => '#059669',
            'Enhancement' => '#0ea5e9',
            'Security' => '#7c2d12',
            'Performance' => '#c2410c',
        ];

        return $this->state(fn (array $attributes) => [
            'name' => $name,
            'color' => $colors[$name] ?? '#6366f1',
        ]);
    }

    /**
     * Create a tag with a custom color.
     */
    public function color(string $color): static
    {
        return $this->state(fn (array $attributes) => [
            'color' => $color,
        ]);
    }

    /**
     * Create a random color tag.
     */
    public function randomColor(): static
    {
        return $this->state(fn (array $attributes) => [
            'color' => '#' . fake()->hexColor(),
        ]);
    }
}