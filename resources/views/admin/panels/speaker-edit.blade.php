@extends('layouts.aethryna')

@section('title', $speaker->name.' | Speakers | Skills Co-op')

@section('content')

@include('admin._nav')
@include('admin._flash')
<section class="vl-engagement">
    <div class="ath-container">

        <a href="{{ route('admin.speakers.index') }}" class="vl-back">Back to speakers</a>

        <header class="vl-engagement-head vl-admin-head">
            <div>
                <span class="vl-eyebrow">Speaker</span>
                <h1 class="vl-engagement-title">{{ $speaker->name }}</h1>
                <p class="vl-side-note">
                    @if ($speaker->sessions->isNotEmpty())
                        Speaking at {{ $speaker->sessions->pluck('tagline')->join(' and ') }}.
                    @else
                        Not on a panel yet.
                    @endif
                    @if ($speaker->application)
                        Arrived as a pitch:
                        <a href="{{ route('admin.speaker-applications.show', $speaker->application) }}">read it in full</a>.
                    @endif
                </p>
            </div>
            <div class="vl-head-actions">
                <a href="{{ route('admin.speakers.index') }}" class="vl-btn vl-btn-quiet">All speakers</a>
                <a href="{{ route('admin.panels.index') }}" class="vl-btn vl-btn-quiet">Manage panels</a>
            </div>
        </header>

        <div class="vl-panel vl-form-panel">
            <h2 class="vl-sub-heading">Details</h2>
            <form method="POST" action="{{ route('admin.speakers.update', $speaker) }}">
                @csrf
                @method('PATCH')

                <div class="vl-field-row">
                    <div class="vl-field">
                        <label for="name">Name</label>
                        <input id="name" name="name" required maxlength="255" value="{{ old('name', $speaker->name) }}">
                        @error('name')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="vl-field">
                        <label for="title">Role or roles</label>
                        <input id="title" name="title" required maxlength="255" value="{{ old('title', $speaker->title) }}">
                        <p class="vl-side-note vl-hint">Written exactly as it should print. Dual roles are welcome.</p>
                        @error('title')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="vl-field">
                        <label for="company">Company <span class="vl-opt">(optional)</span></label>
                        <input id="company" name="company" maxlength="255" value="{{ old('company', $speaker->company) }}">
                    </div>
                </div>

                <div class="vl-field">
                    <label for="bio">Bio <span class="vl-opt">(optional)</span></label>
                    <textarea id="bio" name="bio" rows="5" maxlength="2000">{{ old('bio', $speaker->bio) }}</textarea>
                    @error('bio')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="linkedin_url">LinkedIn <span class="vl-opt">(optional)</span></label>
                    <input id="linkedin_url" name="linkedin_url" type="url" maxlength="255"
                           value="{{ old('linkedin_url', $speaker->linkedin_url) }}">
                    @error('linkedin_url')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-form-actions">
                    <button type="submit" class="vl-btn vl-btn-primary">Save details</button>
                </div>
            </form>
        </div>

        <div class="vl-panel vl-form-panel">
            <h2 class="vl-sub-heading">Panels</h2>
            @if ($speaker->sessions->isNotEmpty())
                @foreach ($speaker->sessions as $onPanel)
                    <div class="sp-panel-row">
                        <div class="sp-panel-chip">
                            {{ $onPanel->tagline }}
                            <form method="POST" action="{{ route('admin.speakers.detach', [$speaker, $onPanel]) }}"
                                  onsubmit="return confirm('Take {{ addslashes($speaker->name) }} off {{ addslashes($onPanel->tagline) }}? The speaker themselves is kept.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="sp-chip-x" aria-label="Remove from {{ $onPanel->tagline }}">&times;</button>
                            </form>
                        </div>
                        {{-- Posting the attached pair again edits the topic in place. --}}
                        <form method="POST" action="{{ route('admin.speakers.attach', $speaker) }}" class="sp-photo-form">
                            @csrf
                            <input type="hidden" name="panel_session_id" value="{{ $onPanel->id }}">
                            <input type="text" name="topic" maxlength="255" value="{{ $onPanel->pivot->topic }}"
                                   placeholder="Session topic, shown on the panel page">
                            <button type="submit" class="vl-mini-btn">Save topic</button>
                        </form>
                    </div>
                @endforeach
            @else
                <p class="vl-side-note">Not on a panel yet.</p>
            @endif
            @php $attachable = $upcomingPanels->whereNotIn('id', $speaker->sessions->pluck('id')); @endphp
            @if ($attachable->isNotEmpty())
                <form method="POST" action="{{ route('admin.speakers.attach', $speaker) }}" class="sp-photo-form" style="margin-top:12px;">
                    @csrf
                    <select name="panel_session_id" required>
                        @foreach ($attachable as $panel)
                            <option value="{{ $panel->id }}">{{ $panel->tagline }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="topic" maxlength="255" placeholder="Session topic (optional)">
                    <button type="submit" class="vl-btn vl-btn-primary">Add {{ str($speaker->name)->before(' ') }} to this panel</button>
                </form>
            @endif
        </div>

        <div class="vl-panel vl-form-panel">
            <h2 class="vl-sub-heading">Photo</h2>
            @if ($speaker->hasPhotoFile())
                <p style="margin:0 0 12px;">
                    <img src="{{ asset($speaker->photo_path) }}?v={{ @filemtime(public_path($speaker->photo_path)) ?: 0 }}"
                         alt="{{ $speaker->name }}" style="width:120px; height:120px; border-radius:12px; object-fit:cover;">
                </p>
            @else
                <p class="vl-side-note">No photo yet; the panel pages show an initials avatar.</p>
            @endif
            <form method="POST" action="{{ route('admin.speakers.photo', $speaker) }}" enctype="multipart/form-data" class="sp-photo-form">
                @csrf
                <input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp" required>
                <label for="photo_focus" class="vl-side-note" style="margin:0;">Keep which part of a tall photo?</label>
                <select id="photo_focus" name="photo_focus">
                    <option value="middle">The middle (usual for portraits)</option>
                    <option value="top">The top</option>
                    <option value="bottom">The bottom</option>
                </select>
                <button type="submit" class="vl-btn vl-btn-primary">{{ $speaker->hasPhotoFile() ? 'Replace photo' : 'Upload photo' }}</button>
            </form>
            <p class="vl-side-note vl-hint" style="margin-top:8px;">
                Photos are cropped to a square. If a crop comes out wrong, upload the same
                photo again with a different part kept.
            </p>
            @error('photo', 'photo'.$speaker->id)<p class="vl-error">{{ $message }}</p>@enderror
            @if (! $speaker->hasPhotoFile() && $speaker->application?->hasHeadshot())
                <form method="POST" action="{{ route('admin.speakers.photo', $speaker) }}" class="sp-photo-form" style="margin-top:10px;">
                    @csrf
                    <input type="hidden" name="from_pitch" value="1">
                    <button type="submit" class="vl-btn vl-btn-quiet">Use the headshot from their pitch</button>
                </form>
            @endif
        </div>

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
    <style>
        .vl-field-row { display: flex; flex-wrap: wrap; gap: 20px; }
        .vl-field-row .vl-field { flex: 1 1 220px; }
        .vl-sub-heading { font-size: 1.15rem; font-weight: 800; margin-bottom: 18px; color: var(--ath-deep); }
        .sp-photo-form { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
        .sp-panel-chip {
            display: inline-flex; align-items: center; gap: 8px;
            margin: 0 6px 8px 0; padding: 6px 12px; border-radius: 100px;
            background: rgba(3, 139, 137, 0.08); border: 1px solid rgba(3, 139, 137, 0.25);
            font-size: 0.88rem; color: var(--ath-deep, #055860); font-weight: 600;
        }
        .sp-chip-topic { font-weight: 400; color: #5a646c; }
        .sp-panel-row { display: flex; flex-wrap: wrap; gap: 10px 14px; align-items: center; margin-bottom: 12px; }
        .sp-panel-row .sp-photo-form input[type="text"] { min-width: 300px; max-width: 100%; }
        .sp-panel-chip form { display: inline-flex; }
        .sp-chip-x {
            border: none; background: none; cursor: pointer; padding: 0 2px;
            font-size: 1.05rem; line-height: 1; color: #9b2c1c; font-weight: 700;
        }
    </style>
@endpush

@endsection
