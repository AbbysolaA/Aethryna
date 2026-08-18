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
                                        <strong>{{ $a->user?->name ?? 'Anonymous' }}</strong>
                                        @if ($a->user?->email)
                                            <span class="vl-cell-sub">{{ $a->user->email }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $a->status === 'completed' ? 'Completed' : 'In progress' }}
                                        @if ($a->status !== 'completed')
                                            <span class="vl-cell-sub">not finished</span>
                                        @endif
                                    </td>
                                    <td>{{ $primary?->pathway?->name ?? '—' }}</td>
                                    <td class="vl-cell-num">{{ $primary?->score ?? '—' }}</td>
                                    <td class="vl-cell-num">{{ is_array($a->responses) ? count($a->responses) : 0 }}</td>
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
    </style>
@endpush

@endsection
