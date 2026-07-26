@extends('layouts.aethryna')

@section('title', 'Risk register | SkillsCo-op')

@section('content')
<section class="rk-wrap">
    <div class="rk-container">

        <div class="rk-head">
            <div>
                <span class="rk-eyebrow">Governance</span>
                <h1>Risk register</h1>
                <p>Highest residual exposure first. Residual is the score after mitigation, which is the one that matters.</p>
            </div>
            <div class="rk-head-actions">
                <a href="{{ route('admin.risks.create') }}" class="rk-btn rk-btn-primary">Add a risk</a>
                <a href="{{ route('admin.dashboard') }}" class="rk-back">&larr; Admin dashboard</a>
            </div>
        </div>

        @if (session('success'))
            <div class="rk-alert"><i class="fas fa-check-circle"></i><div>{{ session('success') }}</div></div>
        @endif

        {{-- Filters --}}
        <div class="rk-tabs">
            @php
                $tabs = [
                    'active'     => 'Active',
                    'overdue'    => 'Review overdue',
                    'open'       => 'Open',
                    'mitigating' => 'Mitigating',
                    'monitoring' => 'Monitoring',
                    'closed'     => 'Closed',
                    'all'        => 'All',
                ];
            @endphp
            @foreach ($tabs as $key => $label)
                <a href="{{ route('admin.risks.index', array_filter(['status' => $key, 'category' => $category])) }}"
                   class="rk-tab {{ $status === $key ? 'is-active' : '' }} {{ $key === 'overdue' && ($counts['overdue'] ?? 0) > 0 ? 'is-warn' : '' }}">
                    {{ $label }}
                    <span class="rk-tab-count">{{ $counts[$key] ?? 0 }}</span>
                </a>
            @endforeach
        </div>

        {{-- Category filter --}}
        <div class="rk-cats">
            <a href="{{ route('admin.risks.index', ['status' => $status]) }}"
               class="rk-cat {{ !$category ? 'is-active' : '' }}">All categories</a>
            @foreach (\App\Models\Risk::CATEGORIES as $key => $label)
                <a href="{{ route('admin.risks.index', ['status' => $status, 'category' => $key]) }}"
                   class="rk-cat {{ $category === $key ? 'is-active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>

        @forelse ($risks as $risk)
            <a href="{{ route('admin.risks.edit', $risk) }}" class="rk-row rk-band-{{ $risk->band }}">
                <div class="rk-score">
                    <span class="rk-score-num">{{ $risk->residual_score }}</span>
                    <span class="rk-score-band">{{ ucfirst($risk->band) }}</span>
                </div>
                <div class="rk-row-main">
                    <div class="rk-row-top">
                        <span class="rk-ref">R-{{ $risk->id }}</span>
                        <span class="rk-pill rk-cat-pill">{{ \App\Models\Risk::CATEGORIES[$risk->category] ?? $risk->category }}</span>
                        <span class="rk-pill rk-status-{{ $risk->status }}">{{ \App\Models\Risk::STATUSES[$risk->status] ?? $risk->status }}</span>
                        @if ($risk->is_overdue)
                            <span class="rk-pill rk-pill-overdue">Review overdue</span>
                        @endif
                    </div>
                    <h3>{{ $risk->title }}</h3>
                    @if ($risk->mitigation)
                        <p class="rk-mitigation"><strong>Mitigation:</strong> {{ Str::limit($risk->mitigation, 130) }}</p>
                    @else
                        <p class="rk-mitigation rk-none">No mitigation recorded yet</p>
                    @endif
                    <p class="rk-meta">
                        Owner: {{ $risk->owner ?: 'unassigned' }}
                        @if ($risk->review_due)
                            &middot; review due {{ $risk->review_due->format('j M Y') }}
                        @endif
                        @if ($risk->residual_likelihood && $risk->residual_impact)
                            &middot; inherent {{ $risk->score }}, residual {{ $risk->residual_score }}
                        @endif
                    </p>
                </div>
                <div class="rk-row-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>
        @empty
            <div class="rk-empty">
                <div class="rk-empty-icon"><i class="fas fa-clipboard-list"></i></div>
                <h3>Nothing here</h3>
                <p>No risks match this filter. <a href="{{ route('admin.risks.create') }}">Add the first one</a>.</p>
            </div>
        @endforelse

        <div class="rk-pagination">{{ $risks->links() }}</div>

        <div class="rk-legend">
            <strong>Scoring.</strong> Likelihood times impact, each rated 1 to 5.
            <span class="rk-key rk-band-low">4 and under, low</span>
            <span class="rk-key rk-band-medium">4 to 7, medium</span>
            <span class="rk-key rk-band-high">8 to 14, high</span>
            <span class="rk-key rk-band-critical">15 and above, critical</span>
            Anything critical needs a named owner and a board level conversation.
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
.rk-wrap { padding: 150px 0 90px; background: var(--ath-light); min-height: 80vh; }
.rk-container { max-width: 1040px; margin: 0 auto; padding: 0 5%; }

