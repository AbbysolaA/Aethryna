<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PanelSpeaker extends Model
{
    protected $fillable = [
        'name', 'title', 'company', 'bio', 'photo_path',
        'linkedin_url', 'twitter_url',
    ];

    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(
            PanelSession::class,
            'panel_session_speakers',
            'panel_speaker_id',
            'panel_session_id'
        )->withPivot('topic', 'sort_order');
    }

    /**
     * Resolve photo, falling back to a generated initials avatar.
     *
     * The check is on the file, not just the column. Speaker photos are
     * uploaded to the server by hand and are not in version control, so a
     * seeder can name a path that has not been uploaded yet. Testing only the
     * column in that window renders a broken image; testing the file renders
     * the avatar until the real photo lands.
     */
    public function photoUrl(): string
    {
        if ($this->photo_path && file_exists(public_path($this->photo_path))) {
            return asset($this->photo_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=038b89&color=fff&size=200';
    }
}
