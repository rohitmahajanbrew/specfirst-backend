<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'project_id',
        'vendor_id',
        'is_read',
        'read_at',
        'email_sent',
        'sms_sent',
        'action_url',
        'expires_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'email_sent' => 'boolean',
        'sms_sent' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user for this notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related project.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the related vendor.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Create a new notification.
     */
    public static function notify(
        User $user,
        string $type,
        string $title,
        string $message = null,
        array $data = []
    ): self {
        return static::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'project_id' => $data['project_id'] ?? null,
            'vendor_id' => $data['vendor_id'] ?? null,
            'action_url' => $data['action_url'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Check if notification is read.
     */
    public function isRead(): bool
    {
        return $this->is_read;
    }

    /**
     * Check if notification is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Mark email as sent.
     */
    public function markEmailAsSent(): void
    {
        $this->update(['email_sent' => true]);
    }

    /**
     * Mark SMS as sent.
     */
    public function markSmsAsSent(): void
    {
        $this->update(['sms_sent' => true]);
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for notifications by type.
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for non-expired notifications.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope for expired notifications.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope for recent notifications.
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get notification icon based on type.
     */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'project_shared' => 'share',
            'project_comment' => 'message-circle',
            'project_completed' => 'check-circle',
            'vendor_lead' => 'briefcase',
            'vendor_quote' => 'dollar-sign',
            'system_update' => 'bell',
            'payment_received' => 'credit-card',
            default => 'bell',
        };
    }

    /**
     * Get notification color based on type.
     */
    public function getColorAttribute(): string
    {
        return match ($this->type) {
            'project_shared' => 'blue',
            'project_comment' => 'green',
            'project_completed' => 'green',
            'vendor_lead' => 'purple',
            'vendor_quote' => 'yellow',
            'system_update' => 'gray',
            'payment_received' => 'green',
            default => 'gray',
        };
    }
}
