<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'project_type',
        'category',
        'template_structure',
        'sample_questions',
        'default_features',
        'is_premium',
        'is_featured',
        'usage_count',
        'avg_rating',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'template_structure' => 'array',
        'sample_questions' => 'array',
        'default_features' => 'array',
        'is_premium' => 'boolean',
        'is_featured' => 'boolean',
        'usage_count' => 'integer',
        'avg_rating' => 'decimal:1',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($template) {
            if (empty($template->slug)) {
                $template->slug = Str::slug($template->name);
            }
        });
    }

    /**
     * Get projects created from this template.
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'template_id');
    }

    /**
     * Increment usage count.
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Check if template is premium.
     */
    public function isPremium(): bool
    {
        return $this->is_premium;
    }

    /**
     * Check if template is featured.
     */
    public function isFeatured(): bool
    {
        return $this->is_featured;
    }

    /**
     * Scope for premium templates.
     */
    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    /**
     * Scope for free templates.
     */
    public function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }

    /**
     * Scope for featured templates.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for templates by project type.
     */
    public function scopeForProjectType($query, string $projectType)
    {
        return $query->where('project_type', $projectType);
    }

    /**
     * Scope for templates by category.
     */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for popular templates.
     */
    public function scopePopular($query)
    {
        return $query->orderBy('usage_count', 'desc');
    }

    /**
     * Scope for highly rated templates.
     */
    public function scopeHighlyRated($query)
    {
        return $query->whereNotNull('avg_rating')
                    ->orderBy('avg_rating', 'desc');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
