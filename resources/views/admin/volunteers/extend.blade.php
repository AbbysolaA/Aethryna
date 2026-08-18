@extends('layouts.aethryna')

@section('title', 'Extend an offer | Skills Co-op')

@section('content')

@include('admin._nav')
@include('admin._flash')
<section class="vl-engagement">
    <div class="ath-container">

        <a href="{{ route('admin.volunteers.index') }}" class="vl-back">Back to the roster</a>

        <header class="vl-engagement-head">
            <span class="vl-eyebrow">Volunteering</span>
            <h1 class="vl-engagement-title">Extend an offer</h1>
            <p class="vl-side-note">{{ $engagement->offer_name }} applied for {{ $engagement->role->title }}. Set the dates and the offer email goes out.</p>
        </header>

        <div class="vl-engagement-grid">

            {{-- What they told us --}}
            <div class="vl-panel">
                <h2 class="vl-panel-title">The application</h2>

                <dl class="vl-appdl">
                    <dt>Applicant</dt>
                    <dd>
                        <strong>{{ $engagement->offer_name }}</strong><br>
                        <a href="mailto:{{ $engagement->offer_email }}">{{ $engagement->offer_email }}</a>
                        @if ($engagement->phone)
                            <br><span class="vl-side-note">{{ $engagement->phone }}</span>
                        @endif
                    </dd>

                    <dt>Applied</dt>
                    <dd>{{ $engagement->applied_at?->timezone('Europe/London')->format('j F Y, H:i') ?? 'Not recorded' }}</dd>

                    <dt>Why this role</dt>
                    <dd class="vl-appdl-long">{{ $engagement->about ?: 'Nothing given' }}</dd>

                    <dt>Availability</dt>
                    <dd>{{ $engagement->availability ?: 'Nothing given' }}</dd>

                    <dt>Relevant experience</dt>
                    <dd class="vl-appdl-long">{{ $engagement->experience ?: 'Nothing given' }}</dd>
                </dl>
            </div>

            {{-- Dates --}}
            <div class="vl-side">
                <div class="vl-panel">
                    <h2 class="vl-panel-title">Offer details</h2>

                    <form method="POST" action="{{ route('admin.volunteers.extend', $engagement) }}">
                        @csrf

                        <div class="vl-field">
                            <label for="starts_on">Starts</label>
                            <input id="starts_on" name="starts_on" type="date" required value="{{ old('starts_on') }}">
                            @error('starts_on')<p class="vl-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="vl-field">
                            <label for="ends_on">Ends <span class="vl-opt">(optional)</span></label>
                            <input id="ends_on" name="ends_on" type="date" value="{{ old('ends_on') }}">
                            @error('ends_on')<p class="vl-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="vl-field">
                            <label for="response_days">Days to respond</label>
                            <input id="response_days" name="response_days" type="number" min="1" max="90"
                                   placeholder="{{ config('volunteering.offer_response_days', 14) }}"
                                   value="{{ old('response_days') }}">
                            @error('response_days')<p class="vl-error">{{ $message }}</p>@enderror
                        </div>

                        @if ($engagement->role->requiresDbs())
                            <p class="vl-side-note vl-dbs-note">
                                This role requires a DBS check. Accepting the offer does not clear it, you mark that off on the roster once it comes back.
                            </p>
                        @endif

                        <button type="submit" class="vl-btn vl-btn-primary vl-btn-block">Send the offer</button>
                <a href="{{ route('admin.volunteers.index') }}" class="vl-back">Cancel</a>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
    <style>
        .vl-appdl { margin: 0; }
        .vl-appdl dt { font-family: var(--font-mono); font-size: 0.7rem; letter-spacing: 1.4px; text-transform: uppercase; color: var(--ath-muted); margin-top: 18px; }
        .vl-appdl dt:first-child { margin-top: 0; }
        .vl-appdl dd { margin: 6px 0 0; font-size: 0.96rem; line-height: 1.65; color: var(--ath-text); }
        .vl-appdl dd a { color: var(--ath-teal); font-weight: 700; }
        .vl-appdl-long { white-space: pre-wrap; }
        .vl-dbs-note { background: rgba(238,157,29,0.08); border-left: 3px solid var(--ath-gold); padding: 12px 16px; border-radius: 0 8px 8px 0; margin-top: 16px; }
    </style>
@endpush

@endsection
