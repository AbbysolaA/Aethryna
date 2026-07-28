@extends('layouts.guest')

@section('auth-title', $user ? 'Welcome, ' . str($user->name)->before(' ') : 'Set your password')
@section('auth-subtitle', 'Choose a password and your account is ready to use.')

@section('caption-title', 'Your account is waiting')
@section('caption-text', 'An administrator set this up for you. Nobody at Skills Co-op knows or can see the password you choose here.')

@section('auth-content')

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-500/10 border border-red-400/30 rounded-lg text-red-200 text-sm">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('staff.invite.store') }}" class="auth-form w-full">
    @csrf

    {{-- The token is the credential. The email travels with it so the broker
         can match the pair; both are fixed here rather than typed. --}}
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">

    <div class="form-group mb-5">
        <label class="block text-light font-semibold mb-2 text-sm">Your account</label>
        <input type="email" value="{{ $email }}" disabled
               class="w-full px-4 py-3 border-2 border-white/10 rounded-lg bg-white/5 text-white/60 font-medium cursor-not-allowed">
        <p class="text-white/50 text-xs mt-2">This is the address the invitation was sent to.</p>
    </div>

    <div class="form-group mb-5">
        <label for="password" class="block text-light font-semibold mb-2 text-sm">Choose a password</label>
        <input id="password" type="password" name="password" required autofocus autocomplete="new-password" placeholder="At least 8 characters"
               class="w-full px-4 py-3 border-2 border-white/10 rounded-lg bg-white/5 text-light font-medium transition-all duration-300 focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20">
        @error('password')
            <div class="error-message text-red-400 text-xs mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mb-6">
        <label for="password_confirmation" class="block text-light font-semibold mb-2 text-sm">Confirm password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password"
               class="w-full px-4 py-3 border-2 border-white/10 rounded-lg bg-white/5 text-light font-medium transition-all duration-300 focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20">
    </div>

    <button type="submit" class="btn-primary w-full py-3 px-6 rounded-full text-dark-gray font-bold text-base transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg" style="background: linear-gradient(135deg, #ee9d1d 0%, #f4b642 100%);">
        Set password and sign in
    </button>

    <p class="text-white/60 text-xs text-center mt-5 leading-relaxed">
        If you were not expecting this invitation, close this page and tell us at hello@skillscoop.org.
    </p>
</form>

@endsection
