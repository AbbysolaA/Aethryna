@php $editing = $panel->exists; @endphp

@extends('layouts.aethryna')

@section('title', ($editing ? 'Edit panel' : 'Add a panel') . ' | Skills Co-op')

@section('content')

@include('admin._nav')
<section class="vl-engagement">
    <div class="ath-container">

        <header class="vl-engagement-head">
            <span class="vl-eyebrow">The Sessions</span>
            <h1 class="vl-engagement-title">{{ $editing ? 'Edit panel' : 'Add a panel' }}</h1>
            <p class="vl-side-note">
                @if ($editing)
                    Live at <a href="{{ route('sessions.show', $panel) }}" target="_blank" rel="noopener">/sessions/{{ $panel->slug }}</a>. The link is fixed once created, so anything already shared keeps working.
                @else
                    Saving publishes the panel straight away. Leave the date empty if it is not fixed yet — the page says "date to be announced" rather than showing a date nobody has committed to.
                @endif
            </p>
        </header>

        @if (session('status'))
            <div class="vl-flash vl-flash-ok" role="status">{{ session('status') }}</div>
        @endif

        <div class="vl-panel vl-form-panel">
            <form method="POST" action="{{ $editing ? route('admin.panels.update', $panel) : route('admin.panels.store') }}">
                @csrf
                @if ($editing)
                    @method('PATCH')
                @endif

                <div class="vl-field">
                    <label for="tagline">Topic</label>
                    <input id="tagline" name="tagline" required maxlength="255"
                           placeholder="The Data Skills Gap"
                           value="{{ old('tagline', $panel->tagline) }}">
                    <p class="vl-side-note vl-hint">The headline on the page. This is what people see, not the title below.</p>
                    @error('tagline')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="title">Internal title</label>
                    <input id="title" name="title" required maxlength="255"
                           placeholder="The Skills Co-op Sessions: Panel 4"
                           value="{{ old('title', $panel->title) }}">
                    @error('title')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="7" required maxlength="4000"
                              placeholder="What the panel is about and what it will cover.">{{ old('description', $panel->description) }}</textarea>
                    <p class="vl-side-note vl-hint">Blank lines between paragraphs are kept on the page.</p>
                    @error('description')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field-row">
                    <div class="vl-field">
                        <label for="event_date">Date and time <span class="vl-opt">(leave empty if not fixed)</span></label>
                        <input id="event_date" name="event_date" type="datetime-local"
                               value="{{ old('event_date', $panel->event_date?->format('Y-m-d\TH:i')) }}">
                        @error('event_date')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="vl-field">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            @foreach (['upcoming' => 'Upcoming', 'live' => 'Live now', 'past' => 'Past'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $panel->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <p class="vl-side-note vl-hint">Only one panel should be upcoming at a time.</p>
                        @error('status')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="vl-field-row">
                    <div class="vl-field">
                        <label for="format">Format</label>
                        <input id="format" name="format" maxlength="100" placeholder="Online"
                               value="{{ old('format', $panel->format) }}">
                    </div>
                    <div class="vl-field">
                        <label for="duration">Duration</label>
                        <input id="duration" name="duration" maxlength="100" placeholder="60 minutes"
                               value="{{ old('duration', $panel->duration) }}">
                    </div>
                    <div class="vl-field">
                        <label for="sort_order">Panel number</label>
                        <input id="sort_order" name="sort_order" type="number" min="1" required
                               value="{{ old('sort_order', $panel->sort_order) }}">
                        @error('sort_order')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="vl-field">
                    <label for="eventbrite_url">Eventbrite URL <span class="vl-opt">(optional)</span></label>
                    <input id="eventbrite_url" name="eventbrite_url" type="url" maxlength="255"
                           placeholder="https://www.eventbrite.co.uk/e/..."
                           value="{{ old('eventbrite_url', $panel->eventbrite_url) }}">
                    <p class="vl-side-note vl-hint">Offered as an alternative. Registration runs through the site form.</p>
                    @error('eventbrite_url')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="recording_url">Recording URL <span class="vl-opt">(after the event)</span></label>
                    <input id="recording_url" name="recording_url" type="url" maxlength="255"
                           placeholder="https://www.youtube.com/live/..."
                           value="{{ old('recording_url', $panel->recording_url) }}">
                    <p class="vl-side-note vl-hint">Saving this embeds the video on the panel page and in the archive.</p>
                    @error('recording_url')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <fieldset class="vl-field vl-speaker-picker">
                    <legend>Speakers</legend>
                    @if ($speakers->isEmpty())
                        <p class="vl-side-note">No speakers on file yet. <a href="{{ route('admin.speakers.index') }}">Add one first</a>, then come back and tick them here.</p>
                    @else
                        <p class="vl-side-note vl-hint">Tick who spoke, and give each one a topic and a running order. Unticking removes them from this panel; it does not delete the speaker.</p>
                        @foreach ($speakers as $speaker)
                            @php $attached = $panel->speakers->firstWhere('id', $speaker->id); @endphp
                            <div class="vl-speaker-row">
                                <label class="vl-speaker-check">
                                    <input type="checkbox" name="speakers[]" value="{{ $speaker->id }}" @checked($attached)>
                                    <span>
                                        <strong>{{ $speaker->name }}</strong>
                                        <span class="vl-cell-sub">{{ $speaker->title }}@if($speaker->company), {{ $speaker->company }}@endif</span>
                                    </span>
                                </label>
                                <input type="text" name="speaker_topic[{{ $speaker->id }}]" maxlength="255"
                                       placeholder="What they spoke to"
                                       value="{{ old('speaker_topic.' . $speaker->id, $attached?->pivot->topic) }}">
                                <input type="number" name="speaker_order[{{ $speaker->id }}]" min="1"
                                       placeholder="#"
                                       value="{{ old('speaker_order.' . $speaker->id, $attached?->pivot->sort_order) }}">
                            </div>
                        @endforeach
                    @endif
                </fieldset>

                <div class="vl-form-actions">
                    <button type="submit" class="vl-btn vl-btn-primary">{{ $editing ? 'Save panel' : 'Create panel' }}</button>
                    <a href="{{ route('admin.panels.index') }}" class="vl-back">Cancel</a>
                </div>
            </form>
        </div>

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    @include('admin.volunteer-roles._admin-styles')
    <style>
        .vl-field-row { display: flex; flex-wrap: wrap; gap: 20px; }
        .vl-field-row .vl-field { flex: 1 1 200px; }
        .vl-speaker-picker legend { font-weight: 700; margin-bottom: 6px; }
        .vl-speaker-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }
        .vl-speaker-row:last-child { border-bottom: none; }
        .vl-speaker-check { display: flex; align-items: flex-start; gap: 10px; flex: 1 1 260px; cursor: pointer; }
        .vl-speaker-check input { margin-top: 4px; width: 18px; height: 18px; accent-color: var(--ath-teal); }
        .vl-speaker-row input[type="text"] { flex: 2 1 240px; }
        .vl-speaker-row input[type="number"] { flex: 0 0 70px; }
    </style>
@endpush

@endsection
