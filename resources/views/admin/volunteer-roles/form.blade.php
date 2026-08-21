@extends('layouts.aethryna')

@php $editing = $role->exists; @endphp

@section('title', ($editing ? 'Edit position' : 'Post a position') . ' | Skills Co-op')

@section('content')

@include('admin._nav')
@include('admin._flash')
<section class="vl-engagement">
    <div class="ath-container">

        <a href="{{ route('admin.volunteer-roles.index') }}" class="vl-back">Back to positions</a>

        <header class="vl-engagement-head">
            <span class="vl-eyebrow">Volunteering</span>
            <h1 class="vl-engagement-title">{{ $editing ? 'Edit position' : 'Post a position' }}</h1>
            <p class="vl-side-note">
                @if ($editing)
                    The web address for this role stays as it is, so any link already shared keeps working.
                @else
                    Open positions are listed on the public application page as soon as you save.
                @endif
            </p>
        </header>

        <div class="vl-panel vl-form-panel">
            <form method="POST" action="{{ $editing ? route('admin.volunteer-roles.update', $role) : route('admin.volunteer-roles.store') }}">
                @csrf
                @if ($editing)
                    @method('PATCH')
                @endif

                {{-- First, because it decides which of the fields below are
                     relevant and where the role gets published. --}}
                <div class="vl-field">
                    <label for="engagement_type">Type of role</label>
                    <select id="engagement_type" name="engagement_type" required>
                        <option value="volunteer" @selected(old('engagement_type', $role->engagement_type ?? 'volunteer') === 'volunteer')>
                            Volunteer, unpaid
                        </option>
                        <option value="employee" @selected(old('engagement_type', $role->engagement_type) === 'employee')>
                            Employee, paid
                        </option>
                        <option value="contractor" @selected(old('engagement_type', $role->engagement_type) === 'contractor')>
                            Contractor, paid
                        </option>
                    </select>
                    <p class="vl-side-note vl-hint">
                        Volunteer roles appear on the volunteer application page and use that form.
                        Paid roles appear at <strong>/careers</strong> with their own page, and applicants
                        email the address you set below rather than filling in the volunteer form.
                    </p>
                    @error('engagement_type')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="title">Title</label>
                    <input id="title" name="title" required maxlength="255"
                           placeholder="Volunteer Project Manager"
                           value="{{ old('title', $role->title) }}">
                    @error('title')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="summary">One-line summary</label>
                    <input id="summary" name="summary" required maxlength="255"
                           placeholder="Delivery planning and coordination across the programme."
                           value="{{ old('summary', $role->summary) }}">
                    <p class="vl-side-note vl-hint">Shown under the title on the application page and the roster.</p>
                    @error('summary')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="description">Full description <span class="vl-opt">(optional)</span></label>
                    <textarea id="description" name="description" rows="5" maxlength="4000"
                              placeholder="What the role actually involves, who they would work with, and anything someone should know before applying.">{{ old('description', $role->description) }}</textarea>
                    @error('description')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                {{-- Employment facts. Left blank on a volunteer role, where the
                     public page never asks for them. --}}
                <fieldset class="vl-field" style="border:1px solid #dfe6e6;border-radius:10px;padding:18px 20px;">
                    <legend style="font-weight:700;color:#055860;padding:0 8px;">Paid roles only</legend>
                    <p class="vl-side-note vl-hint" style="margin-top:0;">
                        Ignore this section for a volunteer role. Anything left blank is simply
                        left off the public page rather than shown empty.
                    </p>

                    <div class="vl-field">
                        <label for="compensation">Salary or rate</label>
                        <input id="compensation" name="compensation" maxlength="255"
                               placeholder="£32,000 per year"
                               value="{{ old('compensation', $role->compensation) }}">
                        <p class="vl-side-note vl-hint">
                            Free text, so "£180 per day" and "Salary under review" both work.
                            Include the currency and the period: a bare number is read as whatever
                            the reader assumes. Leave blank to omit it entirely.
                        </p>
                        @error('compensation')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="vl-field">
                        <label for="employment_basis">Basis</label>
                        <input id="employment_basis" name="employment_basis" maxlength="255"
                               placeholder="Full-time"
                               value="{{ old('employment_basis', $role->employment_basis) }}">
                        <p class="vl-side-note vl-hint">"Full-time", "Part-time" and "Contract" are understood by job boards.</p>
                    </div>

                    <div class="vl-field">
                        <label for="location">Location</label>
                        <input id="location" name="location" maxlength="255"
                               placeholder="Remote first, UK-adjacent time zones"
                               value="{{ old('location', $role->location) }}">
                        <p class="vl-side-note vl-hint">Say "remote" if it is, and the listing declares it properly to search engines.</p>
                    </div>

                    <div class="vl-field">
                        <label for="reports_to">Reports to</label>
                        <input id="reports_to" name="reports_to" maxlength="255"
                               placeholder="Founder"
                               value="{{ old('reports_to', $role->reports_to) }}">
                    </div>

                    <div class="vl-field">
                        <label for="apply_email">Applications go to</label>
                        <input id="apply_email" name="apply_email" type="email" maxlength="255"
                               placeholder="hr@skillscoop.org"
                               value="{{ old('apply_email', $role->apply_email) }}">
                        <p class="vl-side-note vl-hint">Without this there is no way to apply, so the page shows no apply button at all.</p>
                        @error('apply_email')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="vl-field">
                        <label for="apply_instructions">What to send</label>
                        <textarea id="apply_instructions" name="apply_instructions" rows="3" maxlength="1000"
                                  placeholder="Send your CV and a short portfolio, with a subject line telling us why this role fits you.">{{ old('apply_instructions', $role->apply_instructions) }}</textarea>
                    </div>

                    <div class="vl-field">
                        <label for="closes_at">Closing date <span class="vl-opt">(optional)</span></label>
                        <input id="closes_at" name="closes_at" type="date"
                               value="{{ old('closes_at', $role->closes_at?->format('Y-m-d')) }}">
                        <p class="vl-side-note vl-hint">
                            The listing takes itself down the day after. Leave blank to recruit
                            until you find the right person.
                        </p>
                        @error('closes_at')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>
                </fieldset>

                <div class="vl-field">
                    <label for="grants_access">Access granted on acceptance</label>
                    <select id="grants_access" name="grants_access" required>
                        <option value="volunteer" @selected(old('grants_access', $role->grants_access) === 'volunteer')>
                            Volunteer, no learner-facing access
                        </option>
                        <option value="mentor" @selected(old('grants_access', $role->grants_access) === 'mentor')>
                            Mentor, opens the mentor area and learner records
                        </option>
                    </select>
                    <p class="vl-side-note vl-hint">Mentor access always requires a cleared DBS, whatever the box below says.</p>
                    @error('grants_access')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-toggles">
                    <label class="vl-check">
                        <input type="checkbox" name="requires_dbs" value="1" @checked(old('requires_dbs', $role->requires_dbs))>
                        <span><strong>Requires a DBS check.</strong> Tick for anything involving contact with learners or prospective learners.</span>
                    </label>
                    <label class="vl-check">
                        <input type="checkbox" name="requires_nda" value="1" @checked(old('requires_nda', $role->requires_nda))>
                        <span><strong>Requires a signed NDA.</strong> Tick for anything touching learner data or unpublished plans.</span>
                    </label>
                    <label class="vl-check">
                        <input type="checkbox" name="is_open" value="1" @checked(old('is_open', $role->is_open))>
                        <span><strong>Open for applications.</strong> Untick to take it off the public page without affecting anyone already engaged.</span>
                    </label>
                </div>

                <button type="submit" class="vl-btn vl-btn-primary">{{ $editing ? 'Save changes' : 'Post the position' }}</button>
                <a href="{{ route('admin.volunteer-roles.index') }}" class="vl-back">Cancel</a>
            </form>
        </div>

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
    <style>
        .vl-hint { margin-top: 7px; font-size: 0.84rem; }
        .vl-toggles .vl-check { margin-top: 0; align-items: flex-start; }
        .vl-toggles .vl-check strong { color: var(--ath-deep); }
    </style>
@endpush

@endsection
