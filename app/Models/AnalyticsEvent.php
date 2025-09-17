<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'event_name',
        'event_category',
        'event_data',
        'page_url',
        'referrer_url',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'os',
    ];

    protected $casts = [
        'event_data' => 'array',
    ];

    /**
     * Get the user for this event.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a new analytics event.
     */
    public static function track(
        string $eventName,
        ?User $user = null,
        string $category = null,
        array $data = [],
        array $context = []
    ): self {
        return static::create([
            'user_id' => $user?->id,
            'session_id' => $context['session_id'] ?? session()->getId(),
            'event_name' => $eventName,
            'event_category' => $category,
            'event_data' => $data,
            'page_url' => $context['page_url'] ?? request()->fullUrl(),
            'referrer_url' => $context['referrer_url'] ?? request()->header('referer'),
            'utm_source' => $context['utm_source'] ?? request()->query('utm_source'),
            'utm_medium' => $context['utm_medium'] ?? request()->query('utm_medium'),
            'utm_campaign' => $context['utm_campaign'] ?? request()->query('utm_campaign'),
            'ip_address' => $context['ip_address'] ?? request()->ip(),
            'user_agent' => $context['user_agent'] ?? request()->userAgent(),
            'device_type' => $context['device_type'] ?? $user?->device_type,
            'browser' => $context['browser'] ?? null,
            'os' => $context['os'] ?? null,
        ]);
    }

    /**
     * Scope for events by name.
     */
    public function scopeEventName($query, string $eventName)
    {
        return $query->where('event_name', $eventName);
    }

    /**
     * Scope for events by category.
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('event_category', $category);
    }

    /**
     * Scope for events by user.
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Scope for events by session.
     */
    public function scopeForSession($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope for events by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for events by UTM source.
     */
    public function scopeUtmSource($query, string $source)
    {
        return $query->where('utm_source', $source);
    }
}
