@extends('layouts.aethryna')

@section('title', 'Your volunteer offer | Skills Co-op')
@section('meta_description', 'Sign in or create an account to accept or decline your Skills Co-op volunteer offer.')

@section('content')

<section class="vl-claim">
    <div class="ath-container">
        <div class="vl-claim-inner">

            <span class="vl-eyebrow">Volunteering</span>
            <h1 class="vl-title">Your offer from <span class="vl-gradient">Skills Co-op</span></h1>
            <p class="vl-lede">Hi {{ str($engagement->offer_name)->before(' ') }}, we would like you on the team. Sign in or create an account to accept or decline.</p>

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

            {{-- Both doors. Volunteers referred by a partner or met at a panel
                 will not have an account, so registering has to be as obvious
                 as signing in. --}}
            <div class="vl-auth-choice">
                <div class="vl-auth-option">
                    <h2>Already have an account?</h2>
                    <p>Sign in and you will come straight back here.</p>
                    <a href="{{ route('login') }}" class="vl-btn vl-btn-primary">Sign in</a>
                </div>

                <div class="vl-auth-divider"><span>or</span></div>

                <div class="vl-auth-option">
                    <h2>New here?</h2>
                    <p>Create an account, it takes a minute.</p>
                    <a href="{{ route('register') }}" class="vl-btn vl-btn-outline">Create an account</a>
                </div>
            </div>

            <p class="vl-note">
                This offer was sent to <strong>{{ $engagement->offer_email }}</strong>. You can use a different address for your account if you prefer.
            </p>

        </div>
    </div>
</section>

@push('styles')
    @include('volunteer._styles')
@endpush

@endsection
