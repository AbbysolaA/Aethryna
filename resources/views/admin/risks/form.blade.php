@extends('layouts.aethryna')

@php $isEdit = (bool) $risk->exists; @endphp

@section('title', ($isEdit ? 'Edit R-' . $risk->id : 'Add a risk') . ' | Skills Co-op')

@section('content')

@include('admin._nav')
@include('admin._flash')
<section class="rkf-wrap">
    <div class="rkf-container">

        <a href="{{ route('admin.risks.index') }}" class="rkf-back">&larr; Risk register</a>

        <div class="rkf-head">
            <span class="rkf-eyebrow">{{ $isEdit ? 'R-' . $risk->id : 'New entry' }}</span>
            <h1>{{ $isEdit ? 'Edit this risk' : 'Add a risk to the register' }}</h1>
            @if ($isEdit && $risk->last_reviewed_at)
                <p>Last reviewed {{ $risk->last_reviewed_at->timezone('Europe/London')->format('j F Y') }}
                   by {{ $risk->lastReviewedBy?->name ?? 'unknown' }}. Saving marks it reviewed again today.</p>
            @else
                <p>Score the risk as it stands today, then again after mitigation. The residual score is the one that drives the register.</p>
            @endif
        </div>

        @if ($errors->any())
            <div class="rkf-errors">
                <strong>Check the following:</strong>
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ $isEdit ? route('admin.risks.update', $risk) : route('admin.risks.store') }}" class="rkf-form">
            @csrf
            @if ($isEdit) @method('PATCH') @endif

            <div class="rkf-field">
                <label for="title">Risk</label>
                <p class="rkf-hint">Write it as something that could happen, not as a topic. "Not enough mentors confirmed for Cohort 1" rather than "mentors".</p>
                <input type="text" name="title" id="title" required maxlength="200"
                       value="{{ old('title', $risk->title) }}"
                       placeholder="Insufficient mentors confirmed before Cohort 1 begins">
            </div>

            <div class="rkf-row">
                <div class="rkf-field">
                    <label for="category">Category</label>
                    <select name="category" id="category" required>
                        @foreach (\App\Models\Risk::CATEGORIES as $key => $label)
                            <option value="{{ $key }}" {{ old('category', $risk->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="rkf-field">
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        @foreach (\App\Models\Risk::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ old('status', $risk->status ?: 'open') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="rkf-field">
                <label for="description">What could happen, and why</label>
                <textarea name="description" id="description" rows="4" maxlength="5000"
                          placeholder="If mentor recruitment does not close by November, learners start the specialised phase without one to one support, which is the part of the model most likely to affect completion.">{{ old('description', $risk->description) }}</textarea>
            </div>

            <fieldset class="rkf-scores">
                <legend>Inherent score, before mitigation</legend>
                <div class="rkf-row">
                    <div class="rkf-field">
                        <label for="likelihood">Likelihood</label>
                        <select name="likelihood" id="likelihood" required>
                            @foreach (\App\Models\Risk::SCALE as $n => $label)
                                <option value="{{ $n }}" {{ (int) old('likelihood', $risk->likelihood ?: 3) === $n ? 'selected' : '' }}>{{ $n }}. {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="rkf-field">
                        <label for="impact">Impact</label>
                        <select name="impact" id="impact" required>
                            @foreach (\App\Models\Risk::SCALE as $n => $label)
                                <option value="{{ $n }}" {{ (int) old('impact', $risk->impact ?: 3) === $n ? 'selected' : '' }}>{{ $n }}. {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </fieldset>

            <div class="rkf-field">
                <label for="mitigation">What are we doing about it</label>
                <p class="rkf-hint">The controls already in place or planned. If this is empty, the risk is unmanaged.</p>
                <textarea name="mitigation" id="mitigation" rows="4" maxlength="5000"
                          placeholder="Mentor page live, outreach through panel speakers, target of fifteen confirmed by end of October with a fallback of group mentoring.">{{ old('mitigation', $risk->mitigation) }}</textarea>
            </div>

            <fieldset class="rkf-scores">
                <legend>Residual score, after mitigation <span class="rkf-optional">optional until mitigation is in place</span></legend>
                <div class="rkf-row">
                    <div class="rkf-field">
                        <label for="residual_likelihood">Likelihood</label>
                        <select name="residual_likelihood" id="residual_likelihood">
                            <option value="">Not scored yet</option>
                            @foreach (\App\Models\Risk::SCALE as $n => $label)
                                <option value="{{ $n }}" {{ (int) old('residual_likelihood', $risk->residual_likelihood) === $n ? 'selected' : '' }}>{{ $n }}. {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="rkf-field">
                        <label for="residual_impact">Impact</label>
                        <select name="residual_impact" id="residual_impact">
                            <option value="">Not scored yet</option>
                            @foreach (\App\Models\Risk::SCALE as $n => $label)
                                <option value="{{ $n }}" {{ (int) old('residual_impact', $risk->residual_impact) === $n ? 'selected' : '' }}>{{ $n }}. {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </fieldset>

            <div class="rkf-row">
                <div class="rkf-field">
                    <label for="owner">Owner</label>
                    <p class="rkf-hint">A named person, not a team.</p>
                    <input type="text" name="owner" id="owner" maxlength="120"
                           value="{{ old('owner', $risk->owner) }}" placeholder="Abby Areola">
                </div>
                <div class="rkf-field">
                    <label for="review_due">Next review due</label>
                    <p class="rkf-hint">Quarterly is a reasonable default.</p>
                    <input type="date" name="review_due" id="review_due"
                           value="{{ old('review_due', $risk->review_due?->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="rkf-actions">
                <button type="submit" class="rkf-submit">{{ $isEdit ? 'Save and mark reviewed' : 'Add to register' }}</button>
                <a href="{{ route('admin.risks.index') }}" class="rkf-cancel">Cancel</a>
            </div>
        </form>

        @if ($isEdit)
            <form method="POST" action="{{ route('admin.risks.destroy', $risk) }}" class="rkf-delete"
                  onsubmit="return confirm('Remove R-{{ $risk->id }} from the register? Closing it instead keeps the history.');">
                @csrf
                @method('DELETE')
                <button type="submit">Delete this risk</button>
                <span>Prefer setting the status to Closed, which keeps the record.</span>
            </form>
        @endif
    </div>
</section>

@push('styles')
<link href="https://fonts.bunny.net/css?family=ibm-plex-mono:500,600&display=swap" rel="stylesheet">
<style>
:root {
    --ath-teal: #038b89;
    --ath-gold: #ee9d1d;
    --ath-deep: #055860;
    --ath-light: #F8FBFB;
    --ath-text: #404952;
    --ath-muted: #57616a;
    --font-mono: 'IBM Plex Mono', 'Courier New', monospace;
}
.rkf-wrap { padding: 150px 0 90px; background: var(--ath-light); min-height: 80vh; }
.rkf-container { max-width: 760px; margin: 0 auto; padding: 0 5%; }

.rkf-back { display: inline-block; font-family: var(--font-mono); font-size: 0.82rem; color: var(--ath-muted); text-decoration: none; margin-bottom: 18px; }
.rkf-back:hover { color: var(--ath-teal); }

.rkf-head { margin-bottom: 24px; }
.rkf-eyebrow {
    display: inline-block; font-family: var(--font-mono); font-size: 0.78rem;
    font-weight: 600; letter-spacing: 3px; text-transform: uppercase;
    color: var(--ath-gold); margin-bottom: 10px; padding-left: 12px;
    border-left: 3px solid var(--ath-gold);
}
.rkf-head h1 { font-family: 'Outfit', sans-serif; font-size: clamp(1.6rem,3.6vw,2.1rem); font-weight: 800; color: var(--ath-deep); margin: 0 0 8px; }
.rkf-head p { color: var(--ath-muted); margin: 0; font-size: 0.97rem; line-height: 1.65; }

.rkf-errors {
    background: #fdecea; border: 1px solid #f0b4ae; color: #7a1a13;
    padding: 16px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 0.93rem;
}
.rkf-errors ul { margin: 8px 0 0; padding-left: 20px; }

.rkf-form { background: #fff; border: 1px solid rgba(0,0,0,0.07); border-radius: 20px; padding: 30px; }
.rkf-field { margin-bottom: 20px; flex: 1; min-width: 0; }
.rkf-row { display: flex; gap: 18px; flex-wrap: wrap; }
.rkf-field label { display: block; font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--ath-deep); margin-bottom: 5px; font-size: 0.97rem; }
.rkf-hint { font-size: 0.86rem; color: var(--ath-muted); line-height: 1.55; margin: 0 0 8px; }
.rkf-field input, .rkf-field select, .rkf-field textarea {
    width: 100%; padding: 12px 15px;
    border: 1.5px solid rgba(0,0,0,0.12); border-radius: 10px;
    font-size: 0.96rem; font-family: inherit; color: var(--ath-text);
    background: #fff; box-sizing: border-box; outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.rkf-field input:focus, .rkf-field select:focus, .rkf-field textarea:focus {
    border-color: var(--ath-teal); box-shadow: 0 0 0 4px rgba(3,139,137,0.1);
}
.rkf-field textarea { resize: vertical; line-height: 1.6; }

.rkf-scores {
    border: 1px solid rgba(3,139,137,0.18); border-radius: 14px;
    padding: 18px 20px 2px; margin: 0 0 22px;
}
.rkf-scores legend {
    font-family: var(--font-mono); font-size: 0.73rem; font-weight: 600;
    letter-spacing: 1.4px; text-transform: uppercase; color: var(--ath-teal);
    padding: 0 8px;
}
.rkf-optional { text-transform: none; letter-spacing: 0; color: var(--ath-muted); font-weight: 500; }

.rkf-actions { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; margin-top: 6px; }
.rkf-submit {
    display: inline-flex; align-items: center; gap: 9px;
    padding: 13px 28px; background: var(--ath-deep); color: #fff;
    border: none; border-radius: 100px;
    font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.98rem;
    cursor: pointer; transition: background 0.2s, transform 0.2s;
}
.rkf-submit:hover { background: var(--ath-teal); transform: translateY(-2px); }
.rkf-cancel { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.93rem; color: var(--ath-muted); text-decoration: none; }
.rkf-cancel:hover { color: var(--ath-deep); }

.rkf-delete { margin-top: 22px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.rkf-delete button {
    background: none; border: none; padding: 0; cursor: pointer;
    color: #b3261e; font-family: inherit; font-size: 0.88rem; font-weight: 700;
    text-decoration: underline;
}
.rkf-delete span { font-size: 0.84rem; color: var(--ath-muted); }

@media (max-width: 640px) {
    .rkf-wrap { padding: 120px 0 60px; }
    .rkf-form { padding: 24px 20px; }
    .rkf-row { flex-direction: column; gap: 0; }
}
</style>
@endpush
@endsection
