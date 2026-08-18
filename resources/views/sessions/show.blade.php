@extends('layouts.aethryna')

@section('title', $session->tagline . ' | The Skills Co-op Sessions')

@section('meta_description', Str::limit(strip_tags($session->description), 155))
@section('og_description', Str::limit(strip_tags($session->description), 200))

@push('structured-data')
@php
    $eventSchema = array_filter([
        '@context'             => 'https://schema.org',
        '@type'                => 'Event',
        'name'                 => $session->tagline,
        'description'          => Str::limit(strip_tags($session->description), 500),
        'url'                  => route('sessions.show', $session),
        'eventStatus'          => 'https://schema.org/EventScheduled',
        'eventAttendanceMode'  => 'https://schema.org/OnlineEventAttendanceMode',
        'startDate'            => $session->event_date?->toIso8601String(),
        'organizer'            => ['@id' => rtrim(url('/'), '/') . '/#organisation'],
        'inLanguage'           => 'en-GB',
        'isAccessibleForFree'  => true,
        'location'             => [
            '@type' => 'VirtualLocation',
            'url'   => $session->eventbrite_url ?: route('sessions.show', $session),
        ],
        'offers' => [
            '@type'        => 'Offer',
            'price'        => '0',
            'priceCurrency' => 'GBP',
            'availability' => 'https://schema.org/InStock',
            'url'          => $session->eventbrite_url ?: route('sessions.show', $session),
        ],
        'performer' => $session->speakers->map(fn ($s) => array_filter([
            '@type'    => 'Person',
            'name'     => $s->name,
            'jobTitle' => $s->title,
        ]))->all() ?: null,
        'recordedIn' => $session->recording_url ? [
            '@type'      => 'VideoObject',
            'name'       => 'Recording: ' . $session->tagline,
            'contentUrl' => $session->recording_url,
        ] : null,
    ], fn ($v) => $v !== null && $v !== []);
@endphp
<script type="application/ld+json">
{!! json_encode($eventSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

<!-- Hero -->
<section class="ss-hero">
    <div class="ath-container">
        <div class="ss-hero-inner">
            <a href="{{ route('sessions') }}" class="ss-eyebrow ss-back-link">&larr; The Skills Co-op Sessions</a>
            <h1 class="ss-title">{{ $session->tagline }}</h1>

            <div class="ss-next-strip">
                <span class="ss-next-label">{{ $session->isPast() ? 'Past panel' : 'Upcoming panel' }}</span>
                @if($session->sort_order)
                    <span class="ss-next-topic">Panel {{ $session->sort_order }}</span>
                @endif
                @if($session->event_date)
                    <span class="ss-next-date"><i class="far fa-calendar"></i> {{ $session->event_date->format('j F Y') }}</span>
                    <span class="ss-next-time"><i class="far fa-clock"></i> {{ $session->event_date->format('g:ia') }} UK</span>
                @else
                    <span class="ss-next-date"><i class="far fa-calendar"></i> Date to be announced</span>
                @endif
            </div>

            <div class="ss-hero-actions">
                @if($session->isPast())
                    @if($session->recording_url)
                        <a href="{{ $session->recording_url }}" target="_blank" rel="noopener" class="ss-btn ss-btn-primary">
                            <i class="fas fa-play-circle"></i> Watch the recording
                        </a>
                    @endif
                    <a href="{{ route('sessions') }}" class="ss-btn ss-btn-ghost">See the next panel</a>
                @else
                    @if($session->event_date)
                        <a href="#register-section" class="ss-btn ss-btn-primary"><i class="fas fa-user-plus"></i> Register for this panel</a>
                    @else
                        <a href="#register-section" class="ss-btn ss-btn-primary"><i class="fas fa-envelope"></i> Tell me when it is announced</a>
                    @endif
                    @if($session->speakers->isNotEmpty())
                        <a href="#speakers" class="ss-btn ss-btn-ghost">Meet the speakers</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Panel detail -->
<section id="speakers" class="ss-featured">
    <div class="ath-container">
        <div class="ss-panel-header">
            <span class="ss-panel-num">{{ $session->title }}</span>
            <h2 class="ss-panel-topic">{{ $session->tagline }}</h2>
            <p class="ss-panel-desc">{{ $session->description }}</p>

            <div class="ss-panel-meta">
                <div class="ss-meta-cell">
                    <span class="ss-meta-lbl">Date</span>
                    <span class="ss-meta-val">{{ $session->event_date?->format('j F Y') ?? 'To be announced' }}</span>
                </div>
                @if($session->event_date)
                <div class="ss-meta-cell">
                    <span class="ss-meta-lbl">Time</span>
                    <span class="ss-meta-val">{{ $session->event_date->format('g:ia') }} UK</span>
                </div>
                @endif
                <div class="ss-meta-cell">
                    <span class="ss-meta-lbl">Format</span>
                    <span class="ss-meta-val">{{ $session->format ?: 'Online' }}</span>
                </div>
                <div class="ss-meta-cell">
                    <span class="ss-meta-lbl">Duration</span>
                    <span class="ss-meta-val">{{ $session->duration ?: '60 minutes' }}</span>
                </div>
                <div class="ss-meta-cell">
                    <span class="ss-meta-lbl">Cost</span>
                    <span class="ss-meta-val">Free</span>
                </div>
            </div>
        </div>

        @if($session->speakers->isNotEmpty())
        <div class="ss-speakers-grid">
            @foreach($session->speakers as $speaker)
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

@if($session->videos->isNotEmpty())
<!-- Recording -->
<section class="ss-past">
    <div class="ath-container">
        <div class="ss-past-header">
            <span class="ath-sub">Watch it back</span>
            <h2>Recording</h2>
        </div>
        <article class="ss-past-card">
            <div class="ss-past-videos">
                @foreach($session->videos as $video)
                    <div class="ss-video">
                        <iframe src="{{ $video->embedUrl() }}"
                            title="{{ $video->caption ?? $session->title }}"
                            frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen loading="lazy"></iframe>
                        @if($video->caption)<p class="ss-caption">{{ $video->caption }}</p>@endif
                    </div>
                @endforeach
            </div>
        </article>
    </div>
</section>
@endif

{{-- A past panel takes no registrations; the whole point of the page then is
     the recording and who spoke. --}}
@unless($session->isPast())
    @include('sessions._register', ['session' => $session, 'namedPanel' => true])
@endunless

@endsection

@push('styles')
    @include('sessions._styles')
    <style>
        .ss-back-link { text-decoration: none; display: inline-block; }
        .ss-back-link:hover { color: var(--ath-gold); }
    </style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var check = document.getElementById('wants_to_speak');
        var group = document.getElementById('speaker-topic-group');
        if (!check || !group) return;

        function sync() { group.hidden = !check.checked; }
        check.addEventListener('change', sync);
        sync();

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
