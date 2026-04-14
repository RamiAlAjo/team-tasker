<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskDependency extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'dependency_task_id',
        'dependency_type',
    ];

    /**
     * Get the task that depends on another task.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the task that is being depended on.
     */
    public function dependencyTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'dependency_task_id');
    }

    /**
     * Check if dependency is blocking (task cannot proceed until dependency is complete)
     */
    public function isBlocking(): bool
    {
        return $this->dependency_type === 'blocking';
    }

    /**
     * Get dependency type label
     */
    public function getDependencyTypeLabel(): string
    {
        return match($this->dependency_type) {
            'blocking' => 'Blocks this task',
            'blocking_reverse' => 'Blocked by this task',
            default => 'Unknown'
        };
    }
}