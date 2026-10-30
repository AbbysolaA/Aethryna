<?php

namespace Tests\Feature;

use App\Models\PanelSession;
use App\Models\PanelSpeaker;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The last three technical SEO gaps: posts carry their own share image,
 * speaker photos are real images a search engine can see, and the sitemap
 * tells crawlers when each page actually changed.
 */
class SeoPolishTest extends TestCase
{
    use RefreshDatabase;

    private function makePost(array $overrides = []): Post
    {
        return Post::create(array_merge([
            'title'        => 'Can I get into tech without a degree?',
            'slug'         => 'tech-without-a-degree',
            'standfirst'   => 'Short answer: yes.',
            'body'         => "## The short answer\n\nYes.",
            'published_at' => now()->subDay(),
        ], $overrides));
    }

    public function test_a_post_with_a_picture_shares_that_picture(): void
    {
        $post = $this->makePost([
            'body' => "Opening words.\n\n![The room at Panel 4](/images/blog/panel-room.jpg)\n\nMore words.",
        ]);

        $this->assertStringEndsWith('/images/blog/panel-room.jpg', $post->coverImageUrl());

        $this->get('/blog/tech-without-a-degree')
            ->assertOk()
            ->assertSee('property="og:image" content="'.$post->coverImageUrl().'"', false)
            ->assertSee('"image": "'.$post->coverImageUrl().'"', false);
    }

    public function test_a_post_without_a_picture_keeps_the_site_image(): void
    {
        $this->makePost();

        $this->get('/blog/tech-without-a-degree')
            ->assertOk()
            ->assertSee('images/og-image.png', false)
            ->assertDontSee('"image"', false);
    }

    public function test_speaker_photos_are_real_images_with_alt_text(): void
    {
        $this->travelTo('2026-10-30 12:00:00');

        $panel = PanelSession::create([
            'title' => 'P4b', 'slug' => 'founders-build-live',
            'tagline' => 'Vibe Coding: Founders Build Live', 'description' => 'D',
            'event_date' => '2026-11-10 18:30:00', 'status' => 'upcoming', 'sort_order' => 4,
        ]);
        $speaker = PanelSpeaker::create(['name' => 'Ayse Basak Cinar', 'title' => 'AI Strategist', 'bio' => 'B.']);
        $panel->speakers()->attach($speaker->id, ['sort_order' => 1]);

        $html = $this->get('/sessions/founders-build-live')->assertOk()->getContent();

        $this->assertStringContainsString('alt="Ayse Basak Cinar"', $html);
        $this->assertMatchesRegularExpression('/<img[^>]+alt="Ayse Basak Cinar"[^>]+loading="lazy"/s', $html);
        $this->assertStringNotContainsString("background-image:url('", $html);
    }

    public function test_the_sitemap_carries_real_dates_per_page(): void
    {
        $this->travelTo('2026-09-01 10:00:00');
        $this->makePost();

        $this->travelTo('2026-10-30 12:00:00');
        PanelSession::create([
            'title' => 'P4b', 'slug' => 'founders-build-live',
            'tagline' => 'Vibe Coding: Founders Build Live', 'description' => 'D',
            'event_date' => '2026-11-10 18:30:00', 'status' => 'upcoming', 'sort_order' => 4,
        ]);

        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

        // The post keeps its September date while the panel carries October:
        // not one deploy-time stamp across the board.
        $this->assertMatchesRegularExpression(
            '#<loc>https://skillscoop\.org/blog/tech-without-a-degree</loc>\s*<lastmod>2026-09-01#',
            $xml
        );
        $this->assertMatchesRegularExpression(
            '#<loc>https://skillscoop\.org/sessions/founders-build-live</loc>\s*<lastmod>2026-10-30#',
            $xml
        );
    }
}
