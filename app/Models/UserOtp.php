<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'otp_code',
        'type',
        'purpose',
        'expires_at',
        'is_used',
        'used_at',
        'ip_address',
        'user_agent',
        'attempts',
        'last_attempt_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'last_attempt_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    /**
     * Generate a static 6-digit OTP code for testing.
     */
    public static function generateCode(): string
    {
        // Static OTP for testing - in production, use random_int(0, 999999)
        return '123456';
    }

    /**
     * Create a new OTP for the given identifier.
     */
    public static function createOtp(
        string $identifier,
        string $type = 'email',
        string $purpose = 'login',
        int $expiresInMinutes = 10
    ): self {
        // Invalidate any existing OTPs for this identifier and purpose
        self::where('identifier', $identifier)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        return self::create([
            'identifier' => $identifier,
            'otp_code' => self::generateCode(),
            'type' => $type,
            'purpose' => $purpose,
            'expires_at' => Carbon::now()->addMinutes($expiresInMinutes),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Verify the OTP code.
     */
    public function verify(string $code): bool
    {
        $this->increment('attempts');
        $this->update(['last_attempt_at' => now()]);

        if ($this->attempts > 5) {
            return false; // Too many attempts
        }

        if ($this->is_used) {
            return false; // Already used
        }

        if ($this->expires_at->isPast()) {
            return false; // Expired
        }

        if ($this->otp_code !== $code) {
            return false; // Invalid code
        }

        $this->update([
            'is_used' => true,
            'used_at' => now(),
        ]);

        return true;
    }

    /**
     * Check if OTP is valid (not used, not expired, attempts < 5).
     */
    public function isValid(): bool
    {
        return !$this->is_used 
            && !$this->expires_at->isPast() 
            && $this->attempts < 5;
    }

    /**
     * Get the user associated with this OTP.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'identifier', 'email');
    }

    /**
     * Scope to get valid OTPs.
     */
    public function scopeValid($query)
    {
        return $query->where('is_used', false)
                    ->where('expires_at', '>', now())
                    ->where('attempts', '<', 5);
    }

    /**
     * Scope to get OTPs by purpose.
     */
    public function scopeForPurpose($query, string $purpose)
    {
        return $query->where('purpose', $purpose);
    }
}
