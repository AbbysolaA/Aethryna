@extends('layouts.aethryna')

@section('title', 'Volunteer positions | Skills Co-op')

@section('content')

@include('admin._nav')
<section class="vl-engagement">
    <div class="ath-container">

        <header class="vl-engagement-head vl-admin-head">
            <div>
                <span class="vl-eyebrow">Volunteering</span>
                <h1 class="vl-engagement-title">Volunteer positions</h1>
                <p class="vl-side-note">Open roles appear on <a href="{{ route('volunteer.apply') }}">/volunteer/apply</a>. Closing a role hides it there without touching anyone already engaged.</p>
            </div>
            <div class="vl-head-actions">
                <a href="{{ route('admin.volunteer-roles.create') }}" class="vl-btn vl-btn-primary">Post a position</a>
                <a href="{{ route('admin.volunteers.index') }}" class="vl-back">Volunteer roster</a>
                <a href="{{ route('admin.volunteer-documents.index') }}" class="vl-back">Onboarding pack</a>
            </div>
        </header>

        @if (session('status'))
            <div class="vl-flash vl-flash-ok" role="status">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="vl-flash vl-flash-err" role="alert">{{ session('error') }}</div>
        @endif

        @if ($roles->isEmpty())
            <div class="vl-panel vl-empty">
                <p>No positions yet.</p>
                <p class="vl-side-note"><a href="{{ route('admin.volunteer-roles.create') }}">Post the first one</a>, or run the seeder for the standard five.</p>
            </div>
        @else
            <div class="vl-panel vl-table-panel">
                <div class="vl-table-scroll">
                    <table class="vl-table">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Grants</th>
                                <th>Requires</th>
                                <th>Engagements</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>
                                        <strong>{{ $role->title }}</strong>
                                        <span class="vl-cell-sub">{{ $role->summary }}</span>
                                    </td>
                                    <td>
                                        @if ($role->grants_access === 'mentor')
                                            <span class="vl-badge vl-badge-active">Mentor access</span>
                                        @else
                                            <span class="vl-badge vl-badge-muted">Volunteer</span>
                                        @endif
                                    </td>
                                    <td class="vl-cell-req">
                                        @if ($role->requiresDbs())<span class="vl-tag">DBS</span>@endif
                                        @if ($role->requires_nda)<span class="vl-tag vl-tag-quiet">NDA</span>@endif
                                        @if (! $role->requiresDbs() && ! $role->requires_nda)
                                            <span class="vl-cell-sub">Nothing</span>
                                        @endif
                                    </td>
                                    <td class="vl-cell-num">{{ $role->engagements_count }}</td>
                                    <td>
                                        @if ($role->is_open)
                                            <span class="vl-badge vl-badge-open">Open</span>
                                        @else
                                            <span class="vl-badge vl-badge-muted">Closed</span>
                                        @endif
                                    </td>
                                    <td class="vl-cell-actions">
                                        <a href="{{ route('admin.volunteer-roles.edit', $role) }}" class="vl-mini-btn">Edit</a>
                                        @if ($role->engagements_count === 0)
                                            <form method="POST" action="{{ route('admin.volunteer-roles.destroy', $role) }}"
                                                  onsubmit="return confirm('Delete {{ addslashes($role->title) }}? This cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="vl-mini-btn vl-mini-btn-danger">Delete</button>
                                            </form>
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
    @include('admin.volunteer-roles._admin-styles')
@endpush

@endsection
