@extends('layouts.aethryna')

@section('title', 'Staff and access | Skills Co-op')

@section('content')
<section class="vl-engagement">
    <div class="ath-container">

        <header class="vl-engagement-head vl-admin-head">
            <div>
                <span class="vl-eyebrow">Access</span>
                <h1 class="vl-engagement-title">Staff and access</h1>
                <p class="vl-side-note">These roles reach other people's records, so nobody can sign up for one. Invite them here and they set their own password.</p>
            </div>
            <div class="vl-head-actions">
                <a href="{{ route('admin.staff.create') }}" class="vl-btn vl-btn-primary">Invite someone</a>
                <a href="{{ route('admin.dashboard') }}" class="vl-back">Admin dashboard</a>
            </div>
        </header>

        @if (session('status'))
            <div class="vl-flash vl-flash-ok" role="status">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="vl-flash vl-flash-err" role="alert">{{ session('error') }}</div>
        @endif

        @if ($staff->isEmpty())
            <div class="vl-panel vl-empty">
                <p>Nobody has been invited yet.</p>
            </div>
        @else
            <div class="vl-panel vl-table-panel">
                <div class="vl-table-scroll">
                    <table class="vl-table">
                        <thead>
                            <tr>
                                <th>Person</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($staff as $person)
                                <tr>
                                    <td>
                                        <strong>{{ $person->name }}</strong>
                                        <span class="vl-cell-sub">{{ $person->email }}</span>
                                        @if ($person->id === auth()->id())
                                            <span class="vl-cell-flag">you</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Changing a role here is the whole point of the
                                             screen, so it is inline rather than behind an edit
                                             page. Own-role changes are refused server side. --}}
                                        <form method="POST" action="{{ route('admin.staff.update', $person) }}" class="vl-role-form">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" @disabled($person->id === auth()->id())>
                                                @foreach ($staffRoles as $value => $label)
                                                    <option value="{{ $value }}" @selected($person->role === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @unless ($person->id === auth()->id())
                                                <button type="submit" class="vl-mini-btn">Save</button>
                                            @endunless
                                        </form>
                                    </td>
                                    <td>
                                        {{-- A never-used invite leaves the account unverified
                                             only if it was created before invites set that at
                                             creation, so lean on last activity instead. --}}
                                        @if ($person->updated_at->eq($person->created_at))
                                            <span class="vl-badge vl-badge-open">Invited</span>
                                        @else
                                            <span class="vl-badge vl-badge-active">Active</span>
                                        @endif
                                        <span class="vl-cell-sub">Added {{ $person->created_at->format('j M Y') }}</span>
                                    </td>
                                    <td class="vl-cell-actions">
                                        <form method="POST" action="{{ route('admin.staff.resend', $person) }}"
                                              onsubmit="return confirm('Send {{ addslashes($person->name) }} a new invitation link?\n\nAny previous link stops working.');">
                                            @csrf
                                            <button type="submit" class="vl-mini-btn">Resend invite</button>
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
    <style>
        .vl-role-form { display: flex; align-items: center; gap: 8px; }
        .vl-role-form select { padding: 7px 10px; border: 1.5px solid rgba(0,0,0,0.12); border-radius: 8px; font-family: inherit; font-size: 0.85rem; background: #fff; color: var(--ath-text); }
        .vl-role-form select:disabled { background: rgba(0,0,0,0.04); color: var(--ath-muted); cursor: not-allowed; }
    </style>
@endpush

@endsection
