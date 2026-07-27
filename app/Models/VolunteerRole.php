<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VolunteerRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'grants_access',
        'requires_dbs',
        'requires_nda',
        'is_open',
    ];

    protected function casts(): array
    {
        return [
            'requires_dbs' => 'boolean',
            'requires_nda' => 'boolean',
            'is_open'      => 'boolean',
        ];
    }

    public function engagements(): HasMany
    {
        return $this->hasMany(VolunteerEngagement::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Mentors get the learner-facing area, so acceptance has to be gated on a
     * cleared DBS regardless of what the role record says. Asked here rather
     * than read from requires_dbs directly so the rule cannot be turned off by
     * editing a row.
     */
    public function requiresDbs(): bool
    {
        return $this->requires_dbs || $this->grants_access === 'mentor';
    }
}
