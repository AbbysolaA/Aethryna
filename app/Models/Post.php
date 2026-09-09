<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'standfirst',
        'body',
        'author_name',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /** Posts are addressed by slug everywhere a person sees a URL. */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->isPast();
    }

    /**
     * Published and not post-dated. A future published_at is a scheduled
     * post: saved, invisible, and live the moment the clock passes it.
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function url(): string
    {
        return route('blog.show', $this);
    }

    /**
     * Any YouTube URL variant, when it is the only thing on its line.
     * Inline links in a sentence are left alone: "as shown in this video"
     * should stay a link, not balloon into a player mid-paragraph.
     */
    private const YOUTUBE_LINE = '#^[ \t]*https?://(?:www\.)?(?:youtube\.com/(?:watch\?v=|live/|shorts/|embed/)|youtu\.be/)([\w-]{6,20})\S*[ \t]*$#m';

    /**
     * The body, rendered.
     *
     * html_input strip: the body is Markdown, and any raw HTML pasted into it
     * is dropped rather than trusted. Admins write the posts today, but a
     * rendering pipeline that is safe regardless of who writes never needs
     * revisiting when that changes.
     *
     * The one exception is deliberate and narrow: a YouTube link alone on a
     * line becomes an embedded player, because stripping HTML also strips a
     * pasted embed code and event write-ups are told in video. The markup is
     * substituted in after rendering, built only from the captured video id
     * (word characters and dashes), and uses youtube-nocookie.com like every
     * other embed on the site.
     */
    public function bodyHtml(): string
    {
        $embeds = [];

        // Swap each video line for a placeholder that Markdown will pass
        // through untouched, render, then swap the wrapping paragraph for the
        // player. The placeholder alphabet is [\w@:-], so it cannot open a
        // tag or escape the paragraph it lands in.
        $markdown = preg_replace_callback(self::YOUTUBE_LINE, function ($m) use (&$embeds) {
            $token = '@@youtube:'.$m[1].'@@';

            $embeds['<p>'.$token.'</p>'] = '<div class="bl-video">'
                .'<iframe src="https://www.youtube-nocookie.com/embed/'.$m[1].'"'
                .' title="Video" loading="lazy" frameborder="0" allowfullscreen'
                .' allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>'
                .'</div>';

            return $token;
        }, $this->body);

        $html = Str::markdown($markdown, [
            'html_input'         => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return $embeds ? strtr($html, $embeds) : $html;
    }

    /**
     * Honest to the nearest minute, floored at one. Shown on the index so a
     * reader can tell a two-minute answer from a long read before clicking.
     */
    public function readingMinutes(): int
    {
        return max(1, (int) round(str_word_count(strip_tags($this->body)) / 200));
    }

    public function authorName(): string
    {
        return $this->author_name ?: 'Skills Co-op';
    }
}
