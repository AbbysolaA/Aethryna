{{--
    Admin navigation.

    Admin screens extend the public marketing layout, so the header they get
    is About / Pathway / Programs / Impact / Stories / Get involved, with one
    "Admin Dashboard" button among them. There was no way to move between
    admin sections without going home first, and no indication of where you
    were. Most screens had no route back to the dashboard at all.

    This bar is included at the top of every admin screen. Sections are listed
    once here, so a new one appears everywhere by adding a row rather than by
    remembering to link it from each page.
--}}
@php
    $adminSections = [
        'Overview' => [
            ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'match' => 'admin.dashboard'],
        ],
        'Assessment' => [
            ['route' => 'admin.assessments.index', 'label' => 'Assessments', 'match' => 'admin.assessments.*'],
            ['route' => 'admin.content',           'label' => 'Questions & pathways', 'match' => 'admin.content*'],
            ['route' => 'admin.reports',           'label' => 'Reports', 'match' => 'admin.reports'],
        ],
        'Sessions' => [
            ['route' => 'admin.panels.index',        'label' => 'Panels', 'match' => 'admin.panels.*'],
            ['route' => 'admin.speakers.index',      'label' => 'Speakers', 'match' => 'admin.speakers.*'],
            ['route' => 'admin.registrations.index', 'label' => 'Registrations', 'match' => 'admin.registrations.*'],
        ],
        'People' => [
            ['route' => 'admin.users',                       'label' => 'Users', 'match' => 'admin.users*'],
            ['route' => 'admin.staff.index',                 'label' => 'Staff', 'match' => 'admin.staff.*'],
            ['route' => 'admin.volunteers.index',            'label' => 'Volunteers', 'match' => 'admin.volunteers.*'],
            ['route' => 'admin.volunteer-roles.index',       'label' => 'Positions', 'match' => 'admin.volunteer-roles.*'],
            ['route' => 'admin.volunteer-documents.index',   'label' => 'Onboarding pack', 'match' => 'admin.volunteer-documents.*'],
        ],
        'Governance' => [
            ['route' => 'admin.risks.index',        'label' => 'Risks', 'match' => 'admin.risks.*'],
            ['route' => 'admin.safeguarding.index', 'label' => 'Safeguarding', 'match' => 'admin.safeguarding.*'],
        ],
    ];

    // Where we are, for the breadcrumb. Falls back to the group name so a
    // screen that is not itself a section entry still says something useful.
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
@endphp

<nav class="ad-nav" aria-label="Admin sections">
    <div class="ath-container">
        <div class="ad-nav-crumbs">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            @if ($currentGroup && $currentGroup !== 'Overview')
                <span aria-hidden="true">/</span><span class="ad-crumb-group">{{ $currentGroup }}</span>
            @endif
            @if ($currentLabel && $currentLabel !== 'Dashboard')
                <span aria-hidden="true">/</span><span class="ad-crumb-current" aria-current="page">{{ $currentLabel }}</span>
            @endif
            <a href="{{ route('home') }}" class="ad-nav-site">View the site &rarr;</a>
        </div>

        <div class="ad-nav-links">
            @foreach ($adminSections as $group => $items)
                <div class="ad-nav-group">
                    <span class="ad-nav-group-label">{{ $group }}</span>
                    @foreach ($items as $item)
                        <a href="{{ route($item['route']) }}"
                           @class(['ad-nav-link', 'is-current' => request()->routeIs($item['match'])])
                           @if(request()->routeIs($item['match'])) aria-current="page" @endif>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</nav>

@push('styles')
<style>
    .ad-nav {
        background: #fff;
        border-bottom: 1px solid rgba(3, 139, 137, 0.12);
        padding: 18px 0 16px;
        margin-bottom: 8px;
    }
    .ad-nav-crumbs {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        font-size: 0.85rem;
        color: var(--ath-muted, #667);
        margin-bottom: 14px;
    }
    .ad-nav-crumbs a { color: var(--ath-teal, #038b89); font-weight: 700; text-decoration: none; }
    .ad-nav-crumbs a:hover { color: var(--ath-gold, #ee9d1d); }
    .ad-crumb-current { color: var(--ath-deep, #055860); font-weight: 700; }
    .ad-nav-site { margin-left: auto; }

    .ad-nav-links { display: flex; flex-wrap: wrap; gap: 10px 22px; }
    .ad-nav-group { display: flex; align-items: center; flex-wrap: wrap; gap: 6px; }
    .ad-nav-group-label {
        font-family: var(--font-mono, ui-monospace, monospace);
        font-size: 0.62rem;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        color: var(--ath-muted, #8a939c);
        margin-right: 2px;
    }
    .ad-nav-link {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ath-deep, #055860);
        text-decoration: none;
        padding: 6px 12px;
        border-radius: 100px;
        border: 1px solid rgba(3, 139, 137, 0.15);
        background: rgba(3, 139, 137, 0.04);
        white-space: nowrap;
    }
    .ad-nav-link:hover { background: rgba(3, 139, 137, 0.12); color: var(--ath-deep, #055860); }
    .ad-nav-link.is-current {
        background: var(--ath-teal, #038b89);
        border-color: var(--ath-teal, #038b89);
        color: #fff;
    }

    @media (max-width: 768px) {
        .ad-nav-site { margin-left: 0; width: 100%; }
        .ad-nav-group-label { width: 100%; margin-bottom: 2px; }
    }
</style>
@endpush
