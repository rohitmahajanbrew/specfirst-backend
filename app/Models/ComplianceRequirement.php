<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplianceRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'requirements_checklist',
        'auto_detect_keywords',
        'industry',
        'region',
        'is_active',
    ];

    protected $casts = [
        'requirements_checklist' => 'array',
        'auto_detect_keywords' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Check if requirement is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if keywords match the given text.
     */
    public function matchesKeywords(string $text): bool
    {
        if (!$this->auto_detect_keywords) {
            return false;
        }

        $text = strtolower($text);
        
        foreach ($this->auto_detect_keywords as $keyword) {
            if (str_contains($text, strtolower($keyword))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get compliance requirements that match the given text.
     */
    public static function getMatchingRequirements(string $text): \Illuminate\Database\Eloquent\Collection
    {
        return static::active()->get()->filter(function ($requirement) use ($text) {
            return $requirement->matchesKeywords($text);
        });
    }

    /**
     * Scope for active requirements.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for requirements by industry.
     */
    public function scopeForIndustry($query, string $industry)
    {
        return $query->where('industry', $industry);
    }

    /**
     * Scope for requirements by region.
     */
    public function scopeForRegion($query, string $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Scope for requirements by code.
     */
    public function scopeByCode($query, string $code)
    {
        return $query->where('code', $code);
    }
}
