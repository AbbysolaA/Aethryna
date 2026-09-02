@extends('layouts.aethryna')

@section('title', 'Blog | Skills Co-op')
@section('meta_title', 'Blog | Skills Co-op')
@section('meta_description', 'Plain answers about free digital skills training, changing career and getting into tech without a degree, from the team behind the Skills Co-op programme.')
@section('og_title', 'The Skills Co-op blog')
@section('og_description', 'Plain answers about free digital skills training, changing career and getting into tech without a degree.')

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
            <h1>Plain answers about getting into digital work</h1>
            <p class="bl-lede">
                What we are learning as we build a free digital skills programme, written for
                the people it is for: career changers, people restarting, and anyone told that
                tech is not for them.
            </p>
        </div>
    </section>

    <section class="bl-body">
        <div class="bl-wrap">

            @if ($posts->isEmpty())
                <div class="bl-empty">
                    <p><strong>Nothing here yet.</strong></p>
                    <p style="margin:8px 0 0;">
                        The first posts are on their way. In the meantime,
                        <a href="{{ route('programs') }}">the courses</a> and
                        <a href="{{ route('sessions') }}">the sessions</a> are the best places to start.
                    </p>
                </div>
            @else
                <ul class="bl-list">
                    @foreach ($posts as $post)
                        <li class="bl-card">
                            <h2><a href="{{ $post->url() }}">{{ $post->title }}</a></h2>
                            <p>{{ $post->standfirst }}</p>
                            <p class="bl-meta">
                                {{ $post->published_at->format('j F Y') }}
                                &middot; {{ $post->readingMinutes() }} minute read
                            </p>
                        </li>
                    @endforeach
                </ul>

                <div class="bl-pagination">{{ $posts->links() }}</div>
            @endif

        </div>
    </section>

</div>

@endsection
