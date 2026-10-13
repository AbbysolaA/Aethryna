<?php

namespace Tests\Feature;

use App\Models\PanelSpeaker;
use App\Models\SpeakerApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Speaker photos without SSH: accepting a pitch publishes its headshot, the
 * speakers screen uploads or reuses one, and a speaker who arrived as a
 * pitch links back to their full pitch page.
 */
class SpeakerPhotoAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        Storage::fake('local');
        Storage::fake('speaker_images');
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function pitchWithHeadshot(): SpeakerApplication
    {
        $this->post('/apply-to-speak', [
            'name'              => 'Omonigho Okome',
            'email'             => 'omonigho@example.com',
            'organisation'      => 'Rakuten Viber',
            'job_title'         => 'Product Manager',
            'bio'               => 'A decade in product.',
            'talk_title'        => 'Breaking into product from support',
            'talk_summary'      => 'What the move really takes.',
            'consent'           => '1',
            'recording_consent' => '1',
            'headshot'          => UploadedFile::fake()->image('omonigho.jpg', 900, 1200),
        ]);

        return SpeakerApplication::firstOrFail();
    }

    public function test_accepting_a_pitch_publishes_its_headshot(): void
    {
        $application = $this->pitchWithHeadshot();

        $speaker = $application->accept();

        $this->assertNotNull($speaker->photo_path);
        $this->assertStringStartsWith('images/speakers/', $speaker->photo_path);
        Storage::disk('speaker_images')->assertExists(basename($speaker->photo_path));

        // Accepting again does not publish a second copy over it.
        $before = $speaker->photo_path;
        $application->accept();
        $this->assertSame($before, $speaker->fresh()->photo_path);
    }

    public function test_the_speakers_screen_can_reuse_the_pitch_headshot(): void
    {
        $application = $this->pitchWithHeadshot();
        $speaker = PanelSpeaker::create(['name' => 'Omonigho Okome', 'title' => 'Product Manager', 'bio' => 'A decade in product.']);
        $application->forceFill(['panel_speaker_id' => $speaker->id, 'status' => 'accepted'])->save();

        $this->actingAs($this->admin())
            ->post(route('admin.speakers.photo', $speaker), ['from_pitch' => '1'])
            ->assertSessionHas('status');

        $speaker->refresh();
        $this->assertNotNull($speaker->photo_path);
        Storage::disk('speaker_images')->assertExists(basename($speaker->photo_path));
    }

    public function test_the_speakers_screen_can_upload_a_photo(): void
    {
        $speaker = PanelSpeaker::create(['name' => 'Miriam Fearon', 'title' => 'Customer Success Leader', 'bio' => 'Customer success.']);

        $this->actingAs($this->admin())
            ->post(route('admin.speakers.photo', $speaker), [
                'photo' => UploadedFile::fake()->image('miriam.png', 1000, 1400),
            ])
            ->assertSessionHas('status');

        $speaker->refresh();
        $this->assertStringStartsWith('images/speakers/', $speaker->photo_path);
        $this->assertStringEndsWith('.jpg', $speaker->photo_path);
        Storage::disk('speaker_images')->assertExists(basename($speaker->photo_path));

        // Replacing deletes the published original.
        $first = basename($speaker->photo_path);
        $this->actingAs($this->admin())->post(route('admin.speakers.photo', $speaker), [
            'photo' => UploadedFile::fake()->image('miriam-two.png', 1000, 1400),
        ]);

        Storage::disk('speaker_images')->assertMissing($first);
        $this->assertNotSame($first, basename($speaker->fresh()->photo_path));
    }

    public function test_an_unreadable_upload_is_refused_politely(): void
    {
        $speaker = PanelSpeaker::create(['name' => 'Sean Adetule', 'title' => 'Partnerships', 'bio' => 'Partnerships.']);

        $this->actingAs($this->admin())
            ->post(route('admin.speakers.photo', $speaker), [
                'photo' => UploadedFile::fake()->create('not-a-picture.jpg', 60, 'image/jpeg'),
            ])
            ->assertSessionHas('error');

        $this->assertNull($speaker->fresh()->photo_path);
    }

    /**
     * Every row's upload shares a field name, so one speaker's validation
     * error used to print under all of them. Errors are scoped per speaker.
     */
    public function test_an_upload_error_stays_on_its_own_row(): void
    {
        $speaker = PanelSpeaker::create(['name' => 'Ore Badmus', 'title' => 'Marketing', 'bio' => 'M.']);
        $other = PanelSpeaker::create(['name' => 'Sean Adetule', 'title' => 'Partnerships', 'bio' => 'P.']);

        $this->actingAs($this->admin())
            ->post(route('admin.speakers.photo', $speaker), [
                'photo' => UploadedFile::fake()->create('cv.pdf', 60, 'application/pdf'),
            ])
            ->assertSessionHasErrorsIn('photo'.$speaker->id, ['photo'])
            ->assertSessionDoesntHaveErrors(['photo'], null, 'photo'.$other->id);
    }

    public function test_speaker_names_link_to_their_pitch_page(): void
    {
        $application = $this->pitchWithHeadshot();
        $speaker = $application->accept();
        PanelSpeaker::create(['name' => 'No Pitch Speaker', 'title' => 'Added by hand', 'bio' => 'Added by hand.']);

        $this->actingAs($this->admin())
            ->get(route('admin.speakers.index'))
            ->assertOk()
            ->assertSee(route('admin.speaker-applications.show', $application), false)
            ->assertSee('Arrived as a pitch, read it in full')
            ->assertSee('No Pitch Speaker');
    }
    /**
     * Returning speakers are the series' model: Bola spoke at Panel 3 and
     * joins the founders session too, from her row on the speakers screen.
     */
    public function test_a_speaker_can_be_added_to_another_panel_from_their_row(): void
    {
        $this->travelTo('2026-10-13 12:00:00');

        $three = \App\Models\PanelSession::create([
            'title' => 'The Skills Co-op Sessions: Panel 3', 'slug' => 'panel-3',
            'tagline' => 'Panel 3 · The data skills gap', 'description' => 'D',
            'event_date' => '2026-09-09 18:30:00', 'status' => 'past', 'sort_order' => 3,
        ]);
        $founders = \App\Models\PanelSession::create([
            'title' => 'The Skills Co-op Sessions: Panel 4', 'slug' => 'founders-build-live',
            'tagline' => 'Vibe Coding: Founders Build Live', 'description' => 'D',
            'event_date' => '2026-11-10 18:30:00', 'status' => 'upcoming', 'sort_order' => 4,
        ]);

        $bola = PanelSpeaker::create(['name' => 'Bola Soko', 'title' => 'Engineer', 'bio' => 'B.']);
        $three->speakers()->attach($bola->id, ['sort_order' => 1]);

        // Her row offers the founders panel and labels the button honestly.
        $this->actingAs($this->admin())
            ->get(route('admin.speakers.index'))
            ->assertOk()
            ->assertSee('Panel 3 · The data skills gap')
            ->assertSee('Add Bola to this panel');

        $this->actingAs($this->admin())
            ->post(route('admin.speakers.attach', $bola), ['panel_session_id' => $founders->id])
            ->assertSessionHas('status');

        $this->assertTrue($founders->speakers()->whereKey($bola->id)->exists());
        $this->assertTrue($three->speakers()->whereKey($bola->id)->exists());

        // Pressing it twice cannot double her onto the panel.
        $this->actingAs($this->admin())
            ->post(route('admin.speakers.attach', $bola), ['panel_session_id' => $founders->id]);
        $this->assertSame(1, $founders->speakers()->whereKey($bola->id)->count());
    }
    /**
     * The Ore case: added by hand with dual roles written as they should
     * print, a session topic, a photo, and straight onto a panel, all from
     * the one form.
     */
    public function test_a_speaker_can_be_added_with_dual_roles_topic_and_panel(): void
    {
        $this->travelTo('2026-10-13 12:00:00');

        $founders = \App\Models\PanelSession::create([
            'title' => 'The Skills Co-op Sessions: Panel 4', 'slug' => 'founders-build-live',
            'tagline' => 'Vibe Coding: Founders Build Live', 'description' => 'D',
            'event_date' => '2026-11-10 18:30:00', 'status' => 'upcoming', 'sort_order' => 4,
        ]);

        $this->actingAs($this->admin())->post(route('admin.speakers.store'), [
            'name'             => 'Ore Badmus',
            'title'            => 'Senior Product Marketing Manager, Locaria; Co-founder, TEMSI Africa',
            'bio'              => 'Marketing and product.',
            'panel_session_id' => $founders->id,
            'topic'            => 'From Prompt to Product: Build a Research Analysis Tool with Claude',
            'photo'            => UploadedFile::fake()->image('ore.jpg', 900, 900),
        ])->assertSessionHas('status');

        $ore = PanelSpeaker::where('name', 'Ore Badmus')->firstOrFail();

        $this->assertSame('Senior Product Marketing Manager, Locaria; Co-founder, TEMSI Africa', $ore->title);
        $this->assertNotNull($ore->photo_path);
        Storage::disk('speaker_images')->assertExists(basename($ore->photo_path));

        $pivot = $founders->speakers()->whereKey($ore->id)->first()->pivot;
        $this->assertSame('From Prompt to Product: Build a Research Analysis Tool with Claude', $pivot->topic);

        // And the panel page prints the slot title.
        $this->get('/sessions/founders-build-live')
            ->assertOk()
            ->assertSee('Ore Badmus')
            ->assertSee('From Prompt to Product');
    }

    public function test_the_row_attach_control_takes_a_topic(): void
    {
        $this->travelTo('2026-10-13 12:00:00');

        $founders = \App\Models\PanelSession::create([
            'title' => 'The Skills Co-op Sessions: Panel 4', 'slug' => 'founders-build-live',
            'tagline' => 'Vibe Coding: Founders Build Live', 'description' => 'D',
            'event_date' => '2026-11-10 18:30:00', 'status' => 'upcoming', 'sort_order' => 4,
        ]);
        $speaker = PanelSpeaker::create(['name' => 'Abby Areola', 'title' => 'Founder', 'bio' => 'F.']);

        $this->actingAs($this->admin())->post(route('admin.speakers.attach', $speaker), [
            'panel_session_id' => $founders->id,
            'topic'            => 'Building Skills Co-op with AI',
        ])->assertSessionHas('status');

        $this->assertSame(
            'Building Skills Co-op with AI',
            $founders->speakers()->whereKey($speaker->id)->first()->pivot->topic
        );
    }
    /**
     * Every speaker has a page now, pitch or not, and their details are
     * editable there: a typo in a hand-added bio used to be permanent.
     */
    public function test_every_speaker_name_opens_an_editable_page(): void
    {
        $ore = PanelSpeaker::create(['name' => 'Ore Badmus', 'title' => 'Marketing', 'bio' => 'M.']);

        $this->actingAs($this->admin())
            ->get(route('admin.speakers.index'))
            ->assertOk()
            ->assertSee(route('admin.speakers.edit', $ore), false)
            ->assertSee('Click to edit their details');

        $this->actingAs($this->admin())
            ->get(route('admin.speakers.edit', $ore))
            ->assertOk()
            ->assertSee('Ore Badmus')
            ->assertSee('Not on a panel yet');

        $this->actingAs($this->admin())->patch(route('admin.speakers.update', $ore), [
            'name'  => 'Ore Badmus',
            'title' => 'Senior Product Marketing Manager, Locaria; Co-founder, TEMSI Africa',
            'bio'   => 'Marketing leader and co-founder.',
        ])->assertSessionHas('status');

        $this->assertSame(
            'Senior Product Marketing Manager, Locaria; Co-founder, TEMSI Africa',
            $ore->fresh()->title
        );
    }
    /**
     * The add control has an undo now: a mis-click put Seun on a session
     * with no way to take him off.
     */
    public function test_a_speaker_can_be_removed_from_a_panel(): void
    {
        $this->travelTo('2026-10-13 12:00:00');

        $founders = \App\Models\PanelSession::create([
            'title' => 'P4b', 'slug' => 'founders-build-live',
            'tagline' => 'Vibe Coding: Founders Build Live', 'description' => 'D',
            'event_date' => '2026-11-10 18:30:00', 'status' => 'upcoming', 'sort_order' => 4,
        ]);
        $seun = PanelSpeaker::create(['name' => 'Seun Adetule', 'title' => 'Partnerships', 'bio' => 'P.']);
        $founders->speakers()->attach($seun->id, ['sort_order' => 1]);

        // The list shows the chip with its remove control.
        $this->actingAs($this->admin())
            ->get(route('admin.speakers.index'))
            ->assertOk()
            ->assertSee(route('admin.speakers.detach', [$seun, $founders]), false);

        $this->actingAs($this->admin())
            ->delete(route('admin.speakers.detach', [$seun, $founders]))
            ->assertSessionHas('status');

        $this->assertFalse($founders->speakers()->whereKey($seun->id)->exists());
        $this->assertNotNull($seun->fresh(), 'Removing from a panel must not delete the speaker.');

        // Off the panel, his row offers to add him again.
        $this->actingAs($this->admin())
            ->get(route('admin.speakers.index'))
            ->assertSee('Add Seun to this panel');
    }

    public function test_attaching_with_a_topic_confirms_the_topic(): void
    {
        $this->travelTo('2026-10-13 12:00:00');

        $founders = \App\Models\PanelSession::create([
            'title' => 'P4b', 'slug' => 'founders-build-live',
            'tagline' => 'Vibe Coding: Founders Build Live', 'description' => 'D',
            'event_date' => '2026-11-10 18:30:00', 'status' => 'upcoming', 'sort_order' => 4,
        ]);
        $ore = PanelSpeaker::create(['name' => 'Ore Badmus', 'title' => 'Marketing', 'bio' => 'M.']);

        $this->actingAs($this->admin())
            ->post(route('admin.speakers.attach', $ore), [
                'panel_session_id' => $founders->id,
                'topic'            => 'From Prompt to Product',
            ])
            ->assertSessionHas('status', fn ($status) => str_contains($status, 'From Prompt to Product'));
    }
    /**
     * Re-posting an attached pair edits the topic in place: Ore and Abby
     * were on the panel with no topic and no way to add one afterwards.
     */
    public function test_the_topic_can_be_edited_after_attachment(): void
    {
        $this->travelTo('2026-10-13 12:00:00');

        $founders = \App\Models\PanelSession::create([
            'title' => 'P4b', 'slug' => 'founders-build-live',
            'tagline' => 'Vibe Coding: Founders Build Live', 'description' => 'D',
            'event_date' => '2026-11-10 18:30:00', 'status' => 'upcoming', 'sort_order' => 4,
        ]);
        $ore = PanelSpeaker::create(['name' => 'Ore Badmus', 'title' => 'Marketing', 'bio' => 'M.']);
        $founders->speakers()->attach($ore->id, ['sort_order' => 1]);

        $this->actingAs($this->admin())
            ->post(route('admin.speakers.attach', $ore), [
                'panel_session_id' => $founders->id,
                'topic'            => 'From Prompt to Product',
            ])
            ->assertSessionHas('status', fn ($m) => str_contains($m, 'is now "From Prompt to Product"'));

        $pivot = $founders->speakers()->whereKey($ore->id)->first()->pivot;
        $this->assertSame('From Prompt to Product', $pivot->topic);
        $this->assertSame(1, (int) $pivot->sort_order, 'Editing the topic must not change the running order.');
        $this->assertSame(1, $founders->speakers()->whereKey($ore->id)->count());

        // The speaker page offers the inline topic form.
        $this->actingAs($this->admin())
            ->get(route('admin.speakers.edit', $ore))
            ->assertOk()
            ->assertSee('Save topic')
            ->assertSee('From Prompt to Product');
    }

    /**
     * A company already written into the role line is not printed twice:
     * "Senior PMM, Locaria; Co-founder, TEMSI Africa · Locaria" was live.
     */
    public function test_the_company_is_not_printed_twice(): void
    {
        $this->travelTo('2026-10-13 12:00:00');

        $panel = \App\Models\PanelSession::create([
            'title' => 'P4b', 'slug' => 'founders-build-live',
            'tagline' => 'Vibe Coding: Founders Build Live', 'description' => 'D',
            'event_date' => '2026-11-10 18:30:00', 'status' => 'upcoming', 'sort_order' => 4,
        ]);
        $ore = PanelSpeaker::create([
            'name'    => 'Ore Badmus',
            'title'   => 'Senior Product Marketing Manager, Locaria; Co-founder, TEMSI Africa',
            'company' => 'Locaria',
            'bio'     => 'M.',
        ]);
        $panel->speakers()->attach($ore->id, ['sort_order' => 1]);

        $html = $this->get('/sessions/founders-build-live')->assertOk()->getContent();

        // The JSON-LD performer block also carries the title, so the text
        // appears there too; what must not appear is the visible card adding
        // the company again after the roles.
        $this->assertStringNotContainsString('TEMSI Africa <span class="ss-role-sep">', $html);
        $this->assertSame('Senior Product Marketing Manager, Locaria; Co-founder, TEMSI Africa', $ore->roleLine());
    }
}
