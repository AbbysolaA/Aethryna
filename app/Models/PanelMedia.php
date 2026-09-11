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
        if ($this->type === 'video' && ($id = $this->youtubeId())) {
            return 'https://www.youtube-nocookie.com/embed/' . $id;
        }
        return $this->url;
    }

    public function youtubeId(): ?string
    {
        if (preg_match('#(?:youtube\.com/(?:watch\?v=|live/|shorts/|embed/)|youtu\.be/)([\w-]+)#', $this->url, $m)) {
            return $m[1];
        }

        return null;
    }

    /**
     * A stored thumbnail wins; otherwise YouTube's own, which exists for
     * every video without anyone maintaining a file.
     */
    public function thumbnailUrl(): ?string
    {
        if ($this->thumbnail_url) {
            return $this->thumbnail_url;
        }

        return ($id = $this->youtubeId())
            ? 'https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg'
            : null;
    }

    /**
     * The VideoObject Search Console asks for: name, description,
     * thumbnailUrl and uploadDate are what turn "video detected on page"
     * warnings into an eligible video result.
     *
     * @return array<string, mixed>
     */
    public function toVideoSchema(PanelSession $session): array
    {
        return array_filter([
            '@context'     => 'https://schema.org',
            '@type'        => 'VideoObject',
            'name'         => $this->caption ?: 'Recording: '.$session->tagline,
            'description'  => $this->caption
                ? $this->caption.'. '.str($session->description)->limit(160)
                : str($session->description)->limit(200)->toString(),
            'thumbnailUrl' => $this->thumbnailUrl(),
            'uploadDate'   => ($session->event_date ?? $this->created_at)?->toIso8601String(),
            'embedUrl'     => $this->embedUrl(),
            'contentUrl'   => $this->url,
        ]);
    }
}
