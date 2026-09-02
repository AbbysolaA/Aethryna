{{--
    Admin navigation.

    Admin screens extend the public marketing layout, so the header they get is
    About / Pathway / Programs / Impact / Stories / Get involved. There was no
    way to move between admin sections without going home first, and no
    indication of where you were.

    The first version of this printed all fourteen destinations as chips on
    every screen, dashboard included. That was wrong twice over: the dashboard
    is already the index of those fourteen things, so listing them above it said
    everything twice; and a wall of chips is a poor way to show one current
    location, because nothing in it is emphasised except by colour.

    So: nothing at all on the dashboard beyond a link out to the site, and on
    every other screen a single quiet line — where you are, the way back, and a
    menu that opens when you want to go somewhere else.

    Sections are listed once here, so a new one appears everywhere by adding a
    row rather than by remembering to link it from each page.
--}}
@php
    // No "Overview / Dashboard" entry: the back link at the left of the bar is
    // already the way to the dashboard, and listing it again made a one-item
    // group that left a hole in the menu next to the longer ones.
    $adminSections = [
        'Assessment' => [
            ['route' => 'admin.assessments.index', 'label' => 'Assessments', 'match' => 'admin.assessments.*'],
            ['route' => 'admin.content',           'label' => 'Questions & pathways', 'match' => 'admin.content*'],
            ['route' => 'admin.reports',           'label' => 'Reports', 'match' => 'admin.reports'],
        ],
        'Sessions' => [
            ['route' => 'admin.panels.index',        'label' => 'Panels', 'match' => 'admin.panels.*'],
            ['route' => 'admin.speakers.index',      'label' => 'Speakers', 'match' => 'admin.speakers.*'],
            ['route' => 'admin.speaker-applications.index', 'label' => 'Speaker pitches', 'match' => 'admin.speaker-applications.*'],
            ['route' => 'admin.registrations.index', 'label' => 'Registrations', 'match' => 'admin.registrations.*'],
        ],
        'People' => [
            ['route' => 'admin.users',                       'label' => 'Users', 'match' => 'admin.users*'],
            ['route' => 'admin.staff.index',                 'label' => 'Staff', 'match' => 'admin.staff.*'],
            ['route' => 'admin.volunteers.index',            'label' => 'Volunteers', 'match' => 'admin.volunteers.*'],
            ['route' => 'admin.volunteer-roles.index',       'label' => 'Positions', 'match' => 'admin.volunteer-roles.*'],
            ['route' => 'admin.job-applications.index',      'label' => 'Job applications', 'match' => 'admin.job-applications.*'],
            ['route' => 'admin.volunteer-documents.index',   'label' => 'Onboarding pack', 'match' => 'admin.volunteer-documents.*'],
        ],
        'Site' => [
            ['route' => 'admin.posts.index', 'label' => 'Blog posts', 'match' => 'admin.posts.*'],
        ],
        'Governance' => [
            ['route' => 'admin.risks.index',        'label' => 'Risks', 'match' => 'admin.risks.*'],
            ['route' => 'admin.safeguarding.index', 'label' => 'Safeguarding', 'match' => 'admin.safeguarding.*'],
        ],
    ];

    // Where we are. Falls back to nothing rather than guessing, so a screen not
    // listed above shows a plain way back instead of claiming a wrong location.
    $currentLabel = null;
    $currentGroup = null;
    foreach ($adminSections as $group => $items) {
        foreach ($items as $item) {
            if (request()->routeIs($item['match'])) {
                $currentLabel = $item['label'];
                $currentGroup = $group;
                break 2;
            }
        }
    }

    $onDashboard = request()->routeIs('admin.dashboard');
@endphp

<nav class="ad-nav" aria-label="Admin">
    <div class="ath-container ad-nav-inner">

        @unless ($onDashboard)
            {{-- The way back, always in the same place. --}}
            <a href="{{ route('admin.dashboard') }}" class="ad-back">
                <span class="ad-back-arrow" aria-hidden="true">&larr;</span> Dashboard
            </a>

            @if ($currentLabel)
                <span class="ad-where">
                    @if ($currentGroup)
                        <span class="ad-where-group">{{ $currentGroup }}</span>
                    @endif
                    <span class="ad-where-page" aria-current="page">{{ $currentLabel }}</span>
                </span>
            @endif

            {{--
                Everywhere else, behind one click.

                A <details> rather than a scripted menu: it opens without
                JavaScript, closes on a second click, and is reachable by
                keyboard for free.
            --}}
            <details class="ad-jump">
                <summary aria-label="Go to another admin section">
                    Go to<span class="ad-jump-caret" aria-hidden="true">&#9662;</span>
                </summary>
                <div class="ad-jump-panel">
                    @foreach ($adminSections as $group => $items)
                        <div class="ad-jump-group">
                            <span class="ad-jump-label">{{ $group }}</span>
                            @foreach ($items as $item)
                                <a href="{{ route($item['route']) }}"
                                   @class(['ad-jump-link', 'is-current' => request()->routeIs($item['match'])])>
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </details>
        @endunless

        <a href="{{ route('home') }}" class="ad-nav-site">View the site &rarr;</a>
    </div>
