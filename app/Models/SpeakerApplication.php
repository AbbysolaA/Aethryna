<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * A pitch to speak at one of our sessions.
 *
 * The accepted end of this pipeline is a PanelSpeaker, which the session pages
 * already know how to render. Accepting an application mints one and links it
 * back here, so the pitch that led to a booking stays on file.
 */
class SpeakerApplication extends Model
{
    /** Private disk. A headshot only becomes public once accepted, on purpose. */
    public const HEADSHOT_DISK = 'local';

    public const STATUSES = ['new', 'accepted', 'declined'];

    /**
     * How they would rather deliver it. Absence means no preference.
     *
     * A live panel and a pre-recorded talk are different asks of a nervous
     * first-timer, and some excellent speakers will only do one of them.
     * Better to know at pitch time than at scheduling time.
     */
    public const FORMATS = [
        'live-panel'   => 'On a live panel',
        'roundtable'   => 'A roundtable conversation',
        'pre-recorded' => 'A pre-recorded talk',
    ];

    /**
     * The tracks a talk can speak to. One place, so when the programme
     * taxonomy is renamed this list is one edit rather than a hunt through
     * the form, the validation and the admin screen.
     */
    public const TOPICS = [
        'Project management and delivery',
        'Product management',
        'Product design and marketing',
        'Data and AI',
        'Software development',
        'Routes into tech, any role',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'organisation',
        'job_title',
        'location',
        'bio',
        'linkedin_url',
        'website_url',
        'talk_title',
        'talk_summary',
        'session_format',
        'topic_areas',
        'prior_speaking',
        'video_url',
        'headshot_path',
        'headshot_original_name',
        'headshot_mime',
        'headshot_size',
        'status',
        'panel_speaker_id',
        'consented_at',
        'recording_consented_at',
    ];

    protected function casts(): array
    {
        return [
            'consented_at'           => 'datetime',
            'recording_consented_at' => 'datetime',
            'topic_areas'            => 'array',
            'headshot_size'          => 'integer',
        ];
    }

    public function panelSpeaker(): BelongsTo
    {
        return $this->belongsTo(PanelSpeaker::class);
    }

    /**
     * Pitches nobody has read yet. Named unread rather than fresh so it
     * cannot be misread as Eloquent's fresh(), which reloads a model.
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'new');
    }

    public function formatLabel(): ?string
    {
        return self::FORMATS[$this->session_format] ?? null;
    }

    public function hasHeadshot(): bool
    {
        return (bool) $this->headshot_path
            && Storage::disk(self::HEADSHOT_DISK)->exists($this->headshot_path);
    }

    /**
     * Accept the pitch: mint the PanelSpeaker the session pages render, link
     * it back, and mark the application. One place, so accepting from any
     * screen produces the same record.
     *
     * The headshot is not copied into the public speakers folder here. Photos
     * go through speakers:photo, which resizes and strips them; publishing a
     * raw upload straight from a stranger would skip both.
     */
    public function accept(): PanelSpeaker
    {
        // By id, not through the relation: the relation caches its first
        // answer, and on a model that was loaded before acceptance that answer
        // is null, which would mint a second speaker on a second call.
        $speaker = ($this->panel_speaker_id ? PanelSpeaker::find($this->panel_speaker_id) : null)
            ?? PanelSpeaker::create([
            'name'         => $this->name,
            'title'        => $this->job_title,
            'company'      => $this->organisation,
            'bio'          => $this->bio,
            'linkedin_url' => $this->linkedin_url,
        ]);

        $this->forceFill([
            'status'           => 'accepted',
            'panel_speaker_id' => $speaker->id,
        ])->save();

        return $speaker;
    }

    protected static function booted(): void
    {
        static::deleting(function (self $application) {
            if ($application->headshot_path) {
                Storage::disk(self::HEADSHOT_DISK)->delete($application->headshot_path);
            }
        });
    }
}
