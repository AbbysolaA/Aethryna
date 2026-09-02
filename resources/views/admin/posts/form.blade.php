@extends('layouts.aethryna')

@php $editing = $post->exists; @endphp

@section('title', ($editing ? 'Edit post' : 'Write a post') . ' | Skills Co-op')

@section('content')

@include('admin._nav')
@include('admin._flash')
<section class="vl-engagement">
    <div class="ath-container">

        <a href="{{ route('admin.posts.index') }}" class="vl-back">Back to posts</a>

        <header class="vl-engagement-head">
            <span class="vl-eyebrow">The blog</span>
            <h1 class="vl-engagement-title">{{ $editing ? 'Edit post' : 'Write a post' }}</h1>
            <p class="vl-side-note">
                @if ($editing)
                    The web address stays as it is, whatever the title becomes, so links and
                    search results already pointing here keep working.
                @else
                    Save it as a draft to proofread at its real URL, or tick publish and it is
                    live the moment you save.
                @endif
            </p>
        </header>

        <div class="vl-panel vl-form-panel">
            <form method="POST" action="{{ $editing ? route('admin.posts.update', $post) : route('admin.posts.store') }}">
                @csrf
                @if ($editing)
                    @method('PATCH')
                @endif

                <div class="vl-field">
                    <label for="title">Title</label>
                    <input id="title" name="title" required maxlength="150"
                           placeholder="Can I really get into tech without a degree?"
                           value="{{ old('title', $post->title) }}">
                    <p class="vl-side-note vl-hint">
                        Write it the way somebody would type it into a search box. Questions work well.
                    </p>
                    @error('title')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="standfirst">Standfirst</label>
                    <textarea id="standfirst" name="standfirst" required maxlength="300" rows="3"
                              placeholder="One or two sentences saying what the post answers and for whom.">{{ old('standfirst', $post->standfirst) }}</textarea>
                    <p class="vl-side-note vl-hint">
                        Doubles as the summary on the blog index and the description in search
                        results. Aim for under 160 characters; the hard limit is 300.
                    </p>
                    @error('standfirst')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="body">Body</label>
                    <textarea id="body" name="body" required rows="24"
                              style="font-family: ui-monospace, monospace; font-size: 0.92rem; line-height: 1.7;"
                              placeholder="Write in Markdown. ## for a heading, blank line between paragraphs, [link text](https://example.org) for a link.">{{ old('body', $post->body) }}</textarea>
                    <p class="vl-side-note vl-hint">
                        Markdown: <code>## Heading</code>, <code>**bold**</code>,
                        <code>- bullet</code>, <code>[text](url)</code>. Pasted HTML is
                        stripped rather than rendered.
                    </p>
                    @error('body')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="author_name">Author</label>
                    <input id="author_name" name="author_name" maxlength="100"
                           placeholder="Abby Areola"
                           value="{{ old('author_name', $post->author_name) }}">
                    <p class="vl-side-note vl-hint">Leave blank to publish as Skills Co-op.</p>
                    @error('author_name')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label class="vl-speaker-check" style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <input type="checkbox" name="publish" value="1"
                               style="width:18px; height:18px; accent-color: var(--ath-teal, #038b89);"
                               @checked(old('publish', $post->isPublished()))>
                        <span>Published</span>
                    </label>
                    <p class="vl-side-note vl-hint">
                        @if ($editing && $post->published_at)
                            First published {{ $post->published_at->format('j F Y') }}. Editing keeps
                            that date; unticking takes the post back to a draft.
                        @else
                            Unticked saves a draft only admins can see.
                        @endif
                    </p>
                </div>

                <button type="submit" class="vl-btn vl-btn-primary">
                    {{ $editing ? 'Save changes' : 'Save post' }}
                </button>
            </form>
        </div>

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
@endpush

@endsection
