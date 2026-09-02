@extends('layouts.aethryna')

@section('title', $post->title.' | Skills Co-op')
@section('meta_title', $post->title.' | Skills Co-op')
@section('meta_description', $post->standfirst)
@section('og_title', $post->title)
@section('og_description', $post->standfirst)
{{-- A draft has no business in an index while it is being proofread. --}}
@unless ($post->isPublished())
    @section('meta_robots', 'noindex, nofollow')
@endunless

@push('styles')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600..800&display=swap">
    <link rel="alternate" type="application/rss+xml" title="Skills Co-op blog" href="{{ route('blog.feed') }}">
    @include('blog._styles')
@endpush

@section('content')

<div class="bl">

    <section class="bl-hero">
        <div class="bl-wrap">
            <p class="bl-eyebrow">Blog</p>
            <h1>{{ $post->title }}</h1>
            <p class="bl-lede">{{ $post->standfirst }}</p>
        </div>
    </section>

    <section class="bl-body">
        <div class="bl-wrap">

            <a href="{{ route('blog.index') }}" class="bl-back">&larr; All posts</a>

            @unless ($post->isPublished())
                <div class="bl-draft">
                    <strong>Draft.</strong> Only admins can see this page. Publish it from the
                    blog admin to make it public.
                </div>
            @endunless

            <article class="bl-article">
                <p class="bl-meta" style="margin:0 0 22px;">
                    {{ $post->authorName() }}
                    @if ($post->published_at)
                        &middot; {{ $post->published_at->format('j F Y') }}
                    @endif
                    &middot; {{ $post->readingMinutes() }} minute read
                </p>

                <div class="bl-prose">{!! $post->bodyHtml() !!}</div>

                <div class="bl-foot">
                    <p>
                        <strong>Skills Co-op</strong> is a community interest company running a free,
                        funded digital skills programme for people facing barriers to employment.
                        No fees, no experience needed.
                    </p>
                    <p>
                        <a href="{{ route('programs') }}">See the courses</a> or
                        <a href="{{ route('register') }}">apply for the next cohort</a>.
                    </p>
                </div>
            </article>

        </div>
    </section>

</div>

@if ($post->isPublished())
<script type="application/ld+json">
{!! json_encode([
    '@context'      => 'https://schema.org',
    '@type'         => 'BlogPosting',
    'headline'      => $post->title,
    'description'   => $post->standfirst,
    'datePublished' => $post->published_at->toIso8601String(),
    'dateModified'  => $post->updated_at->toIso8601String(),
    'url'           => $post->url(),
    'author'        => [
        '@type' => $post->author_name ? 'Person' : 'Organization',
        'name'  => $post->authorName(),
    ],
    'publisher'     => [
        '@type' => 'Organization',
        'name'  => 'Skills Co-op',
        'url'   => url('/'),
    ],
    'mainEntityOfPage' => $post->url(),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

@endsection
