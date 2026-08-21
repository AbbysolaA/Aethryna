@extends('layouts.aethryna')

@section('title', 'Apply to speak | Skills Co-op')
@section('meta_description', 'Pitch a talk for a Skills Co-op session. Real routes into digital work, told by people who have walked them. First-time speakers welcome.')
@section('og_title', 'Apply to speak at a Skills Co-op session')
@section('og_description', 'Pitch a talk for our community sessions. First-time speakers welcome, lived experience valued.')

@section('content')

<section class="vl-claim vl-apply-hero">
    <div class="ath-container">
        <div class="vl-claim-inner">
            <span class="vl-eyebrow">Apply to speak</span>
            <h1 class="vl-title">Your story could be <span class="vl-gradient">someone's way in</span></h1>
            <p class="vl-lede">
                Our sessions put real routes into digital work in front of people the industry
                overlooks. If you have walked one of those routes, or you work somewhere our
                learners want to reach, we would like to hear what you would say to them.
                First-time speakers are welcome. Lived experience counts as much as a job title.
            </p>
        </div>
    </div>
</section>

<section class="vl-apply">
    <div class="ath-container">

        {{-- What makes a good pitch, above the form, so nobody writes into a
             void. The cards pull up over the hero, which would bury a visible
             heading beneath them, so the heading exists for the outline and
             screen readers and the cards carry themselves visually. --}}
        <h2 class="vl-sr-only">What we look for</h2>
        <ul class="vl-role-list">
            <li class="vl-role-card">
                <h3>A route someone could copy</h3>
                <p class="vl-role-card-summary">How you got in, what it actually took, and what you would tell someone starting where you started.</p>
            </li>
            <li class="vl-role-card">
                <h3>A corner of the industry, made concrete</h3>
                <p class="vl-role-card-summary">What a week in tech sales, support, data or delivery really looks like, from someone doing it.</p>
            </li>
            <li class="vl-role-card">
                <h3>Plain talk</h3>
                <p class="vl-role-card-summary">Our audience is smart and new to this world. Twenty honest minutes beat a polished keynote.</p>
            </li>
        </ul>

        <div class="vl-panel vl-apply-panel" id="pitch">
            <h2 class="vl-panel-title">Pitch your talk</h2>

            {{-- enctype, or the headshot silently never arrives. --}}
            <form method="POST" action="{{ route('speakers.apply.store') }}"
                  enctype="multipart/form-data" novalidate>
                @csrf

                {{-- Honeypot, same convention as the other public forms. --}}
                <div class="vl-ref-trap" aria-hidden="true">
                    <label for="sp_reference">Reference</label>
                    <input id="sp_reference" name="sp_reference" type="text" tabindex="-1" autocomplete="off">
                </div>

                <div class="vl-field">
                    <label for="name">Full name</label>
                    <input id="name" name="name" required maxlength="255"
                           autocomplete="name" value="{{ old('name') }}">
                    @error('name')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" required maxlength="255"
                           autocomplete="email" value="{{ old('email') }}">
                    @error('email')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="phone">Phone <span class="vl-opt">(optional)</span></label>
                    <input id="phone" name="phone" type="tel" maxlength="40"
                           autocomplete="tel" value="{{ old('phone') }}">
                    @error('phone')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="organisation">Organisation <span class="vl-opt">(optional)</span></label>
                    <input id="organisation" name="organisation" maxlength="255"
                           autocomplete="organization" value="{{ old('organisation') }}">
                    @error('organisation')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="job_title">Role or title <span class="vl-opt">(optional)</span></label>
                    <input id="job_title" name="job_title" maxlength="255"
                           autocomplete="organization-title" value="{{ old('job_title') }}">
                    @error('job_title')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="location">Where you are based <span class="vl-opt">(optional)</span></label>
                    <input id="location" name="location" maxlength="255"
                           placeholder="City and time zone is plenty" value="{{ old('location') }}">
                    @error('location')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="bio">A short bio</label>
                    <textarea id="bio" name="bio" rows="3" required maxlength="2000"
                              placeholder="Who you are and what you do, in your own words. This is what we would introduce you with.">{{ old('bio') }}</textarea>
                    @error('bio')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="linkedin_url">LinkedIn <span class="vl-opt">(optional)</span></label>
                    <input id="linkedin_url" name="linkedin_url" type="url" maxlength="255"
                           placeholder="https://www.linkedin.com/in/you" value="{{ old('linkedin_url') }}">
                    @error('linkedin_url')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="website_url">Website <span class="vl-opt">(optional)</span></label>
                    <input id="website_url" name="website_url" type="url" maxlength="255"
                           placeholder="https://" value="{{ old('website_url') }}">
                    @error('website_url')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="talk_title">Working title of your talk</label>
                    <input id="talk_title" name="talk_title" required maxlength="255"
                           placeholder="It can change. We just need the shape of it."
                           value="{{ old('talk_title') }}">
                    @error('talk_title')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="talk_summary">What you would cover</label>
                    <textarea id="talk_summary" name="talk_summary" rows="5" required maxlength="4000"
                              placeholder="What you would talk about, who it is for, and the one thing you want people to leave with.">{{ old('talk_summary') }}</textarea>
                    @error('talk_summary')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="prior_speaking">Where you have spoken before <span class="vl-opt">(optional)</span></label>
                    <textarea id="prior_speaking" name="prior_speaking" rows="2" maxlength="2000"
                              placeholder="Events, panels, podcasts, a work presentation. None at all is fine, everyone starts somewhere.">{{ old('prior_speaking') }}</textarea>
                    @error('prior_speaking')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="video_url">A link to you speaking <span class="vl-opt">(optional)</span></label>
                    <input id="video_url" name="video_url" type="url" maxlength="255"
                           placeholder="https://" value="{{ old('video_url') }}">
                    @error('video_url')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-field">
                    <label for="headshot">A photo of you <span class="vl-opt">(optional)</span></label>
                    <input id="headshot" name="headshot" type="file"
                           accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                    <p class="vl-side-note vl-hint">
                        JPG, PNG or WebP, up to 5MB. Only used on the session page if your talk
                        goes ahead, and we will check with you first.
                    </p>
                    @error('headshot')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <div class="vl-consent">
                    <label class="vl-check">
                        <input type="checkbox" name="consent" value="1" required @checked(old('consent'))>
                        <span>I am happy for Skills Co-op to hold these details while my pitch is considered. See our <a href="{{ route('privacy') }}">privacy notice</a>.</span>
                    </label>
                    @error('consent')<p class="vl-error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="vl-btn vl-btn-primary">Send my pitch</button>

                <p class="vl-side-note vl-form-note">
                    Sessions are usually online, forty five minutes, with a friendly Q&amp;A.
                    We help every speaker prepare, whether it is your first talk or your fiftieth.
                </p>
            </form>
        </div>

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    <style>
        /* Same page furniture as /volunteer/apply. Those rules live in that
           page's own style block rather than the shared sheet, so the ones
           this page uses are repeated here. */
        .vl-apply-hero { min-height: 0; padding-bottom: 110px; }
        .vl-sr-only {
            position: absolute; width: 1px; height: 1px; margin: -1px;
            padding: 0; overflow: hidden; clip: rect(0 0 0 0); border: 0;
        }
        .vl-apply { padding: 0 0 100px; background: var(--ath-light); }
        .vl-section-title { font-family: 'Outfit', sans-serif; font-size: 1.4rem; font-weight: 800; color: var(--ath-deep); margin: 0 0 20px; }
        .vl-role-list { list-style: none; margin: -60px 0 44px; padding: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; position: relative; z-index: 2; }
        .vl-role-card { background: #fff; border: 1px solid rgba(3,139,137,0.1); border-radius: 18px; padding: 26px 28px; box-shadow: 0 12px 40px rgba(0,0,0,0.05); }
        .vl-role-card h3 { font-family: 'Outfit', sans-serif; font-size: 1.15rem; font-weight: 800; color: var(--ath-deep); margin: 0 0 8px; }
        .vl-role-card-summary { font-size: 0.94rem; line-height: 1.6; color: var(--ath-text); font-weight: 600; margin: 0; }
        .vl-apply-panel { max-width: 760px; }
        .vl-field select, .vl-field textarea {
            width: 100%; padding: 11px 14px; border: 1.5px solid rgba(0,0,0,0.1); border-radius: 10px;
            font-size: 0.95rem; font-family: inherit; color: var(--ath-text); background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box; outline: none;
        }
        .vl-field select:focus, .vl-field textarea:focus { border-color: var(--ath-teal); box-shadow: 0 0 0 4px rgba(3,139,137,0.1); }
        .vl-field textarea { resize: vertical; min-height: 90px; }
        .vl-consent { background: rgba(238,157,29,0.06); border-left: 4px solid var(--ath-gold); padding: 18px 22px; border-radius: 0 12px 12px 0; margin: 22px 0; }
        .vl-consent .vl-check { margin-top: 0; }
        .vl-consent a { color: var(--ath-teal); font-weight: 700; }
        .vl-form-note { margin-top: 16px; }
        @media (max-width: 640px) {
            .vl-role-list { margin-top: -40px; }
        }

        /* The honeypot, moved off the page rather than display:none, which
           some form fillers treat as a cue to skip it. */
        .vl-ref-trap {
            position: absolute; left: -9999px; top: auto;
            width: 1px; height: 1px; overflow: hidden;
        }
    </style>
@endpush

@endsection
