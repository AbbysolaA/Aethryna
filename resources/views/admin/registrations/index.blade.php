@extends('layouts.aethryna')

@section('title', 'Session registrations | Skills Co-op')

@section('content')

@include('admin._nav')
@include('admin._flash')
<section class="vl-engagement">
    <div class="ath-container">

        <header class="vl-engagement-head vl-admin-head">
            <div>
                <span class="vl-eyebrow">The Sessions</span>
                <h1 class="vl-engagement-title">Registrations</h1>
                <p class="vl-side-note">
                    {{ number_format($totalCount) }} registration{{ $totalCount === 1 ? '' : 's' }},
                    {{ number_format($speakerCount) }} of whom offered to speak on a future panel.
                </p>
            </div>
            <div class="vl-head-actions">
                <a href="{{ route('admin.registrations.export', request()->query()) }}" class="vl-btn vl-btn-primary">Download CSV</a>
                <a href="{{ route('admin.panels.index') }}" class="vl-back">Panels</a>
                <a href="{{ route('admin.speakers.index') }}" class="vl-back">Speakers</a>
            </div>
        </header>

        <div class="vl-panel vl-filter-panel">
            <form method="GET" action="{{ route('admin.registrations.index') }}" class="vl-filters">
                <div class="vl-field">
                    <label for="panel">Panel</label>
                    <select id="panel" name="panel" onchange="this.form.submit()">
                        <option value="">All panels</option>
                        @foreach ($panels as $panel)
                            <option value="{{ $panel->id }}" @selected((string) $selectedPanel === (string) $panel->id)>
                                {{ $panel->tagline }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <label class="vl-speaker-check vl-filter-check">
                    <input type="checkbox" name="speakers" value="1" @checked($speakersOnly) onchange="this.form.submit()">
                    <span>Only those offering to speak</span>
                </label>
                <noscript><button type="submit" class="vl-btn vl-btn-small">Apply</button></noscript>
            </form>
        </div>

        @if ($registrations->isEmpty())
            <div class="vl-panel vl-empty">
                <p>No registrations match that filter.</p>
                @if ($totalCount === 0)
                    <p class="vl-side-note">Registrations taken before this screen existed were never saved to the database — they only exist in EmailOctopus.</p>
                @endif
            </div>
        @else
            <div class="vl-panel vl-table-panel">
                <div class="vl-table-scroll">
                    <table class="vl-table">
                        <thead>
                            <tr>
                                <th>Registered</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Panel</th>
                                <th>Joining as</th>
                                <th>Heard about us</th>
                                <th>Speaking</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registrations as $r)
                                <tr>
                                    <td class="vl-cell-dates">{{ $r->created_at?->format('j M Y') }}<span class="vl-cell-sub">{{ $r->created_at?->format('g:ia') }}</span></td>
                                    <td><strong>{{ $r->name }}</strong></td>
                                    <td><a href="mailto:{{ $r->email }}">{{ $r->email }}</a></td>
                                    <td>{{ $r->panelSession?->tagline ?? '—' }}</td>
                                    <td>{{ $r->interestLabel() }}</td>
                                    <td>{{ $r->referral_source ?: '—' }}</td>
                                    <td>
                                        @if ($r->wants_to_speak)
                                            <span class="vl-cell-flag">Yes</span>
                                            @if ($r->speaker_topic)
                                                <span class="vl-cell-sub">{{ $r->speaker_topic }}</span>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="vl-pagination">{{ $registrations->links() }}</div>
        @endif

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
    <style>
        .vl-filter-panel { margin-bottom: 20px; }
        .vl-filters { display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end; }
        .vl-filters .vl-field { margin-bottom: 0; flex: 0 1 320px; }
        .vl-speaker-check { display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .vl-speaker-check input { width: 18px; height: 18px; accent-color: var(--ath-teal); }
        .vl-filter-check { padding-bottom: 10px; }
        .vl-pagination { margin-top: 24px; }
    </style>
@endpush

@endsection
