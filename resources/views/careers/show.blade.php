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
        // True now: the vacancy page takes the application itself rather
        // than pointing at an inbox.
        'directApply' => true,
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

            @if ($role->isAcceptingApplications())
                <div class="cr-apply" id="apply">
                    <h2>Apply for this role</h2>

                    @if (session('success'))
                        <p class="cr-flash-ok" role="status">{{ session('success') }}</p>
                    @else
                        @if (session('error'))
                            <p class="cr-flash-err" role="alert">{{ session('error') }}</p>
                        @endif

                        @if ($errors->any())
                            <p class="cr-flash-err" role="alert">
                                Something needs a look before this can go in. The fields below say what.
                            </p>
                        @endif

                        @if ($role->apply_instructions)
                            <p>{{ $role->apply_instructions }}</p>
                        @endif

                        {{-- enctype, or the CV silently never arrives. --}}
                        <form method="POST" action="{{ route('careers.apply', $role) }}"
                              enctype="multipart/form-data" novalidate>
                            @csrf

                            {{-- Honeypot. Real users never see it; bots fill it. --}}
                            <div class="cr-ref" aria-hidden="true">
                                <label for="jb_reference">Reference</label>
                                <input id="jb_reference" name="jb_reference" type="text"
                                       tabindex="-1" autocomplete="off">
                            </div>

                            <div class="cr-grid">
                                <div class="cr-field">
                                    <label for="name">Full name</label>
                                    <input id="name" name="name" required maxlength="255"
                                           autocomplete="name" value="{{ old('name') }}">
                                    @error('name')<p class="cr-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="cr-field">
                                    <label for="email">Email</label>
                                    <input id="email" name="email" type="email" required maxlength="255"
                                           autocomplete="email" value="{{ old('email') }}">
                                    @error('email')<p class="cr-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="cr-field">
                                    <label for="phone">Phone <span class="cr-opt">(optional)</span></label>
                                    <input id="phone" name="phone" type="tel" maxlength="40"
                                           autocomplete="tel" value="{{ old('phone') }}">
                                    @error('phone')<p class="cr-error">{{ $message }}</p>@enderror
                                </div>
                                <div class="cr-field">
                                    <label for="portfolio_url">Portfolio or work you are proud of <span class="cr-opt">(optional)</span></label>
                                    <input id="portfolio_url" name="portfolio_url" type="url" maxlength="255"
                                           placeholder="https://" value="{{ old('portfolio_url') }}">
                                    @error('portfolio_url')<p class="cr-error">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="cr-field">
                                <label for="cover_note">Why does this role fit you?</label>
                                <textarea id="cover_note" name="cover_note" rows="5" required
                                          maxlength="4000">{{ old('cover_note') }}</textarea>
                                <p class="cr-hint">A few honest sentences beat a formal cover letter.</p>
                                @error('cover_note')<p class="cr-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="cr-field">
                                <label for="cv">CV</label>
                                <input id="cv" name="cv" type="file" required
                                       accept=".pdf,.doc,.docx,.odt,.rtf,application/pdf">
                                <p class="cr-hint">PDF or Word, up to 5MB.</p>
                                @error('cv')<p class="cr-error">{{ $message }}</p>@enderror
                            </div>

                            <label class="cr-consent">
                                <input type="checkbox" name="consent" value="1" required @checked(old('consent'))>
                                <span>I am happy for Skills Co-op to hold these details and my CV while my
                                    application is considered. See our <a href="{{ route('privacy') }}">privacy notice</a>.</span>
                            </label>
                            @error('consent')<p class="cr-error">{{ $message }}</p>@enderror

                            <button type="submit" class="ath-btn ath-btn-primary" style="margin-top:18px;">
                                Send my application
                            </button>

                            @if ($role->apply_email)
                                <p class="cr-hint" style="margin-top:14px;">
                                    Forms not your thing? Email
                                    <a href="mailto:{{ $role->apply_email }}?subject={{ rawurlencode('Application: '.$role->title) }}">{{ $role->apply_email }}</a>
                                    instead. Both routes land on the same desk.
                                </p>
                            @endif
                        </form>
                    @endif
                </div>
            @endif

            <a class="cr-back" href="{{ route('careers.index') }}">&larr; All open roles</a>

        </div>
    </section>

</div>

@endsection
