<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'user_id',
        'export_format',
        'file_url',
        'file_size_bytes',
        'sent_to_email',
        'email_sent_at',
        'expires_at',
    ];

    protected $casts = [
        'email_sent_at' => 'datetime',
        'expires_at' => 'datetime',
        'file_size_bytes' => 'integer',
    ];

    /**
     * Get the project for this export.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who requested the export.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if export is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if email was sent.
     */
    public function wasEmailSent(): bool
    {
        return !is_null($this->email_sent_at);
    }

    /**
     * Mark email as sent.
     */
    public function markEmailAsSent(string $email): void
    {
        $this->update([
            'sent_to_email' => $email,
            'email_sent_at' => now(),
        ]);
    }

    /**
     * Get human readable file size.
     */
    public function getHumanFileSizeAttribute(): string
    {
        if (!$this->file_size_bytes) {
            return 'Unknown';
        }

        $bytes = $this->file_size_bytes;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Scope for exports by format.
     */
    public function scopeFormat($query, string $format)
    {
        return $query->where('export_format', $format);
    }

    /**
     * Scope for non-expired exports.
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope for expired exports.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope for exports with email sent.
     */
    public function scopeEmailSent($query)
    {
        return $query->whereNotNull('email_sent_at');
    }
}
