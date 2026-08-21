@extends('layouts.aethryna')

@section('title', $role->title . ' | Careers | Skills Co-op')
@section('meta_description', \Illuminate\Support\Str::limit($role->summary, 155))
@section('og_title', $role->title . ' at Skills Co-op')
@section('og_description', \Illuminate\Support\Str::limit($role->summary, 200))

{{-- A closed vacancy still resolves, because the link is in inboxes and on job
     boards by then. It should not still be inviting search traffic though. --}}
@unless ($role->isAcceptingApplications())
    @section('meta_robots', 'noindex, follow')
@endunless

@push('structured-data')
@php
    /*
     * JobPosting structured data.
     *
     * Worth more here than on any other page on the site. Google Jobs, LinkedIn
     * and most aggregators build their listings from this markup, and an
     * organisation with no recruitment budget reaching jobseekers for free is
     * the difference between an applicant pool and an empty inbox.
     *
     * Everything is conditional: an incomplete JobPosting is dropped by Google
     * anyway, and asserting a salary or a deadline we do not have would be
     * worse than omitting the field.
     */
    $remote = str_contains(strtolower((string) $role->location), 'remote');

    $jobSchema = array_filter([
        '@context'    => 'https://schema.org',
        '@type'       => 'JobPosting',
        'title'       => $role->title,
        // Google wants the full description here, not the one-line summary.
        'description' => $role->description,
        'datePosted'  => $role->created_at?->toDateString(),
        'validThrough' => $role->closes_at?->endOfDay()->toIso8601String(),
        'employmentType' => match (strtolower((string) $role->employment_basis)) {
            'full-time' => 'FULL_TIME',
            'part-time' => 'PART_TIME',
            'contract', 'contractor' => 'CONTRACTOR',
            default     => null,
        },
        'hiringOrganization' => array_filter([
            '@type'  => 'Organization',
            'name'   => config('organisation.name'),
            'legalName' => config('organisation.legal_name'),
            'sameAs' => config('organisation.url'),
            'logo'   => config('organisation.logo'),
        ]),
        // Remote posts declare it properly rather than inventing an office
        // address nobody works at.
        'jobLocationType' => $remote ? 'TELECOMMUTE' : null,
        'applicantLocationRequirements' => $remote ? [
            '@type' => 'Country',
            'name'  => 'United Kingdom',
        ] : null,
        'jobLocation' => $remote ? null : array_filter([
            '@type'   => 'Place',
            'address' => array_filter([
                '@type'           => 'PostalAddress',
                'addressLocality' => config('organisation.locality'),
                'addressCountry'  => config('organisation.country'),
            ]),
        ]),
        'directApply' => false,
    ], fn ($v) => ! is_null($v) && $v !== [] && $v !== '');
@endphp
<script type="application/ld+json">{!! json_encode($jobSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@push('styles')
    @include('careers._styles')
@endpush

@section('content')

<div class="cr">

    <section class="cr-hero">
        <div class="cr-wrap">
            <p class="cr-eyebrow">{{ $role->engagement_type === 'contractor' ? 'Contract role' : 'Join the team' }}</p>
            <h1>{{ $role->title }}</h1>
            <p class="cr-lede">{{ $role->summary }}</p>

            <ul class="cr-facts">
                @if ($role->employment_basis)
                    <li class="cr-fact">{{ $role->employment_basis }}</li>
                @endif
                @if ($role->location)
                    <li class="cr-fact">{{ $role->location }}</li>
                @endif
                @if ($role->reports_to)
                    <li class="cr-fact">Reports to {{ $role->reports_to }}</li>
                @endif
                @if ($role->compensation)
                    <li class="cr-fact">{{ $role->compensation }}</li>
                @endif
                @if ($role->closes_at)
                    <li class="cr-fact">Closes {{ $role->closes_at->format('j F Y') }}</li>
                @endif
            </ul>
        </div>
    </section>

    <section class="cr-body">
        <div class="cr-wrap">

            @unless ($role->isAcceptingApplications())
                <p class="cr-closed">
                    <strong>This role is closed.</strong>
                    We are no longer accepting applications for it. Our
                    <a href="{{ route('careers.index') }}">current openings are here</a>.
                </p>
            @endunless

            @if ($role->description)
                <div class="cr-section">
                    <h2>About the role</h2>
                    <p class="cr-prose">{{ $role->description }}</p>
                </div>
            @endif

            @foreach ($role->sections ?? [] as $section)
                <div class="cr-section">
                    <h2>{{ $section['heading'] }}</h2>
                    <ul>
                        @foreach ($section['items'] ?? [] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            @if ($role->isAcceptingApplications() && $role->apply_email)
                <div class="cr-apply">
                    <h2>How to apply</h2>
                    @if ($role->apply_instructions)
                        <p>{{ $role->apply_instructions }}</p>
                    @endif
                    {{-- A mailto with the subject prefilled, because the
                         instructions ask for a specific subject line and
                         nobody reliably reads that far before clicking. --}}
                    <a class="ath-btn ath-btn-primary"
                       href="mailto:{{ $role->apply_email }}?subject={{ rawurlencode('Application: '.$role->title) }}">
                        Email your application
                    </a>
                    <p style="margin:14px 0 0;font-size:.92rem;color:#59626A;">
                        Or write to <a href="mailto:{{ $role->apply_email }}">{{ $role->apply_email }}</a> directly.
                    </p>
                </div>
            @endif

            <a class="cr-back" href="{{ route('careers.index') }}">&larr; All open roles</a>

        </div>
    </section>

</div>

@endsection
