@extends('layouts.aethryna')

@section('title', $session->tagline . ' | The Skills Co-op Sessions')

@section('meta_description', Str::limit(strip_tags($session->description), 155))
@section('og_description', Str::limit(strip_tags($session->description), 200))

@if ($session->bannerUrl())
    @section('og_image', $session->bannerUrl())
    @section('twitter_image', $session->bannerUrl())
@endif

@push('structured-data')
@php
    /*
     * event_date holds UK wall-clock time. Every screen that prints it appends
     * "UK" and none of them convert, so 18:30 in the column means 18:30 to the
     * person attending.
     *
     * The application timezone is UTC, though, so toIso8601String() stamped it
     * +00:00 — which is only correct in winter. Every panel so far has fallen in
     * British Summer Time, so search results and any calendar built from this
     * markup were putting the event an hour late. Nobody would have noticed
     * until somebody turned up at 19:30 to an event that started at 18:30.
     *
     * shiftTimezone, not setTimezone: the wall-clock reading is what is correct
     * and the offset is what was missing. setTimezone would move the time.
     */
    $startsAt = $session->event_date?->copy()->shiftTimezone('Europe/London');

    $eventSchema = array_filter([
        '@context'             => 'https://schema.org',
        '@type'                => 'Event',
        'name'                 => $session->tagline,
        'description'          => Str::limit(strip_tags($session->description), 500),
        'image'                => $session->bannerUrl(),
        'url'                  => route('sessions.show', $session),
        'eventStatus'          => 'https://schema.org/EventScheduled',
        'eventAttendanceMode'  => 'https://schema.org/OnlineEventAttendanceMode',
        'startDate'            => $startsAt?->toIso8601String(),
        // Panels run 60 minutes. Google wants an end time and will otherwise
        // guess, usually badly.
        'endDate'              => $startsAt?->copy()->addHour()->toIso8601String(),
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
        // Full VideoObject fields: Search Console flags name-and-url-only
        // video markup as missing uploadDate, thumbnailUrl and description.
        'recordedIn' => $session->recording_url
            ? collect((new \App\Models\PanelMedia(['type' => 'video', 'url' => $session->recording_url]))
                ->toVideoSchema($session))->except('@context')->all()
            : null,
    ], fn ($v) => $v !== null && $v !== []);
