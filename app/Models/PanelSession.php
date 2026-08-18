<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PanelSession extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'tagline', 'event_date',
        'duration', 'format', 'eventbrite_url', 'recording_url',
        'status', 'sort_order',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    /**
     * Panels are addressed by slug in URLs, not id: /sessions/{slug} is the
     * link that gets shared, and it should stay readable and stable.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(SessionRegistration::class);
    }


    // ── Relationships ────────────────────────────────────────────────────────

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(
            PanelSpeaker::class,
            'panel_session_speakers',
            'panel_session_id',
            'panel_speaker_id'
        )->withPivot('topic', 'sort_order')
         ->orderByPivot('sort_order');
    }

    public function media(): HasMany
    {
        return $this->hasMany(PanelMedia::class)->orderBy('sort_order');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PanelMedia::class)->where('type', 'image')->orderBy('sort_order');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(PanelMedia::class)->where('type', 'video')->orderBy('sort_order');
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', ['upcoming', 'live'])->orderBy('event_date');
    }

    public function scopePast($query)
    {
        return $query->where('status', 'past')->orderBy('event_date', 'desc');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isUpcoming(): bool
    {
        return in_array($this->status, ['upcoming', 'live']);
    }

    public function isPast(): bool
    {
        return $this->status === 'past';
    }
}
