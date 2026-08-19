<?php

namespace App\Support;

use App\Models\PanelSession;
use App\Models\Pathway;

/**
 * Every public URL, in one place.
 *
 * There were two lists and they had drifted. The sitemap started from the
 * config list and then appended panels and courses from the database; the
 * IndexNow command used the config list alone. So the pages most in need of
 * announcing — twenty-one of them, every panel and every course — were in the
 * sitemap and never pushed, while a comment in routes/web.php promised the two
 * stayed in sync.
 *
 * Anything database-backed belongs here rather than in config, because a new
 * panel or course should be submitted without anybody remembering to add a
 * line to a file.
 */
class SiteUrls
{
    /**
     * Absolute paths for every page worth indexing.
     *
     * Ordered deliberately: the fixed pages first, then panels, then courses
     * with the pilot tracks ahead of the rest. Crawlers work a budget, and the
     * four tracks somebody can actually enrol on should be reached before the
     * thirteen they cannot.
     *
     * @return array<int, string>
     */
    public static function all(): array
    {
        return array_values(array_unique(array_merge(
            config('services.indexnow.urls', []),
            self::panels(),
            self::courses(),
        )));
    }

    /** @return array<int, string> */
    public static function panels(): array
    {
        return PanelSession::orderBy('sort_order')
            ->pluck('slug')
            ->map(fn ($slug) => '/sessions/' . $slug)
            ->all();
    }

    /** @return array<int, string> */
    public static function courses(): array
    {
        return Pathway::active()
            ->orderByDesc('is_pilot')
            ->orderBy('name')
            ->pluck('slug')
            ->map(fn ($slug) => '/programs/' . $slug)
            ->all();
    }
}
