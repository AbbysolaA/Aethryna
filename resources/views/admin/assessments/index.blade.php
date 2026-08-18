@extends('layouts.aethryna')

@section('title', 'Assessments | Skills Co-op')

@section('content')

@include('admin._nav')
<section class="vl-engagement">
    <div class="ath-container">

        <header class="vl-engagement-head vl-admin-head">
            <div>
                <span class="vl-eyebrow">Assessment</span>
                <h1 class="vl-engagement-title">Assessments</h1>
                <p class="vl-side-note">
                    {{ number_format($totalCount) }} started, {{ number_format($completedCount) }} completed.
                    Open one to see the answers given and the pathway it recommended.
                </p>
            </div>
            <div class="vl-head-actions">
                <a href="{{ route('admin.assessments.export', request()->query()) }}" class="vl-btn vl-btn-primary">Download CSV</a>
                <a href="{{ route('admin.content') }}" class="vl-back">Questions &amp; pathways</a>
                <a href="{{ route('admin.dashboard') }}" class="vl-back">Dashboard</a>
            </div>
        </header>

        @if (session('status'))
            <div class="vl-flash vl-flash-ok" role="status">{{ session('status') }}</div>
        @endif

        {{--
            Where people stop.

            "3 completed out of 40 started" says there is a problem but not what
            it is. Split by how far people got, it usually says which: a pile in
            the first band is a landing page that oversells, a pile in the last
            is a question set that outstays its welcome.
        --}}
        @if ($dropOff['unfinished'] > 0)
            <div class="vl-panel as-dropoff">
                <div class="as-dropoff-head">
                    <h2 class="vl-sub-heading">Where people stop</h2>
                    <p class="vl-side-note">
                        {{-- Written as an expression rather than @if/@endif: Blade only
                             compiles a directive at a non-word boundary, so
                             "started@if(...)" would render verbatim on the page. --}}
                        {{ number_format($dropOff['unfinished']) }} unfinished
                        of {{ number_format($totalCount) }} started{{ $dropOff['totalQuestions'] ? ' · '.$dropOff['totalQuestions'].' questions in the set' : '' }}.
                        <strong>{{ number_format($dropOff['reachable']) }}</strong> of them left an email address, so
                        they can be reminded ({{ number_format($dropOff['reminded']) }} reminded so far).
                    </p>
                </div>

                <div class="as-bands">
                    @foreach ($dropOff['bands'] as $band)
                        <div class="as-band">
                            <div class="as-band-head">
                                <span>{{ $band['label'] }}</span>
                                <strong>{{ number_format($band['count']) }}</strong>
                            </div>
                            <div class="as-bar"><span style="width: {{ $band['percent'] }}%"></span></div>
                            <p class="vl-cell-sub">{{ $band['percent'] }}% of unfinished · {{ $band['hint'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="vl-panel vl-filter-panel">
            <form method="GET" action="{{ route('admin.assessments.index') }}" class="vl-filters">
                <div class="vl-field">
                    <label for="q">Search</label>
                    <input type="search" id="q" name="q" value="{{ $search }}" placeholder="Name or email">
                </div>
                <div class="vl-field">
                    <label for="status">Status</label>
                    <select id="status" name="status" onchange="this.form.submit()">
                        <option value="">All</option>
                        <option value="completed" @selected($status === 'completed')>Completed</option>
                        <option value="in_progress" @selected($status === 'in_progress')>In progress</option>
                        <option value="abandoned" @selected($status === 'abandoned')>Abandoned</option>
                    </select>
                </div>
                <button type="submit" class="vl-btn vl-btn-small">Search</button>
                @if ($search !== '' || $status)
                    <a href="{{ route('admin.assessments.index') }}" class="vl-back">Clear</a>
                @endif
            </form>
        </div>

        @if ($assessments->isEmpty())
            <div class="vl-panel vl-empty">
                <p>No assessments match that filter.</p>
            </div>
        @else
            <div class="vl-panel vl-table-panel">
                <div class="vl-table-scroll">
                    <table class="vl-table">
                        <thead>
                            <tr>
                                <th>Taken</th>
                                <th>Who</th>
                                <th>Status</th>
                                <th>Recommended</th>
                                <th>Score</th>
                                <th>Answers</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assessments as $a)
                                @php $primary = $a->results->firstWhere('result_type', 'primary') ?? $a->results->first(); @endphp
                                <tr>
                                    <td class="vl-cell-dates">
                                        {{ $a->started_at?->format('j M Y') ?? '—' }}
                                        <span class="vl-cell-sub">{{ $a->started_at?->format('g:ia') }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $a->recipientName() ?? 'Anonymous' }}</strong>
                                        @if ($a->recipientEmail())
                                            <span class="vl-cell-sub">
                                                {{ $a->recipientEmail() }}
                                                @unless ($a->user_id)
                                                    · no account
                                                @endunless
                                            </span>
                                        @else
                                            <span class="vl-cell-sub">no way to contact</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($a->status === 'completed')
                                            Completed
                                            @if ($a->results_emailed_at)
                                                <span class="vl-cell-sub">results emailed</span>
                                            @endif
                                        @elseif ($a->status === 'abandoned')
                                            Abandoned
                                            <span class="vl-cell-sub">
                                                @if ($a->hasOptedOutOfReminders())
                                                    asked us to stop
                                                @elseif ($a->reminder_sent_at)
                                                    reminded {{ $a->reminder_sent_at->diffForHumans() }}
                                                @else
                                                    no reminder sent
                                                @endif
                                            </span>
                                        @else
                                            In progress
                                            <span class="vl-cell-sub">
                                                @if ($a->hasOptedOutOfReminders())
                                                    asked us to stop
                                                @elseif ($a->reminder_sent_at)
                                                    reminded {{ $a->reminder_sent_at->diffForHumans() }}
                                                @else
                                                    not finished
                                                @endif
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $primary?->pathway?->name ?? '—' }}</td>
                                    <td class="vl-cell-num">{{ $primary?->score ?? '—' }}</td>
                                    <td class="vl-cell-num">{{ $a->answeredCount() }}</td>
                                    <td class="vl-cell-actions">
                                        <a href="{{ route('admin.assessments.show', $a) }}" class="vl-btn vl-btn-small">View</a>
                                        <form method="POST" action="{{ route('admin.assessments.destroy', $a) }}"
                                              onsubmit="return confirm('Delete this assessment and its results? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="vl-btn vl-btn-small vl-btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="vl-pagination">{{ $assessments->links() }}</div>
        @endif

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
    <style>
        .vl-filter-panel { margin-bottom: 20px; }
        .vl-filters { display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end; }
        .vl-filters .vl-field { margin-bottom: 0; flex: 0 1 260px; }
        .vl-pagination { margin-top: 24px; }

        .as-dropoff { margin-bottom: 20px; }
        .as-dropoff-head { margin-bottom: 22px; }
        .vl-sub-heading { font-size: 1.1rem; font-weight: 800; color: var(--ath-deep); margin-bottom: 6px; }

        .as-bands { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 22px; }
        .as-band-head { display: flex; justify-content: space-between; align-items: baseline; gap: 10px; font-size: 0.9rem; margin-bottom: 8px; }
        .as-band-head strong { color: var(--ath-deep); font-size: 1.25rem; font-variant-numeric: tabular-nums; }
        .as-bar { background: rgba(3,139,137,0.1); border-radius: 100px; height: 8px; overflow: hidden; margin-bottom: 8px; }
        .as-bar span { display: block; height: 100%; background: var(--ath-teal); border-radius: 100px; }
    </style>
@endpush

@endsection
