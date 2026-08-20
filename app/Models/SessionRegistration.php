<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionRegistration extends Model
{
    protected $fillable = [
        'panel_session_id',
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'interest_type',
        'audience_group',
        'notes',
        'consented_at',
        'waitlisted',
        'referral_source',
        'wants_to_speak',
        'speaker_topic',
    ];

    protected $casts = [
        'wants_to_speak' => 'boolean',
        'waitlisted'     => 'boolean',
        'consented_at'   => 'datetime',
    ];

    /**
     * Keep `name` in step with the split fields.
     *
     * The admin list, the CSV export and the panel confirmation email all read
     * `name`. Rather than change three consumers so a form can collect two
     * fields, the composed value is maintained here — one place, and no way for
     * the two representations to drift.
     */
    protected static function booted(): void
    {
        static::saving(function (self $registration) {
            if ($registration->first_name || $registration->last_name) {
                $registration->name = trim($registration->first_name.' '.$registration->last_name);
            }
        });
    }

    /**
     * The panel this person registered for.
     *
     * Nullable: registrations taken before the panel link existed have none,
     * and someone can register interest when nothing is scheduled.
     */
    public function panelSession(): BelongsTo
    {
        return $this->belongsTo(PanelSession::class);
    }

    public function scopeWantsToSpeak($query)
    {
        return $query->where('wants_to_speak', true);
    }

    public function scopeWaitlisted($query)
    {
        return $query->where('waitlisted', true);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('waitlisted', false);
    }

    /**
     * What to call someone in an email.
     *
     * Falls back through the split field, the composed one, then the email's
     * local part, then a greeting that works without a name at all. A
     * confirmation that opens "Hi ," is worse than one that opens "Hi there".
     */
    public function firstName(): string
    {
        if ($this->first_name) {
            return $this->first_name;
        }

        if ($this->name && ($part = trim(strtok($this->name, ' ')))) {
            return $part;
        }

        return 'there';
    }

    /**
     * The audience groups the programme was designed around, in plain words.
     *
     * These are the labels shown on the form, kept here so the form, the admin
     * list and the staff notification cannot describe the same person three
     * different ways.
     */
    public static function audienceGroups(): array
    {
        return [
            'neet'              => 'Not currently in education, employment or training',
            'justice'           => 'Rebuilding after contact with the justice system',
            'returning'         => 'Returning to work after a break or caring',
            'none'              => 'None of these, just curious about digital work',
            'prefer_not_to_say' => 'Prefer not to say',
        ];
    }

    public function audienceLabel(): ?string
    {
        return $this->audience_group
            ? (self::audienceGroups()[$this->audience_group] ?? $this->audience_group)
            : null;
    }

    /**
     * Human-readable audience type, matching the labels on the form.
     */
    public function interestLabel(): string
    {
        return match ($this->interest_type) {
            'learner' => 'Learner or career changer',
            'mentor'  => 'Mentor or industry professional',
            'partner' => 'Partner or employer',
            'curious' => 'Just curious',
            default   => ucfirst((string) $this->interest_type),
        };
    }
}
