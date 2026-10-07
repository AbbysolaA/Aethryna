@extends('layouts.aethryna')

@section('title', 'Speaker pitches | Skills Co-op')

@section('content')

@include('admin._nav')
<section class="vl-engagement">
    <div class="ath-container">

        <header class="vl-engagement-head vl-admin-head">
            <div>
                <span class="vl-eyebrow">Sessions</span>
                <h1 class="vl-engagement-title">Speaker pitches</h1>
                <p class="vl-side-note">
                    Pitches arrive from the public <a href="{{ route('speakers.apply') }}">apply to speak</a> page.
                    Accepting one adds the person to the speakers list; attaching them to a
                    session happens in <a href="{{ route('admin.panels.index') }}">Panels</a> as usual.
                    Registrants who ticked "I would like to speak" when signing up for a panel
                    are a separate list, under Registrations with the speakers filter.
                </p>
            </div>
            <div class="vl-head-actions">
                <a href="{{ route('admin.speakers.index') }}" class="vl-btn vl-btn-quiet">Speakers list</a>
            </div>
        </header>

        @include('admin._flash')

        @if ($applications->isEmpty())
            <div class="vl-panel vl-empty">
                <p>No pitches yet.</p>
                <p class="vl-side-note">
                    They arrive from <a href="{{ route('speakers.apply') }}">/apply-to-speak</a>,
                    which is linked from the session pages.
                </p>
            </div>
        @else
            <div class="vl-panel vl-table-panel">
                <div class="vl-table-scroll">
                    <table class="vl-table">
                        <thead>
                            <tr>
                                <th>Speaker</th>
                                <th>Pitch</th>
                                <th>Background</th>
                                <th>Received</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $application)
                                @php
                                    $badge = match ($application->status) {
                                        'new'      => ['New', 'vl-badge-open'],
                                        'accepted' => ['Accepted', 'vl-badge-done'],
                                        'future'   => ['Future session', 'vl-badge-active'],
                                        default    => ['Declined', 'vl-badge-muted'],
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <strong><a href="{{ route('admin.speaker-applications.show', $application) }}">{{ $application->name }}</a></strong>
                                        <span class="vl-cell-sub">{{ $application->email }}</span>
                                        <a class="vl-mini-btn" style="margin-top:8px; display:inline-block;" href="{{ route('admin.speaker-applications.show', $application) }}">Read the full pitch &rarr;</a>
                                        @if ($application->job_title || $application->organisation)
                                            <span class="vl-cell-sub">
                                                {{ collect([$application->job_title, $application->organisation])->filter()->implode(', ') }}
                                            </span>
                                        @endif
                                        @if ($application->location)
                                            <span class="vl-cell-sub">{{ $application->location }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $application->talk_title }}</strong>
                                        @if ($application->formatLabel())
                                            <span class="vl-cell-sub">Prefers: {{ strtolower($application->formatLabel()) }}</span>
                                        @endif
                                        @if ($application->topic_areas)
                                            <span class="vl-cell-sub">{{ implode(' · ', $application->topic_areas) }}</span>
                                        @endif
                                        <details class="vl-note-details">
                                            <summary>What they would cover</summary>
                                            <p>{{ $application->talk_summary }}</p>
                                        </details>
                                    </td>
                                    <td>
                                        <details class="vl-note-details">
                                            <summary>Bio and links</summary>
                                            <p>{{ $application->bio }}</p>
                                            @if ($application->prior_speaking)
                                                <p><strong>Spoken before:</strong> {{ $application->prior_speaking }}</p>
                                            @endif
                                        </details>
                                        <span class="vl-cell-sub">
                                            @if ($application->linkedin_url)
                                                <a href="{{ $application->linkedin_url }}" target="_blank" rel="noopener noreferrer">LinkedIn</a>
                                            @endif
                                            @if ($application->website_url)
                                                <a href="{{ $application->website_url }}" target="_blank" rel="noopener noreferrer">Website</a>
                                            @endif
                                            @if ($application->video_url)
                                                <a href="{{ $application->video_url }}" target="_blank" rel="noopener noreferrer">Video</a>
                                            @endif
                                            @if ($application->hasHeadshot())
                                                <a href="{{ route('admin.speaker-applications.headshot', $application) }}">Photo</a>
                                            @endif
                                        </span>
                                    </td>
                                    <td class="vl-cell-dates">{{ $application->created_at->format('j M Y') }}</td>
                                    <td>
                                        <span class="vl-badge {{ $badge[1] }}">{{ $badge[0] }}</span>
                                        @if ($application->status !== 'accepted')
                                            <form method="POST" action="{{ route('admin.speaker-applications.update', $application) }}" class="vl-onboard-form">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" aria-label="Set status for {{ $application->name }}">
                                                    @foreach (\App\Models\SpeakerApplication::STATUSES as $status)
                                                        <option value="{{ $status }}" @selected($application->status === $status)>
                                                            {{ \App\Models\SpeakerApplication::STATUS_LABELS[$status] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        @else
                                            {{-- Accepted is one way from here. Un-accepting
                                                 would strand the PanelSpeaker it minted, so
                                                 undoing a booking is a speakers list decision. --}}
                                            <span class="vl-cell-sub">On the speakers list</span>
                                        @endif
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
    <style>
        .vl-note-details { margin-top: 6px; font-size: 0.88rem; }
        .vl-note-details summary { cursor: pointer; color: #038b89; font-weight: 600; }
        .vl-note-details p { margin: 8px 0 0; line-height: 1.6; color: #404952; white-space: pre-wrap; max-width: 46ch; }
        .vl-cell-sub a { margin-right: 8px; }
    </style>
@endpush

@endsection
