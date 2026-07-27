@extends('layouts.aethryna')

@section('title', 'My volunteering | SkillsCo-op')
@section('meta_description', 'Your volunteer opportunities with SkillsCo-op.')

@section('content')

<section class="vl-engagement">
    <div class="ath-container">

        <header class="vl-engagement-head">
            <span class="vl-eyebrow">Volunteering</span>
            <h1 class="vl-engagement-title">My volunteering</h1>
        </header>

        @if (session('status'))
            <div class="vl-flash vl-flash-ok" role="status">{{ session('status') }}</div>
        @endif

        @if ($engagements->isEmpty())
            <div class="vl-panel vl-empty">
                <p>You do not have any volunteer opportunities yet.</p>
                <p class="vl-side-note">
                    Interested in mentoring? <a href="{{ route('mentors') }}">Read what it involves</a>.
                </p>
            </div>
        @else
            <ul class="vl-engagement-list">
                @foreach ($engagements as $engagement)
                    @php
                        $badge = match (true) {
                            $engagement->status === 'offer_declined' => ['Declined', 'vl-badge-muted'],
                            $engagement->status === 'withdrawn'       => ['Withdrawn', 'vl-badge-muted'],
                            $engagement->status === 'complete'        => ['Complete', 'vl-badge-done'],
                            $engagement->isVolunteeringNow()          => ['Active', 'vl-badge-active'],
                            $engagement->wasAccepted()                => ['Accepted', 'vl-badge-active'],
                            default                                   => ['Offer open', 'vl-badge-open'],
                        };
                    @endphp
                    <li class="vl-engagement-item">
                        <a href="{{ route('volunteer.show', $engagement) }}">
                            <div class="vl-item-main">
                                <p class="vl-item-title">{{ $engagement->role->title }}</p>
                                <p class="vl-item-summary">{{ $engagement->role->summary }}</p>
                            </div>
                            <div class="vl-item-side">
                                <span class="vl-badge {{ $badge[1] }}">{{ $badge[0] }}</span>
                                @if ($engagement->hours_sum_hours)
                                    <span class="vl-item-hours">{{ rtrim(rtrim(number_format($engagement->hours_sum_hours, 2), '0'), '.') }}h logged</span>
                                @endif
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
@endpush

@endsection
