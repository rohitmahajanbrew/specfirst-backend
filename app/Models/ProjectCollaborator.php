<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectCollaborator extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'role',
        'invited_by',
        'invitation_email',
        'invitation_token',
        'invitation_accepted',
        'permissions',
        'accepted_at',
        'last_accessed_at',
    ];

    protected $casts = [
        'permissions' => 'array',
        'invitation_accepted' => 'boolean',
        'accepted_at' => 'datetime',
        'last_accessed_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($collaborator) {
            if (empty($collaborator->invitation_token)) {
                $collaborator->invitation_token = Str::random(64);
            }
        });
    }

    /**
     * Get the project for this collaboration.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the collaborator user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who sent the invitation.
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Accept the invitation.
     */
    public function acceptInvitation(User $user): void
    {
        $this->update([
            'user_id' => $user->id,
            'invitation_accepted' => true,
            'accepted_at' => now(),
        ]);
    }

    /**
     * Check if invitation is accepted.
     */
    public function isAccepted(): bool
    {
        return $this->invitation_accepted;
    }

    /**
     * Check if user is owner.
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Check if user is editor.
     */
    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    /**
     * Check if user is viewer.
     */
    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }

    /**
     * Check if user can edit.
     */
    public function canEdit(): bool
    {
        return in_array($this->role, ['owner', 'editor']);
    }

    /**
     * Check if user has specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isOwner()) {
            return true; // Owners have all permissions
        }

        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Update last accessed timestamp.
     */
    public function updateLastAccess(): void
    {
        $this->update(['last_accessed_at' => now()]);
    }

    /**
     * Scope for accepted invitations.
     */
    public function scopeAccepted($query)
    {
        return $query->where('invitation_accepted', true);
    }

    /**
     * Scope for pending invitations.
     */
    public function scopePending($query)
    {
        return $query->where('invitation_accepted', false);
    }

    /**
     * Scope for collaborators by role.
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope for owners.
     */
    public function scopeOwners($query)
    {
        return $query->where('role', 'owner');
    }

    /**
     * Scope for editors.
     */
    public function scopeEditors($query)
    {
        return $query->where('role', 'editor');
    }

    /**
     * Scope for viewers.
     */
    public function scopeViewers($query)
    {
        return $query->where('role', 'viewer');
    }
}
