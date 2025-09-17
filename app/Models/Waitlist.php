<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
    use HasFactory;

    protected $table = 'waitlist';

    protected $fillable = [
        'email',
        'project_type',
        'company_size',
        'use_case',
        'status',
        'invited_at',
        'activated_at',
        'referral_source',
        'utm_source',
        'utm_campaign',
    ];

    protected $casts = [
        'invited_at' => 'datetime',
        'activated_at' => 'datetime',
    ];

    /**
     * Add user to waitlist.
     */
    public static function addToWaitlist(
        string $email,
        array $data = []
    ): self {
        return static::firstOrCreate(
            ['email' => $email],
            array_merge([
                'status' => 'waiting',
                'utm_source' => request()->query('utm_source'),
                'utm_campaign' => request()->query('utm_campaign'),
            ], $data)
        );
    }

    /**
     * Invite user from waitlist.
     */
    public function invite(): void
    {
        $this->update([
            'status' => 'invited',
            'invited_at' => now(),
        ]);
    }

    /**
     * Activate user from waitlist.
     */
    public function activate(): void
    {
        $this->update([
            'status' => 'activated',
            'activated_at' => now(),
        ]);
    }

    /**
     * Check if user is waiting.
     */
    public function isWaiting(): bool
    {
        return $this->status === 'waiting';
    }

    /**
     * Check if user is invited.
     */
    public function isInvited(): bool
    {
        return $this->status === 'invited';
    }

    /**
     * Check if user is activated.
     */
    public function isActivated(): bool
    {
        return $this->status === 'activated';
    }

    /**
     * Get position in waitlist.
     */
    public function getPositionAttribute(): int
    {
        return static::where('status', 'waiting')
            ->where('created_at', '<', $this->created_at)
            ->count() + 1;
    }

    /**
     * Scope for waiting users.
     */
    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    /**
     * Scope for invited users.
     */
    public function scopeInvited($query)
    {
        return $query->where('status', 'invited');
    }

    /**
     * Scope for activated users.
     */
    public function scopeActivated($query)
    {
        return $query->where('status', 'activated');
    }

    /**
     * Scope for users by project type.
     */
    public function scopeProjectType($query, string $projectType)
    {
        return $query->where('project_type', $projectType);
    }

    /**
     * Scope for users by company size.
     */
    public function scopeCompanySize($query, string $companySize)
    {
        return $query->where('company_size', $companySize);
    }

    /**
     * Scope for users by UTM source.
     */
    public function scopeUtmSource($query, string $utmSource)
    {
        return $query->where('utm_source', $utmSource);
    }

    /**
     * Get waitlist statistics.
     */
    public static function getStats(): array
    {
        return [
            'total' => static::count(),
            'waiting' => static::waiting()->count(),
            'invited' => static::invited()->count(),
            'activated' => static::activated()->count(),
            'conversion_rate' => static::count() > 0 
                ? round((static::activated()->count() / static::count()) * 100, 2) 
                : 0,
        ];
    }
}
