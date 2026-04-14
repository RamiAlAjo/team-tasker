<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    protected $actions = [
        'create_task',
        'update_task',
        'delete_task',
        'create_comment',
        'upload_attachment',
        'login',
        'logout',
        'create_workspace',
        'join_workspace',
        'leave_workspace',
        'update_profile',
        'change_password',
    ];

    protected $subjects = [
        'App\Models\Task',
        'App\Models\TaskComment',
        'App\Models\TaskAttachment',
        'App\Models\Workspace',
        'App\Models\User',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $action = fake()->randomElement($this->actions);

        return [
            'user_id' => User::factory(),
            'action' => $action,
            'subject_id' => fake()->optional(0.8)->randomNumber(5), // 80% chance of having a subject
            'subject_type' => fake()->optional(0.8)->randomElement($this->subjects),
            'description' => $this->getActionDescription($action),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Generate a description based on the action.
     */
    private function getActionDescription(string $action): string
    {
        return match($action) {
            'create_task' => 'Created a new task',
            'update_task' => 'Updated task details',
            'delete_task' => 'Deleted a task',
            'create_comment' => 'Added a comment to task',
            'upload_attachment' => 'Uploaded a file to task',
            'login' => 'Logged into the system',
            'logout' => 'Logged out of the system',
            'create_workspace' => 'Created a new workspace',
            'join_workspace' => 'Joined a workspace',
            'leave_workspace' => 'Left a workspace',
            'update_profile' => 'Updated profile information',
            'change_password' => 'Changed account password',
            default => 'Performed an action',
        };
    }

    /**
     * Create an activity for a specific user.
     */
    public function byUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Create an activity with a specific action.
     */
    public function action(string $action): static
    {
        return $this->state(fn (array $attributes) => [
            'action' => $action,
            'description' => $this->getActionDescription($action),
        ]);
    }

    /**
     * Create a task-related activity.
     */
    public function taskActivity(): static
    {
        $taskActions = ['create_task', 'update_task', 'delete_task', 'create_comment', 'upload_attachment'];

        return $this->state(fn (array $attributes) => [
            'action' => fake()->randomElement($taskActions),
            'subject_type' => 'App\Models\Task',
            'description' => $this->getActionDescription(fake()->randomElement($taskActions)),
        ]);
    }

    /**
     * Create a workspace-related activity.
     */
    public function workspaceActivity(): static
    {
        $workspaceActions = ['create_workspace', 'join_workspace', 'leave_workspace'];

        return $this->state(fn (array $attributes) => [
            'action' => fake()->randomElement($workspaceActions),
            'subject_type' => 'App\Models\Workspace',
            'description' => $this->getActionDescription(fake()->randomElement($workspaceActions)),
        ]);
    }

    /**
     * Create a recent activity.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => fake()->dateTimeBetween('-1 week', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Create an old activity.
     */
    public function old(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => fake()->dateTimeBetween('-6 months', '-1 month'),
            'updated_at' => fake()->dateTimeBetween('-6 months', '-1 month'),
        ]);
    }
}