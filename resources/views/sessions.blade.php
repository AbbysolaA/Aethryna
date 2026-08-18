@extends('layouts.aethryna')

@section('title', 'The Skills Co-op Sessions | Monthly Panel Series on AI, Work, and Inclusion')

@section('meta_description', 'A free monthly panel series exploring AI, work, and inclusion. Honest conversations with practitioners, researchers, and industry leaders. No jargon walls, no gatekeeping.')
@section('og_description', 'A free monthly panel series exploring AI, work, and inclusion. Honest conversations with practitioners, researchers, and industry leaders. No jargon walls, no gatekeeping.')

@section('content')

@php $nextSession = $upcoming->first(); @endphp

<!-- Hero -->
<section class="ss-hero">
    <div class="ath-container">
        <div class="ss-hero-inner">
            <span class="ss-eyebrow">The Skills Co-op Sessions</span>
            <h1 class="ss-title">Real conversations about the future of <span class="ss-gradient">digital work.</span></h1>
            <p class="ss-lede">A monthly panel series with practitioners, researchers, and community leaders. Free. Online. Open to everyone.</p>

            @if($nextSession)
                @php $hasSpeakers = $nextSession->speakers->isNotEmpty(); @endphp
                <div class="ss-next-strip">
                    <span class="ss-next-label">Next panel</span>
                    <span class="ss-next-topic">{{ $nextSession->tagline }}</span>
                    @if($nextSession->event_date)
                        <span class="ss-next-date"><i class="far fa-calendar"></i> {{ $nextSession->event_date->format('j F Y') }}</span>
                        <span class="ss-next-time"><i class="far fa-clock"></i> {{ $nextSession->event_date->format('g:ia') }} UK</span>
                    @else
                        <span class="ss-next-date"><i class="far fa-calendar"></i> Date to be announced</span>
                    @endif
                </div>
                <div class="ss-hero-actions">
                    @if($nextSession->event_date)
                        <a href="#register-section" class="ss-btn ss-btn-primary"><i class="fas fa-user-plus"></i> Register for this panel</a>
                    @else
                        <a href="#register-section" class="ss-btn ss-btn-primary"><i class="fas fa-envelope"></i> Tell me when it is announced</a>
                    @endif
                    @if($hasSpeakers)
                        <a href="#speakers" class="ss-btn ss-btn-ghost">Meet the speakers</a>
                    @endif
                </div>
            @else
                <div class="ss-hero-actions">
                    <a href="#register-section" class="ss-btn ss-btn-primary"><i class="fas fa-envelope"></i> Get session announcements</a>
                </div>
            @endif
        </div>
    </div>
</section>

@if($nextSession)
<!-- Featured Panel -->
<section id="speakers" class="ss-featured">
    <div class="ath-container">
        <div class="ss-panel-header">
            <span class="ss-panel-num">Panel {{ $nextSession->sort_order ?? '' }}</span>
            <a href="{{ route('sessions.show', $nextSession) }}" class="ss-panel-permalink">Open this panel's page &rarr;</a>
            <h2 class="ss-panel-topic">{{ $nextSession->tagline }}</h2>
            <p class="ss-panel-desc">{{ $nextSession->description }}</p>

            <div class="ss-panel-meta">
                <div class="ss-meta-cell">
                    <span class="ss-meta-lbl">Date</span>
                    <span class="ss-meta-val">{{ $nextSession->event_date?->format('j F Y') ?? 'To be announced' }}</span>
                </div>
                @if($nextSession->event_date)
                <div class="ss-meta-cell">
                    <span class="ss-meta-lbl">Time</span>
                    <span class="ss-meta-val">{{ $nextSession->event_date->format('g:ia') }} UK</span>
                </div>
                @endif
                <div class="ss-meta-cell">
                    <span class="ss-meta-lbl">Format</span>
                    <span class="ss-meta-val">{{ $nextSession->format ?: 'Online' }}</span>
                </div>
                <div class="ss-meta-cell">
                    <span class="ss-meta-lbl">Duration</span>
                    <span class="ss-meta-val">{{ $nextSession->duration ?: '60 minutes' }}</span>
                </div>
                <div class="ss-meta-cell">
                    <span class="ss-meta-lbl">Cost</span>
                    <span class="ss-meta-val">Free</span>
                </div>
            </div>
        </div>

        @if($nextSession->speakers->isNotEmpty())
        <div class="ss-speakers-grid">
            @foreach($nextSession->speakers as $speaker)
                <article class="ss-speaker-card">
                    <div class="ss-speaker-portrait" style="background-image:url('{{ $speaker->photoUrl() }}');">
                        <div class="ss-portrait-gradient"></div>
                    </div>
                    <div class="ss-speaker-body">
                        <div class="ss-speaker-tag">
                            <span class="ss-tag-bar"></span>Guest Speaker
                        </div>
                        <h3 class="ss-speaker-name">{{ $speaker->name }}</h3>
                        <p class="ss-speaker-role">
                            {{ $speaker->title }}@if($speaker->company) <span class="ss-role-sep">·</span> {{ $speaker->company }}@endif
                        </p>
                        @if($speaker->pivot->topic)
                            <p class="ss-speaker-topic"><i class="fas fa-comment-dots"></i> {{ $speaker->pivot->topic }}</p>
                        @endif
                        @if($speaker->bio)
                            <p class="ss-speaker-bio">{{ $speaker->bio }}</p>
                        @endif
                        @if($speaker->linkedin_url)
                            <a href="{{ $speaker->linkedin_url }}" target="_blank" rel="noopener" class="ss-speaker-linkedin">
                                <i class="fab fa-linkedin"></i> LinkedIn
                            </a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif

<!-- What to expect -->
<section class="ss-expect">
    <div class="ath-container">
        <div class="ss-expect-header">
            <span class="ath-sub">What to Expect</span>
            <h2>Every session, done the same way</h2>
        </div>
        <div class="ss-expect-grid">
            <div class="ss-expect-card">
                <div class="ss-expect-icon"><i class="fas fa-microphone-alt"></i></div>
                <h3>Real practitioners</h3>
                <p>People doing the work, not people reading off slides. Every panellist brings first-hand experience.</p>
            </div>
            <div class="ss-expect-card">
                <div class="ss-expect-icon"><i class="fas fa-comments"></i></div>
                <h3>Honest Q&amp;A</h3>
                <p>Open, unedited questions from the audience. If it is a hard question, we take it.</p>
            </div>
            <div class="ss-expect-card">
                <div class="ss-expect-icon"><i class="fas fa-handshake"></i></div>
                <h3>A community you can join</h3>
                <p>Connect with fellow learners, mentors, and partners. The conversation continues after the session ends.</p>
            </div>
            <div class="ss-expect-card">
                <div class="ss-expect-icon"><i class="fas fa-lightbulb"></i></div>
                <h3>Track spotlights</h3>
                <p>Each session touches one of our pilot tracks: Project and Product Delivery, Data and AI Analytics, Product Design and Marketing, or Software Development.</p>
            </div>
        </div>
    </div>
</section>

@include('sessions._register', ['session' => $nextSession])


@if($nextSession && $nextSession->speakers->isNotEmpty())
<!-- Call for Speakers (only when a real panel is being finalised) -->
<section class="ss-cfs">
    <div class="ath-container">
        <div class="ss-cfs-card">
            <div class="ss-cfs-header">
                <span class="ss-cfs-eyebrow">Call for Speakers</span>
                <h2>Do you work at the edge of this topic?</h2>
                <p>We are still looking for one or two more voices to join the panel on <strong>{{ $nextSession->tagline }}</strong>. If your work touches any of these areas, please get in touch.</p>
            </div>
            <ul class="ss-cfs-list">
                <li><i class="fas fa-university"></i> AI adoption inside public sector organisations</li>
                <li><i class="fas fa-bullseye"></i> Bias and equity in automated public services</li>
                <li><i class="fas fa-users"></i> Digital exclusion in healthcare or housing</li>
                <li><i class="fas fa-user-friends"></i> The human impact of algorithmic decision-making</li>
                <li><i class="fas fa-comments"></i> Community-level responses to AI in public life</li>
            </ul>
            <div class="ss-cfs-actions">
                <a href="mailto:hello@skillscoop.org?subject=Speaker%20interest%20-%20{{ urlencode($nextSession->tagline) }}" class="ss-btn ss-btn-primary">
                    <i class="far fa-envelope"></i> Reach out directly
                </a>
                <span class="ss-cfs-contact">or email <strong>hello@skillscoop.org</strong></span>
            </div>
        </div>
    </div>
</section>
@endif

@if($past->isNotEmpty())
<!-- Past sessions archive -->
<section class="ss-past">
    <div class="ath-container">
        <div class="ss-past-header">
            <span class="ath-sub">Archive</span>
            <h2>Past sessions</h2>
            <p>Every session recorded and available to watch back.</p>
        </div>
        @foreach($past as $pastSession)
            <article class="ss-past-card">
                <header class="ss-past-head">
                    <div>
                        <span class="ss-past-badge">{{ $pastSession->event_date?->format('F Y') ?? 'Date not recorded' }}</span>
                        <h3><a href="{{ route('sessions.show', $pastSession) }}">{{ $pastSession->tagline ?? $pastSession->title }}</a></h3>
                        <p class="ss-past-desc">{{ $pastSession->description }}</p>
                    </div>
                    @if($pastSession->recording_url)
                        <a href="{{ $pastSession->recording_url }}" target="_blank" rel="noopener" class="ss-btn ss-btn-ghost ss-btn-sm">
                            <i class="fas fa-play-circle"></i> Watch recording
                        </a>
                    @endif
                </header>

                @if($pastSession->videos->isNotEmpty())
                    <div class="ss-past-videos">
                        @foreach($pastSession->videos as $video)
                            <div class="ss-video">
                                <iframe src="{{ $video->embedUrl() }}"
                                    title="{{ $video->caption ?? $pastSession->title }}"
                                    frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen loading="lazy"></iframe>
                                @if($video->caption)<p class="ss-caption">{{ $video->caption }}</p>@endif
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($pastSession->speakers->isNotEmpty())
                    <div class="ss-past-speakers">
                        <p class="ss-past-speakers-lbl">Speakers</p>
                        <div class="ss-past-chips">
                            @foreach($pastSession->speakers as $s)
                                <div class="ss-past-chip">
                                    <img src="{{ $s->photoUrl() }}" alt="{{ $s->name }}">
                                    <div>
                                        <strong>{{ $s->name }}</strong>
                                        <span>{{ $s->title }}{{ $s->company ? ', ' . $s->company : '' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($pastSession->images->isNotEmpty())
                    <div class="ss-past-photos">
                        @foreach($pastSession->images as $img)
                            <figure class="ss-past-photo">
                                <img src="{{ $img->url }}" alt="{{ $img->caption ?? $pastSession->title }}" loading="lazy">
                                @if($img->caption)<figcaption class="ss-caption">{{ $img->caption }}</figcaption>@endif
                            </figure>
                        @endforeach
                    </div>
                @endif
            </article>
        @endforeach
    </div>
</section>
@endif

@push('styles')
    @include('sessions._styles')
@endpush

@push('scripts')
<script>
    // The "what would you speak about" field only matters once someone has
    // said they want to speak, so it stays hidden until they tick the box.
    // It ships unhidden if the form comes back with validation errors and the
    // box was ticked, so nothing a person typed disappears on them.
    document.addEventListener('DOMContentLoaded', function () {
        var check = document.getElementById('wants_to_speak');
        var group = document.getElementById('speaker-topic-group');
        if (!check || !group) return;

        function sync() {
            group.hidden = !check.checked;
        }
        check.addEventListener('change', sync);
        sync();

        // The link in the intro column is for people who came to offer to
        // speak rather than to attend: tick the box for them and put the
        // cursor where they can start typing.
        var cta = document.querySelector('[data-speaker-cta]');
        if (cta) {
            cta.addEventListener('click', function (e) {
                e.preventDefault();
                check.checked = true;
                sync();
                group.scrollIntoView({ behavior: 'smooth', block: 'center' });
                var topic = document.getElementById('speaker_topic');
                if (topic) window.setTimeout(function () { topic.focus(); }, 400);
            });
        }
    });
</script>
@endpush

@endsection
