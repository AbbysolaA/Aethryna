@extends('layouts.aethryna')

@section('title', 'Speakers | Skills Co-op')

@section('content')
<section class="vl-engagement">
    <div class="ath-container">

        <header class="vl-engagement-head vl-admin-head">
            <div>
                <span class="vl-eyebrow">The Sessions</span>
                <h1 class="vl-engagement-title">Speakers</h1>
                <p class="vl-side-note">Speakers live here rather than on a panel because people come back across panels. Add someone once, then tick them on whichever panels they speak at.</p>
            </div>
            <div class="vl-head-actions">
                <a href="{{ route('admin.panels.index') }}" class="vl-back">Panels</a>
                <a href="{{ route('admin.registrations.index') }}" class="vl-back">Registrations</a>
            </div>
        </header>

        @if (session('status'))
            <div class="vl-flash vl-flash-ok" role="status">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="vl-flash vl-flash-err" role="alert">{{ session('error') }}</div>
        @endif

        <div class="vl-panel vl-form-panel">
            <h2 class="vl-sub-heading">Add a speaker</h2>
            <form method="POST" action="{{ route('admin.speakers.store') }}">
                @csrf

                <div class="vl-field-row">
                    <div class="vl-field">
                        <label for="name">Name</label>
                        <input id="name" name="name" required maxlength="255" placeholder="Bola Soko"
                               value="{{ old('name') }}">
                        @error('name')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="vl-field">
                        <label for="title">Job title</label>
                        <input id="title" name="title" required maxlength="255" placeholder="Procurement Leader &amp; Founder"
                               value="{{ old('title') }}">
                        <p class="vl-side-note vl-hint">Role only. The employer goes in the next field, or it prints twice.</p>
                        @error('title')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="vl-field">
                        <label for="company">Company <span class="vl-opt">(optional)</span></label>
                        <input id="company" name="company" maxlength="255" placeholder="Women in AI &amp; Automation"
                               value="{{ old('company') }}">
                    </div>
                </div>

                <div class="vl-field">
                    <label for="bio">Bio <span class="vl-opt">(optional)</span></label>
                    <textarea id="bio" name="bio" rows="4" maxlength="2000"
                              placeholder="One paragraph, in their own words where possible.">{{ old('bio') }}</textarea>
                    @error('bio')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field-row">
                    <div class="vl-field">
                        <label for="photo_path">Photo path <span class="vl-opt">(optional)</span></label>
                        <input id="photo_path" name="photo_path" maxlength="255"
                               placeholder="images/speakers/bola-soko.jpg" value="{{ old('photo_path') }}">
                        <p class="vl-side-note vl-hint">Upload the file to public/images/speakers first. Until it exists they get an initials avatar, not a broken image.</p>
                    </div>
                    <div class="vl-field">
                        <label for="linkedin_url">LinkedIn <span class="vl-opt">(optional)</span></label>
                        <input id="linkedin_url" name="linkedin_url" type="url" maxlength="255"
                               placeholder="https://www.linkedin.com/in/..." value="{{ old('linkedin_url') }}">
                        @error('linkedin_url')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="vl-form-actions">
                    <button type="submit" class="vl-btn vl-btn-primary">Add speaker</button>
                </div>
            </form>
        </div>

        @if ($speakers->isNotEmpty())
            <div class="vl-panel vl-table-panel">
                <div class="vl-table-scroll">
                    <table class="vl-table">
                        <thead>
                            <tr>
                                <th>Speaker</th>
                                <th>Role</th>
                                <th>Panels</th>
                                <th>Photo</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($speakers as $speaker)
                                <tr>
                                    <td><strong>{{ $speaker->name }}</strong></td>
                                    <td>
                                        {{ $speaker->title }}
                                        @if ($speaker->company)<span class="vl-cell-sub">{{ $speaker->company }}</span>@endif
                                    </td>
                                    <td class="vl-cell-num">{{ $speaker->sessions_count }}</td>
                                    <td>
                                        @if (str_contains($speaker->photoUrl(), 'ui-avatars'))
                                            <span class="vl-cell-sub">Initials avatar</span>
                                        @else
                                            <span class="vl-cell-sub">Photo set</span>
                                        @endif
                                    </td>
                                    <td class="vl-cell-actions">
                                        @if ($speaker->sessions_count === 0)
                                            <form method="POST" action="{{ route('admin.speakers.destroy', $speaker) }}"
                                                  onsubmit="return confirm('Delete {{ addslashes($speaker->name) }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="vl-btn vl-btn-small vl-btn-danger">Delete</button>
                                            </form>
                                        @else
                                            <span class="vl-cell-sub">On a panel</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
    <style>
        .vl-field-row { display: flex; flex-wrap: wrap; gap: 20px; }
        .vl-field-row .vl-field { flex: 1 1 220px; }
        .vl-sub-heading { font-size: 1.15rem; font-weight: 800; margin-bottom: 18px; color: var(--ath-deep); }
    </style>
@endpush

@endsection
