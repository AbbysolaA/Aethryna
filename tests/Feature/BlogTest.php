<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Support\SiteUrls;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The blog: public pages, the admin that writes them, and the plumbing that
 * gets them found.
 *
 * The blog exists for search, so the things worth pinning down are the ones
 * search depends on: drafts stay invisible, published posts reach the sitemap
 * and feed, the URL never changes once shared, and the Markdown pipeline
 * cannot be used to inject markup.
 */
class BlogTest extends TestCase
{
    use RefreshDatabase;

    private function makePost(array $overrides = []): Post
    {
        return Post::create(array_merge([
            'title'        => 'Can I get into tech without a degree?',
            'slug'         => 'tech-without-a-degree',
            'standfirst'   => 'Short answer: yes. Here is what employers actually look for.',
            'body'         => "## The short answer\n\nYes, and **most** people on our courses prove it.",
            'published_at' => now()->subDay(),
        ], $overrides));
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_the_index_lists_published_posts_newest_first(): void
    {
        $this->makePost();
        $this->makePost([
            'title'        => 'What is a Skills Bootcamp?',
            'slug'         => 'what-is-a-skills-bootcamp',
            'published_at' => now()->subHour(),
        ]);

        $this->get('/blog')
            ->assertOk()
            ->assertSeeInOrder(['What is a Skills Bootcamp?', 'Can I get into tech without a degree?']);
    }

    public function test_the_index_survives_having_no_posts(): void
    {
        $this->get('/blog')
            ->assertOk()
            ->assertSee('Nothing here yet');
    }

    public function test_a_post_renders_its_markdown(): void
    {
        $this->makePost();

        $this->get('/blog/tech-without-a-degree')
            ->assertOk()
            ->assertSee('<h2>The short answer</h2>', false)
            ->assertSee('<strong>most</strong>', false)
            ->assertSee('BlogPosting', false);
    }

    /**
     * Raw HTML in the body is stripped, not rendered. Admins write the posts
     * today, but the pipeline should be safe whoever writes tomorrow.
     */
    public function test_pasted_html_is_stripped_from_the_body(): void
    {
        $this->makePost([
            'slug' => 'html-post',
            'body' => 'Before <script>alert(1)</script> after.',
        ]);

        $response = $this->get('/blog/html-post')->assertOk();

        $this->assertStringNotContainsString('<script>alert(1)</script>', $response->getContent());
    }

    /**
     * A YouTube link alone on a line becomes a privacy-friendly embed, the
     * same youtube-nocookie treatment as the session recordings. One in the
     * middle of a sentence stays a plain mention: prose should not sprout a
     * video player because it cited one.
     */
    public function test_a_youtube_link_on_its_own_line_becomes_an_embed(): void
    {
        $this->makePost([
            'slug' => 'video-post',
            'body' => "The day in full:\n\nhttps://www.youtube.com/watch?v=dQw4w9WgXcQ\n\nWe also mentioned https://youtu.be/dQw4w9WgXcQ in passing.",
        ]);

        $response = $this->get('/blog/video-post')->assertOk();
        $html = $response->getContent();

        $this->assertStringContainsString('youtube-nocookie.com/embed/dQw4w9WgXcQ', $html);
        $this->assertSame(1, substr_count($html, '<iframe'), 'only the standalone link should embed');
        // GitHub-flavoured Markdown autolinks the inline mention, so it
        // renders as a hyperlink rather than a second player.
        $this->assertStringContainsString('<a href="https://youtu.be/dQw4w9WgXcQ">', $html);
    }

    public function test_drafts_are_invisible_to_the_public_but_readable_by_admins(): void
    {
        $this->makePost(['published_at' => null]);

        $this->get('/blog')->assertDontSee('tech-without-a-degree');
        $this->get('/blog/tech-without-a-degree')->assertNotFound();

        $this->actingAs($this->admin())
            ->get('/blog/tech-without-a-degree')
            ->assertOk()
            ->assertSee('Draft')
            ->assertSee('noindex', false);
    }

    /** A post dated in the future is scheduled, not live. */
    public function test_a_post_dated_in_the_future_stays_hidden(): void
    {
        $this->makePost(['published_at' => now()->addDay()]);

        $this->get('/blog')->assertDontSee('Can I get into tech');
        $this->get('/blog/tech-without-a-degree')->assertNotFound();
    }

    public function test_published_posts_reach_the_sitemap_and_drafts_do_not(): void
    {
        $this->makePost();
        $this->makePost(['slug' => 'a-draft', 'title' => 'A draft', 'published_at' => null]);

        $urls = SiteUrls::all();

        $this->assertContains('/blog', $urls);
        $this->assertContains('/blog/tech-without-a-degree', $urls);
        $this->assertNotContains('/blog/a-draft', $urls);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/blog/tech-without-a-degree', false);
    }

    public function test_the_feed_lists_published_posts(): void
    {
        $this->makePost();

        $this->get('/blog/feed')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/rss+xml; charset=utf-8')
            ->assertSee('<title>Can I get into tech without a degree?</title>', false)
            ->assertSee('/blog/tech-without-a-degree', false);
    }

    public function test_the_admin_screens_are_admin_only(): void
    {
        $this->get('/admin/posts')->assertRedirect();

        $learner = User::factory()->create(['role' => 'learner']);
        $this->actingAs($learner)->get('/admin/posts')->assertForbidden();
    }

    public function test_an_admin_can_write_and_publish_a_post(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/posts', [
                'title'      => 'Free courses in Liverpool',
                'standfirst' => 'Where to find genuinely free digital skills training near Liverpool.',
                'body'       => "## Start here\n\nOur own courses are free and funded.",
                'publish'    => '1',
            ])
            ->assertRedirect(route('admin.posts.index'));

