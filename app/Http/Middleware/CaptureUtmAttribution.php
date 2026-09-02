<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Remember which campaign link somebody arrived on.
 *
 * The ads point at UTM-tagged URLs, for example
 * /discovery-session?utm_source=facebook&utm_medium=paid_social&utm_campaign=discovery-aug.
 * This notes the tag in the session when they land, so that if they go on to
 * register, the registration can record which platform actually filled the
 * place. Server-side and first-party throughout: no pixel, no third-party
 * script, no cookie beyond the session cookie the site already needs, which is
 * what keeps the cookie policy's "no advertising cookies" line true while the
 * campaigns run.
 *
 * The latest tagged click wins. Somebody who sees the Facebook ad on Tuesday
 * and the TikTok ad on Thursday registered because of Thursday's.
 */
class CaptureUtmAttribution
{
    public const SESSION_KEY = 'utm_attribution';

    /**
     * The longest value worth keeping, matching the column width. Real
     * campaign names are two words; anything longer is somebody probing.
     */
    private const MAX_LENGTH = 100;

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && ($source = $this->clean($request->query('utm_source')))) {
            $request->session()->put(self::SESSION_KEY, [
                'source'   => $source,
                'medium'   => $this->clean($request->query('utm_medium')),
                'campaign' => $this->clean($request->query('utm_campaign')),
            ]);
        }

        return $next($request);
    }

    /**
     * The attribution owed to a registration being saved right now, shaped for
     * the session_registrations columns. Empty when the person arrived
     * untagged, so a spread into updateOrCreate leaves earlier attribution
     * alone instead of overwriting it with nulls.
     */
    public static function forRegistration(Request $request): array
    {
        $utm = $request->session()->get(self::SESSION_KEY);

        if (! is_array($utm) || empty($utm['source'])) {
            return [];
        }

        return [
            'utm_source'   => $utm['source'],
            'utm_medium'   => $utm['medium'] ?? null,
            'utm_campaign' => $utm['campaign'] ?? null,
        ];
    }

    /**
     * Query values are attacker-typed. Anything that is not a plain string of
     * word characters, dots, dashes and spaces is cut down until it is, and an
     * array (utm_source[]=x) or an empty husk becomes null rather than an
     * error page.
     */
    private function clean(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim(preg_replace('/[^\w\-. ]+/u', '', $value) ?? '');

        return $value === ''
            ? null
            : mb_substr($value, 0, self::MAX_LENGTH);
    }
}
