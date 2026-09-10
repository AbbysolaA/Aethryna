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
        'review_requested_at',
    ];

    protected $casts = [
        'published_at'            => 'datetime',
        'review_requested_at'     => 'datetime',
        // Set by blog:notify-subscribers only, hence cast but not fillable.
        'subscribers_notified_at' => 'datetime',
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

    /**
     * Finished by its writer, waiting for an admin to press publish.
     */
    public function isAwaitingReview(): bool
    {
        return ! $this->isPublished() && $this->review_requested_at !== null;
    }

    public function scopeAwaitingReview($query)
    {
        return $query->whereNull('published_at')->whereNotNull('review_requested_at');
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
     * [subscribe] alone on a line becomes the inline subscribe form, the way
     * Substack drops its button mid-article. Same only-on-its-own-line rule
     * as the video embeds: mentioning [subscribe] in a sentence is prose.
     */
    private const SUBSCRIBE_LINE = '#^[ \t]*\[subscribe\][ \t]*$#mi';

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

        if (preg_match(self::SUBSCRIBE_LINE, $markdown)) {
            // Rendered once however many times the token appears: the same
            // form twice in one article is nagging, not persuasion. The
            // first occurrence becomes the form, the rest disappear.
            $first = true;
            $markdown = preg_replace_callback(self::SUBSCRIBE_LINE, function () use (&$first) {
                if ($first) {
                    $first = false;

                    return '@@subscribe@@';
                }

                return '';
            }, $markdown);

            $embeds['<p>@@subscribe@@</p>'] = view('blog._subscribe-inline')->render();
        }

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

    /**
     * The author's photo, by convention rather than configuration: a square
     * image named after the author in public/images/authors, for example
     * abisola-areola.jpg. Null when there is none, and the byline falls back
     * to initials, so the feature is complete before any photo is uploaded.
     */
    public function authorPhotoUrl(): ?string
    {
        $slug = Str::slug($this->authorName());

        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            if (is_file(public_path('images/authors/'.$slug.'.'.$ext))) {
                return asset('images/authors/'.$slug.'.'.$ext);
            }
        }

        return null;
    }

    /**
     * At most two initials, so "Skills Co-op" reads SC and a mononym still
     * gets a letter.
     */
    public function authorInitials(): string
    {
        return collect(preg_split('/\s+/', trim($this->authorName())))
            ->filter()
            ->map(fn ($word) => Str::upper(Str::substr($word, 0, 1)))
            ->take(2)
            ->implode('');
    }
}