.rk-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; margin-bottom: 24px; flex-wrap: wrap; }
.rk-eyebrow {
    display: inline-block; font-family: var(--font-mono); font-size: 0.78rem;
    font-weight: 600; letter-spacing: 3px; text-transform: uppercase;
    color: var(--ath-gold); margin-bottom: 10px; padding-left: 12px;
    border-left: 3px solid var(--ath-gold);
}
.rk-head h1 { font-family: 'Outfit', sans-serif; font-size: clamp(1.7rem,4vw,2.3rem); font-weight: 800; color: var(--ath-deep); margin: 0 0 8px; }
.rk-head p { color: var(--ath-muted); margin: 0; font-size: 0.99rem; max-width: 520px; }
.rk-head-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 10px; }
.rk-back { font-family: var(--font-mono); font-size: 0.82rem; color: var(--ath-muted); text-decoration: none; white-space: nowrap; }
.rk-back:hover { color: var(--ath-teal); }

.rk-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 24px; border-radius: 100px;
    font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.93rem;
    text-decoration: none; border: none; cursor: pointer; transition: all 0.2s;
    white-space: nowrap;
}
.rk-btn-primary { background: var(--ath-deep); color: #fff; }
.rk-btn-primary:hover { background: var(--ath-teal); transform: translateY(-2px); }

.rk-alert {
    display: flex; gap: 12px; align-items: flex-start;
    background: rgba(3,139,137,0.08); border: 1px solid rgba(3,139,137,0.3);
    color: #0a5f5d; padding: 15px 19px; border-radius: 12px; margin-bottom: 20px;
    font-size: 0.96rem; line-height: 1.6;
}
.rk-alert i { color: var(--ath-teal); margin-top: 2px; }

.rk-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
.rk-tab {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 9px 16px; border-radius: 100px; background: #fff;
    border: 1px solid rgba(0,0,0,0.09);
    font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.87rem;
    color: var(--ath-muted); text-decoration: none; transition: all 0.2s;
}
.rk-tab:hover { border-color: var(--ath-teal); color: var(--ath-deep); }
.rk-tab.is-active { background: var(--ath-deep); border-color: var(--ath-deep); color: #fff; }
.rk-tab.is-warn:not(.is-active) { border-color: #e8a33d; color: #9a6510; }
.rk-tab-count { font-family: var(--font-mono); font-size: 0.73rem; background: rgba(0,0,0,0.07); padding: 1px 7px; border-radius: 100px; }
.rk-tab.is-active .rk-tab-count { background: rgba(255,255,255,0.22); }

.rk-cats { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 20px; }
.rk-cat {
    font-family: var(--font-mono); font-size: 0.72rem; letter-spacing: 0.5px;
    text-transform: uppercase; padding: 5px 11px; border-radius: 6px;
    background: rgba(0,0,0,0.04); color: var(--ath-muted); text-decoration: none;
}
.rk-cat:hover { background: rgba(3,139,137,0.1); color: var(--ath-teal); }
.rk-cat.is-active { background: var(--ath-teal); color: #fff; }

/* Rows */
.rk-row {
    display: flex; align-items: center; gap: 18px;
    background: #fff; border: 1px solid rgba(0,0,0,0.07);
    border-left: 5px solid var(--ath-teal);
    border-radius: 14px; padding: 18px 22px; margin-bottom: 12px;
    text-decoration: none; transition: transform 0.18s, box-shadow 0.18s;
}
.rk-row:hover { transform: translateX(3px); box-shadow: 0 10px 30px rgba(0,0,0,0.06); }
.rk-band-low { border-left-color: #7aa87a; }
.rk-band-medium { border-left-color: #e8b647; }
.rk-band-high { border-left-color: #e07a2f; }
.rk-band-critical { border-left-color: #b3261e; }

.rk-score { flex-shrink: 0; text-align: center; width: 58px; }
.rk-score-num {
    display: block; font-family: 'Outfit', sans-serif;
    font-size: 1.7rem; font-weight: 800; line-height: 1; color: var(--ath-deep);
}
.rk-score-band {
    display: block; font-family: var(--font-mono); font-size: 0.62rem;
    letter-spacing: 0.8px; text-transform: uppercase; color: var(--ath-muted); margin-top: 3px;
}
.rk-row-main { flex: 1; min-width: 0; }
.rk-row-top { display: flex; align-items: center; gap: 7px; margin-bottom: 6px; flex-wrap: wrap; }
.rk-ref { font-family: var(--font-mono); font-size: 0.76rem; font-weight: 600; color: var(--ath-muted); }
.rk-pill {
    font-family: var(--font-mono); font-size: 0.64rem; font-weight: 600;
    letter-spacing: 0.9px; text-transform: uppercase; padding: 3px 9px; border-radius: 100px;
}
.rk-cat-pill { background: rgba(3,139,137,0.11); color: var(--ath-teal); }
.rk-status-open { background: rgba(238,157,29,0.16); color: #9a6510; }
.rk-status-mitigating { background: rgba(3,139,137,0.13); color: var(--ath-teal); }
.rk-status-monitoring { background: rgba(5,88,96,0.13); color: var(--ath-deep); }
.rk-status-closed { background: rgba(0,0,0,0.07); color: var(--ath-muted); }
.rk-pill-overdue { background: #fdecea; color: #b3261e; }
.rk-row h3 { font-family: 'Outfit', sans-serif; font-size: 1.06rem; font-weight: 800; color: var(--ath-deep); margin: 0 0 5px; }
.rk-mitigation { font-size: 0.9rem; color: var(--ath-text); line-height: 1.55; margin: 0 0 5px; }
.rk-mitigation.rk-none { color: #b3261e; font-style: italic; }
.rk-meta { font-size: 0.81rem; color: var(--ath-muted); margin: 0; }
.rk-row-arrow { color: rgba(0,0,0,0.18); flex-shrink: 0; }

.rk-empty { background: #fff; border-radius: 18px; padding: 60px 30px; text-align: center; border: 1px solid rgba(0,0,0,0.06); }
.rk-empty-icon {
    width: 60px; height: 60px; margin: 0 auto 16px; border-radius: 50%;
    background: rgba(3,139,137,0.08); color: var(--ath-teal);
    display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
}
.rk-empty h3 { font-family: 'Outfit', sans-serif; color: var(--ath-deep); font-weight: 800; margin: 0 0 6px; }
.rk-empty p { color: var(--ath-muted); margin: 0; }
.rk-empty a { color: var(--ath-teal); font-weight: 700; }

.rk-pagination { margin-top: 22px; }

.rk-legend {
    margin-top: 26px; padding: 18px 22px; background: #fff;
    border-radius: 12px; border: 1px solid rgba(0,0,0,0.06);
    font-size: 0.86rem; color: var(--ath-muted); line-height: 1.9;
}
.rk-legend strong { color: var(--ath-deep); }
.rk-key {
    display: inline-block; font-family: var(--font-mono); font-size: 0.72rem;
    padding: 2px 9px; border-radius: 5px; margin: 0 3px;
    border-left: 3px solid;
}
.rk-key.rk-band-low { background: rgba(122,168,122,0.13); color: #3f6b3f; }
.rk-key.rk-band-medium { background: rgba(232,182,71,0.16); color: #8a6510; }
.rk-key.rk-band-high { background: rgba(224,122,47,0.14); color: #99490f; }
.rk-key.rk-band-critical { background: #fdecea; color: #b3261e; }

@media (max-width: 780px) {
    .rk-wrap { padding: 120px 0 60px; }
    .rk-head-actions { align-items: flex-start; flex-direction: row; }
    .rk-row { flex-wrap: wrap; gap: 12px; }
    .rk-row-arrow { display: none; }
}
</style>
@endpush
@endsection
