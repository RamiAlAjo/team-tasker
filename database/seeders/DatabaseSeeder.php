<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create task statuses first (needed for tasks)
        \DB::table('task_statuses')->insert([
            ['name' => 'todo', 'color' => '#94a3b8', 'order' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'in_progress', 'color' => '#f59e0b', 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'review', 'color' => '#f43f5e', 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'done', 'color' => '#10b981', 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Create users
        $john = \App\Models\User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $jane = \App\Models\User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        // Create workspace
        $workspace = \App\Models\Workspace::factory()->create([
            'name' => 'Development Team',
            'description' => 'Main development workspace',
            'owner_id' => $john->id,
        ]);

        // Add workspace members
        $workspace->members()->attach([
            $john->id => ['role' => 'owner'],
            $jane->id => ['role' => 'member'],
        ]);

        // Create task tags
        $frontendTag = \App\Models\TaskTag::factory()->create(['name' => 'Frontend', 'color' => '#3b82f6']);
        $backendTag = \App\Models\TaskTag::factory()->create(['name' => 'Backend', 'color' => '#10b981']);
        $designTag = \App\Models\TaskTag::factory()->create(['name' => 'Design', 'color' => '#f59e0b']);
        $testingTag = \App\Models\TaskTag::factory()->create(['name' => 'Testing', 'color' => '#ef4444']);

        // Create tasks
        $apiTask = \App\Models\Task::factory()->create([
            'title' => 'Complete Laravel API setup',
            'description' => 'Set up RESTful API endpoints for task management',
            'completed' => true,
            'status_id' => 4, // done
            'user_id' => $john->id,
            'due_date' => now()->addDays(3),
            'priority' => 2,
        ]);

        $frontendTask = \App\Models\Task::factory()->create([
            'title' => 'Implement React frontend',
            'description' => 'Create task list and form components',
            'completed' => false,
            'status_id' => 2, // in_progress
            'user_id' => $john->id,
            'due_date' => now()->addDays(7),
            'priority' => 3,
        ]);

        $authTask = \App\Models\Task::factory()->create([
            'title' => 'Add authentication',
            'description' => 'Implement JWT token-based authentication',
            'completed' => false,
            'status_id' => 1, // todo
            'user_id' => $john->id,
            'due_date' => now()->addDays(5),
            'priority' => 2,
        ]);

        $designTask = \App\Models\Task::factory()->create([
            'title' => 'Design UI components',
            'description' => 'Create responsive UI components for the dashboard',
            'completed' => false,
            'status_id' => 3, // review
            'user_id' => $jane->id,
            'due_date' => now()->addDays(10),
            'priority' => 1,
        ]);

        // Attach tags to tasks
        $apiTask->tags()->attach($backendTag);
        $frontendTask->tags()->attach($frontendTag);
        $authTask->tags()->attach($backendTag);
        $designTask->tags()->attach($designTag);

        // Create task comments
        \App\Models\TaskComment::factory()->create([
            'task_id' => $apiTask->id,
            'user_id' => $john->id,
            'content' => 'API endpoints are working correctly!',
        ]);

        \App\Models\TaskComment::factory()->create([
            'task_id' => $frontendTask->id,
            'user_id' => $jane->id,
            'content' => 'Frontend components look great. Need to add some animations.',
        ]);

        // Create subtasks for frontend task
        \App\Models\TaskSubtask::factory()->create([
            'task_id' => $frontendTask->id,
            'title' => 'Create task list component',
            'completed' => true,
        ]);

        \App\Models\TaskSubtask::factory()->create([
            'task_id' => $frontendTask->id,
            'title' => 'Create task form component',
            'completed' => true,
        ]);

        \App\Models\TaskSubtask::factory()->create([
            'task_id' => $frontendTask->id,
            'title' => 'Add drag and drop functionality',
            'completed' => false,
        ]);

        // Create some additional sample data
        \App\Models\User::factory(3)->create();
        \App\Models\Task::factory(10)->create();
        \App\Models\TaskComment::factory(15)->create();
        \App\Models\TaskAttachment::factory(8)->create();
        \App\Models\TaskSubtask::factory(20)->create();
        \App\Models\Activity::factory(25)->create();
    }
}
