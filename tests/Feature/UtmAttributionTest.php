<?php

namespace Tests\Feature;

use App\Models\SessionRegistration;
use App\Models\User;
use Database\Seeders\DiscoverySessionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Campaign attribution without a pixel.
 *
 * The ads for the Discovery Session run on Facebook and TikTok, but the site
 * promises visitors it uses no advertising cookies and no Facebook Pixel, and
 * runs without a consent banner on the strength of that promise. So the
 * question "which platform filled this place" is answered server-side instead:
 * tagged landing, session, registration row. These tests pin down that the
 * answer is recorded, that it is honest, and that hostile input in a public
 * query string stays boring.
 */
class UtmAttributionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DiscoverySessionSeeder::class);
        Mail::fake();
        Http::fake();
    }

    private function registerPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Bola',
            'last_name'  => 'Soko',
            'email'      => 'bola@example.com',
            'consent'    => '1',
        ], $overrides);
    }

    private function registration(): SessionRegistration
    {
        return SessionRegistration::where('email', 'bola@example.com')->firstOrFail();
    }

    public function test_a_tagged_landing_is_recorded_against_the_registration(): void
    {
        $this->get('/discovery-session?utm_source=facebook&utm_medium=paid_social&utm_campaign=discovery-aug')
            ->assertOk();

        $this->post('/discovery-session', $this->registerPayload())
            ->assertSessionHas('success');

        $registration = $this->registration();

        $this->assertSame('facebook', $registration->utm_source);
        $this->assertSame('paid_social', $registration->utm_medium);
        $this->assertSame('discovery-aug', $registration->utm_campaign);
        $this->assertSame('Facebook ad (discovery-aug)', $registration->attributionLabel());
    }

    /**
     * The tag survives ordinary browsing. Nobody registers on the page they
     * land on without looking around first.
     */
    public function test_the_tag_survives_visiting_other_pages_before_registering(): void
    {
        $this->get('/?utm_source=tiktok&utm_medium=paid_social&utm_campaign=discovery-aug');
        $this->get('/about');
        $this->get('/discovery-session');

        $this->post('/discovery-session', $this->registerPayload());

        $this->assertSame('tiktok', $this->registration()->utm_source);
    }

    public function test_an_untagged_registration_records_no_attribution(): void
    {
        $this->get('/discovery-session');
        $this->post('/discovery-session', $this->registerPayload());

        $registration = $this->registration();

        $this->assertNull($registration->utm_source);
        $this->assertNull($registration->attributionLabel());
    }

    /**
     * Two ads seen, one registration: the click that led here gets the credit.
     */
    public function test_the_latest_tagged_click_wins(): void
    {
        $this->get('/discovery-session?utm_source=facebook&utm_medium=paid_social');
        $this->get('/discovery-session?utm_source=tiktok&utm_medium=paid_social');

        $this->post('/discovery-session', $this->registerPayload());

        $this->assertSame('tiktok', $this->registration()->utm_source);
    }

    /**
     * Re-registering from an untagged visit must not wipe the answer already
     * on file. The person updating their phone number a week after clicking
     * the ad is still the ad's registration.
     */
    public function test_re_registering_untagged_keeps_the_original_attribution(): void
    {
        $this->get('/discovery-session?utm_source=facebook&utm_medium=paid_social');
        $this->post('/discovery-session', $this->registerPayload());

        $this->flushSession();

        $this->post('/discovery-session', $this->registerPayload(['phone' => '07700 900123']));

        $registration = $this->registration();

        $this->assertSame('07700 900123', $registration->phone);
        $this->assertSame('facebook', $registration->utm_source);
    }

    /**
     * utm_* parameters are a public query string, so they will be fed script
     * tags, arrays and worse. Whatever arrives, the stored value is a short
     * plain string or nothing, and the page never errors over it.
     */
    public function test_hostile_utm_values_are_stripped_not_stored(): void
    {
        $this->get('/discovery-session?utm_source=face"><script>book</script>&utm_medium='.urlencode(str_repeat('x', 300)))
            ->assertOk();

        $this->post('/discovery-session', $this->registerPayload());

        $registration = $this->registration();

        $this->assertSame('facescriptbookscript', $registration->utm_source);
        $this->assertSame(100, mb_strlen($registration->utm_medium));

        // An array where a string belongs is a probe, not a campaign.
        $this->flushSession();
        $this->get('/discovery-session?utm_source[]=facebook')->assertOk();
    }

    /**
     * The online panel flow records attribution too, alongside the person's
     * own "how did you hear about us" answer rather than instead of it.
     */
    public function test_the_panel_registration_flow_records_attribution(): void
    {
        $this->get('/sessions?utm_source=facebook&utm_medium=paid_social&utm_campaign=panel-2');

        $this->post('/sessions/register', [
            'name'            => 'Bola Soko',
            'email'           => 'bola@example.com',
            'interest_type'   => 'learner',
            'referral_source' => 'A friend told me',
        ]);

        $registration = $this->registration();

        $this->assertSame('A friend told me', $registration->referral_source);
        $this->assertSame('facebook', $registration->utm_source);
        $this->assertSame('panel-2', $registration->utm_campaign);
    }

    public function test_the_admin_screen_shows_where_a_registration_came_from(): void
    {
        $this->get('/discovery-session?utm_source=tiktok&utm_medium=paid_social&utm_campaign=discovery-aug');
        $this->post('/discovery-session', $this->registerPayload());

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.registrations.index'))
            ->assertOk()
            ->assertSee('TikTok ad (discovery-aug)')
            ->assertSee('1 arrived from a tagged campaign link');
    }

    public function test_the_csv_export_carries_the_raw_utm_columns(): void
    {
        $this->get('/discovery-session?utm_source=facebook&utm_medium=paid_social&utm_campaign=discovery-aug');
        $this->post('/discovery-session', $this->registerPayload());

        $admin = User::factory()->create(['role' => 'admin']);

        $csv = $this->actingAs($admin)
            ->get(route('admin.registrations.export'))
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString('UTM source', $csv);
        $this->assertStringContainsString('facebook,paid_social,discovery-aug', $csv);
    }

    /**
     * A tagged organic post is labelled as a link, not an ad, so the admin
     * screen never claims paid credit for a free share.
     */
    public function test_an_organic_tag_is_not_labelled_as_an_ad(): void
    {
        $registration = new SessionRegistration([
            'utm_source' => 'instagram',
            'utm_medium' => 'social',
        ]);

        $this->assertSame('Instagram link', $registration->attributionLabel());
    }
}
