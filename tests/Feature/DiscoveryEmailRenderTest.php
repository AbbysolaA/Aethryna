<?php

namespace Tests\Feature;

use App\Mail\DiscoverySessionRegistered;
use App\Mail\DiscoverySessionStaffNotification;
use App\Models\PanelSession;
use App\Models\SessionRegistration;
use Database\Seeders\DiscoverySessionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The confirmation emails, actually rendered.
 *
 * Mail::fake() records that a mailable was sent without ever building it, so a
 * template that throws on render passes every test and fails every visitor. It
 * did: "BOOKED@endif" on one line left the @if unclosed, because Blade only
 * compiles a directive at a non-word boundary, and the whole confirmation died
 * at render. The controller catches and logs, so nobody registering saw
 * anything wrong — they just never got an email. Nor did anyone before them.
 *
 * These tests build the message for real, which is the only way that class of
 * bug is caught before a person is told they have a place and hears nothing.
 */
class DiscoveryEmailRenderTest extends TestCase
{
    use RefreshDatabase;

    private function event(): PanelSession
    {
        $this->seed(DiscoverySessionSeeder::class);

        return PanelSession::where('slug', 'discovery-session')->firstOrFail();
    }

    private function registration(PanelSession $session, bool $waitlisted = false): SessionRegistration
    {
        return SessionRegistration::create([
            'panel_session_id' => $session->id,
            'name'             => "D'Arcy O'Brien",
            'first_name'       => "D'Arcy",
            'last_name'        => "O'Brien",
            'email'            => 'aoife@example.com',
            'interest_type'    => 'learner',
            'waitlisted'       => $waitlisted,
            'consented_at'     => now(),
        ]);
    }

    public function test_the_confirmation_renders_for_a_confirmed_place(): void
    {
        $session = $this->event();
        $body = (new DiscoverySessionRegistered($this->registration($session), $session))->render();

        $this->assertStringContainsString('Wirral Multicultural Organisation', $body);
        $this->assertStringContainsString('Saturday 29 August 2026', $body);
        $this->assertStringNotContainsString('WAITING LIST', $body);
    }

    public function test_the_confirmation_renders_for_the_waiting_list(): void
    {
        $session = $this->event();
        $body = (new DiscoverySessionRegistered($this->registration($session, waitlisted: true), $session))->render();

        $this->assertStringContainsString('waiting list', $body);
    }

    public function test_the_staff_notification_renders(): void
    {
        $session = $this->event();
        $body = (new DiscoverySessionStaffNotification($this->registration($session), $session))->render();

        $this->assertStringContainsString('aoife@example.com', $body);
    }

    /**
     * The plain text part is not HTML and must not be escaped like it.
     *
     * The map link carries a query string, so escaping turned its & into &amp;
     * and the parameter read "amp;query=" — a link to nowhere, in the one email
     * somebody opens while trying to find the building.
     */
    public function test_the_plain_text_part_is_not_html_escaped(): void
    {
        $session = $this->event();
        $mailable = new DiscoverySessionRegistered($this->registration($session), $session);

        $text = $this->textPartOf($mailable);

        $this->assertStringContainsString('&query=', $text, 'The map URL must keep a real ampersand.');
        $this->assertStringNotContainsString('&amp;', $text);
        $this->assertStringNotContainsString('&#039;', $text, "D'Arcy must not arrive as D&#039;Arcy.");
        $this->assertStringContainsString("Hi D'Arcy", $text);
    }

    /**
     * Every plain text template, not just this event's two.
     *
     * The escaping bug was in all twelve, because they were all written the
     * same way. A guard that only covers the two that were noticed would let
     * the next one through.
     */
    public function test_no_plain_text_template_escapes_its_output(): void
    {
        $offenders = [];

        foreach (glob(resource_path('views/emails/*text*.blade.php')) as $file) {
            // Blade comments are stripped before output, so what they quote
            // does not count — including a comment explaining this very rule.
            $body = preg_replace('/\{\{--.*?--\}\}/s', '', file_get_contents($file));

            if (str_contains($body, '{{')) {
                $offenders[] = basename($file);
            }
        }

        $this->assertSame([], $offenders, 'Plain text emails must use {!! !!}: escaping breaks URLs and apostrophes.');
    }

    private function textPartOf(DiscoverySessionRegistered $mailable): string
    {
        $mailable->render();   // builds the view/text pair

        return view($mailable->textView, $mailable->buildViewData())->render();
    }
}
