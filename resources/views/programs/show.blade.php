@extends('layouts.aethryna')

@section('title', $pathway->name . ' | Free ' . ucfirst($pathway->category) . ' Course | Skills Co-op')

{{-- Composed from the record rather than the raw description, which runs 60 to
     95 characters and leaves most of what a search result shows sitting empty.
     See Pathway::metaDescription(). --}}
@section('meta_description', $pathway->metaDescription())
@section('og_description', $pathway->metaDescription())

@push('structured-data')
@php
    /*
     * Course structured data. The site had none anywhere, and for a training
     * provider it is worth more than the meta description: Google renders
     * course results from it, and every field it wants was already on the
     * record.
     *
     * hasCourseInstance is only claimed for the four tracks a cohort actually
     * runs. Declaring a scheduled instance of a course nobody is teaching
     * would be a lie told to a search engine, which is still a lie.
     */
    $courseSchema = array_filter([
        '@context'    => 'https://schema.org',
        '@type'       => 'Course',
        'name'        => $pathway->name,
        'description' => $pathway->metaDescription(),
        'url'         => route('programs.show', $pathway),
        'provider'    => ['@id' => rtrim(url('/'), '/') . '/#organisation'],
        'inLanguage'  => 'en-GB',
        'isAccessibleForFree' => true,
        'educationalLevel'    => $pathway->difficulty_level,
        'teaches'     => $pathway->skills ?: null,
        'offers'      => [
            '@type'         => 'Offer',
            'price'         => '0',
            'priceCurrency' => 'GBP',
            'category'      => 'Free',
            'url'           => route('programs.show', $pathway),
        ],
        'hasCourseInstance' => $pathway->is_pilot ? [
            '@type'        => 'CourseInstance',
            'courseMode'   => 'Blended',
            'courseWorkload' => 'P' . ($pathway->duration_months ?? 6) . 'M',
            'location'     => ['@type' => 'VirtualLocation', 'url' => rtrim(url('/'), '/')],
        ] : null,
    ], fn ($v) => $v !== null && $v !== []);
