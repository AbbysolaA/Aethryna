@extends('layouts.aethryna')

@section('title', 'Raise a safeguarding concern | SkillsCo-op')

@section('content')
<section class="sg-wrap">
    <div class="sg-container">

        <a href="{{ url()->previous() }}" class="sg-back">&larr; Back</a>

        <div class="sg-head">
            <span class="sg-eyebrow">Safeguarding</span>
            <h1>Raise a concern about {{ $learner->name }}</h1>
            <p>This goes to the safeguarding lead for review and decision. You are not expected to judge whether it is serious enough. If you noticed it, write it down.</p>
        </div>

        @if (session('success'))
            <div class="sg-alert sg-alert-ok">
                <i class="fas fa-check-circle"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('warning'))
            <div class="sg-alert sg-alert-warn">
                <i class="fas fa-triangle-exclamation"></i>
                <div>{{ session('warning') }}</div>
            </div>
        @endif

        <div class="sg-emergency">
            <i class="fas fa-phone"></i>
            <div>
                <strong>If someone is in immediate danger, call 999 first.</strong>
                This form is for concerns that need review, not for emergencies in progress.
            </div>
        </div>

        <form method="POST" action="{{ route('safeguarding.store', $learner) }}" class="sg-form">
            @csrf

            <div class="sg-field">
                <label for="urgency">How urgent is this?</label>
                <select name="urgency" id="urgency" required>
                    <option value="routine" {{ old('urgency') === 'routine' ? 'selected' : '' }}>
                        Routine, needs review in the next few days
                    </option>
                    <option value="urgent" {{ old('urgency') === 'urgent' ? 'selected' : '' }}>
                        Urgent, needs attention today
                    </option>
                </select>
                @error('urgency')<p class="sg-error">{{ $message }}</p>@enderror
            </div>

            <div class="sg-field">
                <label for="concern">What have you noticed?</label>
                <p class="sg-hint">Write what you saw or were told, in the words used if you can. Stick to facts rather than conclusions, and include when it happened. Avoid guessing at causes.</p>
                <textarea name="concern" id="concern" rows="9" required minlength="20" maxlength="5000"
                    placeholder="On our call on 14 July, they mentioned...">{{ old('concern') }}</textarea>
                @error('concern')<p class="sg-error">{{ $message }}</p>@enderror
            </div>

            <div class="sg-notice">
                <p><strong>What happens next.</strong> Your concern is recorded immediately with a reference number, then emailed to the safeguarding lead. They decide what action is taken and will come back to you. The record exists whether or not the email is delivered.</p>
            </div>

            <button type="submit" class="sg-submit">
                <i class="fas fa-shield-halved"></i> Send to safeguarding lead
            </button>
        </form>
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
.sg-wrap { padding: 150px 0 90px; background: var(--ath-light); min-height: 70vh; }
.sg-container { max-width: 720px; margin: 0 auto; padding: 0 5%; }

.sg-back {
    display: inline-block;
    font-family: var(--font-mono);
    font-size: 0.82rem;
    color: var(--ath-muted);
    text-decoration: none;
    margin-bottom: 20px;
}
.sg-back:hover { color: var(--ath-teal); }

.sg-head { margin-bottom: 28px; }
.sg-eyebrow {
    display: inline-block;
    font-family: var(--font-mono);
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--ath-gold);
    margin-bottom: 12px;
    padding-left: 12px;
    border-left: 3px solid var(--ath-gold);
}
.sg-head h1 {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(1.7rem, 4vw, 2.3rem);
    font-weight: 800;
    color: var(--ath-deep);
    margin-bottom: 12px;
    line-height: 1.2;
}
.sg-head p { color: var(--ath-muted); line-height: 1.75; font-size: 1.02rem; margin: 0; }

.sg-alert {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 0.96rem;
    line-height: 1.6;
}
.sg-alert-ok { background: rgba(3,139,137,0.08); border: 1px solid rgba(3,139,137,0.3); color: #0a5f5d; }
.sg-alert-ok i { color: var(--ath-teal); }
.sg-alert-warn { background: #fff4e5; border: 1px solid #f0c384; color: #7a4d06; }
.sg-alert-warn i { color: #c17d10; }
.sg-alert i { margin-top: 2px; flex-shrink: 0; }

.sg-emergency {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    background: #fdecea;
    border-left: 4px solid #b3261e;
    border-radius: 0 12px 12px 0;
    padding: 16px 20px;
    margin-bottom: 24px;
    color: #7a1a13;
    font-size: 0.95rem;
    line-height: 1.6;
}
.sg-emergency i { margin-top: 3px; flex-shrink: 0; }
.sg-emergency strong { display: block; margin-bottom: 2px; }

.sg-form {
    background: #fff;
    border: 1px solid rgba(3,139,137,0.12);
    border-radius: 20px;
    padding: 32px;
}
.sg-field { margin-bottom: 24px; }
.sg-field label {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    color: var(--ath-deep);
    margin-bottom: 6px;
    font-size: 1rem;
}
.sg-hint { font-size: 0.89rem; color: var(--ath-muted); line-height: 1.6; margin: 0 0 10px; }
.sg-field select,
.sg-field textarea {
    width: 100%;
    padding: 13px 16px;
    border: 1.5px solid rgba(0,0,0,0.12);
    border-radius: 10px;
    font-size: 0.98rem;
    font-family: inherit;
    color: var(--ath-text);
    background: #fff;
    box-sizing: border-box;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.sg-field select:focus,
.sg-field textarea:focus {
    border-color: var(--ath-teal);
    box-shadow: 0 0 0 4px rgba(3,139,137,0.1);
}
.sg-field textarea { resize: vertical; min-height: 170px; line-height: 1.65; }
.sg-error { color: #b91c1c; font-size: 0.86rem; margin-top: 6px; }

.sg-notice {
    background: var(--ath-light);
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
}
.sg-notice p { margin: 0; font-size: 0.9rem; line-height: 1.7; color: var(--ath-muted); }
.sg-notice strong { color: var(--ath-deep); }

.sg-submit {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 30px;
    background: var(--ath-deep);
    color: #fff;
    border: none;
    border-radius: 100px;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s;
}
.sg-submit:hover { background: var(--ath-teal); transform: translateY(-2px); }

@media (max-width: 640px) {
    .sg-wrap { padding: 120px 0 60px; }
    .sg-form { padding: 24px 20px; }
}
</style>
@endpush
@endsection
