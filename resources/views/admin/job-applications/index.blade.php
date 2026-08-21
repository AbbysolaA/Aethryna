@extends('layouts.aethryna')

@section('title', 'Job applications | Skills Co-op')

@section('content')

@include('admin._nav')
<section class="vl-engagement">
    <div class="ath-container">

        <header class="vl-engagement-head vl-admin-head">
            <div>
                <span class="vl-eyebrow">Hiring</span>
                <h1 class="vl-engagement-title">Job applications</h1>
                <p class="vl-side-note">Everyone who applied for a paid role through the site. CVs open from here and nowhere else.</p>
            </div>
            <div class="vl-head-actions">
                <a href="{{ route('admin.volunteer-roles.index') }}" class="vl-back">Positions</a>
            </div>
        </header>

        @include('admin._flash')

        @if ($applications->isEmpty())
            <div class="vl-panel vl-empty">
                <p>Nobody has applied yet.</p>
                <p class="vl-side-note">
                    Applications land here from the vacancy pages at <a href="{{ route('careers.index') }}">/careers</a>.
                </p>
            </div>
        @else
            <div class="vl-panel vl-table-panel">
                <div class="vl-table-scroll">
                    <table class="vl-table">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>Role</th>
                                <th>Applied</th>
                                <th>Application</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $application)
                                @php
                                    $badge = match ($application->status) {
                                        'new'         => ['New', 'vl-badge-open'],
                                        'shortlisted' => ['Shortlisted', 'vl-badge-active'],
                                        'hired'       => ['Hired', 'vl-badge-done'],
                                        default       => ['Declined', 'vl-badge-muted'],
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $application->name }}</strong>
                                        <span class="vl-cell-sub">{{ $application->email }}</span>
                                        @if ($application->phone)
                                            <span class="vl-cell-sub">{{ $application->phone }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $application->role?->title ?? 'Role since removed' }}</td>
                                    <td class="vl-cell-dates">{{ $application->created_at->format('j M Y') }}</td>
                                    <td>
                                        @if ($application->hasCv())
                                            <a href="{{ route('admin.job-applications.cv', $application) }}">
                                                {{ $application->cv_original_name }}
                                            </a>
                                            <span class="vl-cell-sub">{{ $application->cvSizeForHumans() }}</span>
                                        @elseif ($application->cv_path)
                                            <span class="vl-cell-sub">CV recorded, file missing from storage</span>
                                        @endif
                                        @if ($application->portfolio_url)
                                            <span class="vl-cell-sub">
                                                <a href="{{ $application->portfolio_url }}" target="_blank" rel="noopener noreferrer">Portfolio</a>
                                            </span>
                                        @endif
                                        {{-- The covering note, inline. Opening a page per
                                             applicant to read three sentences is the kind of
                                             friction that stops the list being worked through. --}}
                                        <details class="vl-note-details">
                                            <summary>Covering note</summary>
                                            <p>{{ $application->cover_note }}</p>
                                        </details>
                                    </td>
                                    <td>
                                        <span class="vl-badge {{ $badge[1] }}">{{ $badge[0] }}</span>
                                        <form method="POST" action="{{ route('admin.job-applications.update', $application) }}" class="vl-onboard-form">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" aria-label="Set status for {{ $application->name }}">
                                                @foreach (\App\Models\JobApplication::STATUSES as $status)
                                                    <option value="{{ $status }}" @selected($application->status === $status)>
                                                        {{ ucfirst($status) }}
                                                    </option>
                                                @endforeach
                                            </select>
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
    <style>
        .vl-note-details { margin-top: 6px; font-size: 0.88rem; }
        .vl-note-details summary { cursor: pointer; color: #038b89; font-weight: 600; }
        .vl-note-details p { margin: 8px 0 0; line-height: 1.6; color: #404952; white-space: pre-wrap; max-width: 46ch; }
    </style>
@endpush

@endsection
