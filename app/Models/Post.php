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
     * The body, rendered.
     *
     * html_input strip: the body is Markdown, and any raw HTML pasted into it
     * is dropped rather than trusted. Admins write the posts today, but a
     * rendering pipeline that is safe regardless of who writes never needs
     * revisiting when that changes.
     */
    public function bodyHtml(): string
    {
        return Str::markdown($this->body, [
            'html_input'         => 'strip',
            'allow_unsafe_links' => false,
        ]);
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
