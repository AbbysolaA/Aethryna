@extends('layouts.aethryna')

@section('title', 'Invite someone | Skills Co-op')

@section('content')

@include('admin._nav')
<section class="vl-engagement">
    <div class="ath-container">

        <a href="{{ route('admin.staff.index') }}" class="vl-back">Back to staff and access</a>

        <header class="vl-engagement-head">
            <span class="vl-eyebrow">Access</span>
            <h1 class="vl-engagement-title">Invite someone</h1>
            <p class="vl-side-note">They get an email with a link to set their own password. You will never see or type it.</p>
        </header>

        @if (session('error'))
            <div class="vl-flash vl-flash-err" role="alert">{{ session('error') }}</div>
        @endif

        <div class="vl-panel vl-form-panel">
            <form method="POST" action="{{ route('admin.staff.store') }}">
                @csrf

                <div class="vl-field-row vl-field-row-even">
                    <div class="vl-field">
                        <label for="name">Their name</label>
                        <input id="name" name="name" required maxlength="255" value="{{ old('name') }}">
                        @error('name')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="vl-field">
                        <label for="email">Their email</label>
                        <input id="email" name="email" type="email" required maxlength="255" value="{{ old('email') }}">
                        @error('email')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="vl-field">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        @foreach ($staffRoles as $value => $label)
                            <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('role')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                {{-- What each role opens up. Written out because "admin" and
                     "safeguarding lead" sound similar and are not, and the
                     difference is who can read learner records. --}}
                <div class="vl-role-guide">
                    <p class="vl-label">What each role can reach</p>
                    <dl>
                        <dt>Safeguarding lead</dt>
                        <dd>Safeguarding concerns only. Reads and records decisions on concerns raised about learners. No user list, no risk register, no volunteer roster.</dd>

                        <dt>Skills coach</dt>
                        <dd>Their own cohort, learner progress, and the ability to flag a concern.</dd>

                        <dt>Mentor</dt>
                        <dd>The learners matched with them, and session logging.</dd>

                        <dt>Administrator</dt>
                        <dd><strong>Everything.</strong> Learner records, safeguarding, the risk register, volunteers, content and other people's access. Give this sparingly.</dd>
                    </dl>
                </div>

                <button type="submit" class="vl-btn vl-btn-primary">Send the invitation</button>

                <p class="vl-side-note vl-form-note">
                    The link lasts {{ (int) round(config('auth.passwords.invites.expire', 10080) / 1440) }} days. You can resend it from the staff list if it expires.
                </p>
            </form>
        </div>

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
    <style>
        .vl-role-guide { background: rgba(3,139,137,0.05); border-radius: 12px; padding: 20px 22px; margin: 6px 0 22px; }
        .vl-role-guide .vl-label { color: var(--ath-deep); margin-bottom: 12px; }
        .vl-role-guide dl { margin: 0; }
        .vl-role-guide dt { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 0.92rem; color: var(--ath-deep); margin-top: 12px; }
        .vl-role-guide dt:first-of-type { margin-top: 0; }
        .vl-role-guide dd { margin: 3px 0 0; font-size: 0.88rem; line-height: 1.6; color: var(--ath-muted); }
        .vl-role-guide dd strong { color: #8a5a06; }
        .vl-form-note { margin-top: 16px; }
    </style>
@endpush

@endsection
