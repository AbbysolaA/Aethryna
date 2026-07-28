@extends('layouts.aethryna')

@section('title', 'Thank you | Skills Co-op')
@section('meta_description', 'Your volunteer application has been received.')

@section('content')

<section class="vl-claim">
    <div class="ath-container">
        <div class="vl-claim-inner vl-claim-narrow">

            <span class="vl-eyebrow">Volunteer with us</span>
            <h1 class="vl-title">Thank you, that is <span class="vl-gradient">with us now</span></h1>

            <p class="vl-lede">
                We read every application properly rather than filtering them, so give us a few days. You will hear back either way.
            </p>

            <div class="vl-offer-card">
                <p class="vl-label">What happens next</p>
                <ol class="vl-next-steps">
                    <li>One of us reads your application and comes back to you.</li>
                    <li>If it looks like a fit, we will have a conversation, nothing formal.</li>
                    <li>If we go ahead, you get an offer by email with everything you need to decide.</li>
                </ol>
            </div>

            <p class="vl-note">
                Anything you want to add in the meantime, write to
                <a href="mailto:hello@skillscoop.org">hello@skillscoop.org</a>.
            </p>

            <div class="vl-auth-choice vl-auth-single">
                <a href="{{ route('home') }}" class="vl-btn vl-btn-outline">Back to the site</a>
            </div>

        </div>
    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    <style>
        .vl-next-steps { margin: 12px 0 0; padding-left: 20px; }
        .vl-next-steps li { font-size: 0.98rem; line-height: 1.7; opacity: 0.9; margin-bottom: 8px; }
        .vl-next-steps li:last-child { margin-bottom: 0; }
    </style>
@endpush

@endsection
