<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionRegistration extends Model
{
    protected $fillable = [
        'panel_session_id',
        'name',
        'email',
        'interest_type',
        'referral_source',
        'wants_to_speak',
        'speaker_topic',
    ];

    protected $casts = [
        'wants_to_speak' => 'boolean',
    ];

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
