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
                <svg viewBox="29.39 17.73 10.48 12.02" class="evb-mark" aria-hidden="true">
                    <path d="M29.4299 26.8212 L34.7031 29.7363 L36.9130 28.5146 L36.9130 25.9627 L34.7807 27.1415 L29.4299 24.1835 Z" fill="#055860"/>
                    <path d="M32.4775 24.9073 L34.8041 26.1935 L37.0504 24.9517 L37.6490 25.2826 L37.6490 27.9898 L39.8619 26.7665 L39.8619 24.0579 L34.6760 21.1910 L32.4775 22.4063 Z" fill="#2D353C"/>
                    <path d="M34.6295 17.7363 L29.3939 20.6306 L29.3939 23.2696 L31.7992 24.5993 L31.7992 22.0785 L34.6716 20.4906 L37.3385 21.9648 L39.8086 20.5993 Z" fill="#C77F14"/>
                    <path d="M34.6302 17.7824 L29.3956 20.6762 L31.8189 22.0158 L34.6714 20.4390 L37.3453 21.9172 L39.7275 20.6003 Z" fill="#EE9D1D"/>
                    <path d="M29.4325 22.5823 L29.4952 22.6169 L29.4952 23.1851 L31.7992 24.5313 L31.7992 22.0097 L29.4325 20.7014 Z" fill="#C77F14"/>
                    <path d="M37.7022 25.3186 L37.7022 27.8376 L37.8947 27.8376 L39.8619 26.7501 L39.8619 24.1246 Z" fill="#2D353C"/>
                    <path d="M32.4775 24.8864 L34.7246 26.1286 L34.9072 26.1286 L37.0755 24.9299 L32.4775 22.3881 Z" fill="#2D353C"/>
                    <path d="M34.9180 23.6993 L37.0422 22.5250 L34.6828 21.2207 L32.5586 22.3950 Z" fill="#414A52"/>
                    <path d="M37.6933 25.2853 L39.8292 24.1046 L37.0132 22.5479 L34.8773 23.7286 Z" fill="#59626A"/>
                    <path d="M34.7541 27.1863 L34.7541 29.6801 L36.9130 28.4867 L36.9130 25.9928 Z" fill="#038B88"/>
                    <path d="M29.4612 26.8429 L31.7212 28.0922 L34.0817 26.7873 L29.4612 24.2331 Z" fill="#038B88"/>
                    <path d="M34.7492 29.7205 L34.7492 27.1758 L34.0683 26.7993 L31.7666 28.0717 Z" fill="#055860"/>
                </svg>
                <span>Skills Co-op</span>
            </div>

            <p class="evb-eyebrow">Free community event</p>
            <h2 class="evb-title">Discovery Session</h2>

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
        .evb-mark { width: 34px; height: auto; display: block; }
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
