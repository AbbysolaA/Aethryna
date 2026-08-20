{{--
    The Discovery Session banner.

    Built from the supplied design, with three changes. The logo mark and copy
    are unchanged; what differs is that nothing here is hard-coded — the date,
    venue and link come from the event record, so moving the event does not mean
    editing a banner someone forgot existed. It renders nothing at all once the
    event is over or missing, rather than advertising a past date indefinitely.

    Self-contained on purpose: its own palette and typefaces, so it can sit on
    any page without inheriting or fighting that page's styles.

    Include with @include('partials.event-banner'), optionally passing a slug.
--}}
@php
    $bannerEvent = \App\Models\PanelSession::query()
        ->where('slug', $slug ?? 'discovery-session')
        ->whereIn('status', ['upcoming', 'live'])
        ->first();

    // Past the day itself it stops being a promotion and starts being a
    // liability. End of the event day rather than the start time, so it stays
    // up for anyone checking the address on the morning.
    if ($bannerEvent?->event_date && $bannerEvent->event_date->copy()->endOfDay()->isPast()) {
        $bannerEvent = null;
    }
@endphp

@if ($bannerEvent)
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Mulish:wght@500;700;800&display=swap">

    <aside class="evb" aria-label="Upcoming event">
        <div class="evb-shape evb-shape-1" aria-hidden="true"></div>
        <div class="evb-shape evb-shape-2" aria-hidden="true"></div>

        <div class="evb-cube" aria-hidden="true">
            <svg viewBox="0 0 100 100">
                <polygon points="50,2 98,26 50,50 2,26" fill="#E8B647"/>
                <polygon points="2,26 50,50 50,98 2,74" fill="#0b6a6f"/>
                <polygon points="50,50 98,26 98,74 50,98" fill="#055860"/>
            </svg>
        </div>

        <div class="evb-text">
            <div class="evb-brand">
                {{-- The real mark, not a redrawing of it. The nav and footer
                     already serve this file on every page, so by the time the
                     banner is reached it is coming from cache. --}}
                <img src="{{ asset('images/logo_white.png') }}" alt="" class="evb-mark" width="40" height="40">
                <span>Skills Co-op</span>
            </div>

            <p class="evb-eyebrow">Free community event</p>
            <h2 class="evb-title">Community Discovery Session</h2>

            <div class="evb-meta">
                <span>{{ $bannerEvent->event_date?->format('l j F Y') }}</span>
                <span class="evb-dot" aria-hidden="true"></span>
                <span>{{ $bannerEvent->venue_name }}</span>
            </div>

            <p class="evb-line">Meet the team. Try the learning. Decide nothing on the day.</p>
        </div>

        <div class="evb-cta">
            <a href="{{ $bannerEvent->url() }}" class="evb-btn">
                {{ $bannerEvent->isFull() ? 'Join the waiting list' : 'Register free' }}
                <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </aside>

    <style>
        .evb {
            width: 100%; box-sizing: border-box;
            background: linear-gradient(135deg, #062f35 0%, #08444A 55%, #0a5a60 100%);
            color: #F7F2E8; font-family: 'Mulish', system-ui, sans-serif;
            position: relative; overflow: hidden;
            display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
            gap: clamp(24px, 4vw, 56px);
            padding: clamp(28px, 4.5vw, 60px) clamp(24px, 5vw, 72px);
        }
        .evb-shape { position: absolute; border-radius: 50%; pointer-events: none; }
        .evb-shape-1 { top: -120px; right: -80px; width: 340px; height: 340px; background: rgba(12,80,88,.55); }
        .evb-shape-2 { bottom: -140px; right: 180px; width: 260px; height: 260px; background: rgba(10,90,96,.4); }
        .evb-cube { position: absolute; right: 36px; top: 22px; width: 64px; height: 64px;
                    transform: rotate(-10deg); pointer-events: none; opacity: .9; }
        .evb-cube svg { width: 100%; height: 100%; }

        .evb-text { position: relative; z-index: 2; flex: 1 1 460px; min-width: 0; }
        .evb-brand { display: flex; align-items: center; gap: 12px; margin-bottom: clamp(14px, 1.6vw, 22px); }
        .evb-mark { width: 40px; height: auto; display: block; }
        .evb-brand span { font-family: 'Playfair Display', Georgia, serif; font-weight: 700; font-size: 18px; color: #F7F2E8; }

        .evb-eyebrow { font-size: clamp(11px, 1.1vw, 13px); letter-spacing: .28em; font-weight: 800;
                       color: #E8B647; text-transform: uppercase; margin: 0; }
        .evb-title { font-family: 'Playfair Display', Georgia, serif; font-weight: 800;
                     font-size: clamp(34px, 5.6vw, 64px); line-height: 1.02; letter-spacing: -.01em;
                     margin: clamp(8px, 1vw, 14px) 0 0; color: #F7F2E8; }

        .evb-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 10px 16px;
                    margin-top: clamp(12px, 1.4vw, 18px); font-size: clamp(14px, 1.4vw, 19px); font-weight: 700; }
        .evb-dot { width: 6px; height: 6px; border-radius: 50%; background: #E8B647; }

        .evb-line { font-size: clamp(14px, 1.35vw, 18px); line-height: 1.5; font-weight: 500;
                    color: #9fc7c4; margin: clamp(12px, 1.4vw, 18px) 0 0; max-width: 44ch; }

        .evb-cta { position: relative; z-index: 2; flex: 0 0 auto; }
        .evb-btn {
            display: inline-flex; align-items: center; gap: 12px;
            background: #EE9D1D; color: #08444A; font-weight: 800;
            font-size: clamp(16px, 1.5vw, 20px);
            padding: clamp(15px, 1.5vw, 20px) clamp(26px, 2.4vw, 38px);
            border-radius: 100px; text-decoration: none; white-space: nowrap;
            box-shadow: 0 10px 30px rgba(0,0,0,.22);
            transition: transform .15s ease, background .15s ease;
        }
        .evb-btn:hover { background: #E8B647; transform: translateY(-2px); color: #08444A; }
        .evb-btn:focus-visible { outline: 3px solid #F7F2E8; outline-offset: 3px; }

        /* Below the CTA's own width the decorative cube starts colliding with
           the headline, so it goes rather than overlapping the text. */
        @media (max-width: 720px) {
            .evb-cube { display: none; }
            .evb-cta { width: 100%; }
            .evb-btn { width: 100%; justify-content: center; }

            /* Date and venue each take their own line at this width, which
               leaves the separator stranded at the end of the first one. */
            .evb-meta { display: block; }
            .evb-meta span { display: block; }
            .evb-meta span + span { margin-top: 4px; }
            /* Scoped to beat .evb-meta span above, which is more specific
               than a lone .evb-dot and would otherwise re-show it. */
            .evb-meta .evb-dot { display: none; }
        }

        @media (prefers-reduced-motion: reduce) {
            .evb-btn { transition: none; }
            .evb-btn:hover { transform: none; }
        }
    </style>
@endif
