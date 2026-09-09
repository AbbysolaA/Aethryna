<?php

namespace Tests\Feature;

use App\Mail\PostReviewRequested;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The review gate and the picture library.
 *
 * The promise being tested: a content writer can do everything about a post
 * except make it public, an admin's yes is one click and one email away,
 * and a picture upload cannot be used to put anything but a picture on the
 * server.
 */
class BlogWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    private function editor(): User
    {
        return User::factory()->create(['role' => 'editor']);
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function postPayload(array $overrides = []): array
    {
        return array_merge([
            'title'      => 'Written for review',
            'standfirst' => 'A post that should wait for an admin.',
            'body'       => 'The words.',
        ], $overrides);
    }

    public function test_a_writer_cannot_publish_even_by_forging_the_admin_field(): void
    {
        $this->actingAs($this->editor())
            ->post('/admin/posts', $this->postPayload(['publish' => '1']));

        $post = Post::firstOrFail();

        $this->assertFalse($post->isPublished());
        $this->get('/blog')->assertDontSee('Written for review');
    }

    public function test_submitting_for_review_queues_the_post_and_emails_the_admins(): void
    {
        $this->actingAs($this->editor())
            ->post('/admin/posts', $this->postPayload(['submit_review' => '1']))
            ->assertRedirect(route('admin.posts.index'));

        $post = Post::firstOrFail();

        $this->assertTrue($post->isAwaitingReview());
        $this->assertFalse($post->isPublished());

        Mail::assertSent(PostReviewRequested::class, function ($mail) {
            return $mail->hasTo(config('organisation.email'));
        });
    }

    /**
     * One submission, one email. Saving again while it waits is a writer
     * polishing, not a second request.
     */
    public function test_saving_again_while_awaiting_review_does_not_email_twice(): void
    {
        $editor = $this->editor();

        $this->actingAs($editor)->post('/admin/posts', $this->postPayload(['submit_review' => '1']));
        $post = Post::firstOrFail();

        $this->actingAs($editor)->patch('/admin/posts/'.$post->slug, $this->postPayload([
            'body'          => 'The words, improved.',
            'submit_review' => '1',
        ]));

        Mail::assertSent(PostReviewRequested::class, 1);
        $this->assertTrue($post->fresh()->isAwaitingReview());
    }

    public function test_a_writer_can_withdraw_a_submission_back_to_draft(): void
    {
        $editor = $this->editor();

        $this->actingAs($editor)->post('/admin/posts', $this->postPayload(['submit_review' => '1']));
        $post = Post::firstOrFail();

        $this->actingAs($editor)->patch('/admin/posts/'.$post->slug, $this->postPayload());

        $this->assertFalse($post->fresh()->isAwaitingReview());
    }

    public function test_an_admin_publishes_a_submission_with_one_click(): void
    {
        $this->actingAs($this->editor())->post('/admin/posts', $this->postPayload(['submit_review' => '1']));
        $post = Post::firstOrFail();

        $this->actingAs($this->admin())
            ->post('/admin/posts/'.$post->slug.'/publish')
            ->assertRedirect(route('admin.posts.index'));

        $post->refresh();

        $this->assertTrue($post->isPublished());
        $this->assertFalse($post->isAwaitingReview());
        $this->get('/blog/'.$post->slug)->assertOk();
    }

    public function test_the_publish_button_is_not_the_writers_to_press(): void
    {
        $editor = $this->editor();

        $this->actingAs($editor)->post('/admin/posts', $this->postPayload(['submit_review' => '1']));
        $post = Post::firstOrFail();

        $this->actingAs($editor)
            ->post('/admin/posts/'.$post->slug.'/publish')
            ->assertForbidden();

        $this->assertFalse($post->fresh()->isPublished());
    }

    public function test_a_writer_editing_a_live_post_cannot_unpublish_or_delete_it(): void
    {
        $post = Post::create($this->postPayload() + [
            'slug'         => 'live-post',
            'published_at' => now()->subDay(),
        ]);

        $editor = $this->editor();

        // No forged field takes it down, and the edit itself goes live.
        $this->actingAs($editor)->patch('/admin/posts/live-post', $this->postPayload([
            'body'    => 'A live edit.',
            'publish' => '0',
        ]));

        $post->refresh();
        $this->assertTrue($post->isPublished());
        $this->assertSame('A live edit.', $post->body);

        $this->actingAs($editor)->delete('/admin/posts/live-post');
        $this->assertDatabaseHas('posts', ['id' => $post->id]);

        // A draft of their own is still theirs to delete.
        $this->actingAs($editor)->post('/admin/posts', $this->postPayload(['title' => 'Disposable draft']));
        $this->actingAs($editor)->delete('/admin/posts/disposable-draft');
        $this->assertDatabaseMissing('posts', ['title' => 'Disposable draft']);
    }

    public function test_a_writer_can_preview_a_draft_at_its_real_url(): void
    {
        $post = Post::create($this->postPayload() + ['slug' => 'a-draft']);

        $this->actingAs($this->editor())
            ->get('/blog/a-draft')
            ->assertOk()
            ->assertSee('noindex', false);
    }

    public function test_a_picture_upload_is_reencoded_and_offered_as_markdown(): void
    {
        Storage::fake('blog_images');

        $this->actingAs($this->editor())
            ->post('/admin/posts/images', [
                'picture' => UploadedFile::fake()->image('Room Photo.jpg', 2400, 1400),
            ])
            ->assertSessionHas('status');

        $files = Storage::disk('blog_images')->files();
        $this->assertCount(1, $files);
        $this->assertStringStartsWith('room-photo-', basename($files[0]));

        // Shrunk to the article column's needs, not stored at camera size.
        [$width] = getimagesize(Storage::disk('blog_images')->path($files[0]));
        $this->assertSame(1600, $width);

        // The form offers the paste-ready line.
        $this->actingAs($this->editor())
            ->get('/admin/posts/create')
            ->assertSee('/images/blog/'.basename($files[0]), false);
    }

    public function test_a_file_that_is_not_a_picture_is_refused(): void
    {
        Storage::fake('blog_images');

        $this->actingAs($this->editor())
            ->post('/admin/posts/images', [
                'picture' => UploadedFile::fake()->create('script.php', 20, 'text/x-php'),
            ])
            ->assertSessionHasErrors('picture');

        $this->assertCount(0, Storage::disk('blog_images')->files());
    }

    public function test_a_picture_can_be_deleted_but_the_route_cannot_escape_the_folder(): void
    {
        Storage::fake('blog_images');
        Storage::disk('blog_images')->put('keep-me.jpg', 'x');

        $this->actingAs($this->editor())
            ->delete('/admin/posts/images/keep-me.jpg')
            ->assertSessionHas('status');

        $this->assertCount(0, Storage::disk('blog_images')->files());

        // A path that is not one plain filename does not match the route.
        $this->actingAs($this->editor())
            ->delete('/admin/posts/images/..%2F..%2F.env')
            ->assertNotFound();
    }
}
