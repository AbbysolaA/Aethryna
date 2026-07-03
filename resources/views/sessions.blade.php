@extends('layouts.aethryna')

@section('title', 'Sessions & Events | SkillsCo-op')

@section('meta_description', 'Join our free monthly panel sessions with industry professionals, mentors, and learners. Register for the next SkillsCo-op Sessions event. Open to everyone.')
@section('og_description', 'Join our free monthly panel sessions with industry professionals, mentors, and learners. Register for the next SkillsCo-op Sessions event. Open to everyone.')

@section('content')

    <!-- Hero Section -->
    <section class="sessions-hero">
        <div class="ath-container">
            <div class="sessions-hero-content">
                <span class="ath-badge">Monthly Panels</span>
                <h1 class="ath-title">Sessions & <span class="ath-gradient-text">Events</span></h1>
                <p>Join our monthly panel discussions with industry experts, mentors, and learners. Network, learn, and grow
                    with the SkillsCo-op community.</p>
                @if($upcoming->isNotEmpty() && $upcoming->first()->eventbrite_url)
                <a href="{{ $upcoming->first()->eventbrite_url }}" target="_blank" rel="noopener" class="btn btn-primary">
                    Register on Eventbrite <i class="fas fa-external-link-alt"></i>
                </a>
                @endif
                <a href="#speakers" class="btn btn-outline" style="margin-left:12px;">Meet the Speakers</a>
            </div>
        </div>
    </section>

    <!-- Upcoming Session Details -->
    <section class="session-details">
        <div class="ath-container">
            <div class="section-title">
                <span class="ath-sub">What to Expect</span>
                <h2>Monthly Panel Sessions</h2>
                <p>Engaging discussions on digital skills, career development, and community impact</p>
            </div>
            <div class="details-grid">
                <div class="detail-card">
                    <div class="detail-icon"><i class="fas fa-microphone-alt"></i></div>
                    <h3>Expert Panels</h3>
                    <p>Hear from industry professionals, data scientists, tech leaders, and educators sharing real-world
                        insights and career advice.</p>
                </div>
                <div class="detail-card">
                    <div class="detail-icon"><i class="fas fa-comments"></i></div>
                    <h3>Open Q&A</h3>
                    <p>Ask questions directly to panelists and get personalised advice on your digital skills and career options.
                        </p>
                </div>
                <div class="detail-card">
                    <div class="detail-icon"><i class="fas fa-handshake"></i></div>
                    <h3>Networking</h3>
                    <p>Connect with fellow learners, potential mentors, and industry partners in a welcoming,
                        community-driven environment.</p>
                </div>
                <div class="detail-card">
                    <div class="detail-icon"><i class="fas fa-lightbulb"></i></div>
                    <h3>Skill Spotlights</h3>
                    <p>Each session features a closer look into one of our learning tracks: Web Development, Digital Design, IT Support, or Digital Sales.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Registration Form -->
    <section id="register-section" class="session-register">
        <div class="ath-container">
            <div class="register-grid">
                <div class="register-info">
                    <span class="ath-sub">Get Involved</span>
                    <h2>Register for Our Next Session</h2>
                    <p>Our sessions are free and open to everyone. Whether you're a learner, mentor, partner, or simply
                        curious - you're welcome.</p>
                    @if($upcoming->isNotEmpty())
                    @php $nextSession = $upcoming->first(); @endphp
                    <div class="session-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <strong>Frequency</strong>
                                <span>Monthly (dates announced via email)</span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>Duration</strong>
                                <span>{{ $nextSession->duration }} (including Q&A)</span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-laptop"></i>
                            <div>
                                <strong>Format</strong>
                                <span>Virtual (Eventbrite), link sent after registration</span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-pound-sign"></i>
                            <div>
                                <strong>Cost</strong>
                                <span>Completely free</span>
                            </div>
                        </div>
                        @if($nextSession->eventbrite_url)
                        <div class="meta-item">
                            <i class="fas fa-ticket-alt"></i>
                            <div>
                                <strong>Register</strong>
                                <a href="{{ $nextSession->eventbrite_url }}" target="_blank" rel="noopener"
                                   style="color:var(--ath-teal);font-weight:600;">
                                    Eventbrite &rarr;
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="session-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <strong>Frequency</strong>
                                <span>Monthly (dates announced via email)</span>
                            </div>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <div><strong>Duration</strong><span>90 minutes (including Q&A)</span></div>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-laptop"></i>
                            <div><strong>Format</strong><span>Virtual, link sent after registration</span></div>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-pound-sign"></i>
                            <div><strong>Cost</strong><span>Completely free</span></div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="register-form-wrapper">
                    @if (session('success'))
                        <div x-data="{ open: true }" x-show="open"
                            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-100">
                            <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-lg">
                                <h3 class="text-2xl font-semibold mb-4">You're Registered!</h3>
                                <p class="mb-6">{{ session('success') }}</p>
                                <a href="{{ route('home') }}" class="btn btn-primary w-full text-center"
                                    x-on:click="open = false">Back to Home</a>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('sessions.register') }}" method="POST" class="register-form">
                            @csrf
                            <h3>Reserve Your Spot</h3>

                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    placeholder="Your full name">
                                @error('name')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    placeholder="you@example.com">
                                @error('email')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="interest_type">I'm interested as a... *</label>
                                <select id="interest_type" name="interest_type" required>
                                    <option value="">Select one</option>
                                    <option value="learner" {{ old('interest_type') == 'learner' ? 'selected' : '' }}>
                                        Learner: I want to develop digital skills</option>
                                    <option value="mentor" {{ old('interest_type') == 'mentor' ? 'selected' : '' }}>Mentor: I want to guide others</option>
                                    <option value="partner" {{ old('interest_type') == 'partner' ? 'selected' : '' }}>
                                        Partner: I represent an organisation</option>
                                    <option value="curious" {{ old('interest_type') == 'curious' ? 'selected' : '' }}>Just
                                        Just Curious: I want to learn more</option>
                                </select>
                                @error('interest_type')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="referral_source">How did you hear about us?</label>
                                <select id="referral_source" name="referral_source">
                                    <option value="">Select one (optional)</option>
                                    <option value="social_media"
                                        {{ old('referral_source') == 'social_media' ? 'selected' : '' }}>Social Media
                                    </option>
                                    <option value="word_of_mouth"
                                        {{ old('referral_source') == 'word_of_mouth' ? 'selected' : '' }}>Word of Mouth
                                    </option>
                                    <option value="search_engine"
                                        {{ old('referral_source') == 'search_engine' ? 'selected' : '' }}>Search Engine
                                        (Google, etc.)</option>
                                    <option value="community_org"
                                        {{ old('referral_source') == 'community_org' ? 'selected' : '' }}>Community
                                        Organisation</option>
                                    <option value="event" {{ old('referral_source') == 'event' ? 'selected' : '' }}>Event
                                        / Workshop</option>
                                    <option value="other" {{ old('referral_source') == 'other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-full">
                                <i class="fas fa-paper-plane"></i> Register Now
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>


    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- SPEAKERS SECTION                                                    --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    @if($upcoming->isNotEmpty() && $upcoming->first()->speakers->isNotEmpty())
    <section id="speakers" class="speakers-section">
        <div class="ath-container">
            <div class="section-title">
                <span class="ath-sub">Panel {{ $upcoming->first()->title }}</span>
                <h2>{{ $upcoming->first()->tagline }}</h2>
                <p>Meet the practitioners joining us for Panel 1 on {{ $upcoming->first()->event_date->format('j F Y') }}.</p>
            </div>
            <div class="speakers-grid">
                @foreach($upcoming->first()->speakers as $speaker)
                <div class="speaker-card">
                    <div class="speaker-photo">
                        <img src="{{ $speaker->photoUrl() }}" alt="{{ $speaker->name }}">
                    </div>
                    <div class="speaker-info">
                        <h3>{{ $speaker->name }}</h3>
                        <p class="speaker-title">{{ $speaker->title }}</p>
                        @if($speaker->company)
                        <p class="speaker-company">{{ $speaker->company }}</p>
                        @endif
                        @if($speaker->pivot->topic)
                        <p class="speaker-topic"><i class="fas fa-comment-dots"></i> {{ $speaker->pivot->topic }}</p>
                        @endif
                        <p class="speaker-bio">{{ $speaker->bio }}</p>
                        @if($speaker->linkedin_url)
                        <a href="{{ $speaker->linkedin_url }}" target="_blank" rel="noopener" class="speaker-linkedin">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @if($upcoming->first()->eventbrite_url)
            <div style="text-align:center;margin-top:48px;">
                <a href="{{ $upcoming->first()->eventbrite_url }}" target="_blank" rel="noopener" class="btn btn-primary btn-lg">
                    <i class="fas fa-ticket-alt"></i> Register Free on Eventbrite
                </a>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    {{-- PAST SESSIONS ARCHIVE                                               --}}
    {{-- ═══════════════════════════════════════════════════════════════════ --}}
    @if($past->isNotEmpty())
    <section class="past-sessions">
        <div class="ath-container">
            <div class="section-title">
                <span class="ath-sub">Archive</span>
                <h2>Past Sessions</h2>
                <p>Every session recorded and available to watch back.</p>
            </div>
            @foreach($past as $pastSession)
            <div class="past-session-card">
                <div class="past-session-header">
                    <div>
                        <span class="past-badge">{{ $pastSession->event_date->format('F Y') }}</span>
                        <h3>{{ $pastSession->tagline ?? $pastSession->title }}</h3>
                        <p>{{ $pastSession->description }}</p>
                    </div>
                    @if($pastSession->recording_url)
                    <a href="{{ $pastSession->recording_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                        <i class="fas fa-play-circle"></i> Watch Recording
                    </a>
                    @endif
                </div>

                {{-- Videos --}}
                @if($pastSession->videos->isNotEmpty())
                <div class="session-videos">
                    @foreach($pastSession->videos as $video)
                    <div class="video-embed-wrap">
                        <iframe src="{{ $video->embedUrl() }}"
                            title="{{ $video->caption ?? $pastSession->title }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen loading="lazy"></iframe>
                        @if($video->caption)
                        <p class="media-caption">{{ $video->caption }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Speakers --}}
                @if($pastSession->speakers->isNotEmpty())
                <div class="past-speakers">
                    <p class="past-speakers-label">Speakers</p>
                    <div class="past-speakers-row">
                        @foreach($pastSession->speakers as $s)
                        <div class="past-speaker-chip">
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

                {{-- Photos --}}
                @if($pastSession->images->isNotEmpty())
                <div class="session-photos">
                    @foreach($pastSession->images as $img)
                    <div class="session-photo">
                        <img src="{{ $img->url }}" alt="{{ $img->caption ?? $pastSession->title }}" loading="lazy">
                        @if($img->caption)<p class="media-caption">{{ $img->caption }}</p>@endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @endif

    @push('styles')
        <style>
            :root {
                --ath-teal: #038b89;
                --ath-gold: #ee9d1d;
                --ath-deep: #055860;
                --ath-light: #F8FBFB;
                --ath-white: #ffffff;
                --ath-text: #404952;
                --ath-muted: #57616a;
                --ath-trans: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
                --ath-radius: 24px;
            }

            .ath-container {
                max-width: 1250px;
                margin: 0 auto;
                padding: 0 5%;
            }

            /* Hero */
            .sessions-hero {
                padding: 180px 0 120px;
                background: var(--ath-deep);
                color: #fff;
                position: relative;
                overflow: hidden;
            }

            .sessions-hero::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: radial-gradient(circle at 80% 20%, rgba(238, 157, 29, 0.15), transparent 50%),
                    radial-gradient(circle at 20% 80%, rgba(3, 139, 137, 0.2), transparent 50%);
            }

            .sessions-hero-content {
                position: relative;
                z-index: 2;
                max-width: 700px;
            }

            .sessions-hero h1 {
                font-size: clamp(3rem, 8vw, 5rem);
                font-weight: 800;
                line-height: 1.05;
                margin-bottom: 25px;
                font-family: 'Outfit', sans-serif;
            }

            .sessions-hero p {
                font-size: 1.3rem;
                opacity: 0.9;
                line-height: 1.6;
                margin-bottom: 40px;
            }

            .ath-badge {
                display: inline-block;
                padding: 10px 20px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 100px;
                font-size: 0.85rem;
                font-weight: 800;
                margin-bottom: 25px;
                letter-spacing: 2px;
                text-transform: uppercase;
                border: 1px solid rgba(255, 255, 255, 0.15);
            }

            .ath-gradient-text {
                background: linear-gradient(135deg, var(--ath-gold), #fff);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            /* Session Details */
            .session-details {
                padding: 100px 0;
                background: var(--ath-light);
            }

            .section-title {
                text-align: left;
                margin-bottom: 3.5rem;
            }

            .section-title h2 {
                font-size: clamp(2rem, 5vw, 3rem);
                color: var(--ath-deep);
                font-weight: 800;
                margin-bottom: 1rem;
                font-family: 'Outfit', sans-serif;
            }

            .section-title p {
                font-size: 1.2rem;
                color: var(--ath-muted);
                max-width: 700px;
            }

            .ath-sub {
                display: block;
                color: var(--ath-gold);
                text-transform: uppercase;
                letter-spacing: 3px;
                font-weight: 700;
                font-size: 0.85rem;
                margin-bottom: 1rem;
            }

            .details-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 30px;
            }

            .detail-card {
                background: #fff;
                padding: 40px;
                border-radius: var(--ath-radius);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
                border: 1px solid rgba(0, 0, 0, 0.04);
                transition: var(--ath-trans);
            }

            .detail-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08);
                border-color: var(--ath-teal);
            }

            .detail-icon {
                width: 65px;
                height: 65px;
                background: rgba(3, 139, 137, 0.08);
                border-radius: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.6rem;
                color: var(--ath-teal);
                margin-bottom: 25px;
                transition: var(--ath-trans);
            }

            .detail-card:hover .detail-icon {
                background: var(--ath-teal);
                color: #fff;
            }

            .detail-card h3 {
                font-size: 1.4rem;
                color: var(--ath-deep);
                font-weight: 800;
                margin-bottom: 15px;
            }

            .detail-card p {
                color: var(--ath-muted);
                line-height: 1.7;
            }

            /* Registration */
            .session-register {
                padding: 100px 0;
                background: #fff;
            }

            .register-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 80px;
                align-items: start;
            }

            .register-info h2 {
                font-size: clamp(2rem, 4vw, 2.8rem);
                color: var(--ath-deep);
                font-weight: 800;
                margin-bottom: 20px;
                font-family: 'Outfit', sans-serif;
            }

            .register-info>p {
                font-size: 1.15rem;
                color: var(--ath-muted);
                line-height: 1.8;
                margin-bottom: 40px;
            }

            .session-meta {
                display: grid;
                gap: 25px;
            }

            .meta-item {
                display: flex;
                gap: 20px;
                align-items: flex-start;
            }

            .meta-item i {
                width: 50px;
                height: 50px;
                background: rgba(3, 139, 137, 0.08);
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--ath-teal);
                font-size: 1.2rem;
                flex-shrink: 0;
            }

            .meta-item strong {
                display: block;
                color: var(--ath-deep);
                font-weight: 700;
                margin-bottom: 3px;
            }

            .meta-item span {
                color: var(--ath-muted);
                font-size: 0.95rem;
            }

            /* Form */
            .register-form-wrapper {
                position: sticky;
                top: 120px;
            }

            .register-form {
                background: var(--ath-light);
                padding: 50px;
                border-radius: var(--ath-radius);
                border: 1px solid rgba(0, 0, 0, 0.05);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.06);
            }

            .register-form h3 {
                font-size: 1.8rem;
                color: var(--ath-deep);
                font-weight: 800;
                margin-bottom: 30px;
                font-family: 'Outfit', sans-serif;
            }

            .form-group {
                margin-bottom: 22px;
            }

            .form-group label {
                display: block;
                font-weight: 700;
                color: var(--ath-deep);
                margin-bottom: 8px;
                font-size: 0.95rem;
            }

            .form-group input,
            .form-group select {
                width: 100%;
                padding: 14px 18px;
                border: 2px solid rgba(0, 0, 0, 0.08);
                border-radius: 14px;
                font-size: 1rem;
                background: #fff;
                transition: var(--ath-trans);
                outline: none;
                color: var(--ath-text);
                font-family: 'Outfit', sans-serif;
                box-sizing: border-box;
            }

            .form-group input:focus,
            .form-group select:focus {
                border-color: var(--ath-teal);
                box-shadow: 0 0 0 4px rgba(3, 139, 137, 0.1);
            }

            .form-group input::placeholder {
                color: #adb5bd;
            }

            .form-error {
                color: #e74c3c;
                font-size: 0.85rem;
                margin-top: 5px;
                display: block;
            }

            .btn-full {
                width: 100%;
                padding: 16px;
                font-size: 1.1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                margin-top: 10px;
            }

            /* Success Message */
            .success-message {
                background: var(--ath-light);
                padding: 60px 50px;
                border-radius: var(--ath-radius);
                text-align: center;
                border: 2px solid rgba(3, 139, 137, 0.2);
            }

            .success-icon {
                font-size: 4rem;
                color: var(--ath-teal);
                margin-bottom: 20px;
            }

            .success-message h3 {
                font-size: 2rem;
                color: var(--ath-deep);
                font-weight: 800;
                margin-bottom: 15px;
            }

            .success-message p {
                color: var(--ath-muted);
                font-size: 1.1rem;
                line-height: 1.6;
            }

            /* Responsive */
            @media (max-width: 992px) {
                .register-grid {
                    grid-template-columns: 1fr;
                    gap: 60px;
                }

                .register-form-wrapper {
                    position: static;
                }
            }

            @media (max-width: 768px) {
                .sessions-hero {
                    padding: 140px 0 80px;
                }

                .details-grid {
                    grid-template-columns: 1fr;
                }

                .register-form {
                    padding: 35px 25px;
                }
            }

            /* ── Speakers ── */
            .speakers-section { padding: 80px 0; background: #f8fafb; }
            .speakers-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(480px, 1fr)); gap: 32px; margin-top: 48px; }
            .speaker-card { background: #fff; border-radius: 16px; box-shadow: 0 2px 16px rgba(3,139,137,0.08); display: flex; gap: 24px; padding: 28px; align-items: flex-start; transition: box-shadow 0.3s; }
            .speaker-card:hover { box-shadow: 0 6px 28px rgba(3,139,137,0.15); }
            .speaker-photo img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--ath-teal, #038b89); flex-shrink: 0; }
            .speaker-info h3 { font-size: 1.15rem; font-weight: 700; color: #055860; margin: 0 0 4px; }
            .speaker-title { font-size: 0.9rem; color: #038b89; font-weight: 600; margin: 0 0 2px; }
            .speaker-company { font-size: 0.85rem; color: #6b7480; margin: 0 0 6px; }
            .speaker-topic { font-size: 0.85rem; color: #ee9d1d; font-weight: 600; margin: 0 0 8px; }
            .speaker-topic i { margin-right: 4px; }
            .speaker-bio { font-size: 0.88rem; color: #404952; line-height: 1.65; margin: 0 0 10px; }
            .speaker-linkedin { font-size: 0.85rem; color: #038b89; text-decoration: none; font-weight: 600; }
            .speaker-linkedin:hover { color: #055860; }
            .btn-lg { padding: 16px 40px; font-size: 1.05rem; }

            /* ── Past Sessions ── */
            .past-sessions { padding: 80px 0; }
            .past-session-card { background: #fff; border-radius: 16px; border: 1px solid #d4e8e8; padding: 36px; margin-bottom: 32px; }
            .past-session-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; margin-bottom: 24px; flex-wrap: wrap; }
            .past-badge { display: inline-block; background: #e8f4f4; color: #038b89; font-size: 0.8rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; margin-bottom: 8px; }
            .past-session-header h3 { font-size: 1.25rem; font-weight: 700; color: #055860; margin: 0 0 8px; }
            .past-session-header p { color: #404952; font-size: 0.95rem; line-height: 1.6; margin: 0; }
            .btn-sm { padding: 8px 18px; font-size: 0.88rem; white-space: nowrap; }
            .session-videos { display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 20px; margin-bottom: 24px; }
            .video-embed-wrap iframe { width: 100%; aspect-ratio: 16/9; border-radius: 10px; }
            .media-caption { font-size: 0.82rem; color: #6b7480; margin: 6px 0 0; text-align: center; }
            .past-speakers { margin-bottom: 24px; }
            .past-speakers-label { font-size: 0.8rem; font-weight: 700; color: #6b7480; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 12px; }
            .past-speakers-row { display: flex; flex-wrap: wrap; gap: 12px; }
            .past-speaker-chip { display: flex; align-items: center; gap: 10px; background: #f8fafb; border: 1px solid #d4e8e8; border-radius: 50px; padding: 6px 16px 6px 6px; }
            .past-speaker-chip img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
            .past-speaker-chip strong { font-size: 0.85rem; color: #055860; display: block; }
            .past-speaker-chip span { font-size: 0.78rem; color: #6b7480; }
            .session-photos { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px; }
            .session-photo img { width: 100%; height: 180px; object-fit: cover; border-radius: 10px; }

            @media (max-width: 640px) {
                .speakers-grid { grid-template-columns: 1fr; }
                .speaker-card { flex-direction: column; }
                .speaker-photo img { width: 80px; height: 80px; }
                .past-session-header { flex-direction: column; }
                .session-videos { grid-template-columns: 1fr; }
            }
        </style>
    @endpush
@endsection
