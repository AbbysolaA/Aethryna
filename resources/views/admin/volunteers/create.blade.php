@extends('layouts.aethryna')

@section('title', 'Extend a volunteer offer | Skills Co-op')

@section('content')
<section class="vl-engagement">
    <div class="ath-container">

        <a href="{{ route('admin.volunteers.index') }}" class="vl-back">Back to the roster</a>

        <header class="vl-engagement-head">
            <span class="vl-eyebrow">Volunteering</span>
            <h1 class="vl-engagement-title">Extend an offer</h1>
            <p class="vl-side-note">Sends the offer email with a single-use link. The person does not need an account, they can create one when they respond.</p>
        </header>

        @if (session('error'))
            <div class="vl-flash vl-flash-err" role="alert">{{ session('error') }}</div>
        @endif

        <div class="vl-panel vl-form-panel">
            <form method="POST" action="{{ route('admin.volunteers.store') }}">
                @csrf

                <div class="vl-field">
                    <label for="volunteer_role_id">Role</label>
                    <select id="volunteer_role_id" name="volunteer_role_id" required>
                        <option value="">Choose a role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @selected(old('volunteer_role_id') == $role->id)>
                                {{ $role->title }}@if ($role->grants_access === 'mentor') (grants mentor access)@endif
                            </option>
                        @endforeach
                    </select>
                    @error('volunteer_role_id')<p class="vl-error">{{ $message }}</p>@enderror
                    @if ($roles->isEmpty())
                        <p class="vl-error">No open roles. Seed or open a role before extending offers.</p>
                    @endif
                </div>

                <div class="vl-field-row vl-field-row-even">
                    <div class="vl-field">
                        <label for="offer_name">Their name</label>
                        <input id="offer_name" name="offer_name" required value="{{ old('offer_name') }}">
                        @error('offer_name')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="vl-field">
                        <label for="offer_email">Their email</label>
                        <input id="offer_email" name="offer_email" type="email" required value="{{ old('offer_email') }}">
                        @error('offer_email')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="vl-field-row vl-field-row-even">
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
                </div>

                <div class="vl-field">
                    <label for="response_days">Days to respond <span class="vl-opt">(defaults to {{ config('volunteering.offer_response_days', 14) }})</span></label>
                    <input id="response_days" name="response_days" type="number" min="1" max="90" value="{{ old('response_days') }}">
                    @error('response_days')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="notes">Internal notes <span class="vl-opt">(optional, not sent to them)</span></label>
                    <textarea id="notes" name="notes" rows="3" maxlength="2000">{{ old('notes') }}</textarea>
                    @error('notes')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="vl-btn vl-btn-primary">Send the offer</button>
            </form>
        </div>

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    <style>
        .vl-form-panel { max-width: 720px; }
        .vl-field-row-even { grid-template-columns: 1fr 1fr; }
        .vl-field select, .vl-field textarea {
            width: 100%; padding: 11px 14px; border: 1.5px solid rgba(0,0,0,0.1); border-radius: 10px;
            font-size: 0.95rem; font-family: inherit; color: var(--ath-text); background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box; outline: none;
        }
        .vl-field select:focus, .vl-field textarea:focus { border-color: var(--ath-teal); box-shadow: 0 0 0 4px rgba(3,139,137,0.1); }
        .vl-field textarea { resize: vertical; min-height: 84px; }
        @media (max-width: 640px) { .vl-field-row-even { grid-template-columns: 1fr; } }
    </style>
@endpush

@endsection
