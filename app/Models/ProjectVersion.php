<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'version_number',
        'requirements_doc',
        'change_summary',
        'created_by',
    ];

    protected $casts = [
        'requirements_doc' => 'array',
        'version_number' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($version) {
            if (empty($version->version_number)) {
                $latestVersion = static::where('project_id', $version->project_id)
                    ->max('version_number');
                $version->version_number = ($latestVersion ?? 0) + 1;
            }
        });
    }

    /**
     * Get the project that owns the version.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who created this version.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get versions for a project.
     */
    public function scopeForProject($query, int $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Get the version name.
     */
    public function getVersionNameAttribute(): string
    {
        return "v{$this->version_number}";
    }
}
