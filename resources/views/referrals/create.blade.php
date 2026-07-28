@extends('layouts.aethryna')

@section('title', 'Refer someone | Skills Co-op')

@section('meta_description', 'If you support someone who could benefit from free digital skills training, refer them to Skills Co-op. Consent-first, gentle follow-up, no pressure on them or on you.')
@section('og_description', 'If you support someone who could benefit from free digital skills training, refer them to Skills Co-op. Consent-first, gentle follow-up, no pressure on them or on you.')

@section('content')

<section class="rf-hero">
    <div class="ath-container">
        <div class="rf-hero-inner">
            <span class="rf-eyebrow">Refer someone you support</span>
            <h1 class="rf-title">Know someone who could <span class="rf-gradient">benefit?</span></h1>
            <p class="rf-lede">If you work with or support someone who could gain from free digital skills training, tell us here. We will follow up gently, with no obligation on them or on you.</p>
        </div>
    </div>
</section>

<section class="rf-form-section">
    <div class="ath-container">
        <form method="POST" action="{{ route('referral.store') }}" class="rf-form" novalidate>
            @csrf
            {{-- Honeypot: hidden from real users; only bots fill this. --}}
            <input type="text" name="company_website" style="position:absolute;left:-9999px;top:-9999px" tabindex="-1" autocomplete="off" aria-hidden="true">

            <div class="rf-block">
                <h2>About you</h2>
                <div class="rf-group">
                    <label for="referrer_name">Your name</label>
                    <input id="referrer_name" name="referrer_name" required value="{{ old('referrer_name') }}">
                    @error('referrer_name')<p class="rf-error">{{ $message }}</p>@enderror
                </div>
                <div class="rf-group">
                    <label for="referrer_email">Your email</label>
                    <input id="referrer_email" name="referrer_email" type="email" required value="{{ old('referrer_email') }}">
                    @error('referrer_email')<p class="rf-error">{{ $message }}</p>@enderror
                </div>
                <div class="rf-row">
                    <div class="rf-group">
                        <label for="referrer_organisation">Your organisation <span class="rf-opt">(optional)</span></label>
                        <input id="referrer_organisation" name="referrer_organisation" value="{{ old('referrer_organisation') }}">
                    </div>
                    <div class="rf-group">
                        <label for="referrer_role">Your role <span class="rf-opt">(optional)</span></label>
                        <input id="referrer_role" name="referrer_role" value="{{ old('referrer_role') }}">
                    </div>
                </div>
            </div>

            <div class="rf-block">
                <h2>About who you are referring</h2>
                <div class="rf-group">
                    <label for="referred_first_name">Their first name</label>
                    <input id="referred_first_name" name="referred_first_name" required value="{{ old('referred_first_name') }}">
                    @error('referred_first_name')<p class="rf-error">{{ $message }}</p>@enderror
                </div>
                <div class="rf-group">
                    <label for="cohort">Which of these best describes them?</label>
                    <select id="cohort" name="cohort">
                        <option value="unsure" {{ old('cohort') === 'unsure' ? 'selected' : '' }}>Not sure yet</option>
                        <option value="neet" {{ old('cohort') === 'neet' ? 'selected' : '' }}>Young person not in education, employment or training</option>
                        <option value="justice" {{ old('cohort') === 'justice' ? 'selected' : '' }}>Rebuilding after contact with the justice system</option>
                        <option value="returner" {{ old('cohort') === 'returner' ? 'selected' : '' }}>Returning to work after a career break or caring</option>
                    </select>
                </div>
                <div class="rf-group">
                    <label for="context">Anything useful for us to know <span class="rf-opt">(optional)</span></label>
                    <textarea id="context" name="context" rows="4" maxlength="1000" placeholder="Their situation, what they are hoping for, anything that helps us respond well.">{{ old('context') }}</textarea>
                </div>
            </div>

            <div class="rf-block rf-consent-block">
                <h2>Contacting them directly</h2>
                <p class="rf-consent-lede">Only complete this section if you have their consent. Otherwise leave it blank and we will reach them through you.</p>
                <div class="rf-group">
                    <label for="referred_contact">Their phone or email <span class="rf-opt">(optional)</span></label>
                    <input id="referred_contact" name="referred_contact" value="{{ old('referred_contact') }}">
                    @error('referred_contact')<p class="rf-error">{{ $message }}</p>@enderror
                </div>
                <label class="rf-consent-check">
                    <input type="checkbox" name="consent_confirmed" value="1" {{ old('consent_confirmed') ? 'checked' : '' }}>
                    <span>I confirm this person knows I am referring them and has agreed to Skills Co-op contacting them.</span>
                </label>
            </div>

            <button type="submit" class="rf-submit">
                <i class="fas fa-paper-plane"></i> Send referral
            </button>

            <p class="rf-privacy-note">
                Referral records are deleted or anonymised twelve months after submission unless the person becomes a learner. See our <a href="{{ route('privacy') }}">privacy policy</a> for the full basis.
            </p>
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
    --ath-navy: #0a2530;
    --ath-light: #F8FBFB;
    --ath-text: #404952;
    --ath-muted: #57616a;
    --font-mono: 'IBM Plex Mono', 'Courier New', monospace;
}
.ath-container { max-width: 1100px; margin: 0 auto; padding: 0 5%; }

