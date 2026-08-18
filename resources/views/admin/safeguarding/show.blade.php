@extends('layouts.aethryna')

@section('title', 'SC-' . $concern->id . ' | Safeguarding | Skills Co-op')

@section('content')

@include('admin._nav')
<section class="sgd-wrap">
    <div class="sgd-container">

        <a href="{{ route('admin.safeguarding.index') }}" class="sgd-back">&larr; All concerns</a>

        @if (session('success'))
            <div class="sgd-alert"><i class="fas fa-check-circle"></i><div>{{ session('success') }}</div></div>
        @endif

        <div class="sgd-head">
            <div class="sgd-head-tags">
                <span class="sgd-ref">SC-{{ $concern->id }}</span>
                @if ($concern->urgency === 'urgent')
                    <span class="sgd-pill sgd-pill-urgent">Urgent</span>
                @endif
                <span class="sgd-pill sgd-status-{{ $concern->status }}">{{ ucfirst($concern->status) }}</span>
            </div>
            <h1>Concern about {{ $concern->learner?->name ?? 'a removed learner' }}</h1>
        </div>

        @if ($concern->urgency === 'urgent' && in_array($concern->status, ['new', 'acknowledged'], true))
            <div class="sgd-urgent-banner">
                <i class="fas fa-triangle-exclamation"></i>
                <div><strong>Marked urgent and still open.</strong> If there is immediate risk to someone's safety, call 999 first, then follow the safeguarding policy.</div>
            </div>
        @endif

        {{-- The concern --}}
        <div class="sgd-panel">
            <h2>What was reported</h2>
            <div class="sgd-concern-body">{{ $concern->concern }}</div>
        </div>

        {{-- Facts --}}
        <div class="sgd-panel">
            <h2>Record</h2>
            <dl class="sgd-facts">
                <div>
                    <dt>Learner</dt>
                    <dd>
                        {{ $concern->learner?->name ?? 'Removed' }}
                        @if ($concern->learner?->email)
                            <span class="sgd-sub">{{ $concern->learner->email }}</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt>Raised by</dt>
                    <dd>
                        {{ $concern->raisedBy?->name ?? 'Unknown' }}
                        @if ($concern->raisedBy?->email)
                            <span class="sgd-sub"><a href="mailto:{{ $concern->raisedBy->email }}">{{ $concern->raisedBy->email }}</a></span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt>Their role</dt>
                    <dd>{{ $concern->raised_by_role ? ucfirst($concern->raised_by_role) : 'Not recorded' }}</dd>
                </div>
                <div>
                    <dt>Raised at</dt>
                    <dd>{{ $concern->created_at->timezone('Europe/London')->format('j F Y, H:i') }} UK</dd>
                </div>
                <div>
                    <dt>Urgency</dt>
                    <dd>{{ ucfirst($concern->urgency) }}</dd>
                </div>
                <div>
                    <dt>Last reviewed</dt>
                    <dd>
                        @if ($concern->reviewed_at)
                            {{ $concern->reviewed_at->timezone('Europe/London')->format('j F Y, H:i') }} UK
                            <span class="sgd-sub">by {{ $concern->reviewedBy?->name ?? 'unknown' }}</span>
                        @else
                            <span class="sgd-muted">Not yet reviewed</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Decision --}}
        <div class="sgd-panel sgd-panel-action">
            <h2>Record your decision</h2>
            <p class="sgd-panel-lede">Saved against this concern with your name and a timestamp, so the trail stays complete.</p>

            <form method="POST" action="{{ route('admin.safeguarding.update', $concern) }}">
                @csrf
                @method('PATCH')

                <div class="sgd-field">
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        <option value="new" {{ $concern->status === 'new' ? 'selected' : '' }}>New, not yet looked at</option>
                        <option value="acknowledged" {{ $concern->status === 'acknowledged' ? 'selected' : '' }}>Acknowledged, reviewing</option>
                        <option value="actioned" {{ $concern->status === 'actioned' ? 'selected' : '' }}>Actioned, steps taken</option>
                        <option value="closed" {{ $concern->status === 'closed' ? 'selected' : '' }}>Closed, no further action needed</option>
                    </select>
                    @error('status')<p class="sgd-error">{{ $message }}</p>@enderror
                </div>

                <div class="sgd-field">
                    <label for="review_notes">Decision and actions taken</label>
                    <p class="sgd-hint">What you decided, what was done, who else was involved, and any date to review again.</p>
                    <textarea name="review_notes" id="review_notes" rows="7" maxlength="5000"
                        placeholder="Spoke with the learner on...">{{ old('review_notes', $concern->review_notes) }}</textarea>
                    @error('review_notes')<p class="sgd-error">{{ $message }}</p>@enderror
                </div>

                <div class="sgd-actions">
                    <button type="submit" class="sgd-submit">Save decision</button>
                    @if ($concern->raisedBy?->email)
                        <a href="mailto:{{ $concern->raisedBy->email }}?subject=Safeguarding%20concern%20SC-{{ $concern->id }}" class="sgd-secondary">
                            Reply to {{ explode(' ', $concern->raisedBy->name)[0] }}
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <p class="sgd-footnote">This page contains personal data about a named individual. Do not share it outside the safeguarding process.</p>
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
.sgd-wrap { padding: 150px 0 90px; background: var(--ath-light); min-height: 80vh; }
.sgd-container { max-width: 780px; margin: 0 auto; padding: 0 5%; }

.sgd-back { display: inline-block; font-family: var(--font-mono); font-size: 0.82rem; color: var(--ath-muted); text-decoration: none; margin-bottom: 20px; }
.sgd-back:hover { color: var(--ath-teal); }

