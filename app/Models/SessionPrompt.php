<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** A prompt a speaker used in a live build, for viewers to replicate. */
class SessionPrompt extends Model
{
    protected $fillable = ['panel_session_id', 'panel_speaker_id', 'title', 'body', 'sort_order'];

    public function panelSession(): BelongsTo
    {
        return $this->belongsTo(PanelSession::class);
    }

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(PanelSpeaker::class, 'panel_speaker_id');
    }
}
