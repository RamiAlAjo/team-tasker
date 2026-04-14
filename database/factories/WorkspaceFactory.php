<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workspace>
 */
class WorkspaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' ' . fake()->randomElement(['Team', 'Project', 'Workspace', 'Group']),
            'description' => fake()->optional(0.8)->sentence(),
            'owner_id' => User::factory(),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'updated_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Create a workspace owned by a specific user.
     */
    public function ownedBy(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'owner_id' => $user->id,
        ]);
    }

    /**
     * Create a workspace with a specific name.
     */
    public function named(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
        ]);
    }

    /**
     * Create a development workspace.
     */
    public function development(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Development Team',
            'description' => 'Main development workspace for coding and collaboration',
        ]);
    }

    /**
     * Create a design workspace.
     */
    public function design(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Design Studio',
            'description' => 'Creative workspace for UI/UX design and prototyping',
        ]);
    }

    /**
     * Create a marketing workspace.
     */
    public function marketing(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Marketing Team',
            'description' => 'Content creation and campaign management workspace',
        ]);
    }
}