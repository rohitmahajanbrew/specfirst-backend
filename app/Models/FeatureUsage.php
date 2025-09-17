<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'feature_name',
        'usage_count',
        'last_used_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'usage_count' => 'integer',
    ];

    /**
     * Get the user for this feature usage.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project for this feature usage.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Track feature usage.
     */
    public static function trackUsage(
        User $user,
        string $featureName,
        ?Project $project = null
    ): self {
        $usage = static::firstOrCreate(
            [
                'user_id' => $user->id,
                'project_id' => $project?->id,
                'feature_name' => $featureName,
            ],
            [
                'usage_count' => 0,
                'last_used_at' => now(),
            ]
        );

        $usage->increment('usage_count');
        $usage->update(['last_used_at' => now()]);

        return $usage;
    }

    /**
     * Get popular features.
     */
    public static function getPopularFeatures(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return static::selectRaw('feature_name, SUM(usage_count) as total_usage')
            ->groupBy('feature_name')
            ->orderByDesc('total_usage')
            ->limit($limit)
            ->get();
    }

    /**
     * Get user's most used features.
     */
    public static function getUserTopFeatures(User $user, int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('user_id', $user->id)
            ->orderByDesc('usage_count')
            ->limit($limit)
            ->get();
    }

    /**
     * Scope for feature usage by user.
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Scope for feature usage by project.
     */
    public function scopeForProject($query, Project $project)
    {
        return $query->where('project_id', $project->id);
    }

    /**
     * Scope for specific feature.
     */
    public function scopeFeature($query, string $featureName)
    {
        return $query->where('feature_name', $featureName);
    }

    /**
     * Scope for recently used features.
     */
    public function scopeRecentlyUsed($query, int $days = 30)
    {
        return $query->where('last_used_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for popular features.
     */
    public function scopePopular($query, int $threshold = 10)
    {
        return $query->where('usage_count', '>=', $threshold);
    }
}
