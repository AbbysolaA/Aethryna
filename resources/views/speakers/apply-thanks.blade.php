@extends('layouts.aethryna')

@section('title', 'Thank you | Skills Co-op')
@section('meta_description', 'Your speaker pitch has been received.')
{{-- Reached from a redirect, not worth a search result. --}}
@section('meta_robots', 'noindex, follow')

@section('content')

<section class="vl-claim">
    <div class="ath-container">
        <div class="vl-claim-inner vl-claim-narrow">

            <span class="vl-eyebrow">Apply to speak</span>
            <h1 class="vl-title">Thank you, your pitch is <span class="vl-gradient">with us now</span></h1>

            <p class="vl-lede">
                We have emailed you a copy of what happens next. Every pitch gets read by a
                person, so give us a little time, and you will hear back either way.
            </p>

            <div class="vl-offer-card">
                <p class="vl-label">What happens next</p>
                <ol class="vl-next-steps">
                    <li>We read your pitch and match it against upcoming sessions.</li>
                    <li>If it fits one, we set up a short call to talk it through, nothing formal.</li>
                    <li>If we book you, we help you prepare, whether it is your first talk or your fiftieth.</li>
                </ol>
            </div>

            <p class="vl-note">
                Anything you want to add in the meantime, write to
                <a href="mailto:hello@skillscoop.org">hello@skillscoop.org</a>.
            </p>

            <div class="vl-auth-choice vl-auth-single">
                <a href="{{ route('sessions') }}" class="vl-btn vl-btn-outline">See our sessions</a>
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
