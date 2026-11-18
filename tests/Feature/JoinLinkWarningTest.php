<?php

namespace Tests\Feature;

use App\Models\PanelSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * An online session close to its date with no joining link is flagged in
 * the admin, because the day-before reminder goes out regardless.
 */
class JoinLinkWarningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo('2026-11-16 12:00:00');
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function panel(array $overrides = []): PanelSession
    {
        return PanelSession::create(array_merge([
            'title'       => 'The Skills Co-op Sessions: Panel 5',
            'slug'        => 'panel-5',
            'tagline'     => 'Panel 5: Data Live',
            'description' => 'D',
            'event_date'  => '2026-11-18 18:30:00',
            'status'      => 'upcoming',
            'sort_order'  => 5,
        ], $overrides));
    }

    public function test_a_session_days_away_without_a_link_is_flagged_everywhere(): void
    {
        $panel = $this->panel();

        $this->actingAs($this->admin());

        foreach ([route('admin.dashboard'), route('admin.panels.index')] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('Panel 5: Data Live')
                ->assertSee('has no joining link')
                ->assertSee(route('admin.panels.edit', $panel), false);
        }

        $this->get(route('admin.panels.edit', $panel))
            ->assertOk()
            ->assertSee('will go out without a link');
    }

    public function test_no_warning_once_the_link_is_set_or_when_it_does_not_apply(): void
    {
        // Link set.
        $this->panel(['join_url' => 'https://streamyard.com/watch/abc']);
        // More than three days away.
        $this->panel(['slug' => 'panel-6', 'tagline' => 'Panel 6 Far Off', 'event_date' => '2026-12-10 18:30:00']);
        // Already happened.
        $this->panel(['slug' => 'panel-3', 'tagline' => 'Panel 3 Done', 'event_date' => '2026-11-15 18:30:00', 'status' => 'past']);
        // In person: there is no link to set.
        $this->panel(['slug' => 'meetup', 'tagline' => 'In-person Meetup', 'venue_name' => 'The Hub']);

        $this->assertTrue(PanelSession::missingJoinLinkSoon()->isEmpty());

        $this->actingAs($this->admin())
            ->get(route('admin.panels.index'))
            ->assertOk()
            ->assertDontSee('has no joining link');
    }
}
