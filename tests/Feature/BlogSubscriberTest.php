<?php

namespace Tests\Feature;

use App\Mail\NewPostPublished;
use App\Models\BlogSubscriber;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * The promise on the subscribe card, kept end to end: an address goes in,
 * every new post reaches it, and one click stops them for good.
 */
class BlogSubscriberTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        Http::fake();
    }

    private function makePost(array $overrides = []): Post
    {
        return Post::create(array_merge([
            'title'        => 'A fresh post',
            'slug'         => 'a-fresh-post',
            'standfirst'   => 'Something worth an email.',
            'body'         => 'The words.',
            'published_at' => now()->subHour(),
        ], $overrides));
    }

    public function test_subscribing_stores_a_local_row_with_a_token(): void
    {
        $this->post('/blog/subscribe', ['email' => 'reader@example.com']);

        $subscriber = BlogSubscriber::firstOrFail();

        $this->assertSame('reader@example.com', $subscriber->email);
        $this->assertNotEmpty($subscriber->token);
        $this->assertNull($subscriber->unsubscribed_at);
    }

    public function test_a_new_post_is_emailed_to_active_subscribers_once(): void
    {
        $active = BlogSubscriber::enrol('reader@example.com');
        BlogSubscriber::enrol('gone@example.com')
            ->forceFill(['unsubscribed_at' => now()])->save();

        $post = $this->makePost();

        $this->artisan('blog:notify-subscribers')->assertSuccessful();

        Mail::assertSent(NewPostPublished::class, 1);
        Mail::assertSent(NewPostPublished::class, fn ($mail) => $mail->hasTo($active->email));
        $this->assertNotNull($post->fresh()->subscribers_notified_at);

        // A second run finds nothing new: once means once.
        $this->artisan('blog:notify-subscribers')->assertSuccessful();
        Mail::assertSent(NewPostPublished::class, 1);
    }

    public function test_drafts_and_submissions_are_not_emailed(): void
    {
        BlogSubscriber::enrol('reader@example.com');
        $this->makePost(['published_at' => null, 'review_requested_at' => now()]);

        $this->artisan('blog:notify-subscribers')->assertSuccessful();

        Mail::assertNothingSent();
    }

    /**
     * A long-published post the command has never seen is stamped, not
     * sent: "new post" emails about last month are worse than none, and a
     * restored backup must not flood every inbox.
     */
    public function test_a_stale_post_is_stamped_without_sending(): void
    {
        BlogSubscriber::enrol('reader@example.com');
        $post = $this->makePost(['published_at' => now()->subDays(30)]);

        $this->artisan('blog:notify-subscribers')->assertSuccessful();

        Mail::assertNothingSent();
        $this->assertNotNull($post->fresh()->subscribers_notified_at);
    }

    public function test_the_unsubscribe_link_works_in_one_click_and_both_verbs(): void
    {
        $subscriber = BlogSubscriber::enrol('reader@example.com');

        // GET is the person clicking the footer link.
        $this->get($subscriber->unsubscribeUrl())
            ->assertRedirect(route('blog.index'));
        $this->assertNotNull($subscriber->fresh()->unsubscribed_at);

        // POST is the mail client's own button (RFC 8058), no CSRF token.
        $subscriber->forceFill(['unsubscribed_at' => null])->save();
        $this->post($subscriber->unsubscribeUrl())->assertRedirect();
        $this->assertNotNull($subscriber->fresh()->unsubscribed_at);

        // A guessed token finds nothing.
        $this->get('/blog/unsubscribe/'.str_repeat('x', 48))->assertNotFound();
    }

    public function test_resubscribing_reactivates_without_minting_a_new_token(): void
    {
        $subscriber = BlogSubscriber::enrol('reader@example.com');
        $token = $subscriber->token;

        $this->get($subscriber->unsubscribeUrl());
        $this->post('/blog/subscribe', ['email' => 'reader@example.com']);

        $subscriber->refresh();

        $this->assertNull($subscriber->unsubscribed_at);
        $this->assertSame($token, $subscriber->token);
        $this->assertSame(1, BlogSubscriber::count());
    }

    public function test_the_post_email_carries_the_one_click_headers(): void
    {
        $subscriber = BlogSubscriber::enrol('reader@example.com');
        $post = $this->makePost();

        $mail = new NewPostPublished($post, $subscriber);
        $rendered = $mail->build();

        $this->assertStringContainsString($subscriber->unsubscribeUrl(), $mail->render());
    }
}
