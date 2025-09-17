<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'role',
        'content',
        'message_type',
        'metadata',
        'confidence_score',
        'requires_follow_up',
    ];

    protected $casts = [
        'metadata' => 'array',
        'confidence_score' => 'decimal:2',
        'requires_follow_up' => 'boolean',
    ];

    /**
     * Get the session that owns the message.
     */
    public function session()
    {
        return $this->belongsTo(InterviewSession::class, 'session_id');
    }

    /**
     * Get the quick replies for this message.
     */
    public function quickReplies()
    {
        return $this->hasMany(InterviewQuickReply::class, 'message_id');
    }

    /**
     * Check if this is an AI message.
     */
    public function isFromAi(): bool
    {
        return $this->role === 'ai';
    }

    /**
     * Check if this is a user message.
     */
    public function isFromUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if this is a system message.
     */
    public function isFromSystem(): bool
    {
        return $this->role === 'system';
    }

    /**
     * Add quick replies to this message.
     */
    public function addQuickReplies(array $replies): void
    {
        foreach ($replies as $index => $reply) {
            $this->quickReplies()->create([
                'session_id' => $this->session_id,
                'reply_text' => $reply['text'] ?? $reply,
                'reply_value' => $reply['value'] ?? null,
                'display_order' => $index,
            ]);
        }
    }

    /**
     * Scope for AI messages.
     */
    public function scopeFromAi($query)
    {
        return $query->where('role', 'ai');
    }

    /**
     * Scope for user messages.
     */
    public function scopeFromUser($query)
    {
        return $query->where('role', 'user');
    }

    /**
     * Scope for messages by type.
     */
    public function scopeType($query, string $type)
    {
        return $query->where('message_type', $type);
    }

    /**
     * Scope for messages requiring follow-up.
     */
    public function scopeRequiringFollowUp($query)
    {
        return $query->where('requires_follow_up', true);
    }
}
