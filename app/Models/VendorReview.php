<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'project_id',
        'user_id',
        'rating',
        'review_text',
        'communication_rating',
        'quality_rating',
        'timeline_rating',
        'value_rating',
        'is_verified',
    ];

    protected $casts = [
        'rating' => 'integer',
        'communication_rating' => 'integer',
        'quality_rating' => 'integer',
        'timeline_rating' => 'integer',
        'value_rating' => 'integer',
        'is_verified' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Update vendor's average rating when review is created/updated
        static::created(function ($review) {
            $review->vendor->update([
                'avg_rating' => $review->vendor->reviews()->avg('rating'),
            ]);
        });

        static::updated(function ($review) {
            $review->vendor->update([
                'avg_rating' => $review->vendor->reviews()->avg('rating'),
            ]);
        });

        static::deleted(function ($review) {
            $review->vendor->update([
                'avg_rating' => $review->vendor->reviews()->avg('rating'),
            ]);
        });
    }

    /**
     * Get the vendor being reviewed.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the project associated with this review.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if review is verified.
     */
    public function isVerified(): bool
    {
        return $this->is_verified;
    }

    /**
     * Verify the review.
     */
    public function verify(): void
    {
        $this->update(['is_verified' => true]);
    }

    /**
     * Get the overall average of detailed ratings.
     */
    public function getDetailedRatingAverageAttribute(): ?float
    {
        $ratings = array_filter([
            $this->communication_rating,
            $this->quality_rating,
            $this->timeline_rating,
            $this->value_rating,
        ]);

        return count($ratings) > 0 ? array_sum($ratings) / count($ratings) : null;
    }

    /**
     * Scope for verified reviews.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for reviews with specific rating.
     */
    public function scopeWithRating($query, int $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope for reviews with rating above threshold.
     */
    public function scopeHighRated($query, int $threshold = 4)
    {
        return $query->where('rating', '>=', $threshold);
    }

    /**
     * Scope for reviews with rating below threshold.
     */
    public function scopeLowRated($query, int $threshold = 3)
    {
        return $query->where('rating', '<=', $threshold);
    }

    /**
     * Scope for reviews with text.
     */
    public function scopeWithText($query)
    {
        return $query->whereNotNull('review_text')
                    ->where('review_text', '!=', '');
    }
}
