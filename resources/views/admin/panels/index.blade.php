@extends('layouts.aethryna')

@section('title', 'Panels | Skills Co-op')

@section('content')
<section class="vl-engagement">
    <div class="ath-container">

        <header class="vl-engagement-head vl-admin-head">
            <div>
                <span class="vl-eyebrow">The Sessions</span>
                <h1 class="vl-engagement-title">Panels</h1>
                <p class="vl-side-note">The upcoming panel is what <a href="{{ route('sessions') }}">/sessions</a> shows. Every panel also has its own page you can share, and keeps it after the event as the archive for its recording.</p>
            </div>
            <div class="vl-head-actions">
                <a href="{{ route('admin.panels.create') }}" class="vl-btn vl-btn-primary">Add a panel</a>
                <a href="{{ route('admin.speakers.index') }}" class="vl-back">Speakers</a>
                <a href="{{ route('admin.registrations.index') }}" class="vl-back">Registrations</a>
            </div>
        </header>

        @if (session('status'))
            <div class="vl-flash vl-flash-ok" role="status">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="vl-flash vl-flash-err" role="alert">{{ session('error') }}</div>
        @endif

        @if ($panels->isEmpty())
            <div class="vl-panel vl-empty">
                <p>No panels yet. Add one and it appears on the sessions page straight away.</p>
            </div>
        @else
            <div class="vl-panel vl-table-panel">
                <div class="vl-table-scroll">
                    <table class="vl-table">
                        <thead>
                            <tr>
                                <th>Panel</th>
                                <th>When</th>
                                <th>Status</th>
                                <th>Speakers</th>
                                <th>Registered</th>
                                <th>Link</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($panels as $panel)
                                <tr>
                                    <td>
                                        <strong>{{ $panel->tagline }}</strong>
                                        <span class="vl-cell-sub">{{ $panel->title }}</span>
                                        @if ($panel->recording_url)
                                            <span class="vl-cell-flag">Recording</span>
                                        @endif
                                    </td>
                                    <td class="vl-cell-dates">
                                        {{ $panel->event_date?->format('j M Y') ?? 'No date yet' }}
                                        @if ($panel->event_date)
                                            <span class="vl-cell-sub">{{ $panel->event_date->format('g:ia') }} UK</span>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($panel->status) }}</td>
                                    <td class="vl-cell-num">{{ $panel->speakers_count }}</td>
                                    <td class="vl-cell-num">
                                        @if ($panel->registrations_count)
                                            <a href="{{ route('admin.registrations.index', ['panel' => $panel->id]) }}">{{ $panel->registrations_count }}</a>
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('sessions.show', $panel) }}" target="_blank" rel="noopener">/sessions/{{ $panel->slug }}</a>
                                    </td>
                                    <td class="vl-cell-actions">
                                        <a href="{{ route('admin.panels.edit', $panel) }}" class="vl-btn vl-btn-small">Edit</a>
                                        <form method="POST" action="{{ route('admin.panels.destroy', $panel) }}"
                                              onsubmit="return confirm('Delete {{ addslashes($panel->tagline) }}? This cannot be undone.');">
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
        @endif

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
@endpush

@endsection
