<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tokens()
    {
        return $this->morphMany(
            'Laravel\Sanctum\PersonalAccessToken',
            'tokenable'
        );
    }

    /**
     * Get all tasks for the user.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get all task comments by the user.
     */
    public function taskComments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    /**
     * Get all task attachments by the user.
     */
    public function taskAttachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }

    /**
     * Get workspaces owned by the user.
     */
    public function ownedWorkspaces(): HasMany
    {
        return $this->hasMany(Workspace::class, 'owner_id');
    }

    /**
     * Get workspaces where user is a member.
     */
    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class, 'workspace_members')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Get user activities.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Check if user owns a workspace
     */
    public function ownsWorkspace(Workspace $workspace): bool
    {
        return $this->id === $workspace->owner_id;
    }

    /**
     * Get user's role in a workspace
     */
    public function getWorkspaceRole(Workspace $workspace): ?string
    {
        $membership = $this->workspaces()->where('workspace_id', $workspace->id)->first();
        return $membership?->pivot?->role;
    }
}

// AuthController moved to separate file
// UserController moved to separate file