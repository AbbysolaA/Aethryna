@extends('layouts.aethryna')

@section('title', 'Assessment · ' . ($assessment->recipientName() ?? 'Anonymous') . ' | Skills Co-op')

@section('content')

@include('admin._nav')
@include('admin._flash')
<section class="vl-engagement">
    <div class="ath-container">

        <header class="vl-engagement-head vl-admin-head">
            <div>
                <span class="vl-eyebrow">Assessment</span>
                <h1 class="vl-engagement-title">{{ $assessment->recipientName() ?? 'Anonymous' }}</h1>
                <p class="vl-side-note">
                    @if ($assessment->recipientEmail())
                        {{ $assessment->recipientEmail() }}
                        @unless ($assessment->user_id)
                            <span class="vl-cell-sub" style="display:inline;">(given on the assessment, no account)</span>
                        @endunless
                        ·
                    @endif
                    {{ ['completed' => 'Completed', 'abandoned' => 'Abandoned'][$assessment->status] ?? 'In progress' }}
                    @if ($assessment->completed_at)
                        {{ $assessment->completed_at->format('j F Y, g:ia') }}
                    @elseif ($assessment->started_at)
                        started {{ $assessment->started_at->format('j F Y, g:ia') }}
                    @endif
                </p>
                {{-- What has already gone out, so nobody chases someone twice by hand. --}}
                @if ($assessment->results_emailed_at || $assessment->reminder_sent_at)
                    <p class="vl-cell-sub">
                        @if ($assessment->results_emailed_at)
                            Results emailed {{ $assessment->results_emailed_at->format('j M Y, g:ia') }}.
                        @endif
                        @if ($assessment->reminder_sent_at)
                            Reminder sent {{ $assessment->reminder_sent_at->format('j M Y, g:ia') }}.
                        @endif
                    </p>
                @elseif ($assessment->status !== 'completed' && $assessment->recipientEmail())
                    <p class="vl-cell-sub">No reminder sent yet.</p>
                @endif
            </div>
            <div class="vl-head-actions">
                <a href="{{ route('admin.assessments.index') }}" class="vl-back">All assessments</a>
                <a href="{{ route('admin.content') }}" class="vl-back">Questions &amp; pathways</a>
            </div>
        </header>

        <div class="vl-panel">
            <h2 class="vl-sub-heading">Recommendation</h2>
            @forelse ($assessment->results->sortBy(fn ($r) => $r->result_type === 'primary' ? 0 : 1) as $result)
                <div class="as-result">
                    <div>
                        <span class="as-result-type">{{ $result->result_type === 'primary' ? 'Primary' : ucfirst($result->result_type) }}</span>
                        <strong>{{ $result->pathway?->name ?? 'Pathway no longer exists' }}</strong>
                        @if ($result->recommendation_text)
                            <p class="vl-side-note">{{ $result->recommendation_text }}</p>
                        @endif
                    </div>
                    <div class="as-result-score">
                        <span class="vl-cell-num">{{ $result->score }}</span>
                        @if ($result->cluster)<span class="vl-cell-sub">cluster {{ $result->cluster }}</span>@endif
                    </div>
                </div>
            @empty
                <p class="vl-side-note">
                    No result recorded.
                    @if ($assessment->status !== 'completed')
                        This assessment was never finished, so nothing was scored.
                    @endif
                </p>
            @endforelse
        </div>

        <div class="vl-panel">
            <h2 class="vl-sub-heading">Cluster scores</h2>
            <div class="as-clusters">
                @foreach ($clusters as $c)
                    <div class="as-cluster">
                        <div class="as-cluster-head">
                            <span>{{ $c['label'] }}</span>
                            <strong>{{ $c['score'] }}</strong>
                        </div>
                        <div class="as-bar"><span style="width: {{ $c['percent'] }}%"></span></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="vl-panel">
            <h2 class="vl-sub-heading">Answers ({{ count($answers) }})</h2>
            @forelse ($answers as $a)
                <div class="as-answer">
                    <span class="as-answer-num">{{ $a['number'] }}</span>
                    <div>
                        <p class="as-question">{{ $a['question'] }}</p>
                        <p class="as-given">{{ $a['answer'] }}</p>
                        @if ($a['clusters'])
                            <p class="vl-cell-sub">Scored: {{ implode(', ', $a['clusters']) }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="vl-side-note">No answers recorded against this assessment.</p>
            @endforelse
        </div>

        <div class="vl-panel">
            <form method="POST" action="{{ route('admin.assessments.destroy', $assessment) }}"
                  onsubmit="return confirm('Delete this assessment and its results? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="vl-btn vl-btn-danger">Delete this assessment</button>
                <p class="vl-side-note vl-hint">Removes the record and its results. There is no undo.</p>
            </form>
        </div>

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
    <style>
        .vl-sub-heading { font-size: 1.1rem; font-weight: 800; color: var(--ath-deep); margin-bottom: 18px; }

        .as-result {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            padding: 16px 0;
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }
        .as-result:last-child { border-bottom: none; }
        .as-result strong { display: block; color: var(--ath-deep); font-size: 1.05rem; }
        .as-result-type {
            display: inline-block;
            font-family: var(--font-mono);
            font-size: 0.68rem;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: var(--ath-teal);
            margin-bottom: 4px;
        }
        .as-result-score { text-align: right; white-space: nowrap; }

        .as-clusters { display: grid; gap: 14px; }
        .as-cluster-head { display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 6px; }
        .as-cluster-head strong { color: var(--ath-deep); font-variant-numeric: tabular-nums; }
        .as-bar { background: rgba(3,139,137,0.1); border-radius: 100px; height: 8px; overflow: hidden; }
        .as-bar span { display: block; height: 100%; background: var(--ath-teal); border-radius: 100px; }

        .as-answer {
            display: flex;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .as-answer:last-child { border-bottom: none; }
        .as-answer-num {
            flex: 0 0 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(3,139,137,0.1);
            color: var(--ath-teal);
            font-weight: 700;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .as-question { color: var(--ath-deep); font-weight: 600; margin-bottom: 4px; }
        .as-given { color: var(--ath-muted); }
    </style>
@endpush

@endsection
