<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'project_type',
        'status',
        'requirements_doc',
        'requirements_html',
        'requirements_markdown',
        'completeness_score',
        'complexity_score',
        'estimated_budget_min',
        'estimated_budget_max',
        'estimated_timeline_weeks',
        'word_count',
        'is_public',
        'share_token',
        'password_protected',
        'password_hash',
        'view_count',
        'download_count',
        'share_count',
        'completed_at',
        'last_accessed_at',
        'expires_at',
    ];

    protected $casts = [
        'requirements_doc' => 'array',
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_public' => 'boolean',
        'password_protected' => 'boolean',
        'completeness_score' => 'integer',
        'view_count' => 'integer',
        'download_count' => 'integer',
        'share_count' => 'integer',
        'word_count' => 'integer',
    ];

    protected $hidden = [
        'password_hash',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->name) . '-' . Str::random(6);
            }
            if (empty($project->share_token)) {
                $project->share_token = Str::random(32);
            }
        });
    }

    /**
     * Get the user that owns the project.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project type.
     */
    public function projectType()
    {
        return $this->belongsTo(ProjectType::class, 'project_type', 'slug');
    }

    /**
     * Get the project versions.
     */
    public function versions()
    {
        return $this->hasMany(ProjectVersion::class)->orderBy('version_number', 'desc');
    }

    /**
     * Get the latest project version.
     */
    public function latestVersion()
    {
        return $this->hasOne(ProjectVersion::class)->latestOfMany('version_number');
    }

    /**
     * Get the interview sessions for this project.
     */
    public function interviewSessions()
    {
        return $this->hasMany(InterviewSession::class);
    }

    /**
     * Get the active interview session.
     */
    public function activeInterviewSession()
    {
        return $this->hasOne(InterviewSession::class)->where('status', 'active');
    }

    /**
     * Get the project collaborators.
     */
    public function collaborators()
    {
        return $this->hasMany(ProjectCollaborator::class);
    }

    /**
     * Get the project comments.
     */
    public function comments()
    {
        return $this->hasMany(ProjectComment::class);
    }

    /**
     * Get the project exports.
     */
    public function exports()
    {
        return $this->hasMany(ProjectExport::class);
    }

    /**
     * Get the project leads (for vendors).
     */
    public function leads()
    {
        return $this->hasMany(ProjectLead::class);
    }

    /**
     * Get the notifications related to this project.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Check if project is owned by user.
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    /**
     * Check if user can access this project.
     */
    public function canBeAccessedBy(?User $user): bool
    {
        if ($this->is_public) {
            return true;
        }

        if (!$user) {
            return false;
        }

        if ($this->isOwnedBy($user)) {
            return true;
        }

        return $this->collaborators()
            ->where('user_id', $user->id)
            ->where('invitation_accepted', true)
            ->exists();
    }

    /**
     * Increment view count.
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
        $this->update(['last_accessed_at' => now()]);
    }

    /**
     * Scope for public projects.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope for projects by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for projects by type.
     */
    public function scopeType($query, string $type)
    {
        return $query->where('project_type', $type);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
