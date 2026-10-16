<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PanelSession extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'tagline', 'event_date',
        'duration', 'format', 'eventbrite_url', 'join_url', 'recording_url',
        'status', 'sort_order',
        'venue_name', 'venue_address', 'accessibility_note',
        'capacity', 'itinerary', 'landing_path', 'banner_path',
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

    /**
     * The event currently worth promoting on the site, or null.
     *
     * Both the hero slide and the standalone banner ask this question, and they
     * have to agree: one advertising a finished event while the other has taken
     * itself down is worse than neither appearing. So the rule lives here rather
     * than being written out twice in Blade.
     *
     * End of the event day rather than its start time, so the promotion stays up
     * for anyone checking the address on the morning.
     */
    public static function promotable(string $slug = 'discovery-session'): ?self
    {
        $event = static::query()
            ->where('slug', $slug)
            ->whereIn('status', ['upcoming', 'live'])
            ->first();

        if ($event?->event_date && $event->event_date->copy()->endOfDay()->isPast()) {
            return null;
        }

        return $event;
    }

    public function isUpcoming(): bool
    {
        return in_array($this->status, ['upcoming', 'live']);
    }

    public function isPast(): bool
    {
        return $this->status === 'past';
    }

    /**
     * The event's day is over, by the calendar or by an admin marking it
     * past. Date first: nobody remembers to flip a status flag the morning
     * after, and the carousel already trusts the date for the same reason.
     */
    public function hasHappened(): bool
    {
        return ($this->event_date && $this->event_date->copy()->endOfDay()->isPast())
            || $this->isPast();
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

    /*
     * There was a shouldShowSpacesLeft() here, deciding when to publish the
     * remaining count. The count is not published at all now: it is a scarcity
     * device, and this is a free event for people who have reason to feel they
     * are competing for something they might not deserve. spacesLeft() stays,
     * because staff and the admin screens need it; what went is the helper
     * whose only purpose was putting it in front of registrants.
     */

    /**
     * Where this event actually lives, whether or not it has a bespoke page.
     */
    public function url(): string
    {
        return $this->landing_path
            ? url($this->landing_path)
            : route('sessions.show', $this);
    }

    /**
     * The event banner's public URL, or null when none is uploaded.
     *
     * Versioned on the file's modification time, because the Panel 4 banner
     * was replaced under the same filename and browsers and the Cloudflare
     * edge kept serving the old image. A changed file changes the URL, which
     * busts every cache at once; an unchanged file keeps its URL and stays
     * cached, which is the point of the caches.
     */
    public function bannerUrl(): ?string
    {
        if (! $this->banner_path) {
            return null;
        }

        $file = public_path('images/panels/'.$this->banner_path);

        return asset('images/panels/'.$this->banner_path)
            .(is_file($file) ? '?v='.filemtime($file) : '');
    }

    /**
     * The start in real time. event_date holds UK wall-clock time (the
     * seeders write 18:30 meaning half six to the person attending), so the
     * missing piece is the offset, not the reading: shiftTimezone pins the
     * wall clock to Europe/London, then utc() converts honestly. The same
     * reasoning as the Event JSON-LD on the panel page.
     */
    public function startsAtUtc(): ?\Illuminate\Support\Carbon
    {
        return $this->event_date?->copy()->shiftTimezone('Europe/London')->utc();
    }

    /** The stated duration as minutes, for calendar entries. */
    public function durationMinutes(): int
    {
        return (int) (preg_match('/\d+/', (string) $this->duration, $m) ? $m[0] : 60) ?: 60;
    }

    /**
     * The event as an iCalendar file: the thing "Add to calendar" downloads
     * and the confirmation email attaches. One VEVENT, UID keyed on the slug
     * so re-importing updates the entry instead of duplicating it.
     */
    public function toIcs(): string
    {
        $escape = fn (?string $v) => str_replace(
            ["\\", ";", ",", "\n"],
            ["\\\\", "\\;", "\\,", "\\n"],
            trim((string) $v)
        );

        $start = $this->startsAtUtc();

        if (! $start) {
            throw new \LogicException('A panel without an event date has no calendar entry.');
        }

        $end = $start->copy()->addMinutes($this->durationMinutes());

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Skills Co-op//Sessions//EN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            'UID:'.$this->slug.'@skillscoop.org',
            'DTSTAMP:'.now()->utc()->format('Ymd\THis\Z'),
            'DTSTART:'.$start->format('Ymd\THis\Z'),
            'DTEND:'.$end->format('Ymd\THis\Z'),
            'SUMMARY:'.$escape($this->tagline ?: $this->title),
            'DESCRIPTION:'.$escape(\Illuminate\Support\Str::limit(strip_tags((string) $this->description), 400).' Details: '.$this->url()),
            'LOCATION:'.$escape($this->format ?: 'Online'),
            // The place a calendar click should land: the stream itself once
            // a joining link exists, the event page until then.
            'URL:'.($this->join_url ?: $this->url()),
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        return implode("\r\n", $lines)."\r\n";
    }
}
