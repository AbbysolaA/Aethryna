@extends('layouts.aethryna')

@section('title', 'Offer not available | Skills Co-op')
@section('meta_description', 'This volunteer offer link is no longer active.')

@section('content')

<section class="vl-claim">
    <div class="ath-container">
        <div class="vl-claim-inner vl-claim-narrow">

            <span class="vl-eyebrow">Volunteering</span>
            <h1 class="vl-title">This link is no longer active</h1>

            {{-- One message for wrong, expired and already-answered tokens, so
                 the page cannot be used to work out which a given link was. --}}
            <p class="vl-lede">
                That can mean the offer has already been answered, the response window has closed, or the link was mistyped.
            </p>

            <p class="vl-note">
                If you think it should still be open, reply to the offer email or write to
                <a href="mailto:hello@skillscoop.org">hello@skillscoop.org</a> and we will sort it out.
            </p>

            <div class="vl-auth-choice vl-auth-single">
                <a href="{{ route('home') }}" class="vl-btn vl-btn-outline">Back to the site</a>
            </div>

        </div>
    </div>
</section>

@push('styles')
    @include('volunteer._styles')
@endpush

@endsection
