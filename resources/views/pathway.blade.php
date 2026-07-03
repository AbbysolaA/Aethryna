@extends('layouts.aethryna')

@section('title', 'Digital Transformation Pathway | SkillsCo-op')

@section('meta_description', 'Our three-phase Digital Transformation Pathway takes learners from digital foundations to independent employment. Fully funded for all participants. Start with a free assessment.')
@section('og_description', 'Our three-phase Digital Transformation Pathway takes learners from digital foundations to independent employment. Fully funded for all participants. Start with a free assessment.')

@section('content')

    <!-- Hero Section -->
    <section class="pathway-hero">
        <div class="ath-container">
            <div class="pathway-hero-grid">
                <div class="hero-content">
                    <span class="ath-sub">How It Works</span>
                    <h1>The Digital <span class="ath-gradient-text">Transformation</span> Pathway</h1>
                    <p>Our three-phase approach turns potential into professional success. From foundations to independence.</p>
                    <a href="#pathway-details" class="btn btn-primary">Explore the Pathway <i class="fas fa-arrow-down"></i></a>
                </div>
                <div class="hero-visual">
                    <div class="pathway-diagram">
                        <div class="phase phase-1">
                            <div class="phase-icon"><i class="fas fa-seedling"></i></div>
                            <div class="phase-title">Foundations</div>
                        </div>
                        <div class="phase-connector"><i class="fas fa-angle-right"></i></div>
                        <div class="phase phase-2">
                            <div class="phase-icon"><i class="fas fa-rocket"></i></div>
                            <div class="phase-title">Specialised Training</div>
                        </div>
                        <div class="phase-connector"><i class="fas fa-angle-right"></i></div>
                        <div class="phase phase-3">
                            <div class="phase-icon"><i class="fas fa-award"></i></div>
                            <div class="phase-title">Project Period</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 25-Week Journey Infographic -->
    <section class="journey-section" id="journey">
        <div class="ath-container">
            <div class="section-title">
                <span class="ath-sub">The 25-Week Journey</span>
                <h2>How the pathway unfolds</h2>
                <p>Three certificates. One cohort. A community for life.</p>
            </div>
            <div class="pathway-journey" id="pathway-journey">
                <svg class="pj-svg" viewBox="0 0 1000 300" role="img" aria-labelledby="pj-title" preserveAspectRatio="xMidYMid meet">
                    <title id="pj-title">The 25-week journey: Foundations in weeks 0 to 4 ending in certificate one, Specialised Training in weeks 5 to 12 ending in certificate two, and the Project Period in weeks 13 to 24 ending in certificate three</title>
                    <defs>
                        <marker id="pj-arrow" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse">
                            <path d="M 0 0 L 10 5 L 0 10 z" fill="#ee9d1d"/>
                        </marker>
                    </defs>

                    <path class="pj-line pj-seg1" d="M 60 225 C 110 222 160 212 205 205" pathLength="1"/>
                    <path class="pj-line pj-seg2" d="M 205 205 C 305 195 400 175 500 160" pathLength="1"/>
                    <path class="pj-line pj-seg3" d="M 500 160 C 650 138 800 115 940 95" pathLength="1"/>
                    <path class="pj-line pj-tail" d="M 940 95 C 958 92 972 90 988 88" pathLength="1" marker-end="url(#pj-arrow)"/>

                    <g class="pj-g0">
                        <circle class="pj-start" cx="60" cy="225" r="6"/>
                        <text class="pj-week" x="60" y="258">WK 0</text>
                    </g>
                    <g class="pj-g1">
                        <text class="pj-stage" x="130" y="168">Foundations</text>
                        <text class="pj-weeks" x="130" y="186">Weeks 0-4</text>
                        <circle class="pj-badge" cx="205" cy="205" r="15"/>
                        <text class="pj-num" x="205" y="210">1</text>
                        <text class="pj-cert" x="205" y="240">Certificate 1</text>
                        <text class="pj-week" x="205" y="258">WK 4</text>
                    </g>
                    <g class="pj-g2">
                        <text class="pj-stage" x="352" y="118">Specialised Training</text>
                        <text class="pj-weeks" x="352" y="136">Weeks 5-12</text>
                        <circle class="pj-badge" cx="500" cy="160" r="15"/>
                        <text class="pj-num" x="500" y="165">2</text>
                        <text class="pj-cert" x="500" y="195">Certificate 2</text>
                        <text class="pj-week" x="500" y="213">WK 12</text>
                    </g>
                    <g class="pj-g3">
                        <text class="pj-stage" x="720" y="52">Project Period</text>
                        <text class="pj-weeks" x="720" y="70">Weeks 13-24</text>
                        <circle class="pj-badge" cx="940" cy="95" r="15"/>
                        <text class="pj-num" x="940" y="100">3</text>
                        <text class="pj-cert" x="940" y="130">Certificate 3</text>
                        <text class="pj-week" x="940" y="148">WK 24</text>
                    </g>
                </svg>

                <div class="pj-stages">
                    <div class="pj-stage">
                        <span class="pj-stage-weeks">WEEKS 0-4</span>
                        <h3>Foundations</h3>
                        <span class="pj-chip">Certificate 1 on completion</span>
                    </div>
                    <div class="pj-stage">
                        <span class="pj-stage-weeks">WEEKS 5-12</span>
                        <h3>Specialised Training</h3>
                        <span class="pj-chip">Certificate 2 on completion</span>
                    </div>
                    <div class="pj-stage">
                        <span class="pj-stage-weeks">WEEKS 13-24</span>
                        <h3>Project Period</h3>
                        <span class="pj-chip">Certificate 3 on completion</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pathway Overview -->
    <section id="pathway-details" class="pathway-overview">
        <div class="ath-container">
            <div class="section-title">
                <span class="ath-sub">Overview</span>
                <h2>How It Works</h2>
                <p>A clear, structured path from beginner to professional</p>
            </div>
            <div class="overview-content">
            <div class="overview-text">
                <p>Our Digital Transformation Pathway takes you from little or no digital experience to becoming a confident professional in the tech industry. Through our three-phase programme, you'll gain practical skills, build a portfolio, and secure your first job in tech.</p>
                <p>Each phase builds upon the previous one, ensuring you have a solid foundation before moving to more advanced topics. Our experienced mentors and industry partners guide you every step of the way.</p>
            </div>
            <div class="overview-stats">
                <div class="stat">
                    <span class="stat-number">25</span>
                    <span class="stat-label">Weeks</span>
                </div>
                <div class="stat">
                    <span class="stat-number">100%</span>
                    <span class="stat-label">Funded Places</span>
                </div>
                <div class="stat">
                    <span class="stat-number">4</span>
                    <span class="stat-label">Specialist Tracks</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Phase 1: Digital Foundations -->
    <section class="phase-section phase-1-bg">
        <div class="ath-container">
            <div class="phase-header">
                <div class="phase-badge">Phase 1 · Weeks 0 to 4</div>
                <h2>Foundations</h2>
                <p>Building confidence, digital fluency, and a growth mindset</p>
            </div>
            <div class="phase-content">
                <div class="phase-description">
                    <h3>Perfect for beginners with limited tech exposure</h3>
                    <p>This foundational phase is designed for individuals who are new to technology or want to build their confidence in digital environments. You'll learn the basics of computer usage, internet navigation, and develop the digital literacy skills needed for modern work.</p>
                    <div class="phase-objectives">
                        <h4>Key Objectives:</h4>
                        <ul>
                            <li>Master basic computer operations and file management</li>
                            <li>Learn safe internet practices and digital communication</li>
                            <li>Develop problem-solving and critical thinking skills</li>
                            <li>Build confidence in using digital tools and platforms</li>
                            <li>Understand online safety and privacy best practices</li>
                        </ul>
                    </div>
                    <div class="phase-duration">
                        <i class="fas fa-clock"></i> <strong>Duration:</strong> Weeks 0 to 4, ending with Certificate 1
                    </div>
                </div>
                <div class="phase-skills">
                    <h4>Skills You'll Learn:</h4>
                    <div class="skills-grid">
                        <div class="skill-item">
                            <i class="fas fa-laptop-code"></i>
                            <span>Computer Basics</span>
                        </div>
                        <div class="skill-item">
                            <i class="fas fa-globe"></i>
                            <span>Internet Navigation</span>
                        </div>
                        <div class="skill-item">
                            <i class="fas fa-envelope-open-text"></i>
                            <span>Communication</span>
                        </div>
                        <div class="skill-item">
                            <i class="fas fa-file-invoice"></i>
                            <span>Documents</span>
                        </div>
                        <div class="skill-item">
                            <i class="fas fa-magnifying-glass"></i>
                            <span>Research</span>
                        </div>
                        <div class="skill-item">
                            <i class="fas fa-user-shield"></i>
                            <span>Digital Safety</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Phase 2: Skill Specialization -->
    <section class="phase-section phase-2-bg">
        <div class="ath-container">
            <div class="phase-header">
                <div class="phase-badge">Phase 2 · Weeks 5 to 12</div>
                <h2>Specialised Training</h2>
                <p>Project-based learning in your chosen track</p>
            </div>
            <div class="phase-content">
                <div class="phase-description">
                    <h3>Choose your track and master your craft</h3>
                    <p>In this phase, you'll dive deep into your chosen specialization. Working with industry mentors, you'll complete real-world projects, build a professional portfolio, and gain hands-on experience that employers value.</p>
                    <div class="phase-tracks">
                        @forelse($pathways->take(4) as $pathway)
                            <div class="track">
                                <h4>{{ $pathway->name }}</h4>
                                <p>{{ Str::limit($pathway->description, 120) }}</p>
                                <div class="track-skills">
                                    @if(is_array($pathway->skills))
                                        @foreach(array_slice($pathway->skills, 0, 3) as $skill)
                                            <span>{{ $skill }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="track">
                                <h4>Web Development Track</h4>
                                <p>Learn HTML, CSS, JavaScript, and modern frameworks to build websites and web applications.</p>
                                <div class="track-skills">
                                    <span>HTML/CSS</span>
                                    <span>JavaScript</span>
                                    <span>React/Vue</span>
                                </div>
                            </div>
                            <div class="track">
                                <h4>Digital Design Track</h4>
                                <p>Master UI/UX design, graphic design, and visual communication using industry-standard tools.</p>
                                <div class="track-skills">
                                    <span>Figma</span>
                                    <span>UI/UX</span>
                                    <span>Adobe Suite</span>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if($pathways->count() > 4)
                        <div class="see-more-container">
                            <a href="{{ route('programs') }}" class="btn-see-more">
                                Explore All Programs <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    @endif

                    <div class="phase-duration">
                        <i class="fas fa-calendar-alt"></i> <strong>Duration:</strong> Weeks 5 to 12, ending with Certificate 2
                    </div>
                </div>
                <div class="phase-projects">
                    <h4>Real-World Projects:</h4>
                    <div class="projects-grid">
                        <div class="project-item">
                            <h5>Portfolio Website</h5>
                            <p>Build a personal portfolio showcasing your skills and projects</p>
                        </div>
                        <div class="project-item">
                            <h5>Client Project</h5>
                            <p>Work on real projects for local businesses and organisations</p>
                        </div>
                        <div class="project-item">
                            <h5>Capstone Project</h5>
                            <p>Create a complete project demonstrating your full skill set</p>
                        </div>
                        <div class="project-item">
                            <h5>Internship</h5>
                            <p>Gain practical experience through our partner company internships</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Phase 3: Economic Independence -->
    <section class="phase-section phase-3-bg">
        <div class="ath-container">
            <div class="phase-header">
                <div class="phase-badge">Phase 3 · Weeks 13 to 24</div>
                <h2>Project Period</h2>
                <p>Build a portfolio through real projects and develop employment readiness</p>
            </div>
            <div class="phase-content">
                <div class="phase-description">
                    <h3>Ongoing mentorship and career advancement</h3>
                    <p>The final phase focuses on transitioning to professional employment. You'll learn job search strategies, interview skills, and begin earning through freelance work while receiving continued mentorship.</p>
                    <div class="phase-objectives">
                        <h4>Key Objectives:</h4>
                        <ul>
                            <li>Develop job search and interview skills</li>
                            <li>Build a professional network and online presence</li>
                            <li>Gain freelance experience and build income</li>
                            <li>Secure full-time employment in your field</li>
                            <li>Continue professional development and growth</li>
                        </ul>
                    </div>
                    <div class="phase-duration">
                        <i class="fas fa-award"></i> <strong>Goal:</strong> Certificate 3, a portfolio, and sustainable employment
                    </div>
                </div>
                <div class="phase-career">
                    <h4>Career Support Services:</h4>
                    <div class="career-services">
                        <div class="service-item">
                            <i class="fas fa-search"></i>
                            <div class="service-content">
                                <h5>Community Network</h5>
                                <p>Connections with organisations in our network and personalised job search support</p>
                            </div>
                        </div>
                        <div class="service-item">
                            <i class="fas fa-briefcase"></i>
                            <div class="service-content">
                                <h5>Freelance Platform</h5>
                                <p>Access to our curated freelance opportunities</p>
                            </div>
                        </div>
                        <div class="service-item">
                            <i class="fas fa-graduation-cap"></i>
                            <div class="service-content">
                                <h5>Interview Coaching</h5>
                                <p>Mock interviews, resume reviews, and professional development workshops</p>
                            </div>
                        </div>
                        <div class="service-item">
                            <i class="fas fa-users"></i>
                            <div class="service-content">
                                <h5>Ongoing Mentoring</h5>
                                <p>Continue working with your mentor after programme completion for career guidance and networking</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Stories -->
    <section class="success-stories">
        <div class="ath-container">
            <div class="section-title">
                <span class="ath-sub">Success Stories</span>
                <h2>Pathway Impact</h2>
                <p>Real journeys from our program graduates</p>
            </div>
            <div class="stories-grid" style="grid-template-columns: 1fr;">
                <div class="story-card" style="text-align: center; padding: 40px 32px;">
                    <div class="story-content">
                        <h3>Your story starts here.</h3>
                        <p>"This is where our first success stories will be written. We are opening places now for our founding cohort of learners and mentors. Step in early and help shape what SkillsCo-op becomes."</p>
                        <div class="story-author" style="justify-content: center; margin-top: 24px; gap: 16px; display: flex; flex-wrap: wrap;">
                            <a href="{{ route('register') }}" class="btn btn-primary">Claim Your Place</a>
                            <a href="{{ route('sessions') }}" class="btn btn-outline">Become a Mentor</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="pathway-cta">
        <div class="ath-container">
            <div class="cta-content">
                <h2>Ready to take the next step?</h2>
                <p>Take our 2-minute assessment to discover which track is right for you and begin your transformation today.</p>
                <div class="cta-buttons">
                    <button class="btn btn-primary" id="startAssessment">Take Assessment</button>
                    <a href="{{ route('assessment.index') }}" class="btn btn-outline">Apply Now</a>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    <link href="https://fonts.bunny.net/css?family=ibm-plex-mono:500,600&display=swap" rel="stylesheet">
    <style>
        /* Hallmark · redesign (section-scope) · pre-emit critique: P4 H4 E4 S4 R5 V4
         * scope: 25-week journey SVG infographic, plain background per user preference
         * theme: existing brand tokens preserved · motion: staged path draw + badge pops, reduced-motion safe */
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
            --font-mono: 'IBM Plex Mono', 'Courier New', monospace;
        }

        /* 25-Week Journey Infographic */
        .journey-section { padding: 100px 0; background: #fff; }
        .pj-svg { width: 100%; height: auto; display: block; }
        .pj-line { fill: none; stroke: var(--ath-teal); stroke-width: 3; }
        .pj-tail { stroke: var(--ath-gold); stroke-dasharray: 0.18 0.14; }
        .pj-start { fill: var(--ath-teal); }
        .pj-badge { fill: var(--ath-gold); }
        .pj-num {
            fill: #fff;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 15px;
            text-anchor: middle;
        }
        .pj-stage {
            fill: var(--ath-deep);
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 18px;
            text-anchor: middle;
        }
        .pj-weeks, .pj-week {
            fill: var(--ath-muted);
            font-family: var(--font-mono);
            font-size: 12px;
            letter-spacing: 1px;
            text-anchor: middle;
        }
        .pj-cert {
            fill: var(--ath-deep);
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 13px;
            text-anchor: middle;
        }

        /* Staged draw-on-scroll: hidden states apply only once JS adds .pj-anim */
        .pj-anim .pj-seg1, .pj-anim .pj-seg2, .pj-anim .pj-seg3 { stroke-dasharray: 1; stroke-dashoffset: 1; }
        .pj-anim .pj-tail, .pj-anim .pj-g1, .pj-anim .pj-g2, .pj-anim .pj-g3 { opacity: 0; }
        .pj-anim.in-view .pj-seg1 { animation: pj-draw 500ms cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .pj-anim.in-view .pj-seg2 { animation: pj-draw 500ms cubic-bezier(0.16, 1, 0.3, 1) 450ms forwards; }
        .pj-anim.in-view .pj-seg3 { animation: pj-draw 500ms cubic-bezier(0.16, 1, 0.3, 1) 950ms forwards; }
        .pj-anim.in-view .pj-g1 { animation: pj-fade 400ms ease-out 400ms forwards; }
        .pj-anim.in-view .pj-g2 { animation: pj-fade 400ms ease-out 900ms forwards; }
        .pj-anim.in-view .pj-g3 { animation: pj-fade 400ms ease-out 1400ms forwards; }
        .pj-anim.in-view .pj-tail { animation: pj-fade 400ms ease-out 1650ms forwards; }
        @keyframes pj-draw { to { stroke-dashoffset: 0; } }
        @keyframes pj-fade { to { opacity: 1; } }
        @media (prefers-reduced-motion: reduce) {
            .pj-anim .pj-seg1, .pj-anim .pj-seg2, .pj-anim .pj-seg3 { stroke-dasharray: none; stroke-dashoffset: 0; animation: none; }
            .pj-anim .pj-tail, .pj-anim .pj-g1, .pj-anim .pj-g2, .pj-anim .pj-g3 { opacity: 1; animation: none; }
        }

        /* Mobile stacked fallback */
        .pj-stages { display: none; }
        @media (max-width: 768px) {
            .pj-svg { display: none; }
            .pj-stages { display: grid; grid-template-columns: 1fr; gap: 28px; }
            .pj-stage { border-left: 3px solid var(--ath-teal); padding-left: 20px; }
            .pj-stage-weeks {
                display: block;
                font-family: var(--font-mono);
                font-size: 0.75rem;
                font-weight: 600;
                letter-spacing: 2px;
                color: var(--ath-muted);
                margin-bottom: 6px;
            }
            .pj-stage h3 {
                font-size: 1.25rem;
                color: var(--ath-deep);
                font-weight: 800;
                margin-bottom: 10px;
                font-family: 'Outfit', sans-serif;
            }
            .pj-chip {
                display: inline-block;
                font-family: var(--font-mono);
                font-size: 0.72rem;
                font-weight: 600;
                letter-spacing: 1px;
                text-transform: uppercase;
                padding: 5px 12px;
                border-radius: 100px;
                background: rgba(238,157,29,0.14);
                color: #9a6510;
            }
        }

        .ath-container {
            max-width: 1250px;
            margin: 0 auto;
            padding: 0 5%;
        }

        /* General Section Header */
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
        }

        .section-title p {
            font-size: 1.25rem;
            color: var(--ath-muted);
            max-width: 800px;
        }

        /* Pathway Hero */
        .pathway-hero {
            padding: 160px 0 100px;
            background: var(--ath-deep);
            position: relative;
            overflow: hidden;
            color: #fff;
        }

        .pathway-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 70% 30%, rgba(3, 139, 137, 0.2), transparent 50%),
                        radial-gradient(circle at 30% 70%, rgba(238, 157, 29, 0.1), transparent 50%);
            z-index: 1;
        }

        .pathway-hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .pathway-hero h1 {
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

        .pathway-hero p {
            font-size: 1.25rem;
            margin-bottom: 40px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .pathway-diagram {
            display: flex;
            align-items: center;
            gap: 30px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: var(--ath-radius);
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .phase {
            text-align: center;
            flex: 1;
        }

        .phase-icon {
            font-size: 2.5rem;
            color: var(--ath-gold);
            margin-bottom: 15px;
        }

        .phase-title {
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.9);
        }

        .phase-connector {
            color: rgba(255,255,255,0.3);
            font-size: 1.5rem;
        }

        /* Overview Section */
        .pathway-overview {
            padding: 100px 0;
            background: var(--ath-light);
        }

        .overview-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .overview-text p {
            font-size: 1.15rem;
            line-height: 1.8;
            color: var(--ath-text);
            margin-bottom: 20px;
        }

        .overview-stats {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .overview-stats .stat {
            padding: 30px;
            background: white;
            border-radius: var(--ath-radius);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
            text-align: center;
            transition: var(--ath-trans);
        }

        .overview-stats .stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .overview-stats .stat-number {
            font-size: 2.8rem;
            font-weight: 800;
            color: var(--ath-teal);
            display: block;
            margin-bottom: 5px;
        }

        .overview-stats .stat-label {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--ath-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Phase Sections */
        .phase-section {
            padding: 100px 0;
            position: relative;
        }

        .phase-1-bg { background-color: #fff; }
        .phase-2-bg { background-color: var(--ath-light); }
        .phase-3-bg { background-color: #fff; }

        .phase-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .phase-badge {
            display: inline-block;
            background: rgba(3, 139, 137, 0.1);
            color: var(--ath-teal);
            padding: 8px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }

        .phase-header h2 {
            font-size: clamp(2rem, 5vw, 3rem);
            color: var(--ath-deep);
            font-weight: 800;
            margin-bottom: 15px;
        }

        .phase-header p {
            font-size: 1.2rem;
            color: var(--ath-muted);
            max-width: 700px;
            margin: 0 auto;
        }

        .phase-content {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 60px;
            align-items: start;
        }

        .phase-description h3 {
            font-size: 1.8rem;
            color: var(--ath-deep);
            font-weight: 700;
            margin-bottom: 25px;
            line-height: 1.3;
        }

        .phase-description p {
            color: var(--ath-text);
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .phase-objectives h4, .phase-skills h4, .phase-projects h4, .phase-career h4 {
            color: var(--ath-deep);
            font-weight: 800;
            margin-bottom: 25px;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .phase-objectives h4::before {
            content: '';
            width: 30px; height: 2px;
            background: var(--ath-gold);
            display: block;
        }

        .phase-objectives ul {
            list-style: none;
            padding: 0;
            display: grid;
            gap: 15px;
        }

        .phase-objectives li {
            padding-left: 35px;
            position: relative;
            color: var(--ath-text);
            font-weight: 500;
        }

        .phase-objectives li::before {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            color: var(--ath-teal);
            font-size: 1rem;
        }

        .phase-duration {
            margin-top: 40px;
            padding: 20px 30px;
            background: var(--ath-light);
            border-radius: var(--ath-radius);
            display: inline-flex;
            align-items: center;
            gap: 15px;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .phase-duration strong {
            color: var(--ath-deep);
            font-weight: 700;
        }

        /* Skills Grid */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .skill-item {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
            padding: 25px;
            background: white;
            border-radius: var(--ath-radius);
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.05);
            transition: var(--ath-trans);
        }

        .skill-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: var(--ath-teal);
        }

        .skill-item i {
            color: var(--ath-teal);
            font-size: 1.8rem;
            background: rgba(3, 139, 137, 0.05);
            width: 60px; height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            transition: var(--ath-trans);
        }

        .skill-item:hover i {
            background: var(--ath-teal);
            color: #fff;
        }

        .skill-item span {
            font-weight: 700;
            color: var(--ath-deep);
            font-size: 1rem;
        }

        /* Phase Tracks */
        .phase-tracks {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 25px;
            margin: 30px 0;
        }

        .track {
            background: white;
            padding: 30px;
            border-radius: var(--ath-radius);
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border-left: 5px solid var(--ath-teal);
            transition: var(--ath-trans);
        }

        .track:hover {
            transform: translateX(10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        .track h4 {
            color: var(--ath-deep);
            font-weight: 800;
            margin-bottom: 12px;
            font-size: 1.2rem;
        }

        .track p {
            color: var(--ath-muted);
            margin-bottom: 20px;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .track-skills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .track-skills span {
            background: var(--ath-light);
            color: var(--ath-teal);
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            border: 1px solid rgba(3, 139, 137, 0.1);
        }

        /* See More Link */
        .see-more-container {
            margin: 30px 0;
            text-align: left;
        }

        .btn-see-more {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--ath-teal);
            font-weight: 700;
            text-decoration: none;
            font-size: 1.1rem;
            transition: var(--ath-trans);
            padding: 10px 0;
            border-bottom: 2px solid rgba(3, 139, 137, 0.1);
        }

        .btn-see-more:hover {
            color: var(--ath-deep);
            border-bottom-color: var(--ath-gold);
            transform: translateX(5px);
        }

        .btn-see-more i {
            font-size: 0.9rem;
            transition: var(--ath-trans);
        }

        .btn-see-more:hover i {
            transform: translateX(3px);
        }

        /* Projects Grid */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .project-item {
            background: white;
            padding: 25px;
            border-radius: var(--ath-radius);
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.05);
            transition: var(--ath-trans);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .project-item::before {
            content: '\f121';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: var(--ath-gold);
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .project-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: var(--ath-gold);
        }

        .project-item h5 {
            color: var(--ath-deep);
            font-weight: 800;
            margin-bottom: 5px;
            font-size: 1.1rem;
        }

        .project-item p {
            color: var(--ath-muted);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Career Services */
        .career-services {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 20px;
        }

        .service-item {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            background: white;
            padding: 25px;
            border-radius: var(--ath-radius);
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.05);
            transition: var(--ath-trans);
        }

        .service-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        }

        .service-item i {
            color: var(--ath-teal);
            font-size: 1.5rem;
            background: rgba(3, 139, 137, 0.1);
            width: 50px; height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            flex-shrink: 0;
        }

        .service-content h5 {
            color: var(--ath-deep);
            font-weight: 800;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }

        .service-content p {
            color: var(--ath-muted);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Success Stories */
        .success-stories {
            padding: 100px 0;
            background: var(--ath-light);
        }

        .stories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .story-card {
            background: white;
            border-radius: var(--ath-radius);
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: var(--ath-trans);
        }

        .story-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.1);
        }

        .story-image {
            height: 250px;
            position: relative;
        }

        .story-image img {
            width: 100%; height: 100%;
            object-fit: cover;
        }

        .story-content {
            padding: 40px;
        }

        .story-content h3 {
            color: var(--ath-deep);
            font-weight: 800;
            margin-bottom: 15px;
            font-size: 1.4rem;
        }

        .story-content p {
            color: var(--ath-text);
            line-height: 1.7;
            font-size: 1.1rem;
            margin-bottom: 25px;
            font-style: italic;
        }

        .story-author {
            color: var(--ath-deep);
            font-weight: 700;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .story-author span {
            color: var(--ath-gold);
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* CTA Section */
        .pathway-cta {
            padding: 100px 0;
            background: var(--ath-deep);
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .pathway-cta::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 50% 50%, rgba(3, 139, 137, 0.3), transparent 70%);
        }

        .pathway-cta .cta-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }

        .pathway-cta h2 {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 800;
            margin-bottom: 25px;
            line-height: 1.1;
        }

        .pathway-cta p {
            font-size: 1.25rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        /* Mobile Responsive */
        @media (max-width: 992px) {
            .pathway-hero-grid {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 40px;
            }

            .pathway-hero p {
                margin-left: auto;
                margin-right: auto;
            }

            .phase-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .overview-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }
        }

        @media (max-width: 768px) {
            .pathway-diagram {
                flex-direction: column;
                gap: 20px;
            }

            .phase-connector {
                transform: rotate(90deg);
            }

            .cta-buttons {
                flex-direction: column;
            }

            .projects-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Assessment modal trigger
        document.getElementById('startAssessment').addEventListener('click', () => {
            // This would open the assessment modal - for now just redirect to assessment
            window.location.href = '{{ route("assessment.index") }}';
        });

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

        // Observe all sections
        document.querySelectorAll('section').forEach(section => {
            observer.observe(section);
        });

        // Observe story cards
        document.querySelectorAll('.story-card').forEach(card => {
            observer.observe(card);
        });

        // Observe track cards
        document.querySelectorAll('.track').forEach(track => {
            observer.observe(track);
        });

        // Journey infographic draw-on-scroll
        (function () {
            var pj = document.getElementById('pathway-journey');
            if (!pj) return;
            pj.classList.add('pj-anim');
            if (!('IntersectionObserver' in window)) {
                pj.classList.add('in-view');
                return;
            }
            var pjObs = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        pj.classList.add('in-view');
                        pjObs.disconnect();
                    }
                });
            }, { threshold: 0.35 });
            pjObs.observe(pj);
        })();
    </script>
    @endpush
@endsection