.sgd-alert {
    display: flex; gap: 12px; align-items: flex-start;
    background: rgba(3,139,137,0.08); border: 1px solid rgba(3,139,137,0.3);
    color: #0a5f5d; padding: 15px 19px; border-radius: 12px; margin-bottom: 22px;
    font-size: 0.96rem; line-height: 1.6;
}
.sgd-alert i { color: var(--ath-teal); margin-top: 2px; }

.sgd-head { margin-bottom: 22px; }
.sgd-head-tags { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; flex-wrap: wrap; }
.sgd-ref { font-family: var(--font-mono); font-size: 0.84rem; font-weight: 600; color: var(--ath-muted); }
.sgd-pill {
    font-family: var(--font-mono); font-size: 0.68rem; font-weight: 600;
    letter-spacing: 1px; text-transform: uppercase; padding: 3px 10px; border-radius: 100px;
}
.sgd-pill-urgent { background: #fdecea; color: #b3261e; }
.sgd-status-new { background: rgba(238,157,29,0.16); color: #9a6510; }
.sgd-status-acknowledged { background: rgba(3,139,137,0.13); color: var(--ath-teal); }
.sgd-status-actioned { background: rgba(5,88,96,0.13); color: var(--ath-deep); }
.sgd-status-closed { background: rgba(0,0,0,0.07); color: var(--ath-muted); }
.sgd-head h1 { font-family: 'Outfit', sans-serif; font-size: clamp(1.6rem,3.6vw,2.1rem); font-weight: 800; color: var(--ath-deep); margin: 0; line-height: 1.2; }

.sgd-urgent-banner {
    display: flex; gap: 12px; align-items: flex-start;
    background: #fdecea; border-left: 4px solid #b3261e; border-radius: 0 12px 12px 0;
    padding: 16px 20px; margin-bottom: 22px; color: #7a1a13; font-size: 0.95rem; line-height: 1.6;
}
.sgd-urgent-banner i { margin-top: 3px; flex-shrink: 0; }

.sgd-panel {
    background: #fff; border: 1px solid rgba(0,0,0,0.07);
    border-radius: 18px; padding: 28px; margin-bottom: 18px;
}
.sgd-panel-action { border-color: rgba(3,139,137,0.25); }
.sgd-panel h2 {
    font-family: 'Outfit', sans-serif; font-size: 1.08rem; font-weight: 800;
    color: var(--ath-deep); margin: 0 0 14px;
    padding-bottom: 11px; border-bottom: 1px solid rgba(0,0,0,0.07);
}
.sgd-panel-lede { font-size: 0.92rem; color: var(--ath-muted); line-height: 1.65; margin: -4px 0 18px; }
.sgd-concern-body {
    font-size: 1rem; line-height: 1.8; color: var(--ath-text);
    white-space: pre-wrap;
    background: var(--ath-light); border-radius: 12px; padding: 18px 20px;
}

.sgd-facts { margin: 0; display: grid; gap: 14px; }
.sgd-facts > div { display: grid; grid-template-columns: 150px 1fr; gap: 14px; align-items: baseline; }
.sgd-facts dt {
    font-family: var(--font-mono); font-size: 0.74rem; font-weight: 600;
    letter-spacing: 1.2px; text-transform: uppercase; color: var(--ath-muted);
}
.sgd-facts dd { margin: 0; color: var(--ath-text); font-size: 0.97rem; line-height: 1.5; }
.sgd-sub { display: block; font-size: 0.86rem; color: var(--ath-muted); margin-top: 2px; }
.sgd-sub a { color: var(--ath-teal); text-decoration: none; }
.sgd-muted { color: var(--ath-muted); font-style: italic; }

.sgd-field { margin-bottom: 20px; }
.sgd-field label { display: block; font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--ath-deep); margin-bottom: 6px; }
.sgd-hint { font-size: 0.88rem; color: var(--ath-muted); line-height: 1.6; margin: 0 0 9px; }
.sgd-field select, .sgd-field textarea {
    width: 100%; padding: 12px 15px;
    border: 1.5px solid rgba(0,0,0,0.12); border-radius: 10px;
    font-size: 0.97rem; font-family: inherit; color: var(--ath-text);
    background: #fff; box-sizing: border-box; outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.sgd-field select:focus, .sgd-field textarea:focus {
    border-color: var(--ath-teal); box-shadow: 0 0 0 4px rgba(3,139,137,0.1);
}
.sgd-field textarea { resize: vertical; min-height: 140px; line-height: 1.65; }
.sgd-error { color: #b91c1c; font-size: 0.85rem; margin-top: 6px; }

.sgd-actions { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
.sgd-submit {
    display: inline-flex; align-items: center; gap: 9px;
    padding: 13px 28px; background: var(--ath-deep); color: #fff;
    border: none; border-radius: 100px;
    font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.98rem;
    cursor: pointer; transition: background 0.2s, transform 0.2s;
}
.sgd-submit:hover { background: var(--ath-teal); transform: translateY(-2px); }
.sgd-secondary { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.93rem; color: var(--ath-teal); text-decoration: none; }
.sgd-secondary:hover { color: var(--ath-gold); }

.sgd-footnote { font-size: 0.87rem; color: var(--ath-muted); font-style: italic; margin-top: 18px; line-height: 1.55; }

@media (max-width: 640px) {
    .sgd-wrap { padding: 120px 0 60px; }
    .sgd-panel { padding: 22px 20px; }
    .sgd-facts > div { grid-template-columns: 1fr; gap: 3px; }
}
</style>
@endpush
@endsection
