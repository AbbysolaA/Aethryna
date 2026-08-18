@extends('layouts.aethryna')

@section('title', 'Find Your Pathway | Skills Co-op Assessment')

@section('meta_description', 'A two-minute assessment to match you with the Skills Co-op pilot track that fits you best: Project and Product Delivery, Data and AI Analytics, Product Design and Marketing, or Software Development.')
@section('og_description', 'A two-minute assessment to match you with the Skills Co-op pilot track that fits you best: Project and Product Delivery, Data and AI Analytics, Product Design and Marketing, or Software Development.')

@section('content')
    {{-- A dead resume link lands here, so say why rather than dropping them on
         the start page with no explanation. --}}
    @if (session('error'))
        <div class="ath-container" style="padding-top: 24px;">
            <div role="alert" style="border-left: 4px solid #c0392b; background: #fdf1ef; color: #9b2c1c; padding: 14px 18px; border-radius: 12px; font-size: 0.95rem;">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Assessment Start Hero -->
    <section class="assessment-intro">
        <div class="ath-container">
            <div class="assessment-card-wrapper">
                <div class="assessment-main-card">
                    <div class="assessment-badge">
                        <i class="fas fa-sparkles"></i> AI Powered Discovery
                    </div>
                    <span class="ath-sub">Identify Your Future</span>
                    <h1>Find Your Digital <span class="ath-gradient-text">Pathway</span></h1>
                    <p class="assessment-lead">This is not a school-style test. It's a discovery session designed to map your
                        natural strengths to high-growth career tracks.</p>

                    <div class="assessment-features-grid">
                        <div class="a-feature">

                            <div class="a-info">
                                <strong>15 Simple Questions</strong>
                                <span>Takes about 5 minutes</span>
                            </div>
                        </div>
                        <div class="a-feature">

                            <div class="a-info">
                                <strong>Precise Match</strong>
                                <span>Custom track recommendations</span>
                            </div>
                        </div>
                        <div class="a-feature">

                            <div class="a-info">
                                <strong>Instant Results</strong>
                                <span>No waiting for review</span>
                            </div>
                        </div>
                    </div>

                    <div class="assessment-cta-area">
                        <form action="{{ route('assessment.start') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary assessment-btn">
                                Start Your Assessment <i class="fas fa-arrow-right"></i>
                            </button>
                        </form>
                        <p class="cta-note"><i class="fas fa-lock"></i> Your data is private and secure</p>
                    </div>
                </div>

                <!-- Guidance Section -->
                <div class="assessment-guidance">
                    <div class="g-column">
                        <h3>What we measure:</h3>
                        <ul>
                            <li><i class="fas fa-check-circle"></i> Cognitive orientation</li>
                            <li><i class="fas fa-check-circle"></i> Behavioral traits</li>
                            <li><i class="fas fa-check-circle"></i> Learning readiness</li>
                        </ul>
                    </div>
                    <div class="g-column">
                        <h3>What you'll get:</h3>
                        <ul>
                            <li><i class="fas fa-check-circle"></i> Primary Career Track</li>
                            <li><i class="fas fa-check-circle"></i> Secondary Options</li>
                            <li><i class="fas fa-check-circle"></i> First-step action plan</li>
                        </ul>
                    </div>
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
                --ath-white: #ffffff;
                --ath-text: #404952;
                --ath-muted: #57616a;
                --ath-trans: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
                --ath-radius: 32px;
            }

            /* Unique Background for Assessment (Grid/Pattern) */
            .assessment-intro {
                min-height: 100vh;
                padding: 140px 0 80px;
                background: var(--ath-light);
                background-image: radial-gradient(var(--ath-teal) 0.5px, transparent 0.5px);
                background-size: 30px 30px;
                background-position: center;
                display: flex;
                align-items: center;
                position: relative;
            }

            .assessment-intro::after {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(to bottom, transparent, var(--ath-light));
                pointer-events: none;
            }

            .ath-container {
                max-width: 1250px;
                margin: 0 auto;
                padding: 0 5%;
                position: relative;
                z-index: 5;
            }

            .assessment-card-wrapper {
                max-width: 850px;
                margin: 0 auto;
            }

            .assessment-main-card {
                background: #fff;
                padding: 70px 60px;
                border-radius: var(--ath-radius);
                box-shadow: 0 40px 100px rgba(5, 88, 96, 0.08);
                border: 1px solid rgba(0, 0, 0, 0.03);
                text-align: center;
                margin-bottom: 40px;
                position: relative;
                overflow: hidden;
            }

            .assessment-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 16px;
                background: rgba(3, 139, 137, 0.08);
                color: var(--ath-teal);
                border-radius: 100px;
                font-size: 0.85rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 1px;
                margin-bottom: 25px;
            }

            .ath-sub {
                display: block;
                color: var(--ath-gold);
                text-transform: uppercase;
                letter-spacing: 3px;
                font-weight: 700;
                font-size: 0.9rem;
                margin-bottom: 15px;
                font-family: 'Outfit', sans-serif;
            }

            .assessment-main-card h1 {
                font-size: clamp(2.5rem, 5vw, 4rem);
                color: var(--ath-deep);
                font-weight: 800;
                margin-bottom: 20px;
                line-height: 1.1;
                font-family: 'Outfit', sans-serif;
            }

            .ath-gradient-text {
                background: linear-gradient(135deg, var(--ath-teal), var(--ath-gold));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .assessment-lead {
                font-size: 1.3rem;
                color: var(--ath-text);
                line-height: 1.6;
                margin-bottom: 50px;
                max-width: 650px;
                margin-left: auto;
                margin-right: auto;
            }

            /* Features Grid */
            .assessment-features-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
                margin-bottom: 50px;
            }

            .a-feature {
                background: var(--ath-light);
                padding: 24px;
                border-radius: 20px;
                text-align: center;
                transition: var(--ath-trans);
                border: 1px solid rgba(0, 0, 0, 0.02);
            }

            .a-feature:hover {
                transform: translateY(-5px);
                background: #fff;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                border-color: var(--ath-teal);
            }

            .a-icon {
                font-size: 2rem;
                display: block;
                margin-bottom: 12px;
            }

            .a-info strong {
                display: block;
                color: var(--ath-deep);
                font-size: 0.95rem;
                margin-bottom: 4px;
            }

            .a-info span {
                font-size: 0.8rem;
                color: var(--ath-muted);
                font-weight: 600;
            }

            /* CTA AREA */
            .assessment-btn {
                font-size: 1.25rem;
                padding: 20px 50px;
                border-radius: 100px;
                box-shadow: 0 15px 35px rgba(3, 139, 137, 0.25);
            }

            .cta-note {
                margin-top: 20px;
                color: var(--ath-muted);
                font-size: 0.85rem;
                font-weight: 600;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }

            /* Guidance Grid */
            .assessment-guidance {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 40px;
                padding: 0 30px;
            }

            .g-column h3 {
                color: var(--ath-deep);
                font-size: 1.1rem;
                font-weight: 800;
                margin-bottom: 20px;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .g-column ul {
                list-style: none;
                padding: 0;
                display: grid;
                gap: 12px;
            }

            .g-column li {
                color: var(--ath-text);
                font-size: 1.05rem;
                display: flex;
                align-items: center;
                gap: 12px;
                font-weight: 600;
            }

            .g-column li i {
                color: var(--ath-teal);
                font-size: 0.9rem;
            }

            /* Responsive */
            @media (max-width: 992px) {
                .assessment-main-card {
                    padding: 50px 30px;
                }

                .assessment-features-grid {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 768px) {
                .assessment-guidance {
                    grid-template-columns: 1fr;
                    text-align: left;
                }

                .assessment-intro {
                    padding-top: 120px;
                }
            }
        </style>
    @endpush
@endsection
