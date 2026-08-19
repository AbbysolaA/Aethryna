<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Push URL updates to IndexNow-compatible search engines (Bing, Yandex,
 * Seznam, Naver, Yep, DuckDuckGo via Bing). Google does not participate.
 *
 * Usage:
 *   php artisan indexnow:push               # submits every URL in config
 *   php artisan indexnow:push /sessions     # submits one URL
 *   php artisan indexnow:push /a /b /c      # submits several
 *
 * Safe to run without INDEXNOW_KEY set (becomes a no-op with a warning).
 */
class IndexNowPushCommand extends Command
{
    protected $signature = 'indexnow:push {urls?* : Absolute paths like /sessions; defaults to config list}';

    protected $description = 'Ping IndexNow (Bing, Yandex, and downstream engines) with updated URLs.';

    public function handle(): int
    {
        $key      = config('services.indexnow.key');
        $endpoint = config('services.indexnow.endpoint');
        $host     = config('services.indexnow.host');

        if (! $key) {
            $this->warn('INDEXNOW_KEY not set. Skipping IndexNow push.');
            return self::SUCCESS;
        }

        $paths = $this->argument('urls');
        if (empty($paths)) {
            // Every public URL, not just the fixed list in config.
            //
            // This used to read config('services.indexnow.urls') alone, which
            // is sixteen static pages. The sitemap appended panels and courses
            // from the database and this did not, so the twenty-one pages most
            // in need of announcing — every panel, every course — were the only
            // ones never submitted. Both now build from App\Support\SiteUrls.
            $paths = \App\Support\SiteUrls::all();
        }

        if (empty($paths)) {
            $this->error('No URLs to submit. Add paths to config/services.php or pass them as arguments.');
            return self::FAILURE;
        }

        $urlList = array_map(
            fn ($path) => 'https://' . $host . '/' . ltrim($path, '/'),
            $paths
        );
        // Trailing slash on / becomes just the domain.
        $urlList = array_map(fn ($u) => rtrim($u, '/') ?: 'https://' . $host . '/', $urlList);
        $urlList = array_values(array_unique($urlList));

        $keyLocation = 'https://' . $host . '/' . $key . '.txt';

        $this->info(sprintf('Pushing %d URLs to IndexNow at %s...', count($urlList), $endpoint));

        $response = Http::acceptJson()
            ->asJson()
            ->timeout(15)
            ->post($endpoint, [
                'host'        => $host,
                'key'         => $key,
                'keyLocation' => $keyLocation,
                'urlList'     => $urlList,
            ]);

        $status = $response->status();
        $body   = Str::limit($response->body(), 500);

        $reason = match (true) {
            $status === 200 => 'URLs submitted successfully.',
            $status === 202 => 'URLs accepted; validation pending.',
            $status === 400 => 'Bad request — invalid JSON or schema.',
            $status === 403 => 'Forbidden — key not valid (verification file may be missing or wrong).',
            $status === 422 => 'Unprocessable — URLs do not belong to host, or key schema mismatch.',
            $status === 429 => 'Rate limited — try again later.',
            default         => 'Unexpected status.',
        };

        if ($response->successful()) {
            $this->info("[$status] $reason");
            return self::SUCCESS;
        }

        $this->error("[$status] $reason");
        if ($body !== '') {
            $this->line('Response body: ' . $body);
        }
        return self::FAILURE;
    }
}
