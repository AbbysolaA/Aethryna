@extends('layouts.aethryna')

@section('title', 'Discovery Session, 29 August, Birkenhead | Skills Co-op')
@section('meta_description', 'A free afternoon in Birkenhead on Saturday 29 August 2026, introducing Skills Co-op. No experience needed, nothing to sign up to on the day. Step-free venue. Register free.')
@section('og_title', 'Skills Co-op Community Discovery Session')
@section('og_description', 'Saturday 29 August 2026, Wirral Multicultural Organisation. Meet the team. Try the learning. Decide nothing on the day.')

@push('styles')
    {{-- Playfair Display and Mulish are this page's typefaces and nowhere
         else's, so they load here rather than in the layout. One extra request
         on one page, instead of two extra families on every page. --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Mulish:wght@400;500;600;700;800&display=swap">
    <style>
        /*
         * The event brand, scoped to this page.
         *
         * Deep teal and amber on cream, which is the palette the printed
         * material uses. It is deliberately not applied site-wide: the rest of
         * the site is still on the older kit, and a half-applied rebrand looks
         * worse than two consistent ones.
         */
        .ds {
            --ds-teal-deep: #08444A;
            --ds-teal: #055860;
            --ds-teal-alt: #0c5058;
            --ds-teal-bright: #038B88;
            --ds-ink: #2D353C;
            --ds-amber: #E8B647;
            --ds-amber-deep: #C77F14;
            --ds-amber-bright: #EE9D1D;
            --ds-cream: #F7F2E8;
            --ds-slate: #59626A;
            --ds-slate-dark: #414A52;

            font-family: 'Mulish', system-ui, -apple-system, sans-serif;
            color: var(--ds-ink);

            /* The layout wraps content in <main class="py-20">, which leaves a
               white band between the nav and any full-bleed hero. Every page
               with one has it; this page cancels it rather than inherits it,
               because this is the URL on the printed flyers and a white stripe
               under the masthead reads as a broken page.
               Scoped here deliberately: changing the shared layout would move
               the top of every page on the site. */
            margin-top: -5rem;
            margin-bottom: -5rem;
        }
        .ds h1, .ds h2, .ds h3 { font-family: 'Playfair Display', Georgia, serif; }
        .ds-wrap { max-width: 1120px; margin: 0 auto; padding: 0 24px; }

        /* Hero */
        .ds-hero {
            background: linear-gradient(135deg, #062f35 0%, var(--ds-teal-deep) 55%, #0a5a60 100%);
            color: var(--ds-cream);
            padding: clamp(48px, 7vw, 92px) 0 clamp(56px, 8vw, 104px);
            position: relative;
            overflow: hidden;
        }
        .ds-hero::before,
        .ds-hero::after {
            content: ''; position: absolute; border-radius: 50%; pointer-events: none;
        }
        .ds-hero::before { top: -160px; right: -110px; width: 420px; height: 420px; background: rgba(12,80,88,.5); }
        .ds-hero::after { bottom: -180px; right: 160px; width: 300px; height: 300px; background: rgba(10,90,96,.36); }
        .ds-hero > * { position: relative; z-index: 2; }

        .ds-eyebrow {
            font-size: clamp(11px, 1.1vw, 13px); letter-spacing: .28em;
            font-weight: 800; color: var(--ds-amber); text-transform: uppercase;
        }
        .ds-hero h1 {
            font-weight: 800; font-size: clamp(38px, 6vw, 68px); line-height: 1.02;
            letter-spacing: -.01em; margin: 14px 0 0; color: var(--ds-cream);
        }
        .ds-tagline {
            font-size: clamp(16px, 1.7vw, 21px); font-weight: 700;
            color: var(--ds-amber); margin: 14px 0 0; letter-spacing: .02em;
        }
        .ds-lede {
            font-size: clamp(16px, 1.5vw, 19px); line-height: 1.6; font-weight: 500;
            color: #b9d6d4; margin: 20px 0 0; max-width: 56ch;
        }

        /* The four facts someone needs before they will read anything else. */
        .ds-facts {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2px; margin: clamp(32px, 4vw, 48px) 0 0;
            background: rgba(255,255,255,.14); border-radius: 12px; overflow: hidden;
        }
        .ds-fact { background: rgba(6,47,53,.88); padding: 20px 22px; }
        .ds-fact-label {
            font-size: 11px; letter-spacing: .16em; text-transform: uppercase;
            font-weight: 800; color: var(--ds-amber); margin: 0 0 7px;
        }
        .ds-fact-value { font-size: 17px; line-height: 1.45; font-weight: 700; color: var(--ds-cream); margin: 0; }
        .ds-fact-value small { display: block; font-size: 14px; font-weight: 500; color: #9fc7c4; margin-top: 3px; }

        .ds-hero-cta { display: flex; flex-wrap: wrap; gap: 14px; align-items: center; margin: clamp(28px, 3.5vw, 40px) 0 0; }
        .ds-btn {
            display: inline-flex; align-items: center; gap: 11px; border: 0; cursor: pointer;
            background: var(--ds-amber-bright); color: var(--ds-teal-deep);
            font-family: 'Mulish', sans-serif; font-weight: 800; font-size: clamp(16px, 1.4vw, 18px);
            padding: 17px 34px; border-radius: 100px; text-decoration: none;
            box-shadow: 0 10px 30px rgba(0,0,0,.22);
            transition: transform .15s ease, background .15s ease;
        }
        .ds-btn:hover { background: var(--ds-amber); transform: translateY(-2px); color: var(--ds-teal-deep); }
        .ds-btn-ghost {
            background: transparent; color: var(--ds-cream);
            border: 2px solid rgba(247,242,232,.45); box-shadow: none;
        }
        .ds-btn-ghost:hover { background: rgba(247,242,232,.1); color: var(--ds-cream); border-color: var(--ds-cream); }

        /* The masthead is sticky, so an in-page jump would otherwise land with
           the section's first line underneath it. */
        .ds [id] { scroll-margin-top: 96px; }

        /* Body sections */
        .ds-section { padding: clamp(48px, 6vw, 84px) 0; }
        .ds-section-cream { background: var(--ds-cream); }
        /* The form section is not a .ds-section, so its heading has to be named
           here too or it renders at body size. */
        .ds-section h2, .ds-form-section h2 {
            font-size: clamp(26px, 3.4vw, 40px); font-weight: 700; color: var(--ds-teal-deep);
            margin: 0 0 8px; line-height: 1.15;
        }
        .ds-section-intro { font-size: 17px; line-height: 1.65; color: var(--ds-slate-dark); margin: 0 0 32px; max-width: 62ch; }

        .ds-who { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; }
        .ds-who-card {
            background: #fff; border: 1px solid #e4ddcd; border-radius: 12px; padding: 22px 24px;
            border-top: 4px solid var(--ds-amber);
        }
        .ds-who-card h3 { font-size: 18px; font-weight: 700; color: var(--ds-teal-deep); margin: 0 0 8px; }
        .ds-who-card p { font-size: 15px; line-height: 1.6; color: var(--ds-slate); margin: 0; }
        .ds-who-note {
            margin: 22px 0 0; font-size: 16px; line-height: 1.6; color: var(--ds-slate-dark);
            padding: 16px 20px; background: #fff; border-left: 4px solid var(--ds-teal-bright); border-radius: 6px;
        }

        /* Pathways */
        .ds-pathways { display: flex; flex-wrap: wrap; gap: 12px; margin: 0; padding: 0; list-style: none; }
        .ds-pathway {
            display: flex; align-items: center; gap: 12px;
            background: var(--ds-teal-deep); color: var(--ds-cream);
            padding: 14px 22px 14px 16px; border-radius: 100px;
            font-weight: 700; font-size: clamp(14px, 1.3vw, 16px);
        }
        .ds-pathway span {
            display: inline-flex; align-items: center; justify-content: center;
            width: 26px; height: 26px; border-radius: 50%; flex: 0 0 26px;
            background: var(--ds-amber); color: var(--ds-teal-deep); font-size: 13px; font-weight: 800;
        }

        /* Itinerary */
        .ds-timeline { margin: 0; padding: 0; list-style: none; border-left: 2px solid #dfd6c2; }
        .ds-timeline li { position: relative; padding: 0 0 26px 28px; }
        .ds-timeline li:last-child { padding-bottom: 0; }
        .ds-timeline li::before {
            content: ''; position: absolute; left: -8px; top: 6px;
            width: 14px; height: 14px; border-radius: 50%;
            background: var(--ds-amber); border: 3px solid var(--ds-cream);
        }
        .ds-time { font-weight: 800; color: var(--ds-amber-deep); font-size: 14px; letter-spacing: .04em; }
        .ds-what { font-size: 18px; font-weight: 700; color: var(--ds-teal-deep); margin: 3px 0 4px; font-family: 'Playfair Display', Georgia, serif; }
        .ds-detail { font-size: 15px; line-height: 1.6; color: var(--ds-slate); margin: 0; max-width: 58ch; }

        /* Form */
        .ds-form-section { background: var(--ds-teal-deep); color: var(--ds-cream); padding: clamp(48px, 6vw, 84px) 0; }
        .ds-form-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.05fr); gap: clamp(28px, 4vw, 56px); align-items: start; }
        @media (max-width: 860px) { .ds-form-grid { grid-template-columns: 1fr; } }
        .ds-form-section h2 { color: var(--ds-cream); }
        .ds-form-intro { font-size: 16px; line-height: 1.65; color: #b9d6d4; margin: 0 0 22px; }
        .ds-assure { margin: 0; padding: 0; list-style: none; }
        .ds-assure li {
            position: relative; padding: 0 0 12px 28px; font-size: 15px; line-height: 1.55; color: #d6e7e5;
        }
        .ds-assure li::before {
            content: '✓'; position: absolute; left: 0; top: 0; color: var(--ds-amber); font-weight: 800;
        }

        .ds-card { background: var(--ds-cream); border-radius: 16px; padding: clamp(24px, 3vw, 36px); color: var(--ds-ink); }
        .ds-field { margin: 0 0 18px; }
        .ds-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        @media (max-width: 520px) { .ds-row { grid-template-columns: 1fr; gap: 0; } }
        .ds-card label { display: block; font-size: 14px; font-weight: 700; color: var(--ds-teal-deep); margin: 0 0 6px; }
        .ds-optional { font-weight: 500; color: var(--ds-slate); }
        .ds-card input[type="text"], .ds-card input[type="email"], .ds-card input[type="tel"],
        .ds-card select, .ds-card textarea {
            width: 100%; box-sizing: border-box; font-family: 'Mulish', sans-serif; font-size: 16px;
            padding: 13px 15px; border: 2px solid #ddd4c2; border-radius: 9px; background: #fff; color: var(--ds-ink);
        }
        .ds-card input:focus, .ds-card select:focus, .ds-card textarea:focus {
            outline: none; border-color: var(--ds-teal-bright); box-shadow: 0 0 0 3px rgba(3,139,136,.16);
        }
        .ds-card textarea { min-height: 96px; resize: vertical; }
        .ds-help { font-size: 13px; color: var(--ds-slate); margin: 6px 0 0; line-height: 1.5; }
        .ds-error { display: block; font-size: 13px; font-weight: 700; color: #a4331f; margin: 6px 0 0; }
        .ds-consent { display: flex; gap: 12px; align-items: flex-start; margin: 4px 0 20px; }
        .ds-consent input { margin: 3px 0 0; width: 19px; height: 19px; flex: 0 0 19px; accent-color: var(--ds-teal-bright); }
        .ds-consent label { font-weight: 500; font-size: 14px; line-height: 1.55; color: var(--ds-slate-dark); margin: 0; }
        .ds-submit { width: 100%; justify-content: center; }

        /* Only ever seen by something that is not reading the page. */
        .ds-hp { position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden; }

        .ds-success { text-align: center; padding: 12px 0 4px; }
        .ds-success-mark {
            width: 58px; height: 58px; border-radius: 50%; margin: 0 auto 18px;
            display: flex; align-items: center; justify-content: center;
            background: var(--ds-teal-bright); color: #fff; font-size: 27px; font-weight: 800;
        }
        .ds-success h3 { font-size: 25px; color: var(--ds-teal-deep); margin: 0 0 10px; }
        .ds-success p { font-size: 16px; line-height: 1.6; color: var(--ds-slate-dark); margin: 0 0 8px; }

        .ds-alert {
            background: #FFF6E0; border-left: 4px solid var(--ds-amber); border-radius: 8px;
            padding: 14px 18px; margin: 0 0 22px; font-size: 15px; line-height: 1.55; color: var(--ds-slate-dark);
        }
        .ds-alert strong { color: var(--ds-amber-deep); }

        .ds-alt-route { margin: 20px 0 0; font-size: 14px; color: #9fc7c4; }
        .ds-alt-route a { color: var(--ds-amber); font-weight: 700; }

        @media (prefers-reduced-motion: reduce) {
            .ds-btn { transition: none; }
            .ds-btn:hover { transform: none; }
        }
    </style>
@endpush

@section('content')
@php
    // event_date holds UK wall-clock time by convention across this table, and
    // every panel view formats it straight. Converting it here would read the
    // stored 12.30 as UTC and print 1.30pm, an hour after the doors open.
    $starts = $session->event_date;
    $ends   = $starts?->copy()->addMinutes(180);
    $left   = $session->spacesLeft();
@endphp

<div class="ds">

    {{-- Hero --}}
    <section class="ds-hero">
        <div class="ds-wrap">
            <p class="ds-eyebrow">Free community event</p>
            <h1>Discovery Session</h1>
            <p class="ds-tagline">{{ $session->tagline }}</p>
            <p class="ds-lede">
                Meet the team. Try the learning. Decide nothing on the day.
            </p>

            <div class="ds-facts">
                <div class="ds-fact">
                    <p class="ds-fact-label">When</p>
                    <p class="ds-fact-value">
                        {{ $starts?->format('l j F Y') }}
                        <small>{{ $starts?->format('g.ia') }} to {{ $ends?->format('g.ia') }}</small>
                    </p>
                </div>
                <div class="ds-fact">
                    <p class="ds-fact-label">Where</p>
                    <p class="ds-fact-value">
                        {{ $session->venue_name }}
                        <small>{{ $session->venue_address }}</small>
                    </p>
                </div>
                <div class="ds-fact">
                    <p class="ds-fact-label">Cost</p>
                    <p class="ds-fact-value">
                        Free
                        <small>Refreshments included</small>
                    </p>
                </div>
                <div class="ds-fact">
                    <p class="ds-fact-label">Access</p>
                    <p class="ds-fact-value">
                        Step-free
                        <small>Including the toilets</small>
                    </p>
                </div>
            </div>

            <div class="ds-hero-cta">
                <a href="#register" class="ds-btn">Register free <span aria-hidden="true">&rarr;</span></a>
                <a href="#on-the-day" class="ds-btn ds-btn-ghost">See what happens</a>
            </div>

            @if ($session->shouldShowSpacesLeft())
                <p class="ds-alt-route">{{ $left }} {{ $left === 1 ? 'place' : 'places' }} left of {{ $session->capacity }}.</p>
            @elseif ($session->isFull())
                <p class="ds-alt-route">The room is full. You can still join the waiting list below.</p>
            @endif

            @if ($session->eventbrite_url)
                <p class="ds-alt-route">
                    Prefer Eventbrite? <a href="{{ $session->eventbrite_url }}" target="_blank" rel="noopener">Register there instead &rarr;</a>
                </p>
            @endif
        </div>
    </section>

    {{-- Who it is for --}}
    <section class="ds-section ds-section-cream">
        <div class="ds-wrap">
            <h2>Who this afternoon is for</h2>
            <p class="ds-section-intro">
                {{ $session->description }}
            </p>

            <div class="ds-who">
                <div class="ds-who-card">
                    <h3>Young people</h3>
                    <p>Not currently in education, employment or training, and wondering what else there is.</p>
                </div>
                <div class="ds-who-card">
                    <h3>Rebuilding after prison</h3>
                    <p>Adults with lived experience of the justice system, looking for a route that does not close the door first.</p>
                </div>
                <div class="ds-who-card">
                    <h3>Returning to work</h3>
                    <p>Women coming back after a career break or time spent caring, and starting from where they are.</p>
                </div>
            </div>

            <p class="ds-who-note">
                You do not have to be in any of those groups. If you are curious about a future in digital work, come along.
            </p>
        </div>
    </section>

    {{-- The pathways --}}
    <section class="ds-section">
        <div class="ds-wrap">
            <h2>The five pathways</h2>
            <p class="ds-section-intro">
                The full programme runs for 25 weeks from January 2027, with AI tools built into all of it. On the day you can try a piece of any of these.
            </p>
            <ul class="ds-pathways">
                @foreach ($pathways as $i => $pathway)
                    <li class="ds-pathway"><span>{{ $i + 1 }}</span>{{ $pathway }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Itinerary --}}
    @if (filled($session->itinerary))
        <section class="ds-section ds-section-cream" id="on-the-day">
            <div class="ds-wrap">
                <h2>On the day</h2>
                <p class="ds-section-intro">
                    Knowing the shape of an afternoon in advance makes an unfamiliar room easier to walk into. Here is all of it.
                </p>
                <ul class="ds-timeline">
                    @foreach ($session->itinerary as $item)
                        <li>
                            <p class="ds-time">{{ $item['time'] }}</p>
                            <p class="ds-what">{{ $item['what'] }}</p>
                            <p class="ds-detail">{{ $item['detail'] }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Register --}}
    <section class="ds-form-section" id="register">
        <div class="ds-wrap">
            <div class="ds-form-grid">
                <div>
                    <p class="ds-eyebrow">Register</p>
                    <h2>Come along</h2>
                    <p class="ds-form-intro">
                        It takes a minute. We will email you the details, and that is the last you will hear from us until the week of the event.
                    </p>
                    <ul class="ds-assure">
                        <li>Free, with refreshments</li>
                        <li>No experience or qualifications needed</li>
                        <li>Nothing to sign up to on the day</li>
                        <li>Step-free access throughout, including the toilets</li>
                        <li>Bring someone with you if that helps</li>
                    </ul>
                    <p class="ds-alt-route">
                        Questions? Email <a href="mailto:{{ config('organisation.email') }}">{{ config('organisation.email') }}</a>.
                    </p>
                </div>

                <div class="ds-card">
                    @if (session('success'))
                        <div class="ds-success">
                            <div class="ds-success-mark" aria-hidden="true">✓</div>
                            <h3>{{ session('waitlisted') ? 'You are on the list' : 'You are registered' }}</h3>
                            <p>{{ session('success') }}</p>
                            <p class="ds-help">Nothing in your inbox after a few minutes? Check the spam folder, then email us.</p>
                        </div>
                    @else
                        @if ($session->isFull())
                            <div class="ds-alert">
                                <strong>The room is full.</strong> Register anyway and you go on the waiting list. Places come up more often than you would think.
                            </div>
                        @endif

                        <form action="{{ route('discovery-session.register') }}" method="POST" novalidate>
                            @csrf

                            <div class="ds-row">
                                <div class="ds-field">
                                    <label for="first_name">First name</label>
                                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}"
                                           autocomplete="given-name" required>
                                    @error('first_name')<span class="ds-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="ds-field">
                                    <label for="last_name">Last name</label>
                                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                                           autocomplete="family-name" required>
                                    @error('last_name')<span class="ds-error">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="ds-field">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                       autocomplete="email" required>
                                @error('email')<span class="ds-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="ds-field">
                                <label for="phone">Phone <span class="ds-optional">(optional)</span></label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel">
                                <p class="ds-help">Only used if something changes on the day.</p>
                                @error('phone')<span class="ds-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="ds-field">
                                <label for="audience_group">Which of these sounds most like you? <span class="ds-optional">(optional)</span></label>
                                <select id="audience_group" name="audience_group">
                                    <option value="">Rather not say right now</option>
                                    @foreach ($groups as $value => $label)
                                        @continue($value === 'prefer_not_to_say')
                                        <option value="{{ $value }}" @selected(old('audience_group') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <p class="ds-help">It helps us plan the room. It does not affect your place.</p>
                                @error('audience_group')<span class="ds-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="ds-field">
                                <label for="notes">Anything we should know? <span class="ds-optional">(optional)</span></label>
                                <textarea id="notes" name="notes" placeholder="Access requirements, dietary needs, coming with someone, or anything that would make it easier to be there.">{{ old('notes') }}</textarea>
                                @error('notes')<span class="ds-error">{{ $message }}</span>@enderror
                            </div>

                            {{-- Not a real field. Anything that fills it in was not reading the page. --}}
                            <div class="ds-hp" aria-hidden="true">
                                <label for="website">Leave this empty</label>
                                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="ds-consent">
                                <input type="checkbox" id="consent" name="consent" value="1" @checked(old('consent'))>
                                <label for="consent">
                                    I understand Skills Co-op will use these details to contact me about this event and future programmes.
                                </label>
                            </div>
                            @error('consent')<span class="ds-error" style="margin-top:-14px; margin-bottom:14px;">{{ $message }}</span>@enderror

                            <button type="submit" class="ds-btn ds-submit">
                                {{ $session->isFull() ? 'Join the waiting list' : 'Register free' }}
                                <span aria-hidden="true">&rarr;</span>
                            </button>

                            <p class="ds-help" style="margin-top:14px;">
                                We will not pass your details to anyone else. See our <a href="{{ route('privacy') }}" style="color:{{ '#08444A' }}; font-weight:700;">privacy notice</a>.
                            </p>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
{{-- An in-person, free event on a specific date is exactly what Event markup
     is for, and it is what puts the date and place into a search result rather
     than just a page title. --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'Event',
    'name'     => $session->title,
    'description' => $session->description,
    'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
    'eventStatus' => 'https://schema.org/EventScheduled',

    // shiftTimezone, not setTimezone: 12.30pm is 12.30pm in that room, and
    // converting it would move the event by an hour in the listing.
    'startDate' => $starts?->copy()->shiftTimezone('Europe/London')->toIso8601String(),
    'endDate'   => $ends?->copy()->shiftTimezone('Europe/London')->toIso8601String(),
    'location'  => [
        '@type'   => 'Place',
        'name'    => $session->venue_name,
        'address' => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => '111 Conway Street',
            'addressLocality' => 'Birkenhead',
            'postalCode'      => 'CH41 4AF',
            'addressCountry'  => 'GB',
        ],
    ],
    'organizer' => [
        '@type' => 'Organization',
        'name'  => config('organisation.name'),
        'url'   => config('organisation.url'),
    ],
    'isAccessibleForFree' => true,
    'offers' => [
        '@type'         => 'Offer',
        'price'         => '0',
        'priceCurrency' => 'GBP',
        'availability'  => $session->isFull()
            ? 'https://schema.org/SoldOut'
            : 'https://schema.org/InStock',
        'url'           => url()->current(),
        'validFrom'     => now()->toIso8601String(),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
