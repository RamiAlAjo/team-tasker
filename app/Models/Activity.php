<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'subject_id',
        'subject_type',
        'description',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the user that performed the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject of the activity (polymorphic).
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Get action label
     */
    public function getActionLabel(): string
    {
        return match($this->action) {
            'create_task' => 'Created task',
            'update_task' => 'Updated task',
            'delete_task' => 'Deleted task',
            'create_comment' => 'Added comment',
            'upload_attachment' => 'Uploaded file',
            'login' => 'Logged in',
            'logout' => 'Logged out',
            'create_workspace' => 'Created workspace',
            'join_workspace' => 'Joined workspace',
            default => ucwords(str_replace('_', ' ', $this->action))
        };
    }

    /**
     * Get icon for activity type
     */
    public function getIcon(): string
    {
        return match($this->action) {
            'create_task', 'create_workspace' => 'plus',
            'update_task' => 'edit',
            'delete_task' => 'trash',
            'create_comment' => 'message',
            'upload_attachment' => 'upload',
            'login' => 'login',
            'logout' => 'logout',
            'join_workspace' => 'user-plus',
            default => 'activity'
        };
    }

    /**
     * Scope for recent activities
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for user activities
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}