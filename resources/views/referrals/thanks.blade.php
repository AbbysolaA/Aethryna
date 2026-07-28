@extends('layouts.aethryna')

@section('title', 'Referral received | Skills Co-op')
@section('meta_description', 'Thank you for your referral to Skills Co-op. We will be in touch within a few days, gently and without pressure.')

@section('content')
<section class="rf-thanks">
    <div class="ath-container">
        <div class="rf-thanks-inner">
            <div class="rf-thanks-icon"><i class="fas fa-check"></i></div>
            <h1>Thank you.</h1>
            <p>Your referral is with us. We will be in touch within a few days, gently and without pressure.</p>
            <div class="rf-thanks-actions">
                <a href="{{ route('home') }}" class="rf-btn rf-btn-primary">Back to home</a>
                <a href="{{ route('referral.create') }}" class="rf-btn rf-btn-ghost">Refer someone else</a>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
:root {
    --ath-teal: #038b89;
    --ath-gold: #ee9d1d;
    --ath-deep: #055860;
    --ath-light: #F8FBFB;
    --ath-muted: #57616a;
}
.ath-container { max-width: 900px; margin: 0 auto; padding: 0 5%; }
.rf-thanks { padding: 200px 0 140px; background: var(--ath-light); text-align: center; }
.rf-thanks-inner { max-width: 620px; margin: 0 auto; }
.rf-thanks-icon {
    width: 88px; height: 88px;
    background: var(--ath-teal); color: #fff;
    border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 2.2rem; margin-bottom: 32px;
    box-shadow: 0 12px 30px rgba(3,139,137,0.25);
}
.rf-thanks h1 { font-family: 'Outfit', sans-serif; font-size: clamp(2.2rem, 5vw, 3rem); color: var(--ath-deep); font-weight: 800; margin-bottom: 18px; }
.rf-thanks p { color: var(--ath-muted); font-size: 1.15rem; line-height: 1.75; margin-bottom: 36px; }
.rf-thanks-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
.rf-btn { display: inline-flex; align-items: center; gap: 8px; padding: 12px 26px; border-radius: 100px; font-family: 'Outfit', sans-serif; font-weight: 700; text-decoration: none; transition: all 0.2s; }
.rf-btn-primary { background: var(--ath-gold); color: #fff; }
.rf-btn-primary:hover { background: var(--ath-teal); }
.rf-btn-ghost { background: transparent; color: var(--ath-deep); border: 2px solid var(--ath-deep); }
.rf-btn-ghost:hover { background: var(--ath-deep); color: #fff; }
</style>
@endpush
@endsection
