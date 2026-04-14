<?php

use Illuminate\Database\Seeder;

class TaskStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default task statuses for Kanban board
        \App\Models\TaskStatus::factory()->createMany([
            ['name' => 'todo', 'color' => '#94a3b8', 'order' => 0],
            ['name' => 'in_progress', 'color' => '#f59e0b', 'order' => 1],
            ['name' => 'review', 'color' => '#f43f5e', 'order' => 2],
            ['name' => 'done', 'color' => '#10b981', 'order' => 3],
        ]);
    }
}