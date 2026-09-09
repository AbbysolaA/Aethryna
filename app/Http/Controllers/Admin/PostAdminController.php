<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PostReviewRequested;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Writing and publishing blog posts.
 *
 * Same shape as the roles admin: list, form, no separate publish screen.
 * Publishing is a checkbox on the form, because the decision "is this ready"
 * belongs next to the text it is about.
 */
class PostAdminController extends Controller
{
    public function index(): View
    {
        // Posts waiting for an admin's decision first: they are the reason an
        // admin opens this screen. Then drafts, then the live posts.
        return view('admin.posts.index', [
            'posts' => Post::orderByRaw('case when published_at is null and review_requested_at is not null then 0 when published_at is null then 1 else 2 end')
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.form', [
            'post'   => new Post(),
            'images' => PostImageController::listing(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $validated['slug'] = $this->uniqueSlug($validated['title']);

        $post = Post::create($validated + $this->stateFor($request));

        $this->notifyIfSubmitted($request, $post, wasAwaiting: false);

        return redirect()
            ->route('admin.posts.index')
            ->with('status', $this->savedMessage($post));
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', [
            'post'   => $post,
            'images' => PostImageController::listing(),
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $wasAwaiting = $post->isAwaitingReview();

        // The slug never changes on edit: the URL may already be indexed,
        // shared or linked from another site, and a retitled post at the same
        // address beats a renamed address nobody can find.
        $post->update($this->validated($request, $post) + $this->stateFor($request, $post));

        $this->notifyIfSubmitted($request, $post, $wasAwaiting);

        return redirect()
            ->route('admin.posts.index')
            ->with('status', $this->savedMessage($post));
    }

    /**
     * The one-click yes on a post a writer submitted. Admin group only, so a
     * writer cannot reach it for their own work.
     */
    public function publish(Post $post): RedirectResponse
    {
        $post->update([
            // First publish stamps the date; a post returned to draft and
            // approved again keeps its original date, same as the form.
            'published_at'        => $post->published_at ?? now(),
            'review_requested_at' => null,
        ]);

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'Published. It is live at /blog/'.$post->slug.'.');
    }

    /**
     * What this save does to the post's state, decided by who is saving.
     *
     * Admins publish with a checkbox. Writers never touch published_at, in
     * either direction: they cannot put a post live, and editing a live post
     * cannot accidentally pull it down. What a writer controls is whether an
     * unpublished post is submitted for review or still theirs to work on.
     *
     * @return array<string, mixed>
     */
    private function stateFor(Request $request, ?Post $post = null): array
    {
        if ($request->user()->isAdmin()) {
            return [
                'published_at' => $request->boolean('publish')
                    ? ($post?->published_at ?? now())
                    : null,
                // Deciding is what clears the queue, whichever way it goes:
                // publishing accepts the submission, unticking sends it back
                // to the writer as a draft.
                'review_requested_at' => null,
            ];
        }

        $published = $post?->published_at;

        return [
            'published_at'        => $published,
            'review_requested_at' => $published
                ? null
                : ($request->boolean('submit_review') ? ($post?->review_requested_at ?? now()) : null),
        ];
    }

    /**
     * Tell the admins a post is newly waiting, once per submission rather
     * than on every save while it waits. Never allowed to cost the writer
     * their save.
     */
    private function notifyIfSubmitted(Request $request, Post $post, bool $wasAwaiting): void
    {
        if (! $post->isAwaitingReview() || $wasAwaiting) {
            return;
        }

        try {
            Mail::to(config('organisation.email'))
                ->send(new PostReviewRequested($post, $request->user()));
        } catch (\Throwable $e) {
            Log::error('Post review notification failed', [
                'post'  => $post->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function savedMessage(Post $post): string
    {
        return match (true) {
            $post->isPublished()      => 'Post saved. It is live at /blog/'.$post->slug.'.',
            $post->isAwaitingReview() => 'Submitted for review. An admin will read it and press publish.',
            default                   => 'Draft saved. Only signed-in staff can see it until it is published.',
        };
    }

    /**
     * Posts delete freely: nothing else in the database points at one. The
     * URL it occupied will 404, which is the honest outcome for a post the
     * organisation decided to unwrite.
     *
     * Freely for admins, that is. A writer who cannot put a post on the site
     * cannot take one off it either; their delete reaches drafts and
     * submissions only.
     */
    public function destroy(Request $request, Post $post): RedirectResponse
    {
        if ($post->isPublished() && ! $request->user()->isAdmin()) {
            return redirect()
                ->route('admin.posts.index')
                ->with('error', 'That post is live, so only an admin can delete it.');
        }

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'Post deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Post $post = null): array
    {
        return $request->validate([
            'title' => [
                'required', 'string', 'max:150',
                Rule::unique('posts', 'title')->ignore($post?->id),
            ],
            'standfirst'  => ['required', 'string', 'max:300'],
            'body'        => ['required', 'string', 'max:60000'],
            'author_name' => ['nullable', 'string', 'max:100'],
        ], [
            'title.unique'        => 'There is already a post with that title.',
            'standfirst.required' => 'The standfirst is the summary shown on the index and in search results.',
            'standfirst.max'      => 'Keep the standfirst under 300 characters; search engines cut it off around 160.',
        ]);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $n    = 2;

        while (Post::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$n++;
        }

        return $slug;
    }
}
