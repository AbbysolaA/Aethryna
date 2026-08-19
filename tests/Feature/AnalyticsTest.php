<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * What the site measures, and what it tells people it measures.
 *
 * Analytics here is cookieless by decision, not by accident: cookie-based
 * measurement needs a consent banner under PECR, and a banner is a cost paid by
 * every visitor on every visit — heaviest on a phone, which is what this
 * audience uses. So the beacon sets nothing and stores no identifier.
 *
 * The part worth testing is not the script tag. It is that the cookie policy
 * and the privacy notice describe whatever is actually running. A page that
 * says "we use no analytics" while a beacon loads underneath it is a false
 * statement in a legal document, and it would be an easy one to introduce: the
 * beacon is configured in .env, and nothing else forces the prose to follow.
 */
class AnalyticsTest extends TestCase
{
    // The home page reads pathways, so the schema has to exist even though
    // nothing here writes a row.
    use RefreshDatabase;

    /**
     * Nothing configured: no beacon anywhere, and the legal pages say so.
     *
     * This is the default and the state production runs in while the beacon is
     * injected at the Cloudflare edge instead.
     */
    public function test_no_beacon_and_no_analytics_claim_when_unconfigured(): void
    {
        config(['services.cloudflare_analytics.token' => null]);

        $this->get('/')->assertOk()->assertDontSee('cloudflareinsights');

        $this->get('/cookies')
            ->assertOk()
            ->assertDontSee('cloudflareinsights')
            ->assertSee('Google Analytics or any other web analytics platform')
            ->assertDontSee('The one thing we do measure');
    }

    /**
     * Configured: the beacon renders, and both legal pages own up to it.
     *
     * The negative assertion is the point of the pair — the "no analytics
     * platform of any kind" line has to disappear, not merely be joined by a
     * correction further down the page.
     *
     * "Cookie-based analytics of any kind" survives in both states, and should:
     * a cookieless beacon does not make that sentence false.
     */
    public function test_beacon_and_honest_legal_copy_when_configured(): void
    {
        config(['services.cloudflare_analytics.token' => 'test-token-not-a-real-one']);

        $this->get('/')
            ->assertOk()
            ->assertSee('static.cloudflareinsights.com/beacon.min.js')
            ->assertSee('test-token-not-a-real-one', false);

        $this->get('/cookies')
            ->assertOk()
            ->assertSee('The one thing we do measure')
            ->assertDontSee('Google Analytics or any other web analytics platform')
            ->assertSee('Cookie-based analytics of any kind');

        $this->get('/privacy')
            ->assertOk()
            ->assertSee('Cloudflare Web Analytics');
    }

    /**
     * The beacon is deferred, and it is the last thing in the body.
     *
     * Measurement must never be ahead of the page in the download queue. A
     * blocking analytics script on a slow connection delays the content the
     * visitor actually came for, to collect a number about how long they waited.
     */
    public function test_beacon_does_not_block_rendering(): void
    {
        config(['services.cloudflare_analytics.token' => 'test-token-not-a-real-one']);

        $html = $this->get('/')->assertOk()->getContent();

        $beaconAt = strpos($html, 'static.cloudflareinsights.com');
        $mainAt   = strpos($html, '</main>');

        $this->assertNotFalse($beaconAt);
        $this->assertGreaterThan($mainAt, $beaconAt, 'The beacon should come after the page content.');
        $this->assertStringContainsString('<script defer', substr($html, $beaconAt - 200, 200));
    }
}