.rf-hero { padding: 160px 0 100px; background: var(--ath-deep); color: #fff; position: relative; overflow: hidden; }
.rf-hero::after { content: ''; position: absolute; top: -20%; right: -10%; width: 60%; height: 130%; background: radial-gradient(closest-side, rgba(238,157,29,0.14), transparent 70%); pointer-events: none; }
.rf-hero-inner { max-width: 780px; position: relative; z-index: 1; }
.rf-eyebrow { display: inline-block; font-family: var(--font-mono); font-size: 0.82rem; font-weight: 600; letter-spacing: 3px; text-transform: uppercase; color: var(--ath-gold); margin-bottom: 22px; padding-left: 14px; border-left: 4px solid var(--ath-gold); }
.rf-title { font-family: 'Outfit', sans-serif; font-size: clamp(2.2rem, 5vw, 3.6rem); font-weight: 800; line-height: 1.05; margin-bottom: 22px; }
.rf-gradient { background: linear-gradient(135deg, var(--ath-gold), #fff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.rf-lede { font-size: 1.15rem; line-height: 1.75; opacity: 0.9; max-width: 640px; }

.rf-form-section { padding: 0 0 100px; background: var(--ath-light); }
.rf-form {
    max-width: 720px;
    margin: -60px auto 0;
    background: #fff;
    border: 1px solid rgba(3,139,137,0.1);
    border-radius: 24px;
    padding: 48px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.06);
    position: relative;
    z-index: 2;
}

.rf-block { margin-bottom: 36px; }
.rf-block h2 { font-family: 'Outfit', sans-serif; font-size: 1.15rem; color: var(--ath-deep); font-weight: 800; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid rgba(3,139,137,0.1); }

.rf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.rf-group { margin-bottom: 18px; }
.rf-group label { display: block; font-weight: 700; color: var(--ath-deep); margin-bottom: 8px; font-size: 0.95rem; }
.rf-opt { font-weight: 500; color: var(--ath-muted); font-size: 0.85rem; }
.rf-group input, .rf-group select, .rf-group textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid rgba(0,0,0,0.1);
    border-radius: 10px;
    font-size: 0.98rem;
    font-family: inherit;
    color: var(--ath-text);
    background: #fff;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
    outline: none;
}
.rf-group input:focus, .rf-group select:focus, .rf-group textarea:focus {
    border-color: var(--ath-teal);
    box-shadow: 0 0 0 4px rgba(3,139,137,0.1);
}
.rf-group textarea { resize: vertical; min-height: 100px; }
.rf-error { color: #b91c1c; font-size: 0.85rem; margin-top: 6px; }

.rf-consent-block { background: rgba(238,157,29,0.06); border-left: 4px solid var(--ath-gold); padding: 24px 26px; border-radius: 0 12px 12px 0; }
.rf-consent-block h2 { border-bottom: none; padding-bottom: 0; margin-bottom: 8px; }
.rf-consent-lede { font-size: 0.92rem; color: var(--ath-muted); line-height: 1.65; margin-bottom: 18px; }
.rf-consent-check { display: flex; align-items: flex-start; gap: 12px; font-size: 0.92rem; color: var(--ath-text); line-height: 1.65; margin-top: 6px; cursor: pointer; }
.rf-consent-check input { margin-top: 4px; flex-shrink: 0; accent-color: var(--ath-teal); }

.rf-submit {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 14px 32px;
    background: var(--ath-gold); color: #fff;
    border: none; border-radius: 100px;
    font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1rem;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s;
}
.rf-submit:hover { background: var(--ath-teal); transform: translateY(-2px); }

.rf-privacy-note { font-size: 0.85rem; color: var(--ath-muted); margin-top: 20px; line-height: 1.65; }
.rf-privacy-note a { color: var(--ath-teal); font-weight: 700; text-decoration: none; }
.rf-privacy-note a:hover { color: var(--ath-gold); text-decoration: underline; }

@media (max-width: 640px) {
    .rf-hero { padding: 130px 0 80px; }
    .rf-form { padding: 30px 22px; margin-top: -40px; }
    .rf-row { grid-template-columns: 1fr; gap: 0; }
}
</style>
@endpush

@endsection
