<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'website_url',
        'logo_url',
        'description',
        'project_types',
        'technologies',
        'team_size',
        'hourly_rate_min',
        'hourly_rate_max',
        'minimum_project_budget',
        'headquarters_country',
        'headquarters_city',
        'timezones_covered',
        'verification_status',
        'verification_date',
        'quality_score',
        'response_time_hours',
        'portfolio_urls',
        'case_studies',
        'client_logos',
        'lead_preferences',
        'max_leads_per_month',
        'current_month_leads',
        'subscription_status',
        'subscription_plan',
        'trial_ends_at',
    ];

    protected $casts = [
        'project_types' => 'array',
        'technologies' => 'array',
        'timezones_covered' => 'array',
        'verification_date' => 'datetime',
        'portfolio_urls' => 'array',
        'case_studies' => 'array',
        'client_logos' => 'array',
        'lead_preferences' => 'array',
        'trial_ends_at' => 'datetime',
        'quality_score' => 'integer',
        'hourly_rate_min' => 'integer',
        'hourly_rate_max' => 'integer',
        'minimum_project_budget' => 'integer',
        'response_time_hours' => 'integer',
        'max_leads_per_month' => 'integer',
        'current_month_leads' => 'integer',
    ];

    /**
     * Get the user that owns the vendor profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project leads for this vendor.
     */
    public function projectLeads()
    {
        return $this->hasMany(ProjectLead::class);
    }

    /**
     * Get the reviews for this vendor.
     */
    public function reviews()
    {
        return $this->hasMany(VendorReview::class);
    }

    /**
     * Get the notifications for this vendor.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Check if vendor is verified.
     */
    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    /**
     * Check if vendor is on trial.
     */
    public function isOnTrial(): bool
    {
        return $this->subscription_status === 'trial' && 
               $this->trial_ends_at && 
               $this->trial_ends_at->isFuture();
    }

    /**
     * Check if vendor has active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return in_array($this->subscription_status, ['trial', 'active']);
    }

    /**
     * Check if vendor can receive more leads this month.
     */
    public function canReceiveMoreLeads(): bool
    {
        return $this->current_month_leads < $this->max_leads_per_month;
    }

    /**
     * Increment monthly lead count.
     */
    public function incrementLeadCount(): void
    {
        $this->increment('current_month_leads');
    }

    /**
     * Reset monthly lead count (called at start of month).
     */
    public function resetMonthlyLeadCount(): void
    {
        $this->update(['current_month_leads' => 0]);
    }

    /**
     * Calculate match score for a project.
     */
    public function calculateMatchScore(Project $project): int
    {
        $score = 0;

        // Project type match (40 points)
        if ($this->project_types && in_array($project->project_type, $this->project_types)) {
            $score += 40;
        }

        // Budget match (30 points)
        if ($project->estimated_budget_min && $this->minimum_project_budget) {
            if ($project->estimated_budget_min >= $this->minimum_project_budget) {
                $score += 30;
            } else if ($project->estimated_budget_min >= ($this->minimum_project_budget * 0.8)) {
                $score += 15; // Partial match
            }
        }

        // Quality score (20 points - scaled)
        $score += ($this->quality_score / 100) * 20;

        // Response time (10 points)
        if ($this->response_time_hours) {
            if ($this->response_time_hours <= 24) {
                $score += 10;
            } else if ($this->response_time_hours <= 48) {
                $score += 5;
            }
        }

        return min(100, $score); // Cap at 100
    }

    /**
     * Get average rating from reviews.
     */
    public function getAverageRating(): ?float
    {
        return $this->reviews()->avg('rating');
    }

    /**
     * Scope for verified vendors.
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    /**
     * Scope for active vendors.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('subscription_status', ['trial', 'active']);
    }

    /**
     * Scope for vendors available for leads.
     */
    public function scopeAvailableForLeads($query)
    {
        return $query->verified()
                    ->active()
                    ->whereRaw('current_month_leads < max_leads_per_month');
    }

    /**
     * Scope for vendors by project type.
     */
    public function scopeForProjectType($query, string $projectType)
    {
        return $query->whereJsonContains('project_types', $projectType);
    }

    /**
     * Scope for vendors by technology.
     */
    public function scopeWithTechnology($query, string $technology)
    {
        return $query->whereJsonContains('technologies', $technology);
    }

    /**
     * Scope for vendors by country.
     */
    public function scopeInCountry($query, string $country)
    {
        return $query->where('headquarters_country', $country);
    }
}
