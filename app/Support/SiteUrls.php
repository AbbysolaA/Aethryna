<?php

namespace App\Support;

use App\Models\PanelSession;
use App\Models\Pathway;
use App\Models\Post;
use App\Models\VolunteerRole;

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
     * five tracks somebody can actually enrol on should be reached before the
     * thirteen they cannot.
     *
     * @return array<int, string>
     */
    public static function all(): array
    {
        return array_keys(self::allWithDates());
    }

    /**
     * The same URLs with the honest last-modified date for each, for the
     * sitemap. The old sitemap stamped every URL with the deploy time, which
     * told crawlers everything changed on every deploy and nothing between
     * deploys; a blog post or panel knows when it really changed. A null
     * date means the page has no record of its own (the static pages) and
     * the sitemap falls back to deploy time for it.
     *
     * Built as one ordered map so all() cannot drift from the dated list.
     *
     * @return array<string, \Illuminate\Support\Carbon|null>
     */
    public static function allWithDates(): array
    {
        $dated = [];

        foreach (config('services.indexnow.urls', []) as $path) {
            $dated[$path] = $dated[$path] ?? null;
        }

        foreach (PanelSession::orderBy('sort_order')->get() as $panel) {
            $path = $panel->landing_path
                ? ($panel->hasHappened() ? null : $panel->landing_path)
                : '/sessions/'.$panel->slug;

            if ($path !== null && ! array_key_exists($path, $dated)) {
                $dated[$path] = $panel->updated_at;
            }
        }

        foreach (Pathway::active()->orderByDesc('is_pilot')->orderBy('name')->get() as $pathway) {
            $dated['/programs/'.$pathway->slug] ??= $pathway->updated_at;
        }

        foreach (self::vacancies() as $path) {
            $dated[$path] = $dated[$path] ?? null;
        }

        foreach (VolunteerRole::paid()->acceptingApplications()->get() as $role) {
            $dated['/careers/'.$role->slug] = $role->updated_at;
        }

        $latestPost = null;

        foreach (Post::published()->orderByDesc('published_at')->get() as $post) {
            $dated['/blog/'.$post->slug] ??= $post->updated_at;
            $latestPost = $latestPost && $latestPost->gt($post->updated_at) ? $latestPost : $post->updated_at;
        }

        $dated['/blog'] = $dated['/blog'] ?? $latestPost;

        return $dated;
    }

    /** @return array<int, string> */
    public static function panels(): array
    {
        // landing_path wins where an event has a page of its own, because
        // /sessions/{slug} redirects to it. Submitting the redirect would ask
        // search engines to index a URL that only points somewhere else. The
        // same rule removes a landing page whose event has happened: that URL
        // now redirects letter-scanners to the referral form, so neither it
        // nor the /sessions/ URL that chains into it belongs in a sitemap.
        return PanelSession::orderBy('sort_order')
            ->get(['slug', 'landing_path', 'event_date', 'status'])
            ->map(function ($panel) {
                if ($panel->landing_path) {
                    return $panel->hasHappened() ? null : $panel->landing_path;
                }

                return '/sessions/'.$panel->slug;
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Open paid vacancies, plus the careers index itself.
     *
     * A job page nobody can find is a job page nobody applies to, and this is
     * an organisation with no recruitment budget. Closed roles are left out:
     * their pages still resolve for anyone holding a link, but there is no
     * reason to ask a search engine to index a post that cannot be applied for.
     *
     * @return array<int, string>
     */
    public static function vacancies(): array
    {
        return array_merge(
            // The speak page rides along here: it is recruiting of a different
            // kind, and it is permanent rather than tied to one vacancy.
            ['/careers', '/apply-to-speak'],
            VolunteerRole::paid()
                ->acceptingApplications()
                ->orderBy('title')
                ->pluck('slug')
                ->map(fn ($slug) => '/careers/'.$slug)
                ->all()
        );
    }

    /**
     * The blog index and every published post. Drafts stay out for the same
     * reason they 404 publicly: a URL submitted to a search engine is a
     * promise there is a page behind it.
     *
     * @return array<int, string>
     */
    public static function posts(): array
    {
        return array_merge(
            ['/blog'],
            Post::published()
                ->orderByDesc('published_at')
                ->pluck('slug')
                ->map(fn ($slug) => '/blog/'.$slug)
                ->all()
        );
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
