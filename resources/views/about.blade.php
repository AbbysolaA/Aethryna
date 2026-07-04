@extends('layouts.aethryna')

@section('title', 'About SkillsCo-op | Widening Access to Digital Skills & Meaningful Progression')

@section('meta_description', 'Meet the team behind SkillsCo-op and learn how Aethryna Digital Skills Co-op CIC is widening access to digital careers for underserved communities across the UK.')
@section('og_description', 'Meet the team behind SkillsCo-op and learn how Aethryna Digital Skills Co-op CIC is widening access to digital careers for underserved communities across the UK.')

@section('content')

    <!-- Hero Section -->
    <section class="about-hero">
        <div class="hero-content">
            <h1>About SkillsCo-op</h1>
            <p>Widening access to digital skills and meaningful progression for underserved communities</p>
        </div>
        <div class="hero-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission">
        <div class="section-title">
            <h2>Our Mission</h2>
            <p>Widening access to digital skills and meaningful career progression for underserved communities</p>
        </div>
        <div class="mission-content">
            <div class="mission-text">
                <h3>Building Futures Together</h3>
                <p>SkillsCo-op is a community-driven skills cooperative designed to widen access to digital skills and
                    meaningful progression for underserved communities. Through our structured curriculum, pathways, and
                    delivery architecture, we connect aspiring learners with experienced professionals who guide them toward
                    achieving their personal and professional goals.</p>
                <p>Our approach combines personalised mentorship with skill-building workshops, trauma-informed learning
                    design, and community engagement opportunities that prepare learners for the challenges of tomorrow.</p>

                <div class="mission-stats">
                    <div class="stat">
                        <span class="stat-number">4</span>
                        <span class="stat-label">Specialist Tracks</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Tuition Funded</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">3</span>
                        <span class="stat-label">Phase Pathway</span>
                    </div>
                </div>
            </div>
            <div class="mission-image">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=870&auto=format&fit=crop"
                    alt="Community learning and collaboration">
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values">
        <div class="section-title">
            <h2>Our Core Values</h2>
            <p>The principles that guide everything we do</p>
        </div>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <h3>Mentorship</h3>
                <p>We believe in the power of one-on-one relationships to transform lives and build lasting connections
                    between mentors and learners.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Accessibility</h3>
                <p>Quality education and skill development are fundamental rights. We provide accessible, fully funded
                    learning opportunities for all.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Dignity & Belonging</h3>
                <p>Every learner journey is built on evidence, dignity, and belonging. We design trauma-informed,
                    psychologically safe learning experiences.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Community</h3>
                <p>Strong communities are built on collaboration and support. We foster inclusive environments where
                    everyone can thrive.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h3>Innovation</h3>
                <p>We embrace AI-driven products, data science, and new technologies to create better solutions for digital
                    skills development.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <h3>Equity</h3>
                <p>We are committed to addressing systemic barriers and ensuring equal opportunities for all, regardless of
                    background.</p>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team">
        <div class="section-title">
            <h2>Our Leadership Team</h2>
            <p>Meet the passionate individuals driving the SkillsCo-op mission forward</p>
        </div>
        <div class="team-grid">
            <!-- Abisola Areola -->
            <div class="team-member">
                <div class="member-image">
                    <img src="{{ asset('images/team/abisola.jpg') }}" alt="Abisola Areola">
                </div>
                <div class="member-info">
                    <h3>Abisola Areola</h3>
                    <p class="member-role">Founder & Executive Director</p>
                    <p class="member-credentials">Project Manager &bull; Data Analyst &bull; AI & Digital Transformation</p>
                    <p class="member-bio">Data analytics and project management professional who designed the entire
                        SkillsCo-op model; curriculum, pathways, and delivery architecture to widen access to digital skills
                        and meaningful progression for underserved communities.</p>
                </div>
            </div>

            <!-- Farouk Abdulrazaq -->
            <div class="team-member">
                <div class="member-image">
                    <img src="{{ asset('images/team/farouk.jpg') }}" alt="Farouk Abdulrazaq">
                </div>
                <div class="member-info">
                    <h3>Saheed Bello</h3>
                    <p class="member-role">Director of learner wellbeing, safeguarding & behavioural design</p>
                    <p class="member-credentials">MSc Social Psychology PhD Researcher</p>
                    <p class="member-bio">Leads the design of trauma-informed, psychologically safe learning experiences
                        across all SkillsCo-op programmes, ensuring every learner journey is built on evidence, dignity, and
                        belonging.</p>
                </div>
            </div>

            <!-- Saheed Bello -->
            <div class="team-member">
                <div class="member-image">
                    <img src="{{ asset('images/team/saheed.jpg') }}" alt="Saheed Bello">
                </div>
                <div class="member-info">
                    <h3>Seun Adetule</h3>
                    <p class="member-role">UK Global Tech Talent Awardee</p>
                    <p class="member-credentials">AI & Digital Transformation B2B SaaS Sales</p>
                    <p class="member-bio">Over a decade of experience in enterprise technology, Go-To-Market strategy, and
                        AI-driven business growth. Brings expertise in scaling technology ventures to SkillsCo-op's mission.
                    </p>
                </div>
            </div>

            <!-- Idowu Seun Adetule -->
            <div class="team-member">
                <div class="member-image">
                    <img src="{{ asset('images/team/idowu.jpg') }}" alt="Idowu Seun Adetule">
                </div>
                <div class="member-info">
                    <h3>Farouk Adams</h3>
                    <p class="member-role">Adviser - data, AI & impact technology</p>
                    <p class="member-credentials">MSc Data Science - STEM Ambassador</p>
                    <p class="member-bio">Data scientist and health-tech innovator with experience building AI-driven
                        products in education and healthcare. Co-founder of Soraflake, bringing deep expertise in using data
                        and machine learning to improve outcomes for underserved communities.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- History Section -->
    <section class="history">
        <div class="section-title">
            <h2>Our Story</h2>
            <p>How SkillsCo-op came to be</p>
        </div>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3>The Vision</h3>
                    <p>SkillsCo-op was born from a recognition that too many talented individuals in underserved communities
                        lacked access to quality digital skills training and meaningful career pathways.</p>
                </div>
                <div class="timeline-marker"></div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3>The Model</h3>
                    <p>Our founder Abisola Areola designed the entire SkillsCo-op model (curriculum, pathways, and
                        delivery architecture), drawing on her expertise in data analytics, project management, and
                        digital transformation.</p>
                </div>
                <div class="timeline-marker"></div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3>Building the Team</h3>
                    <p>Assembled a team of experts across data science, social psychology, enterprise technology, and
                        community development to create an integrated approach to skills training.</p>
                </div>
                <div class="timeline-marker"></div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3>The Pathway</h3>
                    <p>Designed the 25-week Skills Co-op Pathway around four pilot tracks: Project and Product Delivery, Data and AI Analytics, Digital Design and Marketing, and Software Development. Every place fully funded.</p>
                </div>
                <div class="timeline-marker"></div>
            </div>
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3>Looking Ahead</h3>
                    <p>Expanding our impact through monthly panel sessions, AI-powered assessment tools, and deeper
                        community partnerships to reach more underserved learners across the UK.</p>
                </div>
                <div class="timeline-marker"></div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-content">
            <h2>Join Our Mission</h2>
            <p>Whether you're seeking digital skills growth or a professional wanting to mentor, there's a place for you at
                SkillsCo-op.</p>
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="btn btn-primary">Become a Learner</a>
                <a href="mailto:hello@skillscoop.org" class="btn btn-outline">Become a Mentor</a>
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

            /* General Section Header */
            .section-title {
                text-align: left;
                margin-bottom: 3rem;
                max-width: 1200px;
                margin-left: auto;
                margin-right: auto;
            }

            .section-title h2,
            .mission-text h3 {
                font-size: clamp(2.2rem, 4vw, 3rem);
                color: var(--ath-deep);
                font-weight: 800;
                margin-bottom: 1rem;
                line-height: 1.2;
            }

            .section-title p {
                font-size: 1.2rem;
                color: var(--ath-muted);
                line-height: 1.6;
                max-width: 800px;
            }

            /* About Hero Section */
            .about-hero {
                height: 60vh;
                background: linear-gradient(135deg, var(--ath-deep), var(--ath-teal));
                background-size: cover;
                background-position: center;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                color: white;
                position: relative;
                overflow: hidden;
            }

            .about-hero .hero-content h1 {
                font-size: clamp(3rem, 8vw, 5rem);
                font-weight: 800;
                margin-bottom: 1.5rem;
                text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            }

            .about-hero .hero-content p {
                font-size: 1.4rem;
                max-width: 700px;
                margin: 0 auto;
                opacity: 0.9;
            }

            .hero-shapes {
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                pointer-events: none;
            }

            .hero-shapes .shape {
                position: absolute;
                background: linear-gradient(135deg, var(--ath-teal), transparent);
                border-radius: 50%;
                filter: blur(40px);
                opacity: 0.3;
                animation: float 8s ease-in-out infinite;
            }

            .hero-shapes .shape:nth-child(1) {
                width: 300px;
                height: 300px;
                top: 10%;
                left: 5%;
                animation-delay: 0s;
            }

            .hero-shapes .shape:nth-child(2) {
                width: 200px;
                height: 200px;
                top: 60%;
                right: 10%;
                animation-delay: 2s;
                background: linear-gradient(135deg, var(--ath-gold), transparent);
            }

            .hero-shapes .shape:nth-child(3) {
                width: 150px;
                height: 150px;
                bottom: 20%;
                left: 20%;
                animation-delay: 4s;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px) rotate(0deg) scale(1);
                }

                50% {
                    transform: translateY(-40px) rotate(180deg) scale(1.1);
                }
            }

            /* Mission Section */
            .mission {
                padding: 100px 5%;
                background: var(--ath-light);
                margin-top: -50px;
                position: relative;
                z-index: 10;
            }

            .mission-content {
                max-width: 1200px;
                margin: 0 auto;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 5rem;
                align-items: center;
            }

            .mission-text p {
                font-size: 1.1rem;
                margin-bottom: 2rem;
                color: var(--ath-text);
                line-height: 1.8;
            }

            .mission-stats {
                display: flex;
                gap: 3rem;
                margin-top: 3rem;
            }

            .mission-stats .stat {
                text-align: center;
            }

            .mission-stats .stat-number {
                font-size: 2.5rem;
                font-weight: 800;
                color: var(--ath-teal);
                display: block;
            }

            .mission-stats .stat-label {
                font-size: 1rem;
                color: var(--ath-text);
                margin-top: 0.5rem;
            }

            .mission-image img {
                width: 100%;
                border-radius: var(--ath-radius);
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            }

            /* Values Section */
            .values {
                padding: 8rem 5%;
                background: white;
            }

            .values-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
                gap: 3rem;
                max-width: 1200px;
                margin: 0 auto;
            }

            .value-card {
                background: var(--ath-light);
                padding: 3rem 2rem;
                border-radius: var(--ath-radius);
                text-align: center;
                transition: var(--ath-trans);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                position: relative;
                overflow: hidden;
            }

            .value-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 5px;
                background: linear-gradient(90deg, var(--ath-teal), var(--ath-gold));
            }

            .value-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
            }

            .value-icon {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, var(--ath-teal), var(--ath-gold));
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 2rem;
                color: white;
                font-size: 2rem;
                transition: var(--ath-trans);
            }

            .value-card:hover .value-icon {
                transform: scale(1.1);
            }

            .value-card h3 {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
                color: var(--ath-deep);
            }

            .value-card p {
                color: var(--ath-text);
                line-height: 1.7;
            }

            /* Team Section */
            .team {
                padding: 100px 5%;
                background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            }

            .team-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 3rem;
                max-width: 1200px;
                margin: 0 auto;
            }

            .team-member {
                background: white;
                border-radius: var(--ath-radius);
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                transition: var(--ath-trans);
            }

            .team-member:hover {
                transform: translateY(-10px);
                box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
            }

            .member-image {
                height: 260px;
                overflow: hidden;
            }

            .member-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: var(--ath-trans);
            }

            .team-member:hover .member-image img {
                transform: scale(1.05);
            }

            .member-info {
                padding: 2rem;
            }

            .member-info h3 {
                font-size: 1.4rem;
                margin-bottom: 0.5rem;
                color: var(--ath-deep);
                font-weight: 700;
            }

            .member-role {
                color: var(--ath-gold);
                font-weight: 600;
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .member-credentials {
                color: var(--ath-teal);
                font-weight: 600;
                font-size: 0.85rem;
                margin-bottom: 1rem;
            }

            .member-bio {
                color: var(--ath-text);
                line-height: 1.6;
                font-size: 0.95rem;
            }

            /* History Section */
            .history {
                padding: 100px 5%;
                background: white;
            }

            .timeline {
                max-width: 800px;
                margin: 0 auto;
                position: relative;
            }

            .timeline::before {
                content: '';
                position: absolute;
                left: 50%;
                top: 0;
                bottom: 0;
                width: 2px;
                background: linear-gradient(to bottom, var(--ath-teal), var(--ath-gold));
                transform: translateX(-50%);
            }

            .timeline-item {
                margin-bottom: 4rem;
                position: relative;
                width: 100%;
            }

            .timeline-item:nth-child(even) .timeline-content {
                margin-left: auto;
                margin-right: 0;
                text-align: right;
            }

            .timeline-content {
                background: var(--ath-light);
                padding: 2.5rem;
                border-radius: var(--ath-radius);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                max-width: 420px;
                position: relative;
                margin-left: 60px;
                transition: var(--ath-trans);
            }

            .timeline-item:nth-child(even) .timeline-content {
                margin-left: auto;
                margin-right: 60px;
            }

            .timeline-content h3 {
                color: var(--ath-teal);
                margin-bottom: 1rem;
                font-size: 1.4rem;
                font-weight: 700;
            }

            .timeline-content p {
                color: var(--ath-text);
                line-height: 1.6;
            }

            .timeline-marker {
                position: absolute;
                left: 50%;
                top: 2rem;
                width: 20px;
                height: 20px;
                background: var(--ath-gold);
                border-radius: 50%;
                border: 4px solid white;
                box-shadow: 0 0 0 4px var(--ath-teal);
                transform: translateX(-50%);
                z-index: 1;
            }

            /* CTA Section */
            .cta {
                background: linear-gradient(135deg, var(--ath-teal), var(--ath-deep));
                color: white;
                text-align: center;
                padding: 100px 2rem;
                position: relative;
                overflow: hidden;
            }

            .cta-content {
                max-width: 800px;
                margin: 0 auto;
                position: relative;
                z-index: 2;
            }

            .cta h2 {
                font-size: 3.5rem;
                margin-bottom: 2rem;
            }

            .cta p {
                font-size: 1.4rem;
                margin-bottom: 3rem;
                opacity: 0.9;
            }

            .cta-buttons {
                display: flex;
                gap: 2rem;
                justify-content: center;
            }

            /* Mobile Responsive */
            @media (max-width: 992px) {
                .about-hero .hero-content h1 {
                    font-size: 3rem;
                }

                .mission-content {
                    grid-template-columns: 1fr;
                    gap: 3rem;
                }

                .mission-stats {
                    justify-content: center;
                }

                .values-grid {
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                }

                .team-grid {
                    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                }

                .timeline::before {
                    left: 30px;
                }

                .timeline-marker {
                    left: 30px;
                }

                .timeline-content {
                    margin-left: 80px;
                    max-width: none;
                }

                .timeline-item:nth-child(even) .timeline-content {
                    margin-left: 80px;
                    margin-right: 0;
                    text-align: left;
                }
            }

            @media (max-width: 768px) {
                .about-hero {
                    height: 50vh;
                }

                .about-hero .hero-content h1 {
                    font-size: 2.5rem;
                }

                .about-hero .hero-content p {
                    font-size: 1.2rem;
                }

                .values-grid {
                    grid-template-columns: 1fr;
                }

                .team-grid {
                    grid-template-columns: 1fr;
                }

                .cta h2 {
                    font-size: 2.8rem;
                }

                .cta-buttons {
                    flex-direction: column;
                    align-items: center;
                }

                .timeline::before {
                    display: none;
                }

                .timeline-marker {
                    display: none;
                }

                .timeline-content {
                    margin-left: 0;
                    text-align: left;
                }

                .timeline-item:nth-child(even) .timeline-content {
                    margin-left: 0;
                }
            }

            @media (max-width: 576px) {
                .about-hero .hero-content h1 {
                    font-size: 2rem;
                }

                .mission-stats {
                    flex-direction: column;
                    gap: 2rem;
                }

                .value-card {
                    padding: 2rem 1.5rem;
                }

                .member-info {
                    padding: 1.5rem;
                }

                .cta h2 {
                    font-size: 2.2rem;
                }

                .cta p {
                    font-size: 1.2rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
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

            // Observe value cards
            document.querySelectorAll('.value-card').forEach(card => {
                observer.observe(card);
            });

            // Observe team members
            document.querySelectorAll('.team-member').forEach(member => {
                observer.observe(member);
            });

            // Observe timeline items
            document.querySelectorAll('.timeline-item').forEach(item => {
                observer.observe(item);
            });
        </script>
    @endpush
@endsection
