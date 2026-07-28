@extends('layouts.aethryna')

@section('title', 'Your volunteer offer | Skills Co-op')
@section('meta_description', 'Accept or decline your Skills Co-op volunteer offer.')

@section('content')

<section class="vl-claim">
    <div class="ath-container">
        <div class="vl-claim-inner">

            <span class="vl-eyebrow">Volunteering</span>
            <h1 class="vl-title">Your offer from <span class="vl-gradient">Skills Co-op</span></h1>
            <p class="vl-lede">Hi {{ str($engagement->offer_name)->before(' ') }}, we would like you on the team.</p>

            @if (session('error'))
                <div class="vl-flash vl-flash-err" role="alert">{{ session('error') }}</div>
            @endif

            {{-- Offer summary --}}
            <div class="vl-offer-card">
                <p class="vl-label">Role</p>
                <p class="vl-role">{{ $engagement->role->title }}</p>
                <p class="vl-role-summary">{{ $engagement->role->summary }}</p>

                <div class="vl-offer-dates">
                    @if ($engagement->starts_on)
                        <div>
                            <p class="vl-label">Starts</p>
                            <p class="vl-value">{{ $engagement->starts_on->format('j F Y') }}</p>
                        </div>
                    @endif
                    @if ($engagement->ends_on)
                        <div>
                            <p class="vl-label">Ends</p>
                            <p class="vl-value">{{ $engagement->ends_on->format('j F Y') }}</p>
                        </div>
                    @endif
                    @if ($engagement->offer_expires_at)
                        <div>
                            <p class="vl-label">Respond by</p>
                            <p class="vl-value">{{ $engagement->offer_expires_at->format('j F Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if ($accountExists)
                {{-- They already hold an account on this address, so they sign
                     in. Setting a password from a link that could have been
                     forwarded would be account takeover, not onboarding. --}}
                <div class="vl-auth-panel">
                    <h2>Sign in to continue</h2>
                    <p>You already have an account for <strong>{{ $engagement->offer_email }}</strong>. Sign in and you will come straight back here to accept or decline.</p>
                    <a href="{{ route('login') }}" class="vl-btn vl-btn-primary">Sign in</a>
                    <p class="vl-panel-note">
                        Forgotten it? <a href="{{ route('password.request') }}">Reset your password</a>.
                    </p>
                </div>
            @else
                {{-- No account yet. They set a password here and are signed
                     straight in. The address comes from the offer rather than
                     being typed, so it cannot drift from the one we wrote to. --}}
                <div class="vl-auth-panel">
                    <h2>Set a password to continue</h2>
                    <p>You do not have an account yet. Choose a password and we will set one up on <strong>{{ $engagement->offer_email }}</strong>, then bring you back to this offer.</p>

                    <form method="POST" action="{{ route('volunteer.claim.store', request()->route('token')) }}" class="vl-set-password">
                        @csrf

                        <div class="vl-field">
                            <label for="name">Your name</label>
                            <input id="name" name="name" required maxlength="255"
                                   value="{{ old('name', $engagement->offer_name) }}">
                            @error('name')<p class="vl-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="vl-field">
                            <label for="claim_email">Email</label>
                            {{-- Shown, not editable. Changing it would break the
                                 link between the offer and the account. --}}
                            <input id="claim_email" type="email" value="{{ $engagement->offer_email }}" disabled>
                            <p class="vl-panel-note vl-hint">This is where we sent your offer. Contact us if you would rather use a different address.</p>
                        </div>

                        <div class="vl-field">
                            <label for="password">Choose a password</label>
                            <input id="password" name="password" type="password" required autocomplete="new-password"
                                   placeholder="At least 8 characters">
                            @error('password')<p class="vl-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="vl-field">
                            <label for="password_confirmation">Confirm password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="vl-btn vl-btn-primary vl-btn-block">Create account and view offer</button>

                        <p class="vl-panel-note">
                            Creating an account does not accept the offer. You will get the chance to read it and decide.
                        </p>
                    </form>
                </div>
            @endif

        </div>
    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    <style>
        .vl-auth-panel { background: #fff; border-radius: 20px; padding: 30px 32px; color: var(--ath-text); margin-top: 30px; max-width: 520px; }
        .vl-auth-panel h2 { font-family: 'Outfit', sans-serif; font-size: 1.2rem; font-weight: 800; color: var(--ath-deep); margin: 0 0 10px; }
        .vl-auth-panel > p { font-size: 0.95rem; line-height: 1.65; color: var(--ath-muted); margin: 0 0 20px; }
        .vl-auth-panel strong { color: var(--ath-deep); }
        .vl-panel-note { font-size: 0.85rem; line-height: 1.6; color: var(--ath-muted); margin: 14px 0 0; }
        .vl-panel-note a { color: var(--ath-teal); font-weight: 700; }
        .vl-hint { margin-top: 7px; }
        .vl-set-password .vl-field { margin-bottom: 16px; }
        .vl-field input[disabled] { background: rgba(0,0,0,0.04); color: var(--ath-muted); cursor: not-allowed; }
        .vl-claim .vl-flash-err { max-width: 520px; }
    </style>
@endpush

@endsection
