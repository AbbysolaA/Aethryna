@extends('layouts.aethryna')

@section('title', 'Check the account | Skills Co-op')
@section('meta_description', 'Confirm which account should hold this volunteer offer.')

@section('content')

<section class="vl-claim">
    <div class="ath-container">
        <div class="vl-claim-inner vl-claim-narrow">

            <span class="vl-eyebrow">Volunteering</span>
            <h1 class="vl-title">Which account should hold this?</h1>

            {{-- Binding silently is how an offer addressed to one person ended
                 up attached to an admin account, with the onboarding pack sent
                 to the wrong inbox. The choice is now explicit. --}}
            <p class="vl-lede">
                This offer was sent to <strong>{{ $engagement->offer_email }}</strong>, but you are signed in as <strong>{{ $signedInAs->email }}</strong>.
            </p>

            <div class="vl-offer-card">
                <p class="vl-label">Role</p>
                <p class="vl-role">{{ $engagement->role->title }}</p>
                <p class="vl-role-summary">{{ $engagement->role->summary }}</p>
            </div>

            <div class="vl-mismatch-actions">
                <form method="POST" action="{{ route('volunteer.claim.as', $token) }}">
                    @csrf
                    <button type="submit" class="vl-btn vl-btn-primary">
                        Continue as {{ $signedInAs->email }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="vl-btn vl-btn-outline">
                        Sign out and use {{ $engagement->offer_email }}
                    </button>
                </form>
            </div>

            <p class="vl-note">
                Whichever you choose becomes the account that holds this role, logs hours against it, and receives the onboarding pack. If you sign out, open the link from your offer email again.
            </p>

        </div>
    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    <style>
        .vl-mismatch-actions { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 28px; }
        .vl-claim .vl-lede strong { color: #fff; }
    </style>
@endpush

@endsection
