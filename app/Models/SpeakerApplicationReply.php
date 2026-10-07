<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * An email sent to a speaker from their pitch page.
 *
 * The email itself goes out from the no-reply address, so without this record
 * nobody could later see what was promised to whom. The pitch page renders
 * these as the correspondence trail under the reply form.
 */
class SpeakerApplicationReply extends Model
{
    protected $fillable = ['speaker_application_id', 'user_id', 'subject', 'body'];

    public function application(): BelongsTo
    {
        return $this->belongsTo(SpeakerApplication::class, 'speaker_application_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
