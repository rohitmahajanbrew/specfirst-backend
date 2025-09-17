<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'full_name',
        'company_name',
        'phone_number',
        'avatar_url',
        'role',
        'onboarding_completed',
        'preferred_project_type',
        'device_type',
        'device_token',
        'last_login_at',
        'timezone',
        'locale',
        'notification_preferences',
        'metadata',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'notification_preferences' => 'array',
            'metadata' => 'array',
            'onboarding_completed' => 'boolean',
        ];
    }

    /**
     * Get the user's OTP codes relationship.
     */
    public function otpCodes()
    {
        return $this->hasMany(UserOtp::class, 'identifier', 'email');
    }

    /**
     * Get the user's OAuth accounts relationship.
     */
    public function oauthAccounts()
    {
        return $this->hasMany(UserOauthAccount::class);
    }

    /**
     * Get the user's projects relationship.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the user's vendor profile.
     */
    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    /**
     * Get the user's interview sessions.
     */
    public function interviewSessions()
    {
        return $this->hasMany(InterviewSession::class);
    }

    /**
     * Get the user's project collaborations.
     */
    public function collaborations()
    {
        return $this->hasMany(ProjectCollaborator::class);
    }

    /**
     * Get the user's comments.
     */
    public function comments()
    {
        return $this->hasMany(ProjectComment::class);
    }

    /**
     * Get the user's notifications.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the user's vendor reviews.
     */
    public function vendorReviews()
    {
        return $this->hasMany(VendorReview::class);
    }

    /**
     * Get the user's analytics events.
     */
    public function analyticsEvents()
    {
        return $this->hasMany(AnalyticsEvent::class);
    }

    /**
     * Get the user's feature usage.
     */
    public function featureUsage()
    {
        return $this->hasMany(FeatureUsage::class);
    }

    /**
     * Get the user's project exports.
     */
    public function projectExports()
    {
        return $this->hasMany(ProjectExport::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is vendor.
     */
    public function isVendor(): bool
    {
        return $this->hasRole('vendor');
    }

    /**
     * Get the identifier for OTP (email or phone).
     */
    public function getOtpIdentifier(): string
    {
        return $this->email;
    }
}
