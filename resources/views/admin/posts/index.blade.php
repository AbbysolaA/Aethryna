@extends('layouts.aethryna')

@section('title', 'Blog posts | Skills Co-op')

@section('content')

@include('admin._nav')
<section class="vl-engagement">
    <div class="ath-container">

        <header class="vl-engagement-head vl-admin-head">
            <div>
                <span class="vl-eyebrow">The blog</span>
                <h1 class="vl-engagement-title">Blog posts</h1>
                <p class="vl-side-note">
                    Published posts appear on <a href="{{ route('blog.index') }}">/blog</a>.
                    Drafts are visible only to admins, at their real URL, so they can be
                    proofread in place before anyone else sees them.
                </p>
            </div>
            <div class="vl-head-actions">
                <a href="{{ route('admin.posts.create') }}" class="vl-btn vl-btn-primary">Write a post</a>
                <a href="{{ route('blog.index') }}" class="vl-back">View the blog</a>
            </div>
        </header>

        @if (session('status'))
            <div class="vl-flash vl-flash-ok" role="status">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="vl-flash vl-flash-err" role="alert">{{ session('error') }}</div>
        @endif

        @if ($posts->isEmpty())
            <div class="vl-panel vl-empty">
                <p>No posts yet.</p>
                <p class="vl-side-note"><a href="{{ route('admin.posts.create') }}">Write the first one.</a></p>
            </div>
        @else
            <div class="vl-panel vl-table-panel">
                <div class="vl-table-scroll">
                    <table class="vl-table">
                        <thead>
                            <tr>
                                <th>Post</th>
                                <th>Status</th>
                                <th>Published</th>
                                <th>Length</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <td>
                                        <strong>{{ $post->title }}</strong>
                                        <span class="vl-cell-sub">{{ $post->standfirst }}</span>
                                    </td>
                                    <td>
                                        @if ($post->isPublished())
                                            <span class="vl-badge vl-badge-open">Live</span>
                                        @else
                                            <span class="vl-badge vl-badge-muted">Draft</span>
                                        @endif
                                    </td>
                                    <td class="vl-cell-dates">
                                        {{ $post->published_at?->format('j M Y') ?? '—' }}
                                    </td>
                                    <td class="vl-cell-num">{{ $post->readingMinutes() }} min</td>
                                    <td class="vl-cell-actions">
                                        <a href="{{ $post->url() }}" class="vl-mini-btn">View</a>
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="vl-mini-btn">Edit</a>
                                        <form method="POST" action="{{ route('admin.posts.destroy', $post) }}"
                                              onsubmit="return confirm('Delete {{ addslashes($post->title) }}? This cannot be undone, and the URL will stop working.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="vl-mini-btn vl-mini-btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
@endpush

@endsection