@endphp
<script type="application/ld+json">
{!! json_encode($courseSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
<section class="cs-hero">
    <div class="ath-container">
        <nav class="cs-crumbs" aria-label="Breadcrumb">
            <a href="{{ route('programs') }}">Programmes</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">{{ $pathway->name }}</span>
        </nav>

        <span class="cs-eyebrow">{{ ucfirst($pathway->category) }} pathway</span>
        <h1>{{ $pathway->name }}</h1>
        <p class="cs-lede">{{ $pathway->description }}</p>

        <div class="cs-meta">
            <span><i class="fas fa-signal" aria-hidden="true"></i> {{ ucfirst($pathway->difficulty_level ?? 'Beginner') }}</span>
            @if ($pathway->duration_months)
                <span><i class="fas fa-clock" aria-hidden="true"></i> {{ $pathway->duration_months }} months</span>
            @endif
            <span><i class="fas fa-tag" aria-hidden="true"></i> Free</span>
        </div>

        {{--
            The honest difference between the two kinds of page.

            Four tracks run in Cohort 1. The other thirteen are real
            directions the assessment can point somebody in, and saying so
            plainly is better than either hiding them or implying we teach
            them. Somebody who reads "not one of the four running now" and
            applies anyway has decided with their eyes open.
        --}}
        @if ($pathway->is_pilot)
            <div class="cs-status cs-status-live">
                <i class="fas fa-circle-check" aria-hidden="true"></i>
                <div>
                    <strong>Running in {{ config('organisation.cohort.name') }}</strong>
                    <span>Starts {{ config('organisation.cohort.starts') }} &middot; {{ config('organisation.cohort.places') }} places across all four tracks</span>
                </div>
            </div>
            <div class="cs-actions">
                <a href="{{ route('register') }}" class="ath-btn ath-btn-primary">Apply for this track</a>
                <a href="{{ route('assessment.index') }}" class="ath-btn ath-btn-outline">Not sure? Find your track &middot; 2 min</a>
            </div>
        @else
            <div class="cs-status cs-status-soon">
                <i class="fas fa-compass" aria-hidden="true"></i>
                <div>
                    <strong>Not one of the four tracks running in {{ config('organisation.cohort.name') }}</strong>
                    <span>A direction we can point you in, and something we may run in a later cohort. The skills below still count.</span>
                </div>
            </div>
            <div class="cs-actions">
                <a href="{{ route('assessment.index') }}" class="ath-btn ath-btn-primary">Find the track that fits you</a>
                <a href="{{ route('programs') }}" class="ath-btn ath-btn-outline">See the four we run</a>
            </div>
        @endif
    </div>
</section>

<section class="cs-body">
    <div class="ath-container cs-grid">
        <div>
            @if ($pathway->recommended_for)
                <div class="cs-block">
                    <h2>Who this suits</h2>
                    <p>{{ $pathway->recommended_for }}</p>
                </div>
            @endif

            @if ($pathway->skills)
                <div class="cs-block">
                    <h2>What you would learn</h2>
                    <ul class="cs-skills">
                        @foreach ($pathway->skills as $skill)
                            <li>{{ $skill }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($pathway->career_paths)
                <div class="cs-block">
                    <h2>Where it leads</h2>
                    <ul class="cs-careers">
                        @foreach ($pathway->career_paths as $career)
                            <li>{{ $career }}</li>
                        @endforeach
                    </ul>
                    <p class="cs-note">
                        Job titles this track prepares you for. We do not promise a job, and anyone who does is
                        selling you something.
                    </p>
                </div>
            @endif
        </div>

        <aside class="cs-aside">
            @unless ($pathway->is_pilot)
                <div class="cs-card">
                    <h3>Running in {{ config('organisation.cohort.name') }}</h3>
                    <p class="cs-card-note">The four tracks you can start in {{ config('organisation.cohort.starts') }}.</p>
                    <ul class="cs-list">
                        @foreach ($pilotTracks as $track)
                            <li><a href="{{ route('programs.show', $track) }}">{{ $track->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endunless

            @if ($related->isNotEmpty())
                <div class="cs-card">
                    <h3>Related</h3>
                    <ul class="cs-list">
                        @foreach ($related as $other)
                            <li>
                                <a href="{{ route('programs.show', $other) }}">{{ $other->name }}</a>
                                @if ($other->is_pilot)<span class="cs-pill">Running</span>@endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="cs-card cs-card-quiet">
                <h3>Not sure this is you?</h3>
                <p class="cs-card-note">
                    The assessment takes about two minutes, needs no account, and matches you against every
                    track we have.
                </p>
                <a href="{{ route('assessment.index') }}" class="cs-card-link">Find your track &rarr;</a>
            </div>
        </aside>
    </div>
</section>

@push('styles')
<style>
    .cs-hero {
        background: linear-gradient(135deg, var(--ath-deep, #055860) 0%, var(--ath-teal, #038b89) 100%);
        color: #fff;
        padding: 64px 0 56px;
    }
    .cs-crumbs { display: flex; gap: 8px; font-size: 0.85rem; margin-bottom: 22px; color: rgba(255,255,255,0.7); flex-wrap: wrap; }
    .cs-crumbs a { color: rgba(255,255,255,0.85); text-decoration: none; }
    .cs-crumbs a:hover { color: var(--ath-gold, #ee9d1d); }
    .cs-eyebrow {
        font-family: var(--font-mono, ui-monospace, monospace);
        font-size: 0.72rem; letter-spacing: 2px; text-transform: uppercase;
        color: var(--ath-gold, #ee9d1d);
    }
    .cs-hero h1 { font-size: clamp(2rem, 5vw, 3rem); font-weight: 800; margin: 10px 0 16px; line-height: 1.1; }
    .cs-lede { font-size: 1.1rem; line-height: 1.65; max-width: 62ch; color: rgba(255,255,255,0.9); margin: 0 0 22px; }
    .cs-meta { display: flex; flex-wrap: wrap; gap: 20px; font-size: 0.9rem; color: rgba(255,255,255,0.85); margin-bottom: 26px; }
    .cs-meta i { color: var(--ath-gold, #ee9d1d); margin-right: 6px; }

    .cs-status {
        display: flex; gap: 14px; align-items: flex-start;
        padding: 16px 18px; border-radius: 14px; margin-bottom: 26px;
        max-width: 70ch; background: rgba(255,255,255,0.1);
    }
    .cs-status i { font-size: 1.1rem; margin-top: 2px; }
    .cs-status strong { display: block; margin-bottom: 3px; }
    .cs-status span { font-size: 0.92rem; color: rgba(255,255,255,0.85); line-height: 1.55; }
    .cs-status-live i { color: #7fe3c8; }
    .cs-status-soon i { color: var(--ath-gold, #ee9d1d); }

    .cs-actions { display: flex; flex-wrap: wrap; gap: 14px; }

    .cs-body { padding: 60px 0 80px; background: #f8fafb; }
    .cs-grid { display: grid; grid-template-columns: minmax(0, 1fr) 320px; gap: 48px; align-items: start; }

    .cs-block { margin-bottom: 40px; }
    .cs-block h2 { font-size: 1.35rem; font-weight: 800; color: var(--ath-deep, #055860); margin: 0 0 14px; }
    .cs-block p { line-height: 1.75; color: #404952; }

    .cs-skills, .cs-careers { list-style: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap; gap: 10px; }
    .cs-skills li, .cs-careers li {
        background: #fff; border: 1px solid rgba(3,139,137,0.18);
        border-radius: 100px; padding: 8px 16px; font-size: 0.92rem; color: var(--ath-deep, #055860);
    }
    .cs-careers li { background: rgba(3,139,137,0.07); }
    .cs-note { font-size: 0.88rem; color: #6b7480; margin-top: 14px; line-height: 1.6; }

    .cs-aside { display: grid; gap: 18px; position: sticky; top: 96px; }
    .cs-card { background: #fff; border: 1px solid rgba(3,139,137,0.15); border-radius: 18px; padding: 22px; }
    .cs-card-quiet { background: rgba(3,139,137,0.05); }
    .cs-card h3 { font-size: 1rem; font-weight: 800; color: var(--ath-deep, #055860); margin: 0 0 8px; }
    .cs-card-note { font-size: 0.9rem; color: #6b7480; line-height: 1.6; margin: 0 0 14px; }
    .cs-list { list-style: none; padding: 0; margin: 0; display: grid; gap: 10px; }
    .cs-list a { color: var(--ath-teal, #038b89); text-decoration: none; font-weight: 600; }
    .cs-list a:hover { text-decoration: underline; }
    .cs-pill {
        font-size: 0.66rem; letter-spacing: 1px; text-transform: uppercase;
        background: rgba(3,139,137,0.12); color: var(--ath-deep, #055860);
        padding: 2px 8px; border-radius: 100px; margin-left: 6px;
    }
    .cs-card-link { color: var(--ath-teal, #038b89); font-weight: 700; text-decoration: none; }
    .cs-card-link:hover { text-decoration: underline; }

    @media (max-width: 900px) {
        .cs-grid { grid-template-columns: 1fr; gap: 32px; }
        .cs-aside { position: static; }
    }
</style>
@endpush
@endsection
