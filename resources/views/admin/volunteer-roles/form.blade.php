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
