<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'vendor_id',
        'status',
        'match_score',
        'match_reasons',
        'viewed_at',
        'contacted_at',
        'quoted_at',
        'quote_amount',
        'quote_timeline_weeks',
        'outcome',
        'outcome_reason',
        'lead_price',
        'paid_at',
        'expires_at',
    ];

    protected $casts = [
        'match_reasons' => 'array',
        'viewed_at' => 'datetime',
        'contacted_at' => 'datetime',
        'quoted_at' => 'datetime',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'match_score' => 'integer',
        'quote_amount' => 'integer',
        'quote_timeline_weeks' => 'integer',
        'lead_price' => 'decimal:2',
    ];

    /**
     * Get the project for this lead.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the vendor for this lead.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Mark lead as viewed.
     */
    public function markAsViewed(): void
    {
        if (!$this->viewed_at) {
            $this->update([
                'status' => 'viewed',
                'viewed_at' => now(),
            ]);
        }
    }

    /**
     * Mark lead as contacted.
     */
    public function markAsContacted(): void
    {
        $this->update([
            'status' => 'contacted',
            'contacted_at' => now(),
        ]);
    }

    /**
     * Submit quote for this lead.
     */
    public function submitQuote(int $amount, int $timelineWeeks): void
    {
        $this->update([
            'status' => 'quoted',
            'quoted_at' => now(),
            'quote_amount' => $amount,
            'quote_timeline_weeks' => $timelineWeeks,
        ]);
    }

    /**
     * Mark lead as won.
     */
    public function markAsWon(string $reason = null): void
    {
        $this->update([
            'status' => 'won',
            'outcome' => 'hired',
            'outcome_reason' => $reason,
        ]);
    }

    /**
     * Mark lead as lost.
     */
    public function markAsLost(string $reason = null): void
    {
        $this->update([
            'status' => 'lost',
            'outcome' => 'rejected',
            'outcome_reason' => $reason,
        ]);
    }

    /**
     * Check if lead is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if lead is new.
     */
    public function isNew(): bool
    {
        return $this->status === 'new';
    }

    /**
     * Check if lead has been viewed.
     */
    public function hasBeenViewed(): bool
    {
        return !is_null($this->viewed_at);
    }

    /**
     * Check if quote has been submitted.
     */
    public function hasQuote(): bool
    {
        return !is_null($this->quoted_at);
    }

    /**
     * Scope for new leads.
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope for leads by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for leads by outcome.
     */
    public function scopeOutcome($query, string $outcome)
    {
        return $query->where('outcome', $outcome);
    }

    /**
     * Scope for non-expired leads.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope for expired leads.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope for paid leads.
     */
    public function scopePaid($query)
    {
        return $query->whereNotNull('paid_at');
    }

    /**
     * Scope for leads with high match score.
     */
    public function scopeHighMatch($query, int $threshold = 80)
    {
        return $query->where('match_score', '>=', $threshold);
    }
}
