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

<!-- Registration form -->
<section id="register-section" class="ss-register">
    <div class="ath-container">
        <div class="ss-register-grid">
            <div class="ss-register-info">
                <span class="ath-sub">The Sessions</span>
                <h2>Register for the next panel</h2>
                <p>The Skills Co-op Sessions are free, online, and open to everyone. Register here and we will email you the details for the next panel.</p>
                <p class="ss-register-note">This registers you for the panel, not for the training programme. If you are looking for the programme, <a href="{{ route('pathway') }}">start with the pathways</a>.</p>
                @if($nextSession && $nextSession->eventbrite_url)
                    <p class="ss-register-alt">Prefer Eventbrite? <a href="{{ $nextSession->eventbrite_url }}" target="_blank" rel="noopener">Register there instead &rarr;</a></p>
                @endif
            </div>

            <div class="ss-register-form-wrap">
                @if(session('success'))
                    <div class="ss-success">
                        <i class="fas fa-check-circle"></i>
                        <h3>You are registered</h3>
                        <p>{{ session('success') }}</p>
                        <a href="{{ route('home') }}" class="ss-btn ss-btn-primary">Back to home</a>
                    </div>
                @else
                    <form action="{{ route('sessions.register') }}" method="POST" class="ss-form">
                        @csrf
                        <div class="ss-form-group">
                            <label for="name">Full name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Your full name">
                            @error('name')<span class="ss-form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="ss-form-group">
                            <label for="email">Email address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
                            @error('email')<span class="ss-form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="ss-form-group">
                            <label for="interest_type">I am joining as a</label>
                            <select id="interest_type" name="interest_type" required>
                                <option value="">Select one</option>
                                <option value="learner" {{ old('interest_type') == 'learner' ? 'selected' : '' }}>A learner or career changer</option>
                                <option value="mentor" {{ old('interest_type') == 'mentor' ? 'selected' : '' }}>A mentor or industry professional</option>
                                <option value="partner" {{ old('interest_type') == 'partner' ? 'selected' : '' }}>A partner or employer</option>
                                <option value="curious" {{ old('interest_type') == 'curious' ? 'selected' : '' }}>Just curious</option>
                            </select>
                            @error('interest_type')<span class="ss-form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="ss-form-group">
                            <label for="referral_source">How did you hear about us? <span class="ss-form-opt">(optional)</span></label>
                            <select id="referral_source" name="referral_source">
                                <option value="">Select one</option>
                                <option value="social_media" {{ old('referral_source') == 'social_media' ? 'selected' : '' }}>Social media</option>
                                <option value="word_of_mouth" {{ old('referral_source') == 'word_of_mouth' ? 'selected' : '' }}>Word of mouth</option>
                                <option value="search_engine" {{ old('referral_source') == 'search_engine' ? 'selected' : '' }}>Search engine</option>
                                <option value="community_org" {{ old('referral_source') == 'community_org' ? 'selected' : '' }}>Community organisation</option>
                                <option value="event" {{ old('referral_source') == 'event' ? 'selected' : '' }}>Event or workshop</option>
                                <option value="other" {{ old('referral_source') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <button type="submit" class="ss-btn ss-btn-primary ss-btn-full">
                            <i class="fas fa-paper-plane"></i> Register for this panel
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>

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
                        <h3>{{ $pastSession->tagline ?? $pastSession->title }}</h3>
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
<link href="https://fonts.bunny.net/css?family=ibm-plex-mono:500,600&display=swap" rel="stylesheet">
<style>
    :root {
        --ath-teal: #038b89;
        --ath-gold: #ee9d1d;
        --ath-deep: #055860;
        --ath-navy: #0a2530;
        --ath-navy-2: #0e2f3c;
        --ath-light: #F8FBFB;
        --ath-text: #404952;
        --ath-muted: #57616a;
        --font-mono: 'IBM Plex Mono', 'Courier New', monospace;
    }

    .ath-container { max-width: 1250px; margin: 0 auto; padding: 0 5%; }

    /* ── Hero ─────────────────────────────────────────────────────────── */
    .ss-hero {
        padding: 180px 0 100px;
        background: radial-gradient(1200px 500px at 80% 20%, rgba(238, 157, 29, 0.16), transparent 60%),
                    radial-gradient(900px 500px at 20% 80%, rgba(3, 139, 137, 0.16), transparent 60%),
                    linear-gradient(180deg, var(--ath-navy) 0%, var(--ath-navy-2) 100%);
        color: #fff;
    }
    .ss-hero-inner { max-width: 900px; }
    .ss-eyebrow {
        display: inline-block;
        font-family: var(--font-mono);
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--ath-gold);
        margin-bottom: 24px;
        position: relative;
        padding-left: 14px;
    }
    .ss-eyebrow::before {
        content: '';
        position: absolute;
        left: 0; top: 3px; bottom: 3px;
        width: 4px;
        background: var(--ath-gold);
        border-radius: 2px;
    }
    .ss-title {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(2.5rem, 6vw, 4.4rem);
        font-weight: 800;
        line-height: 1.05;
        margin-bottom: 24px;
    }
    .ss-gradient {
        background: linear-gradient(135deg, var(--ath-gold), #fff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .ss-lede {
        font-size: 1.2rem;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.82);
        max-width: 680px;
        margin-bottom: 40px;
    }
    .ss-next-strip {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 18px;
        padding: 14px 22px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 100px;
        margin-bottom: 32px;
    }
    .ss-next-label {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--ath-gold);
        padding-right: 14px;
        border-right: 1px solid rgba(255, 255, 255, 0.15);
    }
    .ss-next-topic { font-weight: 700; font-size: 1rem; color: #fff; }
    .ss-next-date, .ss-next-time {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.75);
    }
    .ss-next-date i, .ss-next-time i { color: var(--ath-teal); margin-right: 6px; }
    .ss-hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }

    /* ── Buttons ──────────────────────────────────────────────────────── */
    .ss-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        border-radius: 100px;
        font-weight: 700;
        font-size: 0.98rem;
        text-decoration: none;
        transition: all 0.25s ease;
        cursor: pointer;
        border: none;
        font-family: inherit;
    }
    .ss-btn-primary { background: var(--ath-gold); color: #fff; }
    .ss-btn-primary:hover { background: #fff; color: var(--ath-navy); transform: translateY(-2px); }
    .ss-btn-ghost {
        background: rgba(255, 255, 255, 0.06);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
    .ss-btn-ghost:hover { background: rgba(255, 255, 255, 0.12); }
    .ss-btn-full { width: 100%; justify-content: center; }
    .ss-btn-sm { padding: 10px 18px; font-size: 0.88rem; }

    /* ── Featured Panel ───────────────────────────────────────────────── */
    .ss-featured {
        padding: 100px 0;
        background: linear-gradient(180deg, var(--ath-navy-2) 0%, var(--ath-navy) 100%);
        color: #fff;
    }
    .ss-panel-header {
        text-align: left;
        max-width: 900px;
        margin: 0 auto 60px;
    }
    .ss-panel-num {
        display: inline-block;
        font-family: var(--font-mono);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--ath-teal);
        margin-bottom: 12px;
    }
    .ss-panel-topic {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(2rem, 5vw, 3.2rem);
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 22px;
        color: #fff;
        border-left: 4px solid var(--ath-gold);
        padding-left: 20px;
    }
    /* Descriptions are stored as plain text and can run to more than one
       paragraph. pre-line keeps the blank lines the copy was written with,
       without having to render the field as unescaped HTML. */
    .ss-panel-desc, .ss-past-desc {
        white-space: pre-line;
    }
    .ss-panel-desc {
        font-size: 1.1rem;
        line-height: 1.75;
        color: rgba(255, 255, 255, 0.82);
        margin-bottom: 36px;
    }
    .ss-panel-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        padding: 20px 24px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
    }
    .ss-meta-cell {
        display: flex;
        flex-direction: column;
        gap: 3px;
        padding-right: 24px;
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        min-width: 90px;
    }
    .ss-meta-cell:last-child { border-right: none; }
    .ss-meta-lbl {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.6);
    }
    .ss-meta-val { font-weight: 700; font-size: 0.95rem; color: #fff; }

    /* ── Speaker cards ────────────────────────────────────────────────── */
    .ss-speakers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 28px;
    }
    .ss-speaker-card {
        background: var(--ath-navy);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s;
    }
    .ss-speaker-card:hover {
        transform: translateY(-6px);
        border-color: rgba(238, 157, 29, 0.35);
    }
    .ss-speaker-portrait {
        aspect-ratio: 4 / 5;
        position: relative;
        background-color: #12303c;
        background-size: cover;
        background-position: center top;
    }
    .ss-portrait-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 40%, rgba(10, 37, 48, 0.65) 75%, var(--ath-navy) 100%);
    }
    .ss-portrait-fallback {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: rgba(255, 255, 255, 0.15);
    }
    .ss-speaker-body {
        padding: 28px 30px 32px;
        margin-top: -10px;
        position: relative;
    }
    .ss-speaker-tag {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--ath-teal);
        margin-bottom: 12px;
    }
    .ss-tag-bar { display: inline-block; width: 4px; height: 14px; background: var(--ath-gold); border-radius: 2px; }
    .ss-speaker-name {
        font-family: 'Outfit', sans-serif;
        font-size: 1.55rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 6px;
        display: inline-block;
        border-bottom: 3px solid var(--ath-gold);
        padding-bottom: 2px;
    }
    .ss-speaker-role {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.98rem;
        line-height: 1.5;
        margin-bottom: 14px;
    }
    .ss-role-sep { color: rgba(255, 255, 255, 0.35); margin: 0 4px; }
    .ss-speaker-topic {
        font-size: 0.9rem;
        color: var(--ath-gold);
        font-weight: 600;
        margin-bottom: 14px;
    }
    .ss-speaker-topic i { margin-right: 6px; }
    .ss-speaker-bio {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.92rem;
        line-height: 1.7;
        margin-bottom: 16px;
    }
    .ss-speaker-linkedin {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--ath-teal);
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
    }
    .ss-speaker-linkedin:hover { color: var(--ath-gold); }

    /* ── What to Expect ───────────────────────────────────────────────── */
    .ss-expect { padding: 100px 0; background: #fff; }
    .ss-expect-header { text-align: left; max-width: 700px; margin-bottom: 50px; }
    .ath-sub {
        display: block;
        font-family: var(--font-mono);
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--ath-gold);
        margin-bottom: 10px;
    }
    .ss-expect-header h2 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(2rem, 4vw, 2.6rem);
        color: var(--ath-deep);
        font-weight: 800;
    }
    .ss-expect-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
    }
    .ss-expect-card {
        background: var(--ath-light);
        border: 1px solid rgba(3, 139, 137, 0.08);
        border-radius: 20px;
        padding: 32px;
        transition: transform 0.3s, border-color 0.3s;
    }
    .ss-expect-card:hover {
        transform: translateY(-4px);
        border-color: var(--ath-teal);
    }
    .ss-expect-icon {
        width: 52px; height: 52px;
        background: rgba(3, 139, 137, 0.1);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ath-teal);
        font-size: 1.3rem;
        margin-bottom: 18px;
    }
    .ss-expect-card h3 {
        font-family: 'Outfit', sans-serif;
        color: var(--ath-deep);
        font-weight: 800;
        font-size: 1.15rem;
        margin-bottom: 8px;
    }
    .ss-expect-card p { color: var(--ath-muted); line-height: 1.7; font-size: 0.95rem; }

    /* ── Registration ─────────────────────────────────────────────────── */
    .ss-register { padding: 100px 0; background: var(--ath-light); }
    .ss-register-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: start;
    }
    .ss-register-info h2 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(1.8rem, 4vw, 2.4rem);
        color: var(--ath-deep);
        font-weight: 800;
        margin-bottom: 16px;
    }
    .ss-register-info > p {
        font-size: 1.05rem;
        color: var(--ath-muted);
        line-height: 1.7;
        margin-bottom: 20px;
    }
    .ss-register-alt { font-size: 0.95rem; color: var(--ath-muted); }
    .ss-register-alt a { color: var(--ath-teal); font-weight: 700; text-decoration: none; }
    .ss-register-alt a:hover { color: var(--ath-gold); }

    /* Registering for a panel is not applying to the programme. Readers were
       conflating the two, so the distinction is stated rather than inferred. */
    .ss-register-note {
        font-size: 0.95rem;
        color: var(--ath-muted);
        margin-top: 14px;
        padding-left: 14px;
        border-left: 3px solid rgba(3, 139, 137, 0.25);
    }
    .ss-register-note a { color: var(--ath-teal); font-weight: 700; text-decoration: none; }
    .ss-register-note a:hover { color: var(--ath-gold); }

    .ss-form {
        background: #fff;
        padding: 40px;
        border-radius: 24px;
        border: 1px solid rgba(3, 139, 137, 0.1);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.06);
    }
    .ss-form-group { margin-bottom: 20px; }
    .ss-form-group label {
        display: block;
        font-weight: 700;
        color: var(--ath-deep);
        margin-bottom: 8px;
        font-size: 0.92rem;
    }
    .ss-form-opt { font-weight: 500; color: var(--ath-muted); font-size: 0.85rem; }
    .ss-form-group input,
    .ss-form-group select {
        width: 100%;
        padding: 13px 16px;
        border: 1.5px solid rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        font-size: 0.98rem;
        font-family: inherit;
        color: var(--ath-text);
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
        box-sizing: border-box;
    }
    .ss-form-group input:focus,
    .ss-form-group select:focus {
        border-color: var(--ath-teal);
        box-shadow: 0 0 0 4px rgba(3, 139, 137, 0.1);
    }
    .ss-form-error { color: #b91c1c; font-size: 0.85rem; margin-top: 6px; display: block; }
    .ss-success {
        background: #fff;
        padding: 50px 40px;
        border-radius: 24px;
        border: 2px solid var(--ath-teal);
        text-align: center;
    }
    .ss-success i { font-size: 3rem; color: var(--ath-teal); margin-bottom: 16px; }
    .ss-success h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.6rem;
        color: var(--ath-deep);
        font-weight: 800;
        margin-bottom: 10px;
    }
    .ss-success p { color: var(--ath-muted); line-height: 1.6; margin-bottom: 20px; }

    /* ── Call for Speakers ────────────────────────────────────────────── */
    .ss-cfs { padding: 100px 0; background: #fff; }
    .ss-cfs-card {
        background: var(--ath-navy);
        border-radius: 32px;
        padding: 60px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .ss-cfs-card::before {
        content: '';
        position: absolute;
        top: -20%; right: -10%;
        width: 60%;
        height: 130%;
        background: radial-gradient(closest-side, rgba(238, 157, 29, 0.18), transparent 70%);
        pointer-events: none;
    }
    .ss-cfs-header { max-width: 700px; margin-bottom: 30px; position: relative; z-index: 1; }
    .ss-cfs-eyebrow {
        display: inline-block;
        font-family: var(--font-mono);
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--ath-gold);
        margin-bottom: 16px;
        padding-left: 14px;
        border-left: 4px solid var(--ath-gold);
    }
    .ss-cfs-header h2 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(1.8rem, 4vw, 2.6rem);
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 16px;
    }
    .ss-cfs-header p {
        font-size: 1.1rem;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.85);
    }
    .ss-cfs-list {
        list-style: none;
        padding: 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px 24px;
        margin: 30px 0;
        position: relative;
        z-index: 1;
    }
    .ss-cfs-list li {
        display: flex;
        align-items: center;
        gap: 14px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.98rem;
    }
    .ss-cfs-list i {
        width: 36px; height: 36px;
        background: rgba(238, 157, 29, 0.15);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ath-gold);
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    .ss-cfs-actions {
        display: flex;
        gap: 20px;
        align-items: center;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    .ss-cfs-contact { color: rgba(255, 255, 255, 0.75); font-size: 0.95rem; }
    .ss-cfs-contact strong { color: var(--ath-gold); font-weight: 700; }

    /* ── Past sessions ────────────────────────────────────────────────── */
    .ss-past { padding: 100px 0; background: var(--ath-light); }
    .ss-past-header { max-width: 700px; margin-bottom: 40px; }
    .ss-past-header h2 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(2rem, 4vw, 2.4rem);
        color: var(--ath-deep);
        font-weight: 800;
        margin-bottom: 8px;
    }
    .ss-past-header p { color: var(--ath-muted); font-size: 1.05rem; }
    .ss-past-card {
        background: #fff;
        border: 1px solid rgba(3, 139, 137, 0.1);
        border-radius: 24px;
        padding: 36px;
        margin-bottom: 28px;
    }
    .ss-past-head {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .ss-past-badge {
        display: inline-block;
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        background: rgba(3, 139, 137, 0.1);
        color: var(--ath-teal);
        padding: 5px 12px;
        border-radius: 100px;
        margin-bottom: 10px;
    }
    .ss-past-head h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.35rem;
        color: var(--ath-deep);
        font-weight: 800;
        margin-bottom: 8px;
    }
    .ss-past-head p { color: var(--ath-text); line-height: 1.65; }
    .ss-past-videos {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    .ss-video iframe { width: 100%; aspect-ratio: 16 / 9; border-radius: 12px; border: 0; }
    .ss-caption { font-size: 0.87rem; color: var(--ath-muted); margin: 8px 0 0; line-height: 1.5; }
    .ss-past-speakers-lbl {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--ath-muted);
        margin-bottom: 12px;
    }
    .ss-past-chips { display: flex; flex-wrap: wrap; gap: 12px; }
    .ss-past-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--ath-light);
        border: 1px solid rgba(3, 139, 137, 0.15);
        border-radius: 100px;
        padding: 5px 16px 5px 5px;
    }
    .ss-past-chip img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
    .ss-past-chip strong { color: var(--ath-deep); font-size: 0.9rem; display: block; }
    .ss-past-chip span { font-size: 0.78rem; color: var(--ath-muted); }
    .ss-past-photos {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
        margin-top: 24px;
    }
    .ss-past-photo img { width: 100%; height: 180px; object-fit: cover; border-radius: 12px; }

    /* ── Responsive ───────────────────────────────────────────────────── */
    @media (max-width: 992px) {
        .ss-register-grid { grid-template-columns: 1fr; gap: 40px; }
        .ss-cfs-list { grid-template-columns: 1fr; }
        .ss-cfs-card { padding: 40px 30px; }
    }
    @media (max-width: 768px) {
        .ss-hero { padding: 140px 0 80px; }
        .ss-next-strip { padding: 12px 18px; gap: 12px; }
        .ss-next-label { padding-right: 12px; }
        .ss-panel-meta { flex-direction: column; padding: 16px; }
        .ss-meta-cell { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.08); padding-right: 0; padding-bottom: 10px; }
        .ss-meta-cell:last-child { border-bottom: none; padding-bottom: 0; }
        .ss-past-head { flex-direction: column; }
        .ss-past-videos { grid-template-columns: 1fr; }
        .ss-form { padding: 28px 22px; }
    }
</style>
@endpush

@endsection
