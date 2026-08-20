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
        'venue_name', 'venue_address', 'accessibility_note',
        'capacity', 'itinerary', 'landing_path',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'itinerary'  => 'array',
        'capacity'   => 'integer',
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

    public function isInPerson(): bool
    {
        return (bool) $this->venue_name;
    }

    /**
     * People holding a place, as opposed to people on the waiting list.
     */
    public function confirmedCount(): int
    {
        return $this->registrations()->where('waitlisted', false)->count();
    }

    /**
     * Places left, or null when the event has no ceiling.
     *
     * Never negative. Capacity can be lowered after people have registered —
     * a venue reduces a room, say — and a negative number on a public page
     * reads as a bug rather than as "we are full".
     */
    public function spacesLeft(): ?int
    {
        if (! $this->capacity) {
            return null;
        }

        return max(0, $this->capacity - $this->confirmedCount());
    }

    public function isFull(): bool
    {
        return $this->spacesLeft() === 0;
    }

    /**
     * Whether to show the count publicly.
     *
     * Scarcity is only worth mentioning when it is real and close. Announcing
     * "30 places left" of 35 says the room is empty, which is a reason not to
     * come; saying nothing until it matters is both more honest and kinder to
     * an audience that will not want to walk into an empty hall.
     */
    public function shouldShowSpacesLeft(): bool
    {
        $left = $this->spacesLeft();

        return $left !== null && $left > 0 && $left <= 10;
    }

    /**
     * Where this event actually lives, whether or not it has a bespoke page.
     */
    public function url(): string
    {
        return $this->landing_path
            ? url($this->landing_path)
            : route('sessions.show', $this);
    }
}
