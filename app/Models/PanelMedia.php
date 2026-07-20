<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PanelMedia extends Model
{
    protected $fillable = [
        'panel_session_id', 'type', 'url', 'caption',
        'thumbnail_url', 'sort_order',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(PanelSession::class);
    }

    // Convert any YouTube URL variant to a privacy-friendly embed URL.
    // Uses youtube-nocookie.com to bypass the consent.youtube.com interstitial
    // that some browsers (and some corporate networks) block on embed load.
    // Handles: watch?v=ID, youtu.be/ID, /live/ID, /shorts/ID, /embed/ID.
    public function embedUrl(): string
    {
        if ($this->type === 'video') {
            $url = $this->url;
            if (preg_match('#(?:youtube\.com/(?:watch\?v=|live/|shorts/|embed/)|youtu\.be/)([\w-]+)#', $url, $m)) {
                return 'https://www.youtube-nocookie.com/embed/' . $m[1];
            }
        }
        return $this->url;
    }
}
