<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        return view('admin.posts.index', [
            'posts' => Post::orderByRaw('published_at is null desc')
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.form', ['post' => new Post()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $validated['slug'] = $this->uniqueSlug($validated['title']);
        $validated['published_at'] = $request->boolean('publish') ? now() : null;

        $post = Post::create($validated);

        return redirect()
            ->route('admin.posts.index')
            ->with('status', $post->isPublished()
                ? 'Post published. It is live at /blog/'.$post->slug.'.'
                : 'Draft saved. Only admins can see it until it is published.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', ['post' => $post]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $this->validated($request, $post);

        // The slug never changes on edit: the URL may already be indexed,
        // shared or linked from another site, and a retitled post at the same
        // address beats a renamed address nobody can find.
        //
        // First publish stamps the date; later edits keep it, so fixing a typo
        // does not re-date the post. Unticking the box takes it back to draft.
        $validated['published_at'] = $request->boolean('publish')
            ? ($post->published_at ?? now())
            : null;

        $post->update($validated);

        return redirect()
            ->route('admin.posts.index')
            ->with('status', $post->isPublished() ? 'Post updated.' : 'Post saved as a draft.');
    }

    /**
     * Posts delete freely: nothing else in the database points at one. The
     * URL it occupied will 404, which is the honest outcome for a post the
     * organisation decided to unwrite.
     */
    public function destroy(Post $post): RedirectResponse
    {
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
