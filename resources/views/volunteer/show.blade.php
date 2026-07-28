@extends('layouts.aethryna')

@section('title', $engagement->role->title . ' | Skills Co-op')
@section('meta_description', 'Your volunteer opportunity with Skills Co-op.')

@section('content')

<section class="vl-engagement">
    <div class="ath-container">

        <a href="{{ route('volunteer.index') }}" class="vl-back">Back to my volunteering</a>

        <header class="vl-engagement-head">
            <h1 class="vl-engagement-title">{{ $engagement->role->title }}</h1>
            <div class="vl-engagement-meta">
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
                <span class="vl-badge {{ $badge[1] }}">{{ $badge[0] }}</span>
                <span class="vl-meta-sep">·</span>
                <span>{{ $engagement->role->summary }}</span>
            </div>
        </header>

        @if (session('status'))
            <div class="vl-flash vl-flash-ok" role="status">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="vl-flash vl-flash-err" role="alert">{{ session('error') }}</div>
        @endif

        <div class="vl-engagement-grid">

            {{-- ── Task list ─────────────────────────────────────────────── --}}
            <div class="vl-panel">
                <h2 class="vl-panel-title">Opportunity task list</h2>

                <ol class="vl-timeline">
                    @foreach ($timeline as $i => $step)
                        <li class="vl-step vl-step-{{ $step['state'] }}">
                            <span class="vl-step-marker" aria-hidden="true">
                                @if ($step['state'] === 'done')
                                    &#10003;
                                @else
                                    {{ $i + 1 }}
                                @endif
                            </span>
                            <div class="vl-step-body">
                                <p class="vl-step-label">{{ $step['label'] }}</p>
                                <p class="vl-step-detail">{{ $step['detail'] }}</p>

                                {{-- Accept / decline sits inside the step it belongs to,
                                     so the decision is where the eye already is. --}}
                                @if ($step['key'] === 'accepted' && $engagement->offerIsOpen())
                                    <form method="POST" action="{{ route('volunteer.respond', $engagement) }}" class="vl-decision">
                                        @csrf
                                        <button type="submit" name="decision" value="accept" class="vl-btn vl-btn-primary">Accept offer</button>
                                        <button type="submit" name="decision" value="decline" class="vl-btn vl-btn-ghost">Decline</button>
                                    </form>
                                    @error('decision')<p class="vl-error">{{ $message }}</p>@enderror
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>

            {{-- ── Side column ───────────────────────────────────────────── --}}
            <div class="vl-side">

                @if ($engagement->wasAccepted())
                    @php $outstanding = $engagement->outstandingOnboarding(); @endphp
                    @if ($outstanding)
                        <div class="vl-panel vl-panel-alert">
                            <h2 class="vl-panel-title">Still outstanding</h2>
                            <ul class="vl-outstanding">
                                @foreach ($outstanding as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                            @php $returnsInbox = config('volunteering.returns_inbox', 'hr@skillscoop.org'); @endphp
                            <p class="vl-side-note">
                                Send these to <a href="mailto:{{ $returnsInbox }}">{{ $returnsInbox }}</a>. A photo or a scan is fine. We will mark them off.
                            </p>
                        </div>
                    @endif
                @endif

                <div class="vl-panel">
                    <h2 class="vl-panel-title">Record volunteering hours</h2>

                    @if (! $engagement->wasAccepted())
                        <p class="vl-side-note">You can log hours once you have accepted the offer.</p>
                    @else
                        @if ($hours->isEmpty())
                            <p class="vl-side-note">No hours recorded yet.</p>
                        @else
                            <p class="vl-hours-total">{{ rtrim(rtrim(number_format($totalHours, 2), '0'), '.') }} <span>hours logged</span></p>
                            <ul class="vl-hours-list">
                                @foreach ($hours as $entry)
                                    <li>
                                        <span class="vl-hours-date">{{ $entry->worked_on->format('j M Y') }}</span>
                                        <span class="vl-hours-qty">{{ rtrim(rtrim(number_format($entry->hours, 2), '0'), '.') }}h</span>
                                        @if ($entry->note)
                                            <span class="vl-hours-note">{{ $entry->note }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        {{-- <details> rather than a JS modal: the disclosure works
                             with no script, and reopens on validation failure. --}}
                        <details class="vl-hours-form" @if ($errors->any()) open @endif>
                            <summary>Record hours</summary>

                            <form method="POST" action="{{ route('volunteer.hours.store', $engagement) }}">
                                @csrf

                                <div class="vl-field-row">
                                    <div class="vl-field">
                                        <label for="worked_on">Date</label>
                                        <input id="worked_on" name="worked_on" type="date" required
                                               max="{{ now()->toDateString() }}"
                                               value="{{ old('worked_on') }}">
                                        @error('worked_on')<p class="vl-error">{{ $message }}</p>@enderror
                                    </div>
                                    <div class="vl-field vl-field-narrow">
                                        <label for="hours">Hours</label>
                                        <input id="hours" name="hours" type="number" step="0.25" min="0.25" max="24" required
                                               value="{{ old('hours') }}">
                                        @error('hours')<p class="vl-error">{{ $message }}</p>@enderror
                                    </div>
                                </div>

                                <div class="vl-field">
                                    <label for="note">What you worked on <span class="vl-opt">(optional)</span></label>
                                    <input id="note" name="note" maxlength="255" value="{{ old('note') }}">
                                    @error('note')<p class="vl-error">{{ $message }}</p>@enderror
                                </div>

                                <label class="vl-check">
                                    <input type="checkbox" name="confirmed" value="1" required>
                                    <span>I confirm these hours are correct. I understand I <strong>will not</strong> be able to change them later.</span>
                                </label>
                                @error('confirmed')<p class="vl-error">{{ $message }}</p>@enderror

                                <button type="submit" class="vl-btn vl-btn-primary vl-btn-block">Record hours</button>
                            </form>
                        </details>
                    @endif
                </div>

            </div>
        </div>
    </div>
</section>

@push('styles')
    @include('volunteer._styles')
@endpush

@endsection
