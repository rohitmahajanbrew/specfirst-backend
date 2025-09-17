<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'parent_comment_id',
        'section_id',
        'highlighted_text',
        'comment_text',
        'is_resolved',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the project for this comment.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who wrote the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment (for replies).
     */
    public function parentComment()
    {
        return $this->belongsTo(ProjectComment::class, 'parent_comment_id');
    }

    /**
     * Get the child comments (replies).
     */
    public function replies()
    {
        return $this->hasMany(ProjectComment::class, 'parent_comment_id')->orderBy('created_at');
    }

    /**
     * Get the user who resolved the comment.
     */
    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Check if comment is resolved.
     */
    public function isResolved(): bool
    {
        return $this->is_resolved;
    }

    /**
     * Check if comment is a reply.
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_comment_id);
    }

    /**
     * Check if comment is a root comment.
     */
    public function isRootComment(): bool
    {
        return is_null($this->parent_comment_id);
    }

    /**
     * Resolve the comment.
     */
    public function resolve(User $user): void
    {
        $this->update([
            'is_resolved' => true,
            'resolved_by' => $user->id,
            'resolved_at' => now(),
        ]);
    }

    /**
     * Unresolve the comment.
     */
    public function unresolve(): void
    {
        $this->update([
            'is_resolved' => false,
            'resolved_by' => null,
            'resolved_at' => null,
        ]);
    }

    /**
     * Add a reply to this comment.
     */
    public function addReply(User $user, string $text): self
    {
        return static::create([
            'project_id' => $this->project_id,
            'user_id' => $user->id,
            'parent_comment_id' => $this->id,
            'comment_text' => $text,
        ]);
    }

    /**
     * Get all descendants (replies and replies to replies).
     */
    public function descendants()
    {
        return $this->replies()->with('descendants');
    }

    /**
     * Scope for root comments (not replies).
     */
    public function scopeRootComments($query)
    {
        return $query->whereNull('parent_comment_id');
    }

    /**
     * Scope for replies.
     */
    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_comment_id');
    }

    /**
     * Scope for resolved comments.
     */
    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    /**
     * Scope for unresolved comments.
     */
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    /**
     * Scope for comments in specific section.
     */
    public function scopeInSection($query, string $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    /**
     * Scope for comments by user.
     */
    public function scopeByUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }
