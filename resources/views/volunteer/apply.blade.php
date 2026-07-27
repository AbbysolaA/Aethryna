@extends('layouts.aethryna')

@section('title', 'Volunteer with us | SkillsCo-op')

@section('meta_description', 'Volunteer with SkillsCo-op. Mentoring, delivery, outreach and more, supporting people the traditional pipeline was never built for.')
@section('og_description', 'Volunteer with SkillsCo-op. Mentoring, delivery, outreach and more, supporting people the traditional pipeline was never built for.')

@section('content')

<section class="vl-claim vl-apply-hero">
    <div class="ath-container">
        <div class="vl-claim-inner">
            <span class="vl-eyebrow">Volunteer with us</span>
            <h1 class="vl-title">Give some time to a <span class="vl-gradient">cohort that needs it</span></h1>
            <p class="vl-lede">Our founding cohort starts in January 2027. Whether you can mentor one learner or hold a whole workstream, there is a way in. Tell us what you can give and we will take it from there.</p>
        </div>
    </div>
</section>

<section class="vl-apply">
    <div class="ath-container">

        @if (session('error'))
            <div class="vl-flash vl-flash-err" role="alert">{{ session('error') }}</div>
        @endif

        @if ($roles->isEmpty())
            <div class="vl-panel vl-empty vl-apply-panel">
                <p>We are not recruiting for any roles at the moment.</p>
                <p class="vl-side-note">
                    Write to <a href="mailto:hello@skillscoop.org">hello@skillscoop.org</a> and we will let you know when that changes.
                </p>
            </div>
        @else

            {{-- Open roles. Listed above the form so someone can see what is
                 actually going before deciding to fill anything in. --}}
            <h2 class="vl-section-title">Open roles</h2>
            <ul class="vl-role-list">
                @foreach ($roles as $role)
                    <li class="vl-role-card">
                        <h3>{{ $role->title }}</h3>
                        <p class="vl-role-card-summary">{{ $role->summary }}</p>
                        @if ($role->description)
                            <p class="vl-role-card-desc">{{ $role->description }}</p>
                        @endif
                        <div class="vl-role-tags">
                            @if ($role->requiresDbs())
                                <span class="vl-tag">DBS check required</span>
                            @endif
                            @if ($role->requires_nda)
                                <span class="vl-tag vl-tag-quiet">NDA required</span>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- Form --}}
            <div class="vl-panel vl-apply-panel" id="apply">
                <h2 class="vl-panel-title">Put yourself forward</h2>

                <form method="POST" action="{{ route('volunteer.apply.store') }}" novalidate>
                    @csrf

                    {{-- Honeypot: hidden from real users; only bots fill this. --}}
                    <input type="text" name="company_website" style="position:absolute;left:-9999px;top:-9999px" tabindex="-1" autocomplete="off" aria-hidden="true">

                    <div class="vl-field">
                        <label for="volunteer_role_id">Which role?</label>
                        <select id="volunteer_role_id" name="volunteer_role_id" required>
                            <option value="">Choose a role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    @selected(old('volunteer_role_id', $selected) == $role->id || old('volunteer_role_id', $selected) === $role->slug)>
                                    {{ $role->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('volunteer_role_id')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="vl-field-row vl-field-row-even">
                        <div class="vl-field">
                            <label for="name">Your name</label>
                            <input id="name" name="name" required value="{{ old('name') }}">
                            @error('name')<p class="vl-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="vl-field">
                            <label for="email">Your email</label>
                            <input id="email" name="email" type="email" required value="{{ old('email') }}">
                            @error('email')<p class="vl-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="vl-field">
                        <label for="phone">Phone <span class="vl-opt">(optional)</span></label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}">
                        @error('phone')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="vl-field">
                        <label for="about">Why this role?</label>
                        <textarea id="about" name="about" rows="4" maxlength="2000" required
                                  placeholder="What draws you to it, and what you would want to get out of it.">{{ old('about') }}</textarea>
                        @error('about')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="vl-field">
                        <label for="availability">How much time can you give?</label>
                        <input id="availability" name="availability" required maxlength="255"
                               placeholder="A couple of hours a fortnight, Tuesday evenings, that sort of thing."
                               value="{{ old('availability') }}">
                        @error('availability')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="vl-field">
                        <label for="experience">Relevant experience <span class="vl-opt">(optional)</span></label>
                        <textarea id="experience" name="experience" rows="3" maxlength="2000"
                                  placeholder="Anything that feels relevant. Lived experience counts as much as professional experience here.">{{ old('experience') }}</textarea>
                        @error('experience')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="vl-consent">
                        <label class="vl-check">
                            <input type="checkbox" name="consent" value="1" required @checked(old('consent'))>
                            <span>I am happy for SkillsCo-op to hold these details while my application is considered. See our <a href="{{ route('privacy') }}">privacy notice</a>.</span>
                        </label>
                        @error('consent')<p class="vl-error">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="vl-btn vl-btn-primary">Send my application</button>

                    <p class="vl-side-note vl-form-note">
                        Applying does not commit you to anything. We read every one and come back to you either way.
                    </p>
                </form>
            </div>
        @endif

    </div>
</section>

@push('styles')
    @include('volunteer._styles')
    <style>
        .vl-apply-hero { min-height: 0; padding-bottom: 110px; }
        .vl-apply { padding: 0 0 100px; background: var(--ath-light); }
        .vl-section-title { font-family: 'Outfit', sans-serif; font-size: 1.4rem; font-weight: 800; color: var(--ath-deep); margin: 0 0 20px; }
        .vl-role-list { list-style: none; margin: -60px 0 44px; padding: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; position: relative; z-index: 2; }
        .vl-role-card { background: #fff; border: 1px solid rgba(3,139,137,0.1); border-radius: 18px; padding: 26px 28px; box-shadow: 0 12px 40px rgba(0,0,0,0.05); }
        .vl-role-card h3 { font-family: 'Outfit', sans-serif; font-size: 1.1rem; font-weight: 800; color: var(--ath-navy); margin: 0 0 8px; }
        .vl-role-card-summary { font-size: 0.94rem; line-height: 1.6; color: var(--ath-deep); font-weight: 600; margin: 0 0 10px; }
        .vl-role-card-desc { font-size: 0.89rem; line-height: 1.65; color: var(--ath-muted); margin: 0 0 14px; }
        .vl-role-tags { display: flex; flex-wrap: wrap; gap: 8px; }
        .vl-tag { display: inline-block; padding: 4px 12px; border-radius: 100px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.4px; text-transform: uppercase; background: rgba(238,157,29,0.16); color: #8a5a06; }
        .vl-tag-quiet { background: rgba(0,0,0,0.06); color: var(--ath-muted); }
        .vl-apply-panel { max-width: 760px; }
        .vl-field-row-even { grid-template-columns: 1fr 1fr; }
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
            .vl-field-row-even { grid-template-columns: 1fr; }
        }
    </style>
@endpush

@endsection
