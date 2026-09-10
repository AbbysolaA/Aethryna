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

                    {{-- The toolbar writes Markdown into the textarea at the
                         cursor, so a writer never has to know the syntax and
                         the syntax never has to change. Deliberately shorter
                         than Substack's: the blocks a post here actually
                         needs, nothing that would need a plugin to render. --}}
                    <div class="mdt" role="toolbar" aria-label="Formatting" aria-controls="body">
                        <button type="button" data-md="h2" title="Section heading">H</button>
                        <button type="button" data-md="bold" title="Bold (Ctrl+B)"><strong>B</strong></button>
                        <button type="button" data-md="italic" title="Italic (Ctrl+I)"><em>I</em></button>
                        <button type="button" data-md="link" title="Link (Ctrl+K)">&#128279;</button>
                        <span class="mdt-gap" aria-hidden="true"></span>
                        <button type="button" data-md="ul" title="Bulleted list">&bull; list</button>
                        <button type="button" data-md="ol" title="Numbered list">1. list</button>
                        <button type="button" data-md="quote" title="Quote">&ldquo;&rdquo;</button>
                        <button type="button" data-md="divider" title="Divider">&middot;&middot;&middot;</button>
                        <span class="mdt-gap" aria-hidden="true"></span>
                        <button type="button" data-md="code" title="Code (select text first for inline, nothing for a block)">&lt;/&gt;</button>
                        <button type="button" data-md="image" title="Picture (upload below, then paste its line)">&#128247; picture</button>
                        <button type="button" data-md="video" title="Video (a YouTube link on its own line)">&#9654; video</button>
                    </div>

                    <textarea id="body" name="body" required rows="24"
                              style="font-family: ui-monospace, monospace; font-size: 0.92rem; line-height: 1.7;"
                              placeholder="Write in Markdown. ## for a heading, blank line between paragraphs, [link text](https://example.org) for a link.">{{ old('body', $post->body) }}</textarea>
                    <p class="vl-side-note vl-hint">
                        Markdown: <code>## Heading</code>, <code>**bold**</code>,
                        <code>- bullet</code>, <code>[text](url)</code>, and
                        <code>---</code> on its own line for a section divider. Pasted
                        HTML is stripped rather than rendered. A YouTube link on a line
                        of its own becomes an embedded video player.
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

                @if (auth()->user()->isAdmin())
                    <div class="vl-field">
                        <label class="vl-speaker-check" style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                            <input type="checkbox" name="publish" value="1"
                                   style="width:18px; height:18px; accent-color: var(--ath-teal, #038b89);"
                                   @checked(old('publish', $post->isPublished()))>
                            <span>Published</span>
                        </label>
                        <p class="vl-side-note vl-hint">
                            @if ($post->isAwaitingReview())
                                Submitted for review {{ $post->review_requested_at->format('j F Y') }}.
                                Tick and save to publish it; saving unticked sends it back to the
                                writer as a draft.
                            @elseif ($editing && $post->published_at)
                                First published {{ $post->published_at->format('j F Y') }}. Editing keeps
                                that date; unticking takes the post back to a draft.
                            @else
                                Unticked saves a draft only signed-in staff can see.
                            @endif
                        </p>
                    </div>
                @elseif (! $post->isPublished())
                    <div class="vl-field">
                        <label class="vl-speaker-check" style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                            <input type="checkbox" name="submit_review" value="1"
                                   style="width:18px; height:18px; accent-color: var(--ath-teal, #038b89);"
                                   @checked(old('submit_review', $post->isAwaitingReview()))>
                            <span>Submit for review</span>
                        </label>
                        <p class="vl-side-note vl-hint">
                            Ticked, the post goes to an admin who reads it and presses publish.
                            Unticked, it stays a draft that is yours to keep working on. You can
                            preview it any time at its own address from the posts list.
                        </p>
                    </div>
                @else
                    <p class="vl-side-note vl-hint">
                        This post is live. Your changes appear on the site when you save.
                    </p>
                @endif

                <button type="submit" class="vl-btn vl-btn-primary">
                    {{ $editing ? 'Save changes' : 'Save post' }}
                </button>
            </form>
        </div>

        {{-- Its own panel and its own form: file inputs cannot live inside
             the post form without nesting forms, and a picture uploaded here
             is shared by every post anyway. --}}
        <div class="vl-panel vl-form-panel" style="margin-top:24px;">
            <h2 style="margin:0 0 6px; font-size:1.15rem; color:var(--ath-deep, #055860);">Pictures</h2>
            <p class="vl-side-note" style="margin:0 0 18px;">
                Upload a picture, then copy its line into the post body where the picture
                should appear. Change the words in the square brackets to say what the
                picture shows; screen readers speak them aloud.
            </p>

            <form method="POST" action="{{ route('admin.posts.images.store') }}" enctype="multipart/form-data" class="pi-upload">
                @csrf
                <input type="file" name="picture" accept=".jpg,.jpeg,.png,.webp,.gif" required>
                <button type="submit" class="vl-btn vl-btn-small">Upload</button>
            </form>
            @error('picture')<p class="vl-error">{{ $message }}</p>@enderror

            @if (empty($images))
                <p class="vl-side-note" style="margin-top:16px;">No pictures uploaded yet.</p>
            @else
                <ul class="pi-list">
                    @foreach ($images as $image)
                        <li class="pi-item">
                            <img src="{{ $image['url'] }}" alt="" loading="lazy">
                            <input type="text" readonly
                                   value="![What the picture shows]({{ $image['url'] }})"
                                   onfocus="this.select()"
                                   aria-label="Line to paste into the post for {{ $image['name'] }}">
                            <form method="POST" action="{{ route('admin.posts.images.destroy', $image['name']) }}"
                                  onsubmit="return confirm('Delete {{ $image['name'] }}? Posts using it will show a gap.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="vl-mini-btn vl-mini-btn-danger">Delete</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
    <style>
        .mdt {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            padding: 6px;
            margin-bottom: -1px;
            border: 1px solid rgba(3, 139, 137, 0.2);
            border-radius: 10px 10px 0 0;
            background: #f4f9f8;
        }
        .mdt button {
            border: 1px solid transparent;
            border-radius: 7px;
            background: none;
            padding: 5px 10px;
            font: inherit;
            font-size: 0.85rem;
            color: var(--ath-deep, #055860);
            cursor: pointer;
            white-space: nowrap;
        }
        .mdt button:hover { background: #fff; border-color: rgba(3, 139, 137, 0.25); }
        .mdt-gap { width: 8px; }
        .mdt + textarea { border-top-left-radius: 0; border-top-right-radius: 0; }

        .pi-upload { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; }
        .pi-upload input[type="file"] { flex: 1 1 260px; }
        .pi-list { list-style: none; margin: 18px 0 0; padding: 0; display: grid; gap: 12px; }
        .pi-item {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            padding: 10px;
            border: 1px solid rgba(3, 139, 137, 0.14);
            border-radius: 10px;
        }
        .pi-item img { width: 84px; height: 56px; object-fit: cover; border-radius: 6px; background: #eef6f4; }
        .pi-item input[type="text"] {
            flex: 1 1 240px;
            font-family: ui-monospace, monospace;
            font-size: 0.8rem;
            padding: 8px 10px;
            border: 1px solid rgba(3, 139, 137, 0.2);
            border-radius: 7px;
            color: #2b333a;
            background: #f8fbfb;
        }
    </style>
@endpush

@push('scripts')
<script>
(function () {
    var body = document.getElementById('body');
    if (!body) return;

    // Replace the current selection with `text` and select the part of it
    // between selFrom and selTo (offsets into `text`), so a writer can click
    // Bold and type straight into the placeholder.
    function insert(text, selFrom, selTo) {
        var start = body.selectionStart, end = body.selectionEnd;
        body.setRangeText(text, start, end, 'preserve');
        body.setSelectionRange(start + selFrom, start + selTo);
        body.focus();
    }

    function selection() {
        return body.value.slice(body.selectionStart, body.selectionEnd);
    }

    function wrap(before, after, placeholder) {
        var text = selection() || placeholder;
        insert(before + text + after, before.length, before.length + text.length);
    }

    // Prefix each selected line, on its own paragraph if the cursor is
    // mid-text, so "- " never lands in the middle of a sentence.
    function prefixLines(prefix, placeholder, numbered) {
        var text = selection() || placeholder;
        var lines = text.split('\n').map(function (line, i) {
            return (numbered ? (i + 1) + '. ' : prefix) + line;
        }).join('\n');
        var lead = body.selectionStart === 0 || body.value[body.selectionStart - 1] === '\n' ? '' : '\n\n';
        insert(lead + lines + '\n', lead.length, lead.length + lines.length);
    }

    var actions = {
        h2:      function () { prefixLines('## ', 'Section heading'); },
        bold:    function () { wrap('**', '**', 'bold text'); },
        italic:  function () { wrap('*', '*', 'italic text'); },
        link:    function () { var t = selection() || 'link text'; insert('[' + t + '](https://)', t.length + 3, t.length + 11); },
        ul:      function () { prefixLines('- ', 'First point\nSecond point'); },
        ol:      function () { prefixLines('', 'First step\nSecond step', true); },
        quote:   function () { prefixLines('> ', 'The quote, word for word'); },
        divider: function () { insert('\n\n---\n\n', 7, 7); },
        code:    function () {
            var t = selection();
            if (t.indexOf('\n') !== -1 || t === '') {
                wrap('\n\n```\n', '\n```\n', 'the code');
            } else {
                wrap('`', '`', t);
            }
        },
        image:   function () { wrap('\n\n![', '](/images/blog/file-name.jpg)\n', 'What the picture shows'); },
        video:   function () { insert('\n\nhttps://www.youtube.com/watch?v=VIDEO-ID\n\n', 2, 42); },
    };

    document.querySelectorAll('.mdt [data-md]').forEach(function (btn) {
        btn.addEventListener('click', function () { actions[btn.dataset.md](); });
    });

    body.addEventListener('keydown', function (e) {
        if (!(e.ctrlKey || e.metaKey)) return;
        var key = e.key.toLowerCase();
        if (key === 'b') { e.preventDefault(); actions.bold(); }
        if (key === 'i') { e.preventDefault(); actions.italic(); }
        if (key === 'k') { e.preventDefault(); actions.link(); }
    });
})();
</script>
@endpush

@endsection
