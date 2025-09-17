<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewQuickReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'message_id',
        'reply_text',
        'reply_value',
        'display_order',
        'was_selected',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'was_selected' => 'boolean',
    ];

    /**
     * Get the session that owns the quick reply.
     */
    public function session()
    {
        return $this->belongsTo(InterviewSession::class, 'session_id');
    }

    /**
     * Get the message that owns the quick reply.
     */
    public function message()
    {
        return $this->belongsTo(InterviewMessage::class, 'message_id');
    }

    /**
     * Mark this reply as selected.
     */
    public function markAsSelected(): void
    {
        $this->update(['was_selected' => true]);
    }

    /**
     * Scope for selected replies.
     */
    public function scopeSelected($query)
    {
        return $query->where('was_selected', true);
    }

    /**
     * Scope for unselected replies.
     */
    public function scopeUnselected($query)
    {
        return $query->where('was_selected', false);
    }

    /**
     * Scope ordered by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
