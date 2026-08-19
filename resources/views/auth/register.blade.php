@extends('layouts.guest')

{{-- Set by VolunteerController::claim when someone arrives from an offer link
     without an account. Registration is otherwise the learner door, so the
     default copy stays as it is; a mentor accepting an offer should not be
     told they are applying to the cohort. --}}
@php $claimingOffer = session('claiming_volunteer_offer'); @endphp

{{--
    This page is crawlable now, so it needs to say what it is.

    It set neither a title nor a description, so it inherited the site-wide
    defaults — meaning it would have entered the index carrying the same title
    and description as the home page, competing with it for the same terms and
    telling a searcher nothing about what they had found.

    Cohort details come from config so the page cannot drift from the dashboard
    and the course pages, which quote the same numbers.
--}}
@section('title', 'Apply to the Founding Cohort | Free Digital Skills Programme | Skills Co-op')

@section('meta_description', 'Apply for one of ' . config('organisation.cohort.places')
    . ' places on the free ' . config('organisation.cohort.starts')
    . ' cohort. AI-integrated digital skills training for people the labour market overlooks.')

@section('auth-title', $claimingOffer
    ? 'Create your account'
    : 'Apply to the founding cohort')

@section('auth-subtitle', $claimingOffer
    ? 'You need an account to accept your offer. It takes a minute, and you will come straight back to it.'
    : 'Create your account and start your application. Takes a couple of minutes.')

@section('caption-title', $claimingOffer
    ? 'One step from joining the team'
    : 'Your Place in the Founding Cohort')

@section('caption-text', $claimingOffer
    ? 'You have been offered a volunteer role with Skills Co-op. Set up an account and we will take you back to the offer, where you can read the detail and accept or decline.'
    : 'Skills Co-op is a fully funded 25-week programme for people the traditional pipeline was never designed for. Cohort 1 launches January 2027 with thirty founding places.')

@section('auth-content')
<!-- Session Status -->
@if (session('status'))
    <div class="mb-4 p-4 bg-white/10 rounded-lg text-white/80 text-center">
        {{ session('status') }}
    </div>
@endif

@if (session('message'))
    <div class="mb-4 p-4 bg-white/10 rounded-lg text-white/80 text-center">
        {{ session('message') }}
    </div>
@endif

<!-- Social sign-up -->
<div class="social-login mb-6">
    <div class="social-login-title relative text-center text-white/80 text-xs uppercase tracking-widest mb-4 font-mono">
        <span class="relative z-10 bg-dark-gray/95 px-4">Sign up with</span>
        <span class="absolute top-1/2 left-0 right-0 h-px bg-white/15"></span>
    </div>
    <div class="social-buttons flex gap-3">
        @if(\Illuminate\Support\Facades\Route::has('login.google'))
            <a href="{{ route('login.google') }}" class="social-btn google flex-1 flex items-center justify-center gap-2 px-3.5 py-3 border border-white/15 rounded-lg bg-white/5 text-light font-semibold text-sm transition-all duration-300 hover:border-white/40 hover:bg-white/10 hover:-translate-y-0.5">
                <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span>Google</span>
            </a>
        @endif
        @if(\Illuminate\Support\Facades\Route::has('login.linkedin'))
            <a href="{{ route('login.linkedin') }}" class="social-btn linkedin flex-1 flex items-center justify-center gap-2 px-3.5 py-3 border border-white/15 rounded-lg bg-white/5 text-light font-semibold text-sm transition-all duration-300 hover:border-white/40 hover:bg-white/10 hover:-translate-y-0.5">
                <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#0A66C2" d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
                <span>LinkedIn</span>
            </a>
        @endif
    </div>
</div>

<!-- Divider -->
<div class="divider relative text-center my-6">
    <span class="relative z-10 bg-dark-gray/95 px-4 text-white/70 text-xs uppercase tracking-widest font-mono">or by email</span>
    <span class="absolute top-1/2 left-0 right-0 h-px bg-white/15"></span>
</div>

<form method="POST" action="{{ route('register') }}" class="auth-form w-full">
    @csrf

    <!-- Name -->
    <div class="form-group mb-5">
        <label for="name" class="block text-light font-semibold mb-2 text-sm">Full name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="First and last name" class="w-full px-4 py-3 border-2 border-white/10 rounded-lg bg-white/5 text-light font-medium transition-all duration-300 focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20">
        @error('name')
            <div class="error-message text-red-400 text-xs mt-2">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email -->
    <div class="form-group mb-5">
        <label for="email" class="block text-light font-semibold mb-2 text-sm">Email address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com" class="w-full px-4 py-3 border-2 border-white/10 rounded-lg bg-white/5 text-light font-medium transition-all duration-300 focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20">
        @error('email')
            <div class="error-message text-red-400 text-xs mt-2">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password -->
    <div class="form-group mb-5">
        <label for="password" class="block text-light font-semibold mb-2 text-sm">Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="At least 8 characters" class="w-full px-4 py-3 border-2 border-white/10 rounded-lg bg-white/5 text-light font-medium transition-all duration-300 focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20">
        @error('password')
            <div class="error-message text-red-400 text-xs mt-2">{{ $message }}</div>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="form-group mb-6">
        <label for="password_confirmation" class="block text-light font-semibold mb-2 text-sm">Confirm password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" class="w-full px-4 py-3 border-2 border-white/10 rounded-lg bg-white/5 text-light font-medium transition-all duration-300 focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20">
        @error('password_confirmation')
            <div class="error-message text-red-400 text-xs mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div class="auth-links flex justify-between items-center mb-6">
        <a href="{{ route('login') }}" class="text-gold text-sm font-medium hover:text-light transition-colors duration-300">Already have an account?</a>
    </div>

    <button type="submit" class="btn-primary w-full py-3 px-6 rounded-full text-dark-gray font-bold text-base transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg" style="background: linear-gradient(135deg, #ee9d1d 0%, #f4b642 100%);">
        Create account and continue
    </button>

    <p class="text-white/60 text-xs text-center mt-5 leading-relaxed">
        @if ($claimingOffer)
            Creating an account does not accept the offer. You will get the chance to read it and decide.
        @else
            Creating an account starts your application. We will guide you through the assessment and next steps.
        @endif
    </p>
</form>
@endsection
