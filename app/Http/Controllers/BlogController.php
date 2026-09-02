<?php

namespace App\Http\Controllers;

use App\Models\Post;

/**
 * The public face of the blog: the index, the articles and the feed.
 *
 * Reading only. Writing happens in admin, and the two never share a route.
 */
class BlogController extends Controller
{
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
        // A draft is invisible to the public but readable by an admin, so a
        // post can be proofread at its real URL before anyone else sees it.
        abort_unless(
            $post->isPublished() || auth()->user()?->isAdmin(),
            404
        );

        return view('blog.show', ['post' => $post]);
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
