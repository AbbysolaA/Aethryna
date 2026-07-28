@extends('layouts.aethryna')

@section('title', 'Safeguarding concerns | Skills Co-op')

@section('content')
<section class="sgr-wrap">
    <div class="sgr-container">

        <div class="sgr-head">
            <div>
                <span class="sgr-eyebrow">Safeguarding</span>
                <h1>Concerns register</h1>
                <p>Urgent concerns appear first, then oldest first, so nothing ages out of sight.</p>
            </div>
            <div class="sgr-head-actions">
                {{-- Admins and the safeguarding lead have no learners list or
                     cohort screen to start from, so the register is where they
                     begin. Matters most for the lead, who takes concerns by
                     phone and has to put them on here themselves. --}}
                <a href="{{ route('admin.safeguarding.picker') }}" class="sgr-record">Record a concern</a>
                <a href="{{ route('admin.dashboard') }}" class="sgr-back">&larr; Admin dashboard</a>
            </div>
        </div>

        @if (session('success'))
            <div class="sgr-alert"><i class="fas fa-check-circle"></i><div>{{ session('success') }}</div></div>
        @endif

        {{-- Filters --}}
        <div class="sgr-tabs">
            @php
                $tabs = [
                    'open'         => 'Open',
                    'new'          => 'New',
                    'acknowledged' => 'Acknowledged',
                    'actioned'     => 'Actioned',
                    'closed'       => 'Closed',
                    'all'          => 'All',
                ];
            @endphp
            @foreach ($tabs as $key => $label)
                <a href="{{ route('admin.safeguarding.index', ['status' => $key]) }}"
                   class="sgr-tab {{ $status === $key ? 'is-active' : '' }}">
                    {{ $label }}
                    <span class="sgr-tab-count">{{ $counts[$key] ?? 0 }}</span>
                </a>
            @endforeach
        </div>

        @forelse ($concerns as $concern)
            <a href="{{ route('admin.safeguarding.show', $concern) }}" class="sgr-row {{ $concern->urgency === 'urgent' ? 'is-urgent' : '' }}">
                <div class="sgr-row-main">
                    <div class="sgr-row-top">
                        <span class="sgr-ref">SC-{{ $concern->id }}</span>
                        @if ($concern->urgency === 'urgent')
                            <span class="sgr-pill sgr-pill-urgent">Urgent</span>
                        @endif
                        <span class="sgr-pill sgr-status-{{ $concern->status }}">{{ ucfirst($concern->status) }}</span>
                    </div>
                    <h3>{{ $concern->learner?->name ?? 'Learner removed' }}</h3>
                    <p class="sgr-excerpt">{{ Str::limit($concern->concern, 150) }}</p>
                    <p class="sgr-meta">
                        Raised by {{ $concern->raisedBy?->name ?? 'Unknown' }}
                        @if ($concern->raised_by_role) ({{ ucfirst($concern->raised_by_role) }}) @endif
                        &middot; {{ $concern->created_at->timezone('Europe/London')->format('j M Y, H:i') }}
                        @if ($concern->reviewed_at)
                            &middot; last reviewed {{ $concern->reviewed_at->timezone('Europe/London')->format('j M Y') }}
                        @endif
                    </p>
                </div>
                <div class="sgr-row-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>
        @empty
            <div class="sgr-empty">
                <div class="sgr-empty-icon"><i class="fas fa-shield-halved"></i></div>
                <h3>Nothing here</h3>
                <p>No concerns match this filter.</p>
            </div>
        @endforelse

        <div class="sgr-pagination">
            {{ $concerns->links() }}
        </div>
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
.sgr-eyebrow {
    display: inline-block;
    font-family: var(--font-mono);
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--ath-gold);
    margin-bottom: 10px;
    padding-left: 12px;
    border-left: 3px solid var(--ath-gold);
}
.sgr-head h1 { font-family: 'Outfit', sans-serif; font-size: clamp(1.7rem,4vw,2.3rem); font-weight: 800; color: var(--ath-deep); margin: 0 0 8px; }
.sgr-head p { color: var(--ath-muted); margin: 0; font-size: 0.99rem; }
.sgr-back { font-family: var(--font-mono); font-size: 0.82rem; color: var(--ath-muted); text-decoration: none; white-space: nowrap; }
.sgr-back:hover { color: var(--ath-teal); }
.sgr-head-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 10px; }
.sgr-record { display: inline-flex; align-items: center; padding: 11px 22px; background: var(--ath-deep); color: #fff; border-radius: 100px; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.9rem; text-decoration: none; white-space: nowrap; transition: background .2s; }
.sgr-record:hover { background: var(--ath-gold); color: #fff; }

.sgr-alert {
    display: flex; gap: 12px; align-items: flex-start;
    background: rgba(3,139,137,0.08); border: 1px solid rgba(3,139,137,0.3);
    color: #0a5f5d; padding: 15px 19px; border-radius: 12px; margin-bottom: 22px;
    font-size: 0.96rem; line-height: 1.6;
}
.sgr-alert i { color: var(--ath-teal); margin-top: 2px; }

/* Tabs */
.sgr-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 22px; }
.sgr-tab {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 9px 16px; border-radius: 100px;
    background: #fff; border: 1px solid rgba(0,0,0,0.09);
    font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.88rem;
    color: var(--ath-muted); text-decoration: none; transition: all 0.2s;
}
.sgr-tab:hover { border-color: var(--ath-teal); color: var(--ath-deep); }
.sgr-tab.is-active { background: var(--ath-deep); border-color: var(--ath-deep); color: #fff; }
.sgr-tab-count {
    font-family: var(--font-mono); font-size: 0.74rem;
    background: rgba(0,0,0,0.07); padding: 1px 7px; border-radius: 100px;
}
.sgr-tab.is-active .sgr-tab-count { background: rgba(255,255,255,0.22); }

/* Rows */
.sgr-row {
    display: flex; align-items: center; gap: 16px;
    background: #fff; border: 1px solid rgba(0,0,0,0.07);
    border-left: 4px solid rgba(3,139,137,0.35);
    border-radius: 14px; padding: 20px 22px; margin-bottom: 12px;
    text-decoration: none; transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
}
.sgr-row:hover { transform: translateX(3px); box-shadow: 0 10px 30px rgba(0,0,0,0.06); border-left-color: var(--ath-teal); }
.sgr-row.is-urgent { border-left-color: #b3261e; }
.sgr-row-main { flex: 1; min-width: 0; }
.sgr-row-top { display: flex; align-items: center; gap: 8px; margin-bottom: 7px; flex-wrap: wrap; }
.sgr-ref { font-family: var(--font-mono); font-size: 0.78rem; font-weight: 600; color: var(--ath-muted); }
.sgr-pill {
    font-family: var(--font-mono); font-size: 0.66rem; font-weight: 600;
    letter-spacing: 1px; text-transform: uppercase;
    padding: 3px 9px; border-radius: 100px;
}
.sgr-pill-urgent { background: #fdecea; color: #b3261e; }
.sgr-status-new { background: rgba(238,157,29,0.16); color: #9a6510; }
.sgr-status-acknowledged { background: rgba(3,139,137,0.13); color: var(--ath-teal); }
.sgr-status-actioned { background: rgba(5,88,96,0.13); color: var(--ath-deep); }
.sgr-status-closed { background: rgba(0,0,0,0.07); color: var(--ath-muted); }
.sgr-row h3 { font-family: 'Outfit', sans-serif; font-size: 1.08rem; font-weight: 800; color: var(--ath-deep); margin: 0 0 5px; }
.sgr-excerpt { color: var(--ath-text); font-size: 0.92rem; line-height: 1.6; margin: 0 0 7px; }
.sgr-meta { font-size: 0.86rem; color: var(--ath-muted); margin: 0; line-height: 1.5; }
.sgr-row-arrow { color: rgba(0,0,0,0.2); flex-shrink: 0; }

.sgr-empty { background: #fff; border-radius: 18px; padding: 60px 30px; text-align: center; border: 1px solid rgba(0,0,0,0.06); }
.sgr-empty-icon {
    width: 60px; height: 60px; margin: 0 auto 16px; border-radius: 50%;
    background: rgba(3,139,137,0.08); color: var(--ath-teal);
    display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
}
.sgr-empty h3 { font-family: 'Outfit', sans-serif; color: var(--ath-deep); font-weight: 800; margin: 0 0 6px; }
.sgr-empty p { color: var(--ath-muted); margin: 0; }

.sgr-pagination { margin-top: 24px; }

@media (max-width: 640px) {
    .sgr-wrap { padding: 120px 0 60px; }
    .sgr-row-arrow { display: none; }
}
</style>
@endpush
@endsection
