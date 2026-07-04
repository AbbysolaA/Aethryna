@extends('layouts.aethryna')

@section('title', 'Skills Pathway Programs | SkillsCo-op')

@section('meta_description', 'Explore SkillsCo-op specialised learning tracks including Web Development, Digital Design, IT Support, Data Analytics and more. All places fully funded for eligible participants.')
@section('og_description', 'Explore SkillsCo-op specialised learning tracks including Web Development, Digital Design, IT Support, Data Analytics and more. All places fully funded for eligible participants.')

@section('content')

    <!-- Hero Section -->
    <section class="programs-hero">
        <div class="ath-container">
            <div class="programs-hero-grid">
                <div class="hero-content">
                    <span class="ath-sub">Skill Mastery</span>
                    <h1>Our Specialised <span class="ath-gradient-text">Pathway</span> Programs</h1>
                    <p>Structured learning paths designed for real-world success. Choose the track that defines your future.</p>
                    <div class="hero-actions">
                        <a href="#program-tracks" class="btn btn-primary">Explore Programs <i class="fas fa-arrow-down"></i></a>
                    </div>
                </div>
                <div class="hero-stats">
                    <div class="stat">
                        <span class="stat-number">{{ $pathways->total() }}</span>
                        <span class="stat-label">Expert Tracks</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Funded Places</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Tuition Funded</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Overview -->
    <section class="program-overview">
        <div class="ath-container">
            <div class="section-title">
                <span class="ath-sub">The Methodology</span>
                <h2>Choose Your Path to Success</h2>
                <p>Four specialised tracks designed for different career goals and interests</p>
            </div>
            <div class="overview-content">
            <div class="overview-text">
                <p>Our Skills Pathway offers four distinct tracks, each designed to provide practical, thorough training and real-world experience in high-demand digital careers. Whether you're creative, technical, or customer-focused, there's a track that matches your strengths and interests.</p>
                <p>Each program combines classroom learning with hands-on projects, mentorship from industry professionals, and supported progression into employment assistance. You'll graduate with a portfolio of work, industry certifications, and the skills employers are looking for.</p>
            </div>
            <div class="overview-features">
                <div class="feature">
                    <i class="fas fa-project-diagram"></i>
                    <h4>Project-Based Learning</h4>
                    <p>Build real projects for real clients</p>
                </div>
                <div class="feature">
                    <i class="fas fa-user-friends"></i>
                    <h4>1-on-1 Mentorship</h4>
                    <p>Personal guidance from industry experts</p>
                </div>
                <div class="feature">
                    <i class="fas fa-certificate"></i>
                    <h4>Industry Certifications</h4>
                    <p>Recognised credentials for your CV</p>
                </div>
                <div class="feature">
                    <i class="fas fa-briefcase"></i>
                    <h4>Fully Funded</h4>
                    <p>Every place is free for eligible participants</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Tracks -->
    <section id="program-tracks" class="program-tracks">
        <div class="ath-container">
            <div class="section-title">
                <span class="ath-sub">Our Offerings</span>
                <h2>Programme Specialisations</h2>
                <p>Industry-standard training across high-growth domains</p>
            </div>
            <div class="tracks-grid">
            @forelse($pathways as $pathway)
                @php
                    $icon = 'fa-laptop-code';
                    $colorClass = 'web-dev';
                    $anchorId = null;
                    $nameLc = strtolower($pathway->name);
                    if (str_contains($nameLc, 'project')) { $icon = 'fa-tasks'; $colorClass = 'web-dev'; $anchorId = 'project-product'; }
                    elseif (str_contains($nameLc, 'data')) { $icon = 'fa-chart-bar'; $colorClass = 'digital-sales'; $anchorId = 'data-ai'; }
                    elseif (str_contains($nameLc, 'design')) { $icon = 'fa-palette'; $colorClass = 'digital-design'; $anchorId = 'digital-design'; }
                    elseif (str_contains($nameLc, 'software') || str_contains($nameLc, 'development') || str_contains($nameLc, 'web')) { $icon = 'fa-code'; $colorClass = 'web-dev'; $anchorId = 'software-dev'; }
                    elseif (str_contains($nameLc, 'support') || str_contains($nameLc, 'it')) { $icon = 'fa-tools'; $colorClass = 'it-support'; }
                    elseif (str_contains($nameLc, 'sales') || str_contains($nameLc, 'marketing')) { $icon = 'fa-chart-line'; $colorClass = 'digital-sales'; }
                @endphp
                <div class="track-card {{ $colorClass }}" @if($anchorId) id="{{ $anchorId }}" @endif>
                    <div class="track-header">
                        <div class="track-icon">
                            <i class="fas {{ $icon }}"></i>
                        </div>
                        <div class="track-info">
                            <h3>{{ $pathway->name }}</h3>
                            <div class="track-meta">
                                <span><i class="fas fa-clock"></i> {{ $pathway->duration_months ?? '6-9' }} Months</span>
                                <span><i class="fas fa-signal"></i> {{ $pathway->difficulty_level ?? 'Beginner' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="track-content">
                        <div class="track-description">
                            <h4>Overview</h4>
                            <p>{{ Str::limit($pathway->description, 200) }}</p>
                        </div>
                        <div class="track-careers">
                            <h4>Career Tracks:</h4>
                            <div class="career-tags">
                                @if(is_array($pathway->skills))
                                    @foreach(array_slice($pathway->skills, 0, 5) as $skill)
                                        <span>{{ $skill }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="track-footer">
                        <div class="track-cta-info">
                            <span class="salary-label">Applications Open</span>
                            <span class="salary">Cohort dates announced by email</span>
                        </div>
                        <a href="{{ route('assessment.index') }}" class="btn btn-primary btn-sm">Join Track</a>
                    </div>
                </div>
            @empty
                <div class="track-card web-dev">
                    <div class="track-header">
                        <div class="track-icon"><i class="fas fa-code"></i></div>
                        <div class="track-info">
                            <h3>Web Development</h3>
                            <div class="track-meta">
                                <span><i class="fas fa-clock"></i> 6-9 Months</span>
                                <span><i class="fas fa-signal"></i> Beginner</span>
                            </div>
                        </div>
                    </div>
                    <div class="track-content">
                        <div class="track-description">
                            <h4>Overview</h4>
                            <p>Master modern web architecture, responsive design, and full-stack development using industry-standard tools.</p>
                        </div>
                        <div class="track-careers">
                            <h4>Key Skills:</h4>
                            <div class="career-tags">
                                <span>HTML/CSS</span>
                                <span>JavaScript</span>
                                <span>PHP/Laravel</span>
                            </div>
                        </div>
                    </div>
                    <div class="track-footer">
                        <div class="track-cta-info">
                            <span class="salary-label">Applications Open</span>
                            <span class="salary">Limited Seats</span>
                        </div>
                        <a href="{{ route('assessment.index') }}" class="btn btn-primary btn-sm">Join Track</a>
                    </div>
                </div>
            @endforelse
            </div>

            <div class="pagination-container">
                {{ $pathways->links() }}
            </div>
        </div>
    </section>

    <!-- Application Process -->
    <section id="apply" class="application-process">
        <div class="ath-container">
            <div class="section-title">
                <span class="ath-sub">Get Started</span>
                <h2>How to Apply</h2>
                <p>Four steps from curious to enrolled in our founding cohort</p>
            </div>

            <div class="apply-infographic" id="apply-infographic">
                <svg class="ai-svg" viewBox="0 0 1000 300" role="img" aria-labelledby="ai-title" preserveAspectRatio="xMidYMid meet">
                    <title id="ai-title">The four-step application: assessment, application review, conversation with the team, and enrolment for the January 2027 cohort.</title>
                    <defs>
                        <marker id="ai-arrow" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse">
                            <path d="M 0 0 L 10 5 L 0 10 z" fill="#ee9d1d"/>
                        </marker>
                        <linearGradient id="ai-fill" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0" stop-color="#038b89" stop-opacity="0.11"/>
                            <stop offset="1" stop-color="#038b89" stop-opacity="0"/>
                        </linearGradient>
                    </defs>

                    <path class="ai-area" d="M 90 190 C 200 170 320 150 380 145 C 500 138 560 138 620 145 C 720 155 820 130 900 100 L 900 260 L 90 260 Z"/>

                    <path class="ai-line ai-seg1" d="M 90 190 C 200 170 320 150 380 145" pathLength="1"/>
                    <path class="ai-line ai-seg2" d="M 380 145 C 500 138 560 138 620 145" pathLength="1"/>
                    <path class="ai-line ai-seg3" d="M 620 145 C 720 155 820 130 900 100" pathLength="1"/>
                    <path class="ai-line ai-tail" d="M 900 100 C 920 96 940 91 970 85" pathLength="1" marker-end="url(#ai-arrow)"/>

                    <g class="ai-node ai-node-1">
                        <circle class="ai-ring" cx="90" cy="190" r="26"/>
                        <circle class="ai-dot" cx="90" cy="190" r="19"/>
                        <text class="ai-num" x="90" y="197">1</text>
                        <text class="ai-lbl" x="90" y="238">Assess</text>
                        <text class="ai-sub-lbl" x="90" y="256">2 minutes</text>
                    </g>
                    <g class="ai-node ai-node-2">
                        <circle class="ai-ring" cx="380" cy="145" r="26"/>
                        <circle class="ai-dot" cx="380" cy="145" r="19"/>
                        <text class="ai-num" x="380" y="152">2</text>
                        <text class="ai-lbl" x="380" y="193">Apply</text>
                        <text class="ai-sub-lbl" x="380" y="211">A short form</text>
                    </g>
                    <g class="ai-node ai-node-3">
                        <circle class="ai-ring" cx="620" cy="145" r="26"/>
                        <circle class="ai-dot" cx="620" cy="145" r="19"/>
                        <text class="ai-num" x="620" y="152">3</text>
                        <text class="ai-lbl" x="620" y="193">Meet us</text>
                        <text class="ai-sub-lbl" x="620" y="211">A 30-minute chat</text>
                    </g>
                    <g class="ai-node ai-node-4">
                        <circle class="ai-ring ai-ring-final" cx="900" cy="100" r="26"/>
                        <circle class="ai-dot ai-dot-final" cx="900" cy="100" r="19"/>
                        <text class="ai-num" x="900" y="107">4</text>
                        <text class="ai-lbl" x="900" y="148">Enrol</text>
                        <text class="ai-sub-lbl" x="900" y="166">Cohort 1 Jan 2027</text>
                    </g>
                </svg>

                <ol class="ai-stages">
                    <li>
                        <span class="ai-tag">Step 1 · 2 minutes</span>
                        <h3>Take the assessment</h3>
                        <p>A short set of questions helps us understand where you are today and which pilot track fits you best. No preparation needed.</p>
                    </li>
                    <li>
                        <span class="ai-tag">Step 2 · A short form</span>
                        <h3>Complete your application</h3>
                        <p>Tell us a little about yourself, your circumstances, and why you want to join. No CV, no essay. Human questions with human answers.</p>
                    </li>
                    <li>
                        <span class="ai-tag">Step 3 · 30-minute chat</span>
                        <h3>Meet the team</h3>
                        <p>A friendly conversation so we understand your goals and you get honest answers to your questions. This is not a test.</p>
                    </li>
                    <li>
                        <span class="ai-tag">Step 4 · Cohort 1 begins January 2027</span>
                        <h3>Enrol and begin</h3>
                        <p>If we are a good fit, you enrol in your track. Onboarding, community access, and Week 0 foundations start here.</p>
                    </li>
                </ol>
            </div>

            <div class="application-cta">
                <h3>Places are limited to 30. Apply early.</h3>
                <p>Applications for the founding cohort are open now. We review as they arrive.</p>
                <a href="{{ route('register') }}" class="btn btn-primary">Start your application</a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq">
        <div class="ath-container">
            <div class="section-title">
                <span class="ath-sub">Common Queries</span>
                <h2>Frequently Asked Questions</h2>
                <p>Everything you need to know about our programs</p>
            </div>
            <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    <h4>Do I need any prior experience?</h4>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>No prior experience is required for any of our tracks. We designed these programs specifically for beginners. Our curriculum builds from the fundamentals up, and our mentors provide personalised support throughout your journey.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h4>How much time do I need to commit?</h4>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Most tracks require 20-30 hours per week, combining classroom time, project work, and mentorship sessions. We understand that many of our students have other commitments, so we offer flexible scheduling options.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h4>Are the programs fully funded?</h4>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Yes! All our programs are completely free for eligible participants. We believe that financial barriers shouldn't prevent anyone from accessing quality education and career opportunities.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h4>What happens after I complete the program?</h4>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Our team works with you to identify employment opportunities and prepare you for applications. You will also have access to ongoing career support and our learner community network.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <h4>Can I switch tracks if I change my mind?</h4>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Yes, we allow track changes within the first month of the program. Our assessment helps match you with the right track initially, but we understand that interests can evolve. We'll work with you to ensure you're on the best path for your goals.</p>
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
            --ath-radius: 24px;
        }

        .ath-container {
            max-width: 1250px;
            margin: 0 auto;
            padding: 0 5%;
        }

        /* Section Headers */
        .section-title {
            text-align: left;
            margin-bottom: 3.5rem;
        }

        .section-title h2 {
            font-size: clamp(2.2rem, 5vw, 3.5rem);
            color: var(--ath-deep);
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.1;
            font-family: 'Outfit', sans-serif;
        }

        .section-title p {
            font-size: 1.25rem;
            color: var(--ath-muted);
            max-width: 800px;
        }

        .ath-sub {
            display: block;
            color: var(--ath-gold);
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        /* Programs Hero */
        .programs-hero {
            padding: 160px 0 100px;
            background: var(--ath-deep);
            position: relative;
            overflow: hidden;
            color: #fff;
        }

        .programs-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 70% 30%, rgba(3, 139, 137, 0.2), transparent 50%),
                        radial-gradient(circle at 30% 70%, rgba(238, 157, 29, 0.1), transparent 50%);
            z-index: 1;
        }

        .programs-hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .programs-hero h1 {
            font-size: clamp(2.5rem, 6vw, 4.5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 25px;
            font-family: 'Outfit', sans-serif;
        }

        .ath-gradient-text {
            background: linear-gradient(135deg, var(--ath-gold), #fff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .programs-hero p {
            font-size: 1.25rem;
            margin-bottom: 40px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .hero-stats {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .hero-stats .stat {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: var(--ath-radius);
            text-align: center;
        }

        .hero-stats .stat-number {
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--ath-gold);
            display: block;
            margin-bottom: 5px;
        }

        .hero-stats .stat-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
            font-weight: 600;
        }

        /* Program Overview */
        .program-overview {
            padding: 100px 0;
            background: var(--ath-light);
        }

        .overview-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .overview-text p {
            font-size: 1.15rem;
            line-height: 1.8;
            color: var(--ath-text);
            margin-bottom: 20px;
        }

        .overview-features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .feature {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: var(--ath-radius);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
            transition: var(--ath-trans);
        }

        .feature:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.1);
            border-color: var(--ath-teal);
        }

        .feature i {
            font-size: 2.2rem;
            color: var(--ath-teal);
            margin-bottom: 15px;
            background: rgba(3, 139, 137, 0.05);
            width: 65px; height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            margin: 0 auto 20px;
            transition: var(--ath-trans);
        }

        .feature:hover i {
            background: var(--ath-teal);
            color: #fff;
        }

        .feature h4 {
            color: var(--ath-deep);
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .feature p {
            color: var(--ath-muted);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Program Tracks */
        .program-tracks {
            padding: 100px 0;
            background: #fff;
        }

        .tracks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 40px;
        }

        .track-card {
            background: white;
            border-radius: var(--ath-radius);
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
            transition: var(--ath-trans);
            display: flex;
            flex-direction: column;
        }

        .track-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 40px 80px rgba(0,0,0,0.1);
        }

        .track-header {
            padding: 40px;
            display: flex;
            align-items: flex-start;
            gap: 30px;
            background: var(--ath-light);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            position: relative;
        }

        .track-header::after {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 6px; height: 100%;
            background: var(--ath-teal);
        }

        .track-card.digital-design .track-header::after { background: #e84393; }
        .track-card.it-support .track-header::after { background: #0984e3; }
        .track-card.digital-sales .track-header::after { background: #2ed573; }

        .track-icon {
            width: 70px;
            height: 70px;
            background: #fff;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--ath-teal);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            flex-shrink: 0;
        }

        .track-info h3 {
            font-size: 1.6rem;
            color: var(--ath-deep);
            font-weight: 800;
            margin-bottom: 10px;
        }

        .track-info p {
            color: var(--ath-muted);
            font-size: 1.05rem;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .track-meta {
            display: flex;
            gap: 20px;
        }

        .track-meta span {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--ath-teal);
        }

        .track-content {
            padding: 40px;
            flex-grow: 1;
        }

        .track-content h4 {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--ath-deep);
            font-weight: 800;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .track-content h4::after {
            content: '';
            flex-grow: 1;
            height: 1px;
            background: rgba(0,0,0,0.05);
        }

        .track-description p {
            color: var(--ath-text);
            line-height: 1.7;
            margin-bottom: 30px;
            font-size: 1.05rem;
        }

        .career-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .career-tags span {
            background: var(--ath-light);
            color: var(--ath-teal);
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            border: 1px solid rgba(3, 139, 137, 0.1);
            transition: var(--ath-trans);
        }

        .career-tags span:hover {
            background: var(--ath-teal);
            color: #fff;
            transform: scale(1.05);
        }

        .track-footer {
            padding: 30px 40px;
            background: var(--ath-light);
            border-top: 1px solid rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .track-cta-info .salary {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--ath-deep);
            display: block;
        }

        .track-cta-info .salary-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--ath-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
            display: block;
        }

        /* Application Process */
        .application-process {
            padding: 100px 0;
            background: var(--ath-deep);
            color: #fff;
        }

        .application-process .section-title h2 { color: #fff; }
        .application-process .section-title p { color: rgba(255,255,255,0.7); }

        /* Application infographic */
        .apply-infographic {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 32px;
            padding: 40px 40px 20px;
            margin-bottom: 60px;
        }
        .ai-svg { width: 100%; height: auto; display: block; margin-bottom: 20px; }
        .ai-line { fill: none; stroke: var(--ath-teal); stroke-width: 3.5; stroke-linecap: round; }
        .ai-tail { stroke: var(--ath-gold); stroke-dasharray: 0.18 0.14; }
        .ai-area { fill: url(#ai-fill); }
        .ai-ring { fill: rgba(255, 255, 255, 0.08); stroke: var(--ath-teal); stroke-width: 2; }
        .ai-dot { fill: var(--ath-teal); }
        .ai-ring-final { stroke: var(--ath-gold); }
        .ai-dot-final { fill: var(--ath-gold); }
        .ai-num {
            fill: #fff;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 18px;
            text-anchor: middle;
        }
        .ai-lbl {
            fill: #fff;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 17px;
            text-anchor: middle;
        }
        .ai-sub-lbl {
            fill: rgba(255,255,255,0.65);
            font-family: 'IBM Plex Mono', 'Courier New', monospace;
            font-size: 12px;
            letter-spacing: 1px;
            text-anchor: middle;
        }

        .ai-anim .ai-seg1, .ai-anim .ai-seg2, .ai-anim .ai-seg3 { stroke-dasharray: 1; stroke-dashoffset: 1; }
        .ai-anim .ai-tail, .ai-anim .ai-area, .ai-anim .ai-node-2, .ai-anim .ai-node-3, .ai-anim .ai-node-4 { opacity: 0; }
        .ai-anim.in-view .ai-seg1 { animation: ai-draw 500ms cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .ai-anim.in-view .ai-seg2 { animation: ai-draw 500ms cubic-bezier(0.16, 1, 0.3, 1) 500ms forwards; }
        .ai-anim.in-view .ai-seg3 { animation: ai-draw 500ms cubic-bezier(0.16, 1, 0.3, 1) 1000ms forwards; }
        .ai-anim.in-view .ai-node-2 { animation: ai-fade 400ms ease-out 450ms forwards; }
        .ai-anim.in-view .ai-node-3 { animation: ai-fade 400ms ease-out 950ms forwards; }
        .ai-anim.in-view .ai-node-4 { animation: ai-fade 400ms ease-out 1450ms forwards; }
        .ai-anim.in-view .ai-area { animation: ai-fade 700ms ease-out 1200ms forwards; }
        .ai-anim.in-view .ai-tail { animation: ai-fade 400ms ease-out 1650ms forwards; }
        @keyframes ai-draw { to { stroke-dashoffset: 0; } }
        @keyframes ai-fade { to { opacity: 1; } }
        @media (prefers-reduced-motion: reduce) {
            .ai-anim .ai-seg1, .ai-anim .ai-seg2, .ai-anim .ai-seg3 { stroke-dasharray: none; stroke-dashoffset: 0; animation: none; }
            .ai-anim .ai-tail, .ai-anim .ai-area, .ai-anim .ai-node-2, .ai-anim .ai-node-3, .ai-anim .ai-node-4 { opacity: 1; animation: none; }
        }

        .ai-stages {
            list-style: none;
            padding: 0;
            margin: 30px 0 10px;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 24px;
        }
        .ai-stages li {
            padding: 24px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .ai-tag {
            display: inline-block;
            font-family: 'IBM Plex Mono', 'Courier New', monospace;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 100px;
            background: rgba(238, 157, 29, 0.15);
            color: var(--ath-gold);
            margin-bottom: 12px;
        }
        .ai-stages h3 {
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.15rem;
            margin-bottom: 8px;
        }
        .ai-stages p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.65;
            font-size: 0.95rem;
            margin: 0;
        }
        @media (max-width: 992px) {
            .ai-stages { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 640px) {
            .ai-svg { display: none; }
            .apply-infographic { padding: 24px; }
            .ai-stages { grid-template-columns: 1fr; }
        }

        .application-cta {
            background: #fff;
            padding: 60px;
            border-radius: var(--ath-radius);
            text-align: center;
            color: var(--ath-deep);
            max-width: 900px;
            margin: 0 auto;
            box-shadow: 0 40px 100px rgba(0,0,0,0.2);
        }

        .application-cta h3 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 15px;
            font-family: 'Outfit', sans-serif;
        }

        .application-cta p {
            font-size: 1.2rem;
            color: var(--ath-muted);
            margin-bottom: 35px;
        }

        /* FAQ Section */
        .faq {
            padding: 100px 0;
            background: var(--ath-light);
        }

        .faq-container {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .faq-item {
            background: #fff;
            border-radius: 18px;
            border: 1px solid rgba(0,0,0,0.05);
            overflow: hidden;
            transition: var(--ath-trans);
        }

        .faq-item:hover {
            border-color: var(--ath-teal);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .faq-question {
            padding: 25px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .faq-question h4 {
            font-size: 1.15rem;
            color: var(--ath-deep);
            font-weight: 700;
            margin: 0;
        }

        .faq-toggle {
            width: 35px; height: 35px;
            background: var(--ath-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ath-teal);
            font-weight: 800;
            transition: var(--ath-trans);
        }

        .faq-item.active {
            border-color: var(--ath-teal);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        .faq-item.active .faq-toggle {
            background: var(--ath-teal);
            color: #fff;
            transform: rotate(45deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            background: #fff;
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
            padding: 0 35px 30px;
        }

        .faq-answer p {
            color: var(--ath-text);
            line-height: 1.8;
            font-size: 1.05rem;
            margin: 0;
            padding-top: 5px;
            border-top: 1px solid rgba(0,0,0,0.03);
        }

        /* Pagination Styling */
        .pagination-container {
            margin-top: 60px;
            display: flex;
            justify-content: center;
        }

        .pagination-container .pagination {
            display: flex;
            gap: 10px;
            list-style: none;
            padding: 0;
        }

        .pagination-container .page-item .page-link {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.1);
            color: var(--ath-deep);
            font-weight: 700;
            text-decoration: none;
            transition: var(--ath-trans);
            background: #fff;
        }

        .pagination-container .page-item.active .page-link {
            background: var(--ath-teal);
            color: #fff;
            border-color: var(--ath-teal);
            box-shadow: 0 10px 20px rgba(3, 139, 137, 0.2);
        }

        .pagination-container .page-item:not(.active):hover .page-link {
            border-color: var(--ath-gold);
            color: var(--ath-gold);
            transform: translateY(-3px);
        }

        .pagination-container .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Mobile Responsive */
        @media (max-width: 992px) {
            .programs-hero-grid {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 50px;
            }

            .programs-hero p { margin: 0 auto 40px; }

            .overview-content {
                grid-template-columns: 1fr;
                gap: 50px;
            }

            .tracks-grid {
                grid-template-columns: 1fr;
            }

            .track-header {
                padding: 30px;
            }

            .track-content {
                padding: 30px;
            }
        }

        @media (max-width: 768px) {
            .hero-stats {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
            }

            .hero-stats .stat {
                flex: 1;
                min-width: 150px;
            }

            .overview-features {
                grid-template-columns: 1fr;
            }

            .application-cta {
                padding: 40px 25px;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // FAQ accordion
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const faqItem = question.parentElement;
                const isActive = faqItem.classList.contains('active');

                // Close all FAQ items
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                });

                // Open clicked item if it wasn't active
                if (!isActive) {
                    faqItem.classList.add('active');
                }
            });
        });

        // Application infographic draw-on-scroll
        (function () {
            var ai = document.getElementById('apply-infographic');
            if (!ai) return;
            ai.classList.add('ai-anim');
            if (!('IntersectionObserver' in window)) {
                ai.classList.add('in-view');
                return;
            }
            var aiObs = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        ai.classList.add('in-view');
                        aiObs.disconnect();
                    }
                });
            }, { threshold: 0.35 });
            aiObs.observe(ai);
        })();

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe track cards
        document.querySelectorAll('.track-card').forEach(card => {
            observer.observe(card);
        });

        // Observe process steps
        document.querySelectorAll('.process-step').forEach(step => {
            observer.observe(step);
        });

        // Observe features
        document.querySelectorAll('.feature').forEach(feature => {
            observer.observe(feature);
        });
    </script>
    @endpush
@endsection