        $post = Post::where('slug', 'free-courses-in-liverpool')->firstOrFail();

        $this->assertTrue($post->isPublished());
        $this->get('/blog/free-courses-in-liverpool')->assertOk();
    }

    public function test_saving_without_publish_makes_a_draft(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/posts', [
                'title'      => 'Not ready yet',
                'standfirst' => 'A half-written thought.',
                'body'       => 'Coming soon.',
            ]);

        $this->assertFalse(Post::where('slug', 'not-ready-yet')->firstOrFail()->isPublished());
    }

    /**
     * Editing keeps the slug and the original publish date: the URL may be
     * indexed and shared, and a typo fix is not a new post.
     */
    public function test_editing_keeps_the_slug_and_the_first_publish_date(): void
    {
        $post = $this->makePost(['published_at' => now()->subWeek()]);
        $originalDate = $post->published_at;

        $this->actingAs($this->admin())
            ->patch('/admin/posts/'.$post->slug, [
                'title'      => 'Can I really get into tech without a degree?',
                'standfirst' => $post->standfirst,
                'body'       => $post->body,
                'publish'    => '1',
            ]);

        $post->refresh();

        $this->assertSame('tech-without-a-degree', $post->slug);
        $this->assertTrue($originalDate->equalTo($post->published_at));
    }

    public function test_two_posts_with_similar_titles_get_distinct_slugs(): void
    {
        $admin = $this->admin();

        foreach (['One', 'Two'] as $n) {
            $this->actingAs($admin)->post('/admin/posts', [
                'title'      => 'Digital skills '.$n,
                'standfirst' => 'A post.',
                'body'       => 'Body.',
            ]);
        }

        // Slugs derive from titles, so same-prefix titles must still diverge.
        $this->assertSame(2, Post::count());
        $this->assertSame(2, Post::pluck('slug')->unique()->count());
    }

    public function test_an_admin_can_delete_a_post(): void
    {
        $post = $this->makePost();

        $this->actingAs($this->admin())
            ->delete('/admin/posts/'.$post->slug)
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $this->get('/blog/tech-without-a-degree')->assertNotFound();
    }
}
