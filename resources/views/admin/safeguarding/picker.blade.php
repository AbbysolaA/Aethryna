@extends('layouts.aethryna')

@section('title', 'Record a concern | Skills Co-op')

@section('content')
<section class="sgr-wrap">
    <div class="sgr-container">

        <div class="sgr-head">
            <div>
                <span class="sgr-eyebrow">Safeguarding</span>
                <h1>Who is the concern about?</h1>
                <p>Choose the learner, then describe what you are worried about on the next screen.</p>
            </div>
            <a href="{{ route('admin.safeguarding.index') }}" class="sgr-back">&larr; Concerns register</a>
        </div>

        {{-- Search. Useful from the first cohort onwards; a picker that only
             paginates stops being usable at thirty names. --}}
        <form method="GET" action="{{ route('admin.safeguarding.picker') }}" class="sgp-search">
            <label for="q" class="sgp-search-label">Search by name or email</label>
            <div class="sgp-search-row">
                <input id="q" name="q" value="{{ $search }}" placeholder="Start typing a name" autofocus>
                <button type="submit" class="sgp-btn">Search</button>
                @if ($search !== '')
                    <a href="{{ route('admin.safeguarding.picker') }}" class="sgp-clear">Clear</a>
                @endif
            </div>
        </form>

        @if ($learners->isEmpty())
            <div class="sgp-empty">
                <p class="sgp-empty-title">No learners found</p>
                <p>
                    @if ($search !== '')
                        Nothing matches "{{ $search }}". Try part of a name or an email address.
                    @else
                        There are no learner accounts yet, so there is nobody to record a concern about.
                    @endif
                </p>
            </div>
        @else
            <ul class="sgp-list">
                @foreach ($learners as $learner)
                    <li class="sgp-item">
                        <div class="sgp-person">
                            <p class="sgp-name">{{ $learner->name }}</p>
                            <p class="sgp-email">{{ $learner->email }}</p>
                        </div>
                        <div class="sgp-meta">
                            {{-- Existing history matters before you write a new one:
                                 a second concern about the same learner reads very
                                 differently from a first. --}}
                            @if ($learner->safeguarding_concerns_count > 0)
                                <span class="sgp-history">
                                    {{ $learner->safeguarding_concerns_count }}
                                    {{ Str::plural('concern', $learner->safeguarding_concerns_count) }} on file
                                </span>
                            @endif
                            <a href="{{ route('safeguarding.create', $learner->id) }}" class="sgp-btn sgp-btn-primary">
                                Record a concern
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="sgp-pagination">{{ $learners->links() }}</div>
        @endif

    </div>
</section>

@push('styles')
<link href="https://fonts.bunny.net/css?family=ibm-plex-mono:500,600&display=swap" rel="stylesheet">
<style>
:root {
    --ath-teal: #038b89;
    --ath-gold: #ee9d1d;
    --ath-deep: #055860;
    --ath-light: #F8FBFB;
    --ath-text: #404952;
    --ath-muted: #57616a;
    --font-mono: 'IBM Plex Mono', 'Courier New', monospace;
}
.sgr-wrap { padding: 150px 0 90px; background: var(--ath-light); min-height: 80vh; }
.sgr-container { max-width: 980px; margin: 0 auto; padding: 0 5%; }
.sgr-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; margin-bottom: 26px; flex-wrap: wrap; }
.sgr-eyebrow { display: inline-block; font-family: var(--font-mono); font-size: 0.78rem; font-weight: 600; letter-spacing: 3px; text-transform: uppercase; color: var(--ath-gold); margin-bottom: 10px; padding-left: 12px; border-left: 3px solid var(--ath-gold); }
.sgr-head h1 { font-family: 'Outfit', sans-serif; font-size: clamp(1.7rem,4vw,2.3rem); font-weight: 800; color: var(--ath-deep); margin: 0 0 8px; }
.sgr-head p { color: var(--ath-muted); margin: 0; font-size: 0.99rem; }
.sgr-back { font-family: var(--font-mono); font-size: 0.82rem; color: var(--ath-muted); text-decoration: none; white-space: nowrap; }
.sgr-back:hover { color: var(--ath-teal); }

.sgp-search { background: #fff; border: 1px solid rgba(3,139,137,0.12); border-radius: 16px; padding: 22px 24px; margin-bottom: 22px; }
.sgp-search-label { display: block; font-weight: 700; color: var(--ath-deep); font-size: 0.92rem; margin-bottom: 10px; }
.sgp-search-row { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
.sgp-search-row input { flex: 1 1 260px; padding: 11px 15px; border: 1.5px solid rgba(0,0,0,0.1); border-radius: 10px; font-family: inherit; font-size: 0.96rem; color: var(--ath-text); outline: none; transition: border-color .2s, box-shadow .2s; }
.sgp-search-row input:focus { border-color: var(--ath-teal); box-shadow: 0 0 0 4px rgba(3,139,137,0.1); }
.sgp-clear { font-size: 0.88rem; color: var(--ath-muted); text-decoration: underline; }
.sgp-clear:hover { color: var(--ath-teal); }

.sgp-btn { display: inline-flex; align-items: center; justify-content: center; padding: 11px 22px; border: 1.5px solid var(--ath-teal); background: transparent; color: var(--ath-teal); border-radius: 100px; font-family: inherit; font-weight: 700; font-size: 0.9rem; cursor: pointer; text-decoration: none; transition: background .2s, color .2s; }
.sgp-btn:hover { background: var(--ath-teal); color: #fff; }
.sgp-btn-primary { background: var(--ath-deep); border-color: var(--ath-deep); color: #fff; }
.sgp-btn-primary:hover { background: var(--ath-gold); border-color: var(--ath-gold); color: #fff; }

.sgp-list { list-style: none; margin: 0; padding: 0; display: grid; gap: 12px; }
.sgp-item { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; background: #fff; border: 1px solid rgba(3,139,137,0.12); border-radius: 14px; padding: 18px 22px; }
.sgp-name { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.02rem; color: var(--ath-deep); margin: 0 0 3px; }
.sgp-email { font-size: 0.88rem; color: var(--ath-muted); margin: 0; }
.sgp-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 14px; }
.sgp-history { font-size: 0.8rem; font-weight: 700; letter-spacing: 0.3px; text-transform: uppercase; color: #8a5a06; background: rgba(238,157,29,0.16); padding: 5px 12px; border-radius: 100px; }

.sgp-empty { background: #fff; border: 1px solid rgba(3,139,137,0.12); border-radius: 16px; padding: 40px 24px; text-align: center; color: var(--ath-muted); }
.sgp-empty-title { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.1rem; color: var(--ath-deep); margin: 0 0 6px; }
.sgp-empty p { margin: 0; }
.sgp-pagination { margin-top: 24px; }

@media (max-width: 640px) {
    .sgr-wrap { padding: 120px 0 70px; }
    .sgp-item { flex-direction: column; align-items: flex-start; }
    .sgp-meta { width: 100%; justify-content: space-between; }
}
</style>
@endpush

@endsection
