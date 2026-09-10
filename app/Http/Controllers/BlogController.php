<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\EmailOctopusService;
use Illuminate\Http\Request;

/**
 * The public face of the blog: the index, the articles, the feed and the
 * subscriber list.
 *
 * Reading only. Writing happens in admin, and the two never share a route.
 */
class BlogController extends Controller
{
    public function __construct(protected EmailOctopusService $emailOctopus)
    {
    }

    public function index()
    {
        return view('blog.index', [
            'posts' => Post::published()
                ->orderByDesc('published_at')
                ->paginate(10),
        ]);
    }

    public function show(Post $post)
    {
        // A draft is invisible to the public but readable by the people who
        // work on it, so a post can be proofread at its real URL before
        // anyone else sees it. That includes the content writer: asking
        // somebody to submit work they cannot look at is absurd.
        abort_unless(
            $post->isPublished()
                || auth()->user()?->isAdmin()
                || auth()->user()?->isEditor(),
            404
        );

        return view('blog.show', [
            'post' => $post,

            // The Substack habit worth copying: a finished article offers the
            // next one, not a dead end.
            'morePosts' => Post::published()
                ->whereKeyNot($post->id)
                ->orderByDesc('published_at')
                ->limit(3)
                ->get(),
        ]);
    }

    /**
     * Blog subscribers, into the same EmailOctopus list as everything else,
     * under their own tag. Fail-soft on purpose: the service logs a real
     * failure, and the person who typed their email gets a thank you rather
     * than an error page for a marketing sync problem.
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
        ]);

        // Same honeypot convention as every other public form: a field real
        // people never see, answered as success so the bot learns nothing.
        if (! filled($request->input('website'))) {
            // The local row is what actually receives the posts; EmailOctopus
            // keeps a copy for marketing. Local first, because the mailing
            // must not depend on a third party being reachable.
            \App\Models\BlogSubscriber::enrol($validated['email']);

            $this->emailOctopus->subscribe($validated['email'], [], ['blog']);
        }

        return back()
            ->with('subscribed', 'You are on the list. New posts will come to your inbox.')
            ->withFragment('subscribe');
    }

    /**
     * The link at the foot of every post email, and the target of the mail
     * clients' own unsubscribe buttons (which POST, hence both verbs on the
     * route). Idempotent and quiet: clicking twice is not an error.
     */
    public function unsubscribe(string $token)
    {
        \App\Models\BlogSubscriber::where('token', $token)
            ->firstOrFail()
            ->forceFill(['unsubscribed_at' => now()])
            ->save();

        return redirect()
            ->route('blog.index')
            ->with('subscribed', 'You are unsubscribed. No more emails from the blog, and you are welcome back any time.');
    }

    /**
     * RSS. Cheap to serve, and the audience it reaches is exactly the one a
     * new blog needs: newsletter curators and aggregator sites, the people
     * who link to things.
     */
    public function feed()
    {
        $posts = Post::published()
            ->orderByDesc('published_at')
            ->limit(20)
            ->get();

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'."\n";
        $xml .= "<channel>\n";
        $xml .= '<title>Skills Co-op blog</title>'."\n";
        $xml .= '<link>'.e(route('blog.index')).'</link>'."\n";
        $xml .= '<description>Plain answers about free digital skills training, career changing and getting into tech without a degree.</description>'."\n";
        $xml .= '<language>en-gb</language>'."\n";
        $xml .= '<atom:link href="'.e(route('blog.feed')).'" rel="self" type="application/rss+xml" />'."\n";

        foreach ($posts as $post) {
            $xml .= "<item>\n";
            $xml .= '<title>'.htmlspecialchars($post->title, ENT_XML1).'</title>'."\n";
            $xml .= '<link>'.e($post->url()).'</link>'."\n";
            $xml .= '<guid>'.e($post->url()).'</guid>'."\n";
            $xml .= '<description>'.htmlspecialchars($post->standfirst, ENT_XML1).'</description>'."\n";
            $xml .= '<pubDate>'.$post->published_at->toRfc2822String().'</pubDate>'."\n";
            $xml .= "</item>\n";
        }

        $xml .= "</channel>\n</rss>\n";

        return response($xml, 200, ['Content-Type' => 'application/rss+xml; charset=utf-8']);
    }
}
