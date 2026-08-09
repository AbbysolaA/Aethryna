@extends('layouts.aethryna')

@section('title', 'Skills Co-op | Digital Skills, AI Literacy, and Real Career Pathways')

@section('meta_description', 'Free AI-integrated digital skills and career pathways for NEET young people, justice-involved adults and women returning to work.')
@section('og_description', 'Free AI-integrated digital skills and career pathways for NEET young people, justice-involved adults and women returning to work.')

@section('content')

    <!-- Hero Section -->
    <section class="ath-hero" id="hero">
        <div class="ath-hero-bg">
            <div class="ath-blob ath-blob-1"></div>
            <div class="ath-blob ath-blob-2"></div>
            <div class="ath-blob ath-blob-3"></div>
            <div class="ath-grid-layer"></div>
        </div>
        <div class="ath-container">
            <div class="ath-hero-slider">
                <!-- Slide 1 -->
                <div class="ath-hero-content active" data-index="0">
                    <div class="ath-hero-badge">Building Digital Careers</div>
                    <h1 class="ath-title">Rise. Learn. <span class="ath-gradient-text">Become.</span></h1>
                    <p>Widening access to digital skills and meaningful progression for underserved communities. Your next chapter starts here.</p>
                    <div class="ath-hero-btns">
                        <a href="{{ route('register') }}" class="ath-btn ath-btn-primary">Join the founding cohort</a>
                        <a href="{{ route('partners') }}" class="ath-btn ath-btn-outline">Partner with us</a>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="ath-hero-content" data-index="1">
                    <div class="ath-hero-badge">Bridging the Tech Gap</div>
                    <h2 class="ath-title">Tech. Future. <span class="ath-gradient-text">Mastery.</span></h2>
                    <p>Practical, funded digital skills programmes for people who have been locked out of the tech industry. Four pilot tracks, AI tools embedded throughout.</p>
                    <div class="ath-hero-btns">
                        <a href="{{ route('pathway') }}" class="ath-btn ath-btn-primary">Explore our pathway</a>
                        <a href="{{ route('programs') }}" class="ath-btn ath-btn-outline">See our tracks</a>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="ath-hero-content" data-index="2">
                    <div class="ath-hero-badge">Mentorship & Community</div>
                    <h2 class="ath-title">Connect. Lead. <span class="ath-gradient-text">Impact.</span></h2>
                    <p>One-to-one guidance from people already working in the industry, in a community built around belonging and mutual support.</p>
                    <div class="ath-hero-btns">
                        <a href="{{ route('mentors') }}" class="ath-btn ath-btn-primary">Become a mentor</a>
                        <a href="{{ route('stories') }}" class="ath-btn ath-btn-outline">Read our stories</a>
                    </div>
                </div>
            </div>

            <!-- Slider Navigation -->
            <div class="ath-slider-dots">
                <span class="ath-dot active" data-slide="0"></span>
                <span class="ath-dot" data-slide="1"></span>
                <span class="ath-dot" data-slide="2"></span>
            </div>
        </div>
        <div class="ath-scroll-indicator reveal-fade">
            <div class="ath-mouse"></div>
        </div>
    </section>

    <!-- About Section -->
    <section class="ath-section ath-about" id="about">
        <div class="ath-container">
            <div class="ath-section-header reveal-fade-up">
                <span class="ath-sub">Our Vision</span>
                <h2>About Skills Co-op</h2>
                <p>We exist to close the gap between ambition and opportunity.</p>
            </div>
            <div class="ath-about-grid">
                <div class="ath-about-card reveal-fade-right">
                    <div class="ath-feature-body">
                            <h4>Building Futures Together</h4>
                            <p>Skills Co-op is a community-led digital skills programme. We work with people who face real barriers to employment: NEET young people, career changers, migrants, refugees, and justice-involved individuals. Every place is fully funded.</p>
                            <p>Our approach combines personalised mentorship with practical, project-based learning and community support designed around dignity and belonging.</p>
                            <p>Every Skills Co-op learner becomes confident with AI tools, regardless of their pathway. We teach AI as a working tool, with the discipline to check what it produces and the judgement to override it when needed. This is what modern digital work looks like, and it is what employers are now looking for. Read about our AI Method and where we are taking it at <a href="{{ route('ai-labs') }}" style="color: var(--ath-teal); font-weight: 700;">AI Labs</a>.</p>
                    </div>
                </div>
                <div class="ath-about-features">
                    <div class="ath-feature reveal-fade-left" style="--delay: 1">
                        <div class="ath-feature-icon"><i class="fas fa-hands-helping"></i></div>
                        <div class="ath-feature-body">
                            <h4>Mentorship</h4>
                            <p>Personalised guidance from people already working in the industry</p>
                        </div>
                    </div>
                    <div class="ath-feature reveal-fade-left" style="--delay: 2">
                        <div class="ath-feature-icon"><i class="fas fa-graduation-cap"></i></div>
                        <div class="ath-feature-body">
                            <h4>Leadership</h4>
                            <p>Developing the next generation of change-makers</p>
                        </div>
                    </div>
                    <div class="ath-feature reveal-fade-left" style="--delay: 3">
                        <div class="ath-feature-icon"><i class="fas fa-rocket"></i></div>
                        <div class="ath-feature-body">
                            <h4>Confidence</h4>
                            <p>Building self-belief alongside practical skills</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Who Is This For? -->
    <section class="ath-section" style="background: var(--ath-light);" id="who-is-this-for">
        <div class="ath-container">
            <div class="ath-section-header reveal-fade-up">
                <h2>Who is this for?</h2>
                <p>Four kinds of learner, all welcome.</p>
            </div>
            <div class="ath-grid-3" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
                <div class="ath-about-card reveal-fade-up" style="--delay: 1; padding: 35px;">
                    <h4 style="color: var(--ath-deep); margin-bottom: 12px;">If you are a young person not in work or training</h4>
                    <p style="color: var(--ath-muted); line-height: 1.6; margin-bottom: 20px;">You are not behind. You are getting started. Our Foundation route is built for confidence first, skill second.</p>
                    <a href="{{ route('pathway') }}" class="ath-link">Find your route <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ath-about-card reveal-fade-up" style="--delay: 2; padding: 35px;">
                    <h4 style="color: var(--ath-deep); margin-bottom: 12px;">If you are a career changer</h4>
                    <p style="color: var(--ath-muted); line-height: 1.6; margin-bottom: 20px;">You have transferable skills already. We help you build the digital ones you need, in a cohort of people doing the same.</p>
                    <a href="{{ route('pathway') }}" class="ath-link">Find your route <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ath-about-card reveal-fade-up" style="--delay: 3; padding: 35px;">
                    <h4 style="color: var(--ath-deep); margin-bottom: 12px;">If you are a refugee, migrant, or asylum seeker</h4>
                    <p style="color: var(--ath-muted); line-height: 1.6; margin-bottom: 20px;">Language support, flexible scheduling, and a community that understands what you have already navigated to get here.</p>
                    <a href="{{ route('pathway') }}" class="ath-link">Find your route <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="ath-about-card reveal-fade-up" style="--delay: 4; padding: 35px;">
                    <h4 style="color: var(--ath-deep); margin-bottom: 12px;">If you are post-prison or in the justice system</h4>
                    <p style="color: var(--ath-muted); line-height: 1.6; margin-bottom: 20px;">We work with partners across the gate. Skills training, a real placement, and a community that does not ask what you did to be here.</p>
                    <a href="{{ route('pathway') }}" class="ath-link">Find your route <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Assessment Box -->
    <section class="ath-section ath-interactive">
        <div class="ath-container">
            <div class="ath-assessment-box reveal-zoom">
                <div class="ath-assessment-content">
                    <h3 class="reveal-fade-up">Discover Your Path</h3>
                    <p class="reveal-fade-up" style="--delay: 1">Take our 2-minute assessment to match with the right track for our pilot cohort.</p>
                    <button class="ath-btn ath-btn-primary reveal-fade-up" style="--delay: 2" id="assessmentToolBtn">Start Free Assessment</button>
                </div>
                <div class="ath-assessment-shapes">
                    <div class="ath-shape-1"></div>
                    <div class="ath-shape-2"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pathway Section -->
    <section class="ath-section ath-pathway" id="pathway">
        <div class="ath-container">
            <div class="ath-section-header reveal-fade-up">
                <span class="ath-sub">How It Works</span>
                <h2>The Skills Co-op Pathway</h2>
                <p>A structured 25-week journey, with three points where you earn a certificate and a portfolio. Every step builds on the last.</p>
            </div>
            <div class="ath-grid-3">
                <div class="ath-step-card reveal-fade-up" style="--delay: 1">
                    <div class="ath-step-num">01</div>
                    <h4>Foundations (Weeks 0-4)</h4>
                    <p>A welcoming start. You set goals, build your first portfolio piece, and lay the groundwork for digital confidence. At the end of week 4, you earn your Foundation Certificate.</p>
                </div>
                <div class="ath-step-card ath-highlight reveal-fade-up" style="--delay: 2">
                    <div class="ath-step-num">02</div>
                    <h4>Specialised Training (Weeks 5-12)</h4>
                    <p>Eight weeks of hands-on learning in your chosen track, with AI tools embedded throughout. Real exercises, weekly demos, and three portfolio pieces. At the end of week 12, you earn your Specialised Training Certificate.</p>
                </div>
                <div class="ath-step-card reveal-fade-up" style="--delay: 3">
                    <div class="ath-step-num">03</div>
                    <h4>Project Period (Weeks 13-24)</h4>
                    <p>Twelve weeks in a cross-pathway team, building a real venture your team came up with, and launching and selling it for real. Or, if employment is your route, delivering a brief for a real employer. Either way, you ship something real, present it, and earn your full Skills Co-op Certificate.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Transition Banner -->
    <div class="ath-brand-banner">
        <div class="ath-container">
            <div class="ath-banner-content reveal-fade-up">
                <h2>From where you are to where you want to be</h2>
                <p>Three certificates. One cohort. A community for life.</p>
            </div>
        </div>
    </div>

    <!-- Programs Section -->
    <section class="ath-section ath-programs" id="programs">
        <div class="ath-container">
            <div class="ath-section-header reveal-fade-up">
                <span class="ath-sub">Choose Your Path</span>
                <h2>Our Pilot Tracks</h2>
                <p>Four learning tracks for our first cohort, each designed for real entry-level digital roles. More tracks open with our second cohort in late 2027.</p>
            </div>
            <div class="ath-grid-3" style="grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
                <div class="ath-prog-item reveal-fade-up" style="--delay: 1">
                    <div class="ath-prog-icon"><i class="fas fa-tasks"></i></div>
                    <h3>Project and Product Delivery</h3>
                    <p>Organise and deliver digital work. Stakeholder communication, requirements, planning, and AI-assisted delivery. Roles: Project Coordinator, Business Analyst, Junior PM.</p>
                    <a href="{{ route('programs') }}#project-delivery" class="ath-link">Explore Track <i class="fas fa-arrow-right"></i></a>
                    <a href="{{ route('ai-labs') }}" class="ath-ai-link">AI is embedded throughout. See how &rarr;</a>
                </div>
                <div class="ath-prog-item reveal-fade-up" style="--delay: 2">
                    <div class="ath-prog-icon"><i class="fas fa-chart-bar"></i></div>
                    <h3>Data and AI Analytics</h3>
                    <p>Work with numbers, evidence, and insight. SQL, spreadsheets, visualisation, and AI-assisted analysis with built-in verification. Roles: Data Analyst, Insight Analyst, AI Operations Analyst.</p>
                    <a href="{{ route('programs') }}#data-analytics" class="ath-link">Explore Track <i class="fas fa-arrow-right"></i></a>
                    <a href="{{ route('ai-labs') }}" class="ath-ai-link">AI is embedded throughout. See how &rarr;</a>
                </div>
                <div class="ath-prog-item reveal-fade-up" style="--delay: 3">
                    <div class="ath-prog-icon"><i class="fas fa-palette"></i></div>
                    <h3>Product Design and Marketing</h3>
                    <p>Design digital products and put them in front of an audience. Figma, UX fundamentals, prototyping, brand, content, social, and analytics. AI-assisted creative work, used responsibly. Roles: Junior Product Designer, UX Designer, Digital Marketer.</p>
                    <a href="{{ route('programs') }}#product-design" class="ath-link">Explore Track <i class="fas fa-arrow-right"></i></a>
                    <a href="{{ route('ai-labs') }}" class="ath-ai-link">AI is embedded throughout. See how &rarr;</a>
                </div>
                <div class="ath-prog-item reveal-fade-up" style="--delay: 4">
                    <div class="ath-prog-icon"><i class="fas fa-code"></i></div>
                    <h3>Software Development</h3>
                    <p>Build and ship working software. Web fundamentals, APIs, modern tooling, and AI-assisted development with verification built in. Roles: Junior Software Developer, Frontend Developer, Automation Developer.</p>
                    <a href="{{ route('programs') }}#software-dev" class="ath-link">Explore Track <i class="fas fa-arrow-right"></i></a>
                    <a href="{{ route('ai-labs') }}" class="ath-ai-link">AI is embedded throughout. See how &rarr;</a>
                </div>
            </div>
            <div class="ath-coming-soon-block reveal-fade-up" style="margin-top: 50px; text-align: center; background: #fff; padding: 35px 40px; border-radius: 20px; box-shadow: 0 8px 30px rgba(0,0,0,0.05); border: 1px solid rgba(3,139,137,0.1);">
                <h4 style="color: var(--ath-deep); font-size: 1.2rem; margin-bottom: 10px;">More tracks coming with Cohort 2</h4>
                <p style="color: var(--ath-muted); margin: 0;">IT Support and Operations, Cyber Security, Cloud and DevOps, and Tech Sales and Customer Success will launch with our second cohort.</p>
            </div>
        </div>
    </section>

    <!-- Operator Core Section (Change 14b) -->
    <section class="ath-section ath-operator" id="operator-core">
        <div class="ath-container">
            <div class="ath-section-header reveal-fade-up">
                <span class="ath-sub">The Operator Core</span>
                <h2>Whatever your track, you graduate able to run your own work</h2>
                <p>Every Skills Co-op learner, in every track, learns the basics that turn a skill into a livelihood: how to support your own product, how to sell and market your own services, and how to run your own service delivery with AI and automation doing the heavy lifting. We call it the operator core. It is why a Skills Co-op designer can find her own clients, a Skills Co-op analyst can handle his own support questions, and every graduate can stand on their own feet, employed, freelancing, or building something of their own.</p>
            </div>
            <div class="ath-operator-grid">
                <div class="ath-feature reveal-fade-up" style="--delay: 1">
                    <div class="ath-feature-icon"><i class="fas fa-handshake"></i></div>
                    <div class="ath-feature-body">
                        <h4>Sell your work.</h4>
                        <p>Pricing, pitching, and finding clients. Taught to everyone, not just the sales track.</p>
                    </div>
                </div>
                <div class="ath-feature reveal-fade-up" style="--delay: 2">
                    <div class="ath-feature-icon"><i class="fas fa-tools"></i></div>
                    <div class="ath-feature-body">
                        <h4>Support your work.</h4>
                        <p>First-line support, troubleshooting, and keeping your users happy. Taught to everyone.</p>
                    </div>
                </div>
                <div class="ath-feature reveal-fade-up" style="--delay: 3">
                    <div class="ath-feature-icon"><i class="fas fa-robot"></i></div>
                    <div class="ath-feature-body">
                        <h4>Run your work.</h4>
                        <p>AI and automation for the admin, so one person can operate like a team.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Section -->
    <section class="ath-section ath-impact" id="impact">
        <div class="ath-container">
            <div class="ath-impact-grid">
                <div class="ath-impact-info reveal-fade-right">
                    <span class="ath-sub">Why We Exist</span>
                    <h2>The Opportunity Gap</h2>
                    <p>Nationally, 12.8% of 16 to 24 year olds are not in education, employment or training. Skills Co-op exists to change that, starting in the communities that need it most.</p>
                </div>
                <div class="ath-stats-grid">
                    <div class="ath-stat reveal-zoom" style="--delay: 1">
                        <div class="ath-stat-val counter" data-target="12.8" data-suffix="%">0</div>
                        <div class="ath-stat-lab">Of UK 16-24 year olds currently NEET</div>
                        <div class="ath-stat-note">Office for National Statistics, 2025</div>
                    </div>
                    <div class="ath-stat reveal-zoom" style="--delay: 2">
                        <div class="ath-stat-val counter" data-target="100" data-suffix="%">0</div>
                        <div class="ath-stat-lab">Of Skills Co-op places funded</div>
                        <div class="ath-stat-note">Free at the point of use for underserved learners</div>
                    </div>
                    <div class="ath-stat reveal-zoom" style="--delay: 3">
                        <div class="ath-stat-val counter" data-target="4">0</div>
                        <div class="ath-stat-lab">Specialist tracks in our pilot cohort</div>
                        <div class="ath-stat-note">Eight tracks at full launch, with two more in development</div>
                    </div>
                    <div class="ath-stat reveal-zoom" style="--delay: 4">
                        <div class="ath-stat-val counter" data-target="30">0</div>
                        <div class="ath-stat-lab">Learner places in our founding cohort</div>
                        <div class="ath-stat-note">Cohort 1 launches January 2027</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Partner logo wall — renders nothing until at least one partner is active in config/partners.php --}}
    <x-partner-wall />

    <!-- Success Stories -->
    <section class="ath-section ath-stories" id="stories">
        <div class="ath-container">
            <div class="ath-section-header reveal-fade-up">
                <span class="ath-sub">Be Part of the Story</span>
                <h2>Join Our First Cohort</h2>
            </div>
            <div class="ath-testimonial reveal-zoom">
                <div class="ath-testi-box">
                    <p class="ath-quote">"This is where the first success stories will be written, and one of them could be yours. We are opening places now for our founding cohort of learners and mentors. Step in early and help shape what Skills Co-op becomes."</p>
                    <div class="ath-testi-user" style="justify-content:center; gap:16px;">
                        <a href="{{ route('register') }}" class="ath-btn ath-btn-primary">Claim Your Place</a>
                        <a href="{{ route('mentors') }}" class="ath-btn" style="background:transparent;color:var(--ath-deep);border:2px solid var(--ath-deep);">Become a Mentor</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partner CTA -->
    <section class="ath-section" style="background: var(--ath-light); padding: 60px 0;">
        <div class="ath-container">
            <div class="ath-partner-cta reveal-zoom" style="background: #fff; border-radius: var(--ath-radius); padding: 60px 50px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); border-left: 5px solid var(--ath-teal); display: flex; align-items: center; justify-content: space-between; gap: 40px; flex-wrap: wrap;">
                <div>
                    <h3 style="color: var(--ath-deep); font-size: 1.7rem; font-weight: 800; margin-bottom: 12px;">Are you an employer or community-sector partner?</h3>
                    <p style="color: var(--ath-muted); margin: 0; max-width: 600px; line-height: 1.7;">Skills Co-op partners give our teams real briefs, host paid micro-placements, and shape what we deliver. Light commitment, real return.</p>
                </div>
                <a href="{{ route('partners') }}" class="ath-btn ath-btn-primary" style="white-space: nowrap; flex-shrink: 0;">Partner with us</a>
            </div>
        </div>
    </section>

    <!-- Sessions CTA -->
    <section class="ath-section" style="background: var(--ath-light);">
        <div class="ath-container">
            <div class="ath-assessment-box reveal-zoom" style="background: linear-gradient(135deg, var(--ath-gold), #d48806);">
                <div class="ath-assessment-content">
                    <h3 class="reveal-fade-up" style="color: #fff;">Monthly Panel Sessions</h3>
                    <p class="reveal-fade-up" style="--delay: 1; color: rgba(255,255,255,0.9);">Join our monthly panel discussions with industry experts, mentors, and learners. Network, learn, and grow with the Skills Co-op community.</p>
                    <a href="{{ route('sessions') }}" class="ath-btn" style="background: #fff; color: var(--ath-deep); font-weight: 800;" class="reveal-fade-up" style="--delay: 2">View Upcoming Sessions</a>
                </div>
                <div class="ath-assessment-shapes">
                    <div class="ath-shape-1"></div>
                    <div class="ath-shape-2"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="ath-cta-section">
        <div class="ath-container">
            <div class="ath-cta-card reveal-zoom">
                <h2>Ready to get started?</h2>
                <p>Join learners who have discovered their potential and built successful careers in tech through Skills Co-op.</p>
                <a href="{{ route('register') }}" class="ath-btn ath-btn-gold">Join the cohort</a>
            </div>
        </div>
    </section>

    <br>
    <br>
    <br>
    <br>

    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Scoped variables and resets */
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

        .ath-section {
            padding: 100px 0;
            overflow: hidden;
            width: 100%;
        }

        .ath-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Hero Fixes */
        .ath-hero {
            /* layouts/aethryna wraps every page in <main class="py-20">, which
               adds 80px top and bottom. A 100vh hero on top of that overflowed
               the viewport by 160px, pushing the CTAs below the fold on
               laptops. Subtract the wrapper so the hero is exactly one screen,
               with a floor so it does not collapse on short windows. */
            min-height: max(560px, calc(100vh - 160px));
            display: flex;
            align-items: center;
            position: relative;
            background: var(--ath-deep);
            color: var(--ath-white);
            overflow: hidden;
        }

        .ath-hero-bg {
            position: absolute;
            inset: 0;
            z-index: 1;
        }

        .ath-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.25;
            animation: athDrift 20s infinite alternate linear;
        }

        .ath-blob-1 { width: 500px; height: 500px; background: var(--ath-teal); top: -100px; right: -50px; }
        .ath-blob-2 { width: 400px; height: 400px; background: var(--ath-gold); bottom: -100px; left: -50px; animation-delay: -5s; }
        .ath-blob-3 { width: 300px; height: 300px; background: var(--ath-teal); top: 40%; right: 15%; animation-delay: -10s; }

        @keyframes athDrift {
            from { transform: translate(0,0) rotate(0); }
            to { transform: translate(50px, 50px) rotate(90deg); }
        }

        .ath-grid-layer {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 30px 30px;
        }

        .ath-hero-content {
            position: absolute;
            top: 50%;
            left: 20px;
            transform: translateY(-50%);
            z-index: 10;
            max-width: 800px;
            text-align: left;
            opacity: 0;
            visibility: hidden;
            transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .ath-hero-content.active {
            opacity: 1;
            visibility: visible;
        }

        .ath-hero-slider {
            position: relative;
            height: 450px; /* Fixed height for slider stability */
            width: 100%;
        }

        .ath-hero-content .ath-title, 
        .ath-hero-content p, 
        .ath-hero-content .ath-hero-badge, 
        .ath-hero-content .ath-hero-btns {
            transform: translateY(30px);
            opacity: 0;
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .ath-hero-content.active .ath-title, 
        .ath-hero-content.active p, 
        .ath-hero-content.active .ath-hero-badge, 
        .ath-hero-content.active .ath-hero-btns {
            transform: translateY(0);
            opacity: 1;
        }

        .ath-hero-content.active .ath-hero-badge { transition-delay: 0.1s; }
        .ath-hero-content.active .ath-title { transition-delay: 0.2s; }
        .ath-hero-content.active p { transition-delay: 0.3s; }
        .ath-hero-content.active .ath-hero-btns { transition-delay: 0.4s; }

        .ath-slider-dots {
            position: absolute;
            bottom: -50px;
            left: 20px;
            display: flex;
            gap: 12px;
            z-index: 20;
        }

        .ath-dot {
            width: 12px;
            height: 12px;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            cursor: pointer;
            transition: var(--ath-trans);
        }

        .ath-dot.active {
            background: var(--ath-gold);
            width: 30px;
            border-radius: 10px;
        }

        .ath-hero-badge {
            display: inline-block;
            padding: 8px 20px;
            background: rgba(255,255,255,0.1);
            border-radius: 100px;
            color: var(--ath-gold);
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 25px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .ath-title {
            font-size: clamp(2.5rem, 8vw, 5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
            letter-spacing: -2px;
        }

        .ath-gradient-text {
            background: linear-gradient(135deg, var(--ath-gold), #fff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .ath-hero-btns {
            display: flex;
            gap: 20px;
            margin-top: 40px;
        }

        /* Buttons */
        .ath-btn {
            padding: 14px 35px;
            border-radius: 100px;
            font-weight: 700;
            text-decoration: none;
            transition: var(--ath-trans);
            display: inline-block;
            cursor: pointer;
            border: none;
            text-align: center;
        }

        .ath-btn-primary { background: var(--ath-teal); color: #fff; }
        .ath-btn-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(3, 139, 137, 0.3); }
        .ath-btn-outline { background: transparent; color: #fff; border: 2px solid rgba(255,255,255,0.3); }
        .ath-btn-outline:hover { border-color: var(--ath-gold); color: var(--ath-gold); }
        .ath-btn-gold { background: var(--ath-gold); color: var(--ath-deep); }
        .ath-btn-gold:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(238, 157, 29, 0.4); }

        /* General Section Header */
        .ath-section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .ath-sub {
            color: var(--ath-teal);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 0.8rem;
            display: block;
            margin-bottom: 10px;
        }

        .ath-section-header h2 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            color: var(--ath-deep);
            font-weight: 800;
            margin-bottom: 15px;
        }

        /* Grids */
        .ath-grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .ath-about-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .ath-about-card {
            background: #fff;
            padding: 50px;
            border-radius: var(--ath-radius);
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        }

        /* About Features Card Style */
        .ath-about-features {
            display: grid;
            gap: 20px;
        }

        .ath-feature {
            display: flex;
            gap: 25px;
            align-items: center;
            background: #fff;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.03);
            transition: var(--ath-trans);
            border: 1px solid transparent;
        }
        
        .ath-feature:hover {
            transform: translateX(10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.06);
            border-color: rgba(3, 139, 137, 0.1);
        }

        .ath-feature-icon {
            width: 60px;
            height: 60px;
            background: rgba(3, 139, 137, 0.1); /* Light Teal Bg */
            color: var(--ath-teal);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            font-size: 1.4rem;
            flex-shrink: 0;
            transition: var(--ath-trans);
        }
        
        .ath-feature:hover .ath-feature-icon {
            background: var(--ath-teal);
            color: #fff;
            transform: rotateY(180deg);
        }
        
        .ath-feature-body h4 { font-size: 1.2rem; margin: 0 0 5px; color: var(--ath-deep); font-weight: 700; }
        .ath-feature-body p { margin: 0; font-size: 0.95rem; color: var(--ath-muted); }
        
        /* Program Cards Enhanced */
        .ath-prog-item {
            background: #fff;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05); /* Softer, deeper shadow */
            transition: var(--ath-trans);
            border: 1px solid rgba(238, 157, 29, 0.05);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            position: relative;
            overflow: hidden;
        }
        
        .ath-prog-item::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 4px; height: 0;
            background: var(--ath-gold);
            transition: height 0.4s ease;
        }
        
        .ath-prog-item:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 25px 60px rgba(5, 88, 96, 0.1);
        }
        
        .ath-prog-item:hover::before { height: 100%; }

        .ath-prog-icon { 
            font-size: 2.5rem; 
            color: var(--ath-gold); 
            margin-bottom: 25px; 
            background: rgba(238, 157, 29, 0.1);
            width: 70px; height: 70px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 18px;
            transition: var(--ath-trans);
        }
        
        .ath-prog-item:hover .ath-prog-icon {
            background: var(--ath-gold);
            color: #fff;
            transform: scale(1.1) rotate(10deg);
        }
        
        .ath-prog-item h3 { font-size: 1.6rem; margin-bottom: 15px; color: var(--ath-text); font-weight: 700; }
        .ath-prog-item p { color: var(--ath-muted); line-height: 1.6; margin-bottom: 25px; }
        
        .ath-link {
            color: var(--ath-teal);
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
            margin-top: auto; /* Push to bottom */
            transition: transform 0.3s ease;
        }
        
        .ath-link:hover { transform: translateX(5px); color: var(--ath-gold); }

        /* Assessment Card */
        .ath-assessment-box {
            background: linear-gradient(135deg, var(--ath-teal), var(--ath-deep));
            border-radius: var(--ath-radius);
            padding: 80px 40px;
            color: #fff;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .ath-assessment-content { position: relative; z-index: 5; max-width: 700px; margin: 0 auto; }

        .ath-assessment-box h3 { font-size: 2.5rem; font-weight: 800; margin-bottom: 20px; }

        /* Pathway Cards with Enhanced UI */
        .ath-step-card {
            background: #fff;
            padding: 50px 30px;
            border-radius: var(--ath-radius);
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            text-align: center;
            transition: var(--ath-trans);
            border: 1px solid rgba(0,0,0,0.03);
            position: relative;
            z-index: 1;
        }

        .ath-step-card:hover { 
            transform: translateY(-15px); 
            box-shadow: 0 20px 50px rgba(3, 139, 137, 0.1);
            border-color: rgba(3, 139, 137, 0.2);
        }
        
        .ath-highlight { 
            background: linear-gradient(to bottom right, #fff, #fffbf0);
            border: 2px solid var(--ath-gold); 
            transform: scale(1.05); /* Slight default scale */
        }
        
        .ath-highlight:hover { transform: scale(1.05) translateY(-15px); }

        .ath-step-num { 
            font-size: 5rem; 
            font-weight: 900; 
            color: rgba(238, 157, 29, 0.15); /* Gold tint */
            margin-bottom: -30px; 
            position: relative; 
            z-index: -1; 
            transition: var(--ath-trans);
        }
        
        .ath-step-card:hover .ath-step-num {
            transform: scale(1.1);
            color: rgba(3, 139, 137, 0.15); /* Change to Teal on hover */
        }
        
        .ath-step-card h4 { font-size: 1.5rem; color: var(--ath-deep); margin-bottom: 15px; font-weight: 700; }
        .ath-step-card p { color: var(--ath-muted); line-height: 1.6; }

        /* Impact Section Stats with Cards */
        .ath-impact { background-color: var(--ath-light); }
        
        .ath-impact-grid {
            display: grid;
            grid-template-columns: 0.8fr 1.2fr;
            gap: 60px;
            align-items: center;
        }

        .ath-impact-info h2 {
            font-size: clamp(2.2rem, 4vw, 3.2rem);
            color: var(--ath-deep);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 25px;
        }

        .ath-impact-info p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--ath-muted);
            margin-bottom: 0;
        }

        .ath-stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .ath-stat {
            background: #fff;
            padding: 40px 30px;
            border-radius: 24px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: var(--ath-trans);
            border: 1px solid rgba(0,0,0,0.03);
            position: relative;
            overflow: hidden;
        }
        
        .ath-stat::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--ath-teal), var(--ath-gold));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .ath-stat:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 20px 40px rgba(3, 139, 137, 0.1); 
        }
        
        .ath-stat:hover::after { opacity: 1; }
        
        .ath-stat-val { font-size: 3.5rem; font-weight: 800; color: var(--ath-teal); margin-bottom: 10px; line-height: 1; }
        .ath-stat-lab { font-weight: 600; color: var(--ath-muted); letter-spacing: 1px; text-transform: uppercase; font-size: 0.85rem; }
        .ath-stat-note { font-size: 0.85rem; color: #6b757e; margin-top: 6px; line-height: 1.5; }

        .ath-ai-link { display: block; margin-top: 10px; font-size: 0.85rem; color: var(--ath-muted); text-decoration: none; font-weight: 600; transition: color 0.2s; }
        .ath-ai-link:hover { color: var(--ath-teal); }

        .ath-operator { background: #fff; }
        .ath-operator-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 30px; margin-top: 20px; }
        @media (max-width: 992px) { .ath-operator-grid { grid-template-columns: 1fr; } }

        /* Stories with Enhanced UI */
        .ath-testi-box {
            background: #fff;
            padding: 60px;
            border-radius: var(--ath-radius);
            box-shadow: 0 15px 50px rgba(0,0,0,0.08); /* Enhanced shadow */
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
            border-top: 5px solid var(--ath-gold); /* Brand detail */
            position: relative;
        }
        
        .ath-testi-box::before {
            content: '\201C';
            position: absolute;
            top: 20px;
            left: 40px;
            font-size: 8rem;
            color: rgba(238, 157, 29, 0.1);
            font-family: serif;
            line-height: 1;
        }

        .ath-quote { 
            font-size: 1.6rem; 
            font-style: italic; 
            line-height: 1.6; 
            color: var(--ath-deep); 
            margin-bottom: 30px; 
            position: relative;
            z-index: 2;
        }
        
        .ath-testi-user { display: flex; align-items: center; justify-content: center; gap: 15px; }
        
        .ath-user-avatar { 
            width: 60px; 
            height: 60px; 
            background: linear-gradient(135deg, var(--ath-gold), #ffb347); 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: 800; 
            color: var(--ath-deep); 
            font-size: 1.5rem;
            box-shadow: 0 5px 15px rgba(238, 157, 29, 0.3);
        }
        
        .ath-user-info h5 { margin: 0; font-size: 1.1rem; color: var(--ath-text); font-weight: 700; }
        .ath-user-info p { margin: 0; font-size: 0.9rem; color: var(--ath-muted); }

        /* CTA */
        .ath-cta-card {
            background: linear-gradient(135deg, var(--ath-teal), var(--ath-deep));
            padding: 100px 40px;
            border-radius: var(--ath-radius);
            text-align: center;
            color: #fff;
            transform: translateY(50px);
        }

        /* Banner */
        .ath-brand-banner { background: var(--ath-deep); padding: 80px 0; color: #fff; text-align: center; }

        /* Reveal Logic */
        .reveal-fade, .reveal-fade-up, .reveal-fade-right, .reveal-fade-left, .reveal-zoom {
            opacity: 0;
            visibility: hidden;
        }
        
        .reveal-visible {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translate(0,0) scale(1) !important;
            transition-delay: calc(var(--delay, 0) * 0.1s);
        }

        @media screen and (min-width: 992px) {
            .reveal-fade, .reveal-fade-up, .reveal-fade-right, .reveal-fade-left, .reveal-zoom {
                transform: translateY(30px);
                transition: opacity 0.8s ease, transform 0.8s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.8s;
            }
            .reveal-fade-right { transform: translateX(-40px); }
            .reveal-fade-left { transform: translateX(40px); }
            .reveal-zoom { transform: scale(0.95); }
        }

        /* Mobile specific fixes for scattered items */
        @media (max-width: 991px) {
            .ath-about-grid, .ath-impact-grid { grid-template-columns: 1fr; text-align: center; }
            .ath-about-card { padding: 30px; }
            .ath-hero-content { text-align: center; margin: 0 auto; }
            .ath-hero-btns { justify-content: center; }
            .ath-section { padding: 80px 0; }
            .ath-hero { height: auto; min-height: 600px; padding: 120px 0 80px; }
            .ath-stats-grid { grid-template-columns: 1fr; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Intersection Observer for Reveal
            const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('reveal-visible');
                        const counter = entry.target.classList.contains('counter') ? entry.target : entry.target.querySelector('.counter');
                        if (counter) startCounter(counter);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.reveal-fade, .reveal-fade-up, .reveal-fade-right, .reveal-fade-left, .reveal-zoom').forEach(el => revealObserver.observe(el));

            // Fire counters that are already visible on load without needing scroll
            document.querySelectorAll('.counter').forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom >= 0) {
                    startCounter(el);
                }
            });

            // Counter Logic
            function startCounter(el) {
                if (el.dataset.started) return;
                el.dataset.started = 'true';
                const target = parseFloat(el.dataset.target);
                const decimals = (el.dataset.target.split('.')[1] || '').length;
                const suffix = el.dataset.suffix || '';
                let current = 0;
                const duration = 2000;
                const step = target / (duration / 16);
                const fmt = (n) => n.toLocaleString(undefined, {
                    minimumFractionDigits: decimals,
                    maximumFractionDigits: decimals
                }) + suffix;
                const update = () => {
                    current += step;
                    if (current < target) {
                        el.textContent = fmt(decimals ? parseFloat(current.toFixed(decimals)) : Math.floor(current));
                        requestAnimationFrame(update);
                    } else {
                        el.textContent = fmt(target);
                    }
                };
                update();
            }

            // Cleanup potential scroll bottlenecks
            document.getElementById('assessmentToolBtn')?.addEventListener('click', () => {
                window.location.href = '{{ route("assessment.index") }}';
            });

            // Hero Slider Logic
            const slides = document.querySelectorAll('.ath-hero-content');
            const dots = document.querySelectorAll('.ath-dot');
            let currentSlide = 0;
            let slideInterval;

            function goToSlide(n) {
                slides[currentSlide].classList.remove('active');
                dots[currentSlide].classList.remove('active');
                currentSlide = (n + slides.length) % slides.length;
                slides[currentSlide].classList.add('active');
                dots[currentSlide].classList.add('active');
            }

            function nextSlide() {
                goToSlide(currentSlide + 1);
            }

            function startSlideShow() {
                slideInterval = setInterval(nextSlide, 6000); // 6 seconds per slide
            }

            function stopSlideShow() {
                clearInterval(slideInterval);
            }

            dots.forEach(dot => {
                dot.addEventListener('click', () => {
                    stopSlideShow();
                    goToSlide(parseInt(dot.dataset.slide));
                    startSlideShow();
                });
            });

            // Start the slider
            startSlideShow();
        });
    </script>
    @endpush
@endsection