@endphp
<script type="application/ld+json">
{!! json_encode($eventSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@if ($session->videos->isNotEmpty())
<script type="application/ld+json">
{!! json_encode($session->videos->map(fn ($v) => $v->toVideoSchema($session))->values()->all(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
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
                        <a href="{{ route('sessions.calendar', $session) }}" class="ss-btn ss-btn-ghost"><i class="far fa-calendar-plus"></i> Add to calendar</a>
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

@if($session->bannerUrl())
<!-- Event banner -->
<section class="ss-banner-wrap">
    <div class="ath-container">
        {{-- Intrinsic dimensions, so the page does not jump as the banner
             loads. Read from the file because uploads vary in shape. Linked
             to itself so the small print is a tap away on phones, where a
             wide banner renders too small to read. --}}
        @php [$bannerW, $bannerH] = @getimagesize(public_path('images/panels/'.$session->banner_path)) ?: [null, null]; @endphp
        <a href="{{ $session->bannerUrl() }}" target="_blank" rel="noopener" aria-label="Open the event banner full size">
            <img class="ss-banner" src="{{ $session->bannerUrl() }}" alt="{{ $session->tagline }}"
                 @if($bannerW) width="{{ $bannerW }}" height="{{ $bannerH }}" @endif fetchpriority="high">
        </a>
    </div>
</section>
@endif

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
        @elseif(! $session->isPast())
            <div class="ss-soon-card">
                <h3>Speakers announced soon</h3>
                <p>
                    The lineup is being cast from the pitches coming in now. Registered
                    attendees hear as soon as names are confirmed, and if you build in
                    this space yourself, the stage is open:
                    <a href="{{ route('speakers.apply') }}">pitch your talk &rarr;</a>
                </p>
            </div>
        @endif
    </div>
</section>

@unless($session->isPast())
@if(is_array($session->itinerary) && count($session->itinerary))
<!-- Agenda -->
<section class="ss-info">
    <div class="ath-container">
        <span class="ath-sub">On the night</span>
        <h2 class="ss-info-title">How the hour runs</h2>
        <ol class="ss-agenda">
            @foreach($session->itinerary as $item)
                @php [$when, $what] = str_contains($item, '|') ? array_map('trim', explode('|', $item, 2)) : [null, trim($item)]; @endphp
                <li class="ss-agenda-item">
                    @if($when)<span class="ss-agenda-time">{{ $when }}</span>@endif
                    <span class="ss-agenda-what">{{ $what }}</span>
                </li>
            @endforeach
        </ol>
    </div>
</section>
@endif

<!-- What to expect / Good to know -->
<section class="ss-info">
    <div class="ath-container">
        <div class="ss-info-grid">
            <div class="ss-info-card">
                <span class="ath-sub">What to expect</span>
                <h2 class="ss-info-title">A panel, not a webinar</h2>
                <ul class="ss-info-list">
                    <li><i class="fas fa-comments"></i> Real practitioners showing real work, not slide decks</li>
                    <li><i class="fas fa-hand-paper"></i> Honest Q&amp;A, hard questions welcome</li>
                    <li><i class="fas fa-users"></i> A community that keeps going after the session</li>
                    <li><i class="fas fa-video"></i> Recorded and shared publicly on this page afterwards</li>
                </ul>
            </div>
            <div class="ss-info-card">
                <span class="ath-sub">Good to know</span>
                <h2 class="ss-info-title">The practical bits</h2>
                <ul class="ss-info-list">
                    <li><i class="fas fa-ticket-alt"></i> Free, no ticket and no account. Registering books your seat</li>
                    <li><i class="fas fa-link"></i> The joining link is emailed to registrants before the start</li>
                    <li><i class="far fa-calendar-plus"></i> Your confirmation email carries a calendar file, reminders follow a week out and the day before</li>
                    <li><i class="fas fa-redo"></i> Cannot make it on the night? The recording lands right here</li>
                    @if($session->accessibility_note)
                        <li><i class="fas fa-universal-access"></i> {{ $session->accessibility_note }}</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Organiser -->
<section class="ss-info">
    <div class="ath-container">
        <div class="ss-organiser">
            <img src="{{ asset('images/logo.png') }}" alt="Skills Co-op" class="ss-organiser-logo">
            <div>
                <span class="ath-sub">Hosted by</span>
                <h2 class="ss-info-title">Skills Co-op</h2>
                <p class="ss-organiser-text">
                    The Skills Co-op Sessions are a monthly panel series from Aethryna Digital
                    Skills Co-op CIC, the community interest company widening access to digital
                    skills and meaningful career progression for underserved communities.
                    Every session is free, online and open to everyone.
                </p>
                <div class="ss-organiser-links">
                    <a href="{{ route('sessions') }}" class="ss-btn ss-btn-ghost">All sessions</a>
                    <a href="{{ route('about') }}" class="ss-btn ss-btn-ghost">About us</a>
                    <a href="mailto:hello@skillscoop.org" class="ss-btn ss-btn-ghost">hello@skillscoop.org</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endunless

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

{{-- Shareable whichever side of the event we are on: before, it is the
     registration link; after, it is the recording. --}}
<section class="ss-share-strip">
    <div class="ath-container">
        @include('sessions._share', [
            'label' => $session->isPast() ? 'Share the recording' : 'Know someone who should be in the room?',
        ])
    </div>
</section>

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

@push('styles')
<style>
    .ss-banner-wrap { padding: 34px 0 0; }
    /* max-height, because a square flyer at container width would fill
       two screens. Centred at its natural shape instead of cropped: the
       flyer is typography, and object-fit cover would eat its edges. */
    .ss-banner {
        display: block; max-width: 100%; max-height: 620px; width: auto;
        margin: 0 auto; border-radius: 16px;
        box-shadow: 0 18px 40px rgba(5, 88, 96, 0.18);
    }
    /* Lives inside the dark featured section, so it keeps light text. */
    .ss-soon-card {
        margin-top: 34px; padding: 26px 30px; border-radius: 14px;
        background: rgba(255, 255, 255, 0.07); border: 1px dashed rgba(255, 255, 255, 0.35);
    }
    .ss-soon-card h3 { margin: 0 0 8px; color: #ffffff; font-size: 1.15rem; }
    .ss-soon-card p { margin: 0; line-height: 1.7; color: rgba(255, 255, 255, 0.85); }
    .ss-soon-card a { color: #f3b55c; font-weight: 600; }
    .ss-info { padding: 26px 0; }
    .ss-info-title { margin: 6px 0 16px; color: var(--ath-deep, #055860); font-size: 1.45rem; }
    .ss-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; align-items: stretch; }
    @media (max-width: 820px) { .ss-info-grid { grid-template-columns: 1fr; } }
    .ss-info-card {
        padding: 26px 30px; border-radius: 14px; background: #fff;
        border: 1px solid rgba(5, 88, 96, 0.12); box-shadow: 0 10px 28px rgba(5, 88, 96, 0.07);
    }
    .ss-info-list { list-style: none; margin: 0; padding: 0; }
    .ss-info-list li {
        display: flex; gap: 12px; align-items: baseline;
        padding: 9px 0; line-height: 1.65; color: #2b333a;
        border-bottom: 1px solid rgba(5, 88, 96, 0.07);
    }
    .ss-info-list li:last-child { border-bottom: none; }
    .ss-info-list i { color: var(--ath-teal, #038b89); min-width: 18px; }
    .ss-agenda { list-style: none; margin: 0; padding: 0; max-width: 640px; }
    .ss-agenda-item {
        display: flex; gap: 18px; align-items: baseline; padding: 12px 0;
        border-bottom: 1px solid rgba(5, 88, 96, 0.1);
    }
    .ss-agenda-item:last-child { border-bottom: none; }
    .ss-agenda-time {
        min-width: 86px; font-family: var(--font-mono, ui-monospace, monospace);
        font-size: 0.82rem; letter-spacing: 0.6px; color: var(--ath-teal, #038b89); font-weight: 700;
    }
    .ss-agenda-what { color: #2b333a; line-height: 1.6; }
    .ss-organiser {
        display: flex; gap: 26px; align-items: flex-start;
        padding: 30px; border-radius: 14px; background: #fff;
        border: 1px solid rgba(5, 88, 96, 0.12); box-shadow: 0 10px 28px rgba(5, 88, 96, 0.07);
    }
    .ss-organiser-logo { width: 72px; height: auto; flex: 0 0 auto; }
    .ss-organiser-text { margin: 0 0 16px; line-height: 1.75; color: #2b333a; max-width: 62ch; }
    .ss-organiser-links { display: flex; flex-wrap: wrap; gap: 10px; }
    .ss-organiser .ss-btn-ghost { border-color: rgba(3, 139, 137, 0.35); color: var(--ath-deep, #055860); }
    @media (max-width: 640px) { .ss-organiser { flex-direction: column; } }
</style>
@endpush
