@extends('layouts.aethryna')

@section('title', 'Volunteers | Skills Co-op')

@section('content')
<section class="vl-engagement">
    <div class="ath-container">

        <header class="vl-engagement-head vl-admin-head">
            <div>
                <span class="vl-eyebrow">Volunteering</span>
                <h1 class="vl-engagement-title">Volunteer roster</h1>
                <p class="vl-side-note">Offers, onboarding returns and logged hours. Mentors appear here too.</p>
            </div>
            <div class="vl-head-actions">
                <a href="{{ route('admin.volunteers.create') }}" class="vl-btn vl-btn-primary">Extend an offer</a>
                <a href="{{ route('admin.volunteer-roles.index') }}" class="vl-back">Positions</a>
                <a href="{{ route('admin.volunteer-documents.index') }}" class="vl-back">Onboarding pack</a>
                <a href="{{ route('admin.dashboard') }}" class="vl-back">Admin dashboard</a>
            </div>
        </header>

        @if (session('status'))
            <div class="vl-flash vl-flash-ok" role="status">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="vl-flash vl-flash-err" role="alert">{{ session('error') }}</div>
        @endif

        @if ($engagements->isEmpty())
            <div class="vl-panel vl-empty">
                <p>No volunteer engagements yet.</p>
                <p class="vl-side-note">Start by <a href="{{ route('admin.volunteers.create') }}">extending an offer</a>.</p>
            </div>
        @else
            <div class="vl-panel vl-table-panel">
                <div class="vl-table-scroll">
                    <table class="vl-table">
                        <thead>
                            <tr>
                                <th>Volunteer</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Dates</th>
                                <th>Hours</th>
                                <th>Onboarding</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($engagements as $engagement)
                                @php
                                    $badge = match (true) {
                                        $engagement->status === 'applied'        => ['Applied', 'vl-badge-open'],
                                        $engagement->status === 'offer_declined' => ['Declined', 'vl-badge-muted'],
                                        $engagement->status === 'withdrawn'       => ['Withdrawn', 'vl-badge-muted'],
                                        $engagement->status === 'complete'        => ['Complete', 'vl-badge-done'],
                                        $engagement->isVolunteeringNow()          => ['Active', 'vl-badge-active'],
                                        $engagement->wasAccepted()                => ['Accepted', 'vl-badge-active'],
                                        $engagement->offerHasExpired()            => ['Expired', 'vl-badge-muted'],
                                        default                                   => ['Offer open', 'vl-badge-open'],
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $engagement->user?->name ?? $engagement->offer_name }}</strong>
                                        <span class="vl-cell-sub">{{ $engagement->user?->email ?? $engagement->offer_email }}</span>
                                        @unless ($engagement->user)
                                            <span class="vl-cell-flag">no account yet</span>
                                        @endunless
                                    </td>
                                    <td>{{ $engagement->role->title }}</td>
                                    <td><span class="vl-badge {{ $badge[1] }}">{{ $badge[0] }}</span></td>
                                    <td class="vl-cell-dates">
                                        {{ $engagement->starts_on?->format('j M Y') ?? 'not set' }}
                                        @if ($engagement->ends_on)
                                            <span class="vl-cell-sub">to {{ $engagement->ends_on->format('j M Y') }}</span>
                                        @endif
                                    </td>
                                    <td class="vl-cell-num">
                                        {{ $engagement->hours_sum_hours ? rtrim(rtrim(number_format($engagement->hours_sum_hours, 2), '0'), '.') : '0' }}
                                    </td>
                                    <td>
                                        @if ($engagement->status === 'applied')
                                            {{-- An application has nothing to tick off yet. What it
                                                 needs is a decision, so that is what the cell offers. --}}
                                            <a href="{{ route('admin.volunteers.extend.form', $engagement) }}" class="vl-mini-btn">Read and extend offer</a>
                                        @else
                                        {{-- Inline so the whole roster can be worked through without
                                             opening a row at a time. --}}
                                        <form method="POST" action="{{ route('admin.volunteers.update', $engagement) }}" class="vl-onboard-form">
                                            @csrf
                                            @method('PATCH')
                                            <label title="Volunteer Agreement returned">
                                                <input type="checkbox" name="agreement_signed" value="1" @checked($engagement->agreement_signed_at)> VA
                                            </label>
                                            @if ($engagement->role->requires_nda)
                                                <label title="Non-Disclosure Agreement returned">
                                                    <input type="checkbox" name="nda_signed" value="1" @checked($engagement->nda_signed_at)> NDA
                                                </label>
                                            @endif
                                            @if ($engagement->role->requiresDbs())
                                                <label title="DBS check cleared">
                                                    <input type="checkbox" name="dbs_cleared" value="1" @checked($engagement->dbs_cleared_at)> DBS
                                                </label>
                                            @endif
                                            @if ($engagement->wasAccepted() && $engagement->status !== 'complete')
                                                <label title="Mark this engagement finished">
                                                    <input type="checkbox" name="mark_complete" value="1"> Done
                                                </label>
                                            @endif
                                            <button type="submit" class="vl-mini-btn">Save</button>
                                        </form>
                                        @endif

                                        {{-- Says what goes with it. Hours cascade on
                                             the foreign key, so this is not recoverable. --}}
                                        <form method="POST" action="{{ route('admin.volunteers.destroy', $engagement) }}"
                                              class="vl-remove-form"
                                              onsubmit="return confirm('Remove the {{ addslashes($engagement->role->title) }} engagement for {{ addslashes($engagement->user?->name ?? $engagement->offer_name) }}?\n\nAny logged hours go with it and this cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="vl-mini-btn vl-mini-btn-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="vl-pagination">{{ $engagements->links() }}</div>
        @endif

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
@endpush

@endsection
