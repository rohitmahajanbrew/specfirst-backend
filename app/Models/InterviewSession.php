<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'status',
        'current_question_index',
        'interview_type',
        'conversation_context',
        'extracted_requirements',
        'started_at',
        'completed_at',
        'duration_seconds',
        'last_activity_at',
    ];

    protected $casts = [
        'conversation_context' => 'array',
        'extracted_requirements' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'current_question_index' => 'integer',
        'duration_seconds' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($session) {
            if (empty($session->started_at)) {
                $session->started_at = now();
            }
            if (empty($session->last_activity_at)) {
                $session->last_activity_at = now();
            }
        });

        static::updating(function ($session) {
            $session->last_activity_at = now();

            // Calculate duration when completing
            if ($session->isDirty('status') && $session->status === 'completed' && !$session->completed_at) {
                $session->completed_at = now();
                if ($session->started_at) {
                    $session->duration_seconds = $session->started_at->diffInSeconds(now());
                }
            }
        });
    }

    /**
     * Get the project that owns the session.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user that owns the session.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the messages for this session.
     */
    public function messages()
    {
        return $this->hasMany(InterviewMessage::class, 'session_id');
    }

    /**
     * Get the quick replies for this session.
     */
    public function quickReplies()
    {
        return $this->hasMany(InterviewQuickReply::class, 'session_id');
    }

    /**
     * Check if session is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if session is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Complete the session.
     */
    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Pause the session.
     */
    public function pause(): void
    {
        $this->update(['status' => 'paused']);
    }

    /**
     * Resume the session.
     */
    public function resume(): void
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Add a message to this session.
     */
    public function addMessage(string $role, string $content, string $messageType = 'question', array $metadata = []): InterviewMessage
    {
        return $this->messages()->create([
            'role' => $role,
            'content' => $content,
            'message_type' => $messageType,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Scope for active sessions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for completed sessions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get the duration in human readable format.
     */
    public function getDurationHumanAttribute(): ?string
    {
        if (!$this->duration_seconds) {
            return null;
        }

        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        return "{$minutes}m {$seconds}s";
    }
}