</nav>

@push('styles')
<style>
    .ad-nav {
        background: #fff;
        border-bottom: 1px solid rgba(3, 139, 137, 0.12);
        padding: 12px 0;
    }
    .ad-nav-inner {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 0.85rem;
    }

    .ad-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-weight: 700;
        color: var(--ath-teal, #038b89);
        text-decoration: none;
        white-space: nowrap;
    }
    .ad-back:hover { color: var(--ath-deep, #055860); }
    .ad-back-arrow { font-size: 1.05rem; line-height: 1; }

    /* Where you are. Stated once, quietly, rather than by colouring one of
       fourteen chips and hoping it is noticed. */
    .ad-where {
        display: inline-flex;
        align-items: baseline;
        gap: 8px;
        min-width: 0;
        padding-left: 14px;
        border-left: 1px solid rgba(3, 139, 137, 0.18);
    }
    .ad-where-group {
        font-family: var(--font-mono, ui-monospace, monospace);
        font-size: 0.62rem;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        color: var(--ath-muted, #8a939c);
    }
    .ad-where-page {
        font-weight: 700;
        color: var(--ath-deep, #055860);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ad-jump { position: relative; margin-left: auto; }
    .ad-jump > summary {
        list-style: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border: 1px solid rgba(3, 139, 137, 0.22);
        border-radius: 100px;
        font-weight: 600;
        color: var(--ath-deep, #055860);
        white-space: nowrap;
    }
    .ad-jump > summary::-webkit-details-marker { display: none; }
    .ad-jump > summary:hover { background: rgba(3, 139, 137, 0.07); }
    .ad-jump[open] > summary {
        background: var(--ath-teal, #038b89);
        border-color: var(--ath-teal, #038b89);
        color: #fff;
    }
    .ad-jump-caret { font-size: 0.7rem; }

    .ad-jump-panel {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        z-index: 60;
        background: #fff;
        border: 1px solid rgba(3, 139, 137, 0.15);
        border-radius: 14px;
        box-shadow: 0 18px 40px rgba(5, 88, 96, 0.14);
        padding: 20px 24px;
        /* Multi-column rather than a grid: groups are different lengths, and a
           grid aligns them into rows, so a short group leaves a hole beside a
           long one. Columns let the content flow and balance itself. */
        columns: 2;
        column-gap: 36px;
        width: max-content;
        max-width: min(560px, calc(100vw - 40px));
    }
    .ad-jump-group {
        display: flex;
        flex-direction: column;
        gap: 2px;
        break-inside: avoid;
        margin-bottom: 18px;
    }
    .ad-jump-group:last-child { margin-bottom: 0; }
    .ad-jump-label {
        font-family: var(--font-mono, ui-monospace, monospace);
        font-size: 0.62rem;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        color: var(--ath-muted, #8a939c);
        margin-bottom: 4px;
    }
    .ad-jump-link {
        color: var(--ath-deep, #055860);
        text-decoration: none;
        padding: 5px 8px;
        margin-left: -8px;
        border-radius: 7px;
        white-space: nowrap;
    }
    .ad-jump-link:hover { background: rgba(3, 139, 137, 0.09); }
    .ad-jump-link.is-current { font-weight: 700; color: var(--ath-teal, #038b89); }

    .ad-nav-site {
        margin-left: auto;
        color: var(--ath-muted, #667);
        text-decoration: none;
        white-space: nowrap;
    }
    /* Once the jump menu is present it takes the auto margin, so the site link
       only needs its own gap. */
    .ad-jump ~ .ad-nav-site { margin-left: 18px; }
    .ad-nav-site:hover { color: var(--ath-teal, #038b89); }

    @media (max-width: 720px) {
        .ad-nav-inner { flex-wrap: wrap; gap: 10px 12px; }
        .ad-where { flex-basis: 100%; padding-left: 0; border-left: none; order: 3; }
        .ad-jump-panel {
            columns: 1;
            right: 0;
            left: auto;
            max-height: 70vh;
            overflow-y: auto;
        }
    }
</style>
@endpush
