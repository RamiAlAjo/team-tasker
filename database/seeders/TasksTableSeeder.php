<?php

use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create some sample tasks for testing
        \DB::table('tasks')->insert([
            [
                'title' => 'Complete Laravel API setup',
                'description' => 'Set up RESTful API endpoints for task management',
                'completed' => true,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Implement React frontend',
                'description' => 'Create task list and form components',
                'completed' => false,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Add authentication',
                'description' => 'Implement JWT token-based authentication',
                'completed' => false,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

// Run with: php artisan db:seed --class=TasksTableSeeder