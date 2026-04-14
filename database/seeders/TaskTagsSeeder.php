<?php

use Illuminate\Database\Seeder;

class TaskTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create common task tags with colors
        $tags = [
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
            ['name' => 'Mobile', 'color' => '#7c3aed'],
            ['name' => 'API', 'color' => '#0891b2'],
            ['name' => 'Database', 'color' => '#92400e'],
            ['name' => 'DevOps', 'color' => '#be123c'],
            ['name' => 'Research', 'color' => '#a16207'],
        ];

        foreach ($tags as $tag) {
            \App\Models\TaskTag::factory()->create($tag);
        }
    }
}