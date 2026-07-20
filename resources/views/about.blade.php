@extends('layouts.aethryna')

@section('title', 'About SkillsCo-op | Aethryna Digital Skills Co-Op CIC')

@section('meta_description', 'SkillsCo-op is the public-facing brand of Aethryna Digital Skills Co-Op CIC, a UK Community Interest Company closing the digital skills gap for the people the labour market overlooks.')
@section('og_description', 'SkillsCo-op is the public-facing brand of Aethryna Digital Skills Co-Op CIC, a UK Community Interest Company closing the digital skills gap for the people the labour market overlooks.')

@section('content')

<!-- Hero -->
<section class="ab-hero">
    <div class="ath-container">
        <div class="ab-hero-inner">
            <span class="ab-eyebrow">About SkillsCo-op</span>
            <h1 class="ab-title">Digital skills for the people the system <span class="ab-gradient">misses.</span></h1>
            <p class="ab-lede">SkillsCo-op is a funded 25-week digital skills programme built for people the traditional pipeline was never designed for. Career changers. Refugees. Young people out of work. People returning after prison, illness, or caring responsibilities. We teach them their craft, we embed AI as a working tool, and we teach them to sell, support, and run their own work. Graduates leave as whole economic units, not just specialists.</p>
            <div class="ab-hero-actions">
                <a href="{{ route('pathway') }}" class="ab-btn ab-btn-primary">See the pathway</a>
                <a href="{{ route('partners') }}" class="ab-btn ab-btn-ghost">Partner with us</a>
            </div>
        </div>
    </div>
</section>

<!-- Why we exist -->
<section class="ab-why">
    <div class="ath-container">
        <div class="ab-why-grid">
            <div class="ab-why-copy">
                <span class="ath-sub">Why we exist</span>
                <h2>The old pipeline was not built for everyone.</h2>
                <p>Digital skills programmes in the UK reach the people already closest to the market. The bootcamp graduate who could pay the fee. The Russell Group student on a placement. The apprentice already inside a supportive employer. SkillsCo-op exists for everyone else.</p>
                <p>We work with NEET young people, career changers, migrants and refugees, and justice-involved learners. We teach them the digital work employers now need, and we give them the operator basics so they never depend on a single employer to earn a living.</p>
            </div>
            <div class="ab-why-stats">
                <div class="ab-stat-card">
                    <div class="ab-stat-num">100%</div>
                    <div class="ab-stat-lbl">of learner places are funded</div>
                </div>
                <div class="ab-stat-card">
                    <div class="ab-stat-num">25</div>
                    <div class="ab-stat-lbl">weeks from foundations to launch</div>
                </div>
                <div class="ab-stat-card">
                    <div class="ab-stat-num">3</div>
                    <div class="ab-stat-lbl">certificates along the journey</div>
                </div>
                <div class="ab-stat-card">
                    <div class="ab-stat-num">4</div>
                    <div class="ab-stat-lbl">pilot tracks for Cohort 1</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What makes us different -->
<section class="ab-pillars">
    <div class="ath-container">
        <div class="ab-pillars-header">
            <span class="ath-sub">The Skills Co-op difference</span>
            <h2>Four things you will not find bundled anywhere else.</h2>
        </div>
        <div class="ab-pillars-grid">
            <article class="ab-pillar">
                <span class="ab-pillar-num">01</span>
                <h3>Verification-first AI</h3>
                <p>Every learner uses AI from week one, and every learner is trained to check its output, spot bias, and override it when it is wrong. The habit is called the AI Method.</p>
                <a href="{{ route('ai-labs') }}" class="ab-pillar-link">Read about the AI Method &rarr;</a>
            </article>
            <article class="ab-pillar">
                <span class="ab-pillar-num">02</span>
                <h3>The Operator Core</h3>
                <p>Sell your work. Support your work. Run your work with AI. Taught to every learner in every track, so graduates can market and deliver a service on the day they leave.</p>
                <a href="{{ route('pathway') }}#operator-core" class="ab-pillar-link">See the Operator Core &rarr;</a>
            </article>
            <article class="ab-pillar">
                <span class="ab-pillar-num">03</span>
                <h3>The Venture Track</h3>
                <p>The last twelve weeks are not a hypothetical brief. Teams originate a business idea, run the professional handoff chain, ship it, then launch and sell it. Or take a Brief Track for those pursuing employment.</p>
                <a href="{{ route('pathway') }}" class="ab-pillar-link">See the 25-week journey &rarr;</a>
            </article>
            <article class="ab-pillar">
                <span class="ab-pillar-num">04</span>
                <h3>Trauma-informed by design</h3>
                <p>Every part of the programme, from ideation to graduation, is built around dignity, psychological safety, and belonging. Not as a bolt-on. As the foundation.</p>
                <a href="{{ route('impact') }}" class="ab-pillar-link">See our Outcomes Framework &rarr;</a>
            </article>
        </div>
    </div>
</section>

<!-- Team -->
<section class="ab-team">
    <div class="ath-container">
        <div class="ab-team-header">
            <span class="ath-sub">The Team</span>
            <h2>Practitioners across data, learning, and community</h2>
            <p>SkillsCo-op is designed and delivered by people who have themselves navigated non-traditional routes into digital work. The founding team brings expertise across data science, project delivery, behavioural design, and enterprise technology.</p>
        </div>
        <div class="ab-team-grid">
            <article class="ab-member">
                <div class="ab-member-photo" style="background-image:url('{{ asset('images/team/abisola.jpg') }}');">
                    <div class="ab-member-gradient"></div>
                </div>
                <div class="ab-member-body">
                    <div class="ab-member-role">Founder &amp; Executive Director</div>
                    <h3>Abisola Areola</h3>
                    <p class="ab-member-cred">Project Manager · Data Analyst · AI &amp; Digital Transformation</p>
                    <p class="ab-member-bio">Data analytics and project management professional who designed the entire SkillsCo-op model: the curriculum, pathways, and delivery architecture that widens access to digital skills and meaningful progression for underserved communities.</p>
                </div>
            </article>
            <article class="ab-member">
                <div class="ab-member-photo" style="background-image:url('{{ asset('images/team/farouk.jpg') }}');">
                    <div class="ab-member-gradient"></div>
                </div>
                <div class="ab-member-body">
                    <div class="ab-member-role">Director of Learner Wellbeing, Safeguarding &amp; Behavioural Design</div>
                    <h3>Saheed Bello</h3>
                    <p class="ab-member-cred">MSc Social Psychology · PhD Researcher</p>
                    <p class="ab-member-bio">Leads the design of trauma-informed, psychologically safe learning experiences across every SkillsCo-op programme, ensuring every learner journey is built on evidence, dignity, and belonging.</p>
                </div>
            </article>
            <article class="ab-member">
                <div class="ab-member-photo" style="background-image:url('{{ asset('images/team/saheed.jpg') }}');">
                    <div class="ab-member-gradient"></div>
                </div>
                <div class="ab-member-body">
                    <div class="ab-member-role">Adviser · Enterprise Technology &amp; Go-To-Market</div>
                    <h3>Seun Adetule</h3>
                    <p class="ab-member-cred">UK Global Tech Talent Awardee · AI &amp; B2B SaaS Sales</p>
                    <p class="ab-member-bio">Over a decade of experience in enterprise technology, go-to-market strategy, and AI-driven business growth. Brings expertise in scaling technology ventures to the SkillsCo-op mission.</p>
                </div>
            </article>
            <article class="ab-member">
                <div class="ab-member-photo" style="background-image:url('{{ asset('images/team/idowu.jpg') }}');">
                    <div class="ab-member-gradient"></div>
                </div>
                <div class="ab-member-body">
                    <div class="ab-member-role">Adviser · Data, AI &amp; Impact Technology</div>
                    <h3>Farouk Adams</h3>
                    <p class="ab-member-cred">MSc Data Science · STEM Ambassador</p>
                    <p class="ab-member-bio">Data scientist and health-tech innovator with experience building AI-driven products in education and healthcare. Co-founder of Soraflake, bringing deep expertise in using data and machine learning to improve outcomes for underserved communities.</p>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- Values -->
<section class="ab-values">
    <div class="ath-container">
        <div class="ab-values-header">
            <span class="ath-sub">What we stand for</span>
            <h2>Six principles we test every decision against</h2>
        </div>
        <div class="ab-values-grid">
            <div class="ab-value">
                <div class="ab-value-icon"><i class="fas fa-hands-helping"></i></div>
                <h3>Mentorship</h3>
                <p>Real relationships between working professionals and learners, not scripted content.</p>
            </div>
            <div class="ab-value">
                <div class="ab-value-icon"><i class="fas fa-unlock-alt"></i></div>
                <h3>Accessibility</h3>
                <p>Quality is a right, not a paywall. Every place is fully funded and every learner has a path in.</p>
            </div>
            <div class="ab-value">
                <div class="ab-value-icon"><i class="fas fa-heart"></i></div>
                <h3>Dignity &amp; Belonging</h3>
                <p>Trauma-informed and psychologically safe. Nobody is asked to perform who they are not.</p>
            </div>
            <div class="ab-value">
                <div class="ab-value-icon"><i class="fas fa-users"></i></div>
                <h3>Community</h3>
                <p>Membership does not end with the cohort. Alumni become mentors, co-founders, and hiring partners.</p>
            </div>
            <div class="ab-value">
                <div class="ab-value-icon"><i class="fas fa-robot"></i></div>
                <h3>AI with judgement</h3>
                <p>AI does the bulk of the work. Humans provide checks, judgement, verification, direction, and ethics.</p>
            </div>
            <div class="ab-value">
                <div class="ab-value-icon"><i class="fas fa-balance-scale"></i></div>
                <h3>Equity</h3>
                <p>Barriers are systemic. So are the solutions. We remove structural friction, not just individual friction.</p>
            </div>
        </div>
    </div>
</section>

<!-- Timeline -->
<section class="ab-story">
    <div class="ath-container">
        <div class="ab-story-header">
            <span class="ath-sub">How we got here</span>
            <h2>A short history of the model</h2>
        </div>
        <ol class="ab-timeline">
            <li>
                <span class="ab-time-tag">The Question</span>
                <h3>What would a different pipeline look like?</h3>
                <p>SkillsCo-op began with a recognition: talented people in underserved communities were being routinely passed over by digital skills programmes designed around learners who already had a way in.</p>
            </li>
            <li>
                <span class="ab-time-tag">The Model</span>
                <h3>Curriculum, pathways, and delivery architecture</h3>
                <p>Founder Abisola Areola designed the whole model: 25 weeks, three certificates, four tracks, an operator core, and a project period that ends with a real venture or a real employer brief.</p>
            </li>
            <li>
                <span class="ab-time-tag">The Team</span>
                <h3>Practitioners across the disciplines the model needs</h3>
                <p>Data science, social psychology, enterprise technology, and community development, brought together around a single mission and a shared operating language.</p>
            </li>
            <li>
                <span class="ab-time-tag">The Pathway</span>
                <h3>Four pilot tracks, all fully funded</h3>
                <p>Project and Product Delivery, Data and AI Analytics, Product Design and Marketing, and Software Development, each with AI embedded from week one and the operator core running through every module.</p>
            </li>
            <li>
                <span class="ab-time-tag">Now</span>
                <h3>Cohort 1 launches January 2027</h3>
                <p>Thirty founding places. The AI Labs Practice Space live from day one. The panel sessions running monthly. The flight path to the AI Labs Fellowship in 2027 already public.</p>
            </li>
        </ol>
    </div>
</section>

<!-- CTA -->
<section class="ab-cta">
    <div class="ath-container">
        <div class="ab-cta-grid">
            <div class="ab-cta-card ab-cta-learner">
                <span class="ab-cta-eyebrow">For learners</span>
                <h3>Ready to build your own way in?</h3>
                <p>Applications for the founding cohort are open now. Places are limited to thirty.</p>
                <a href="{{ route('register') }}" class="ab-btn ab-btn-primary">Start your application</a>
            </div>
            <div class="ab-cta-card ab-cta-partner">
                <span class="ab-cta-eyebrow">For partners</span>
                <h3>Back the pipeline, or bring a brief.</h3>
                <p>Fund a place, share a real business challenge, or offer a placement to a graduating cohort.</p>
                <a href="{{ route('partners') }}" class="ab-btn ab-btn-ghost">Partner with us</a>
            </div>
        </div>
    </div>
</section>

@push('styles')
<link href="https://fonts.bunny.net/css?family=ibm-plex-mono:500,600&display=swap" rel="stylesheet">
<style>
    :root {
        --ath-teal: #038b89;
        --ath-gold: #ee9d1d;
        --ath-deep: #055860;
        --ath-navy: #0a2530;
        --ath-light: #F8FBFB;
        --ath-text: #404952;
        --ath-muted: #57616a;
        --font-mono: 'IBM Plex Mono', 'Courier New', monospace;
    }

    .ath-container { max-width: 1250px; margin: 0 auto; padding: 0 5%; }

    /* ── Hero ────────────────────────────────────────────────────────── */
    .ab-hero {
        padding: 180px 0 100px;
        background: linear-gradient(180deg, var(--ath-deep) 0%, var(--ath-navy) 100%);
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .ab-hero::after {
        content: '';
        position: absolute;
        top: -20%; right: -10%;
        width: 60%; height: 130%;
        background: radial-gradient(closest-side, rgba(238,157,29,0.16), transparent 70%);
        pointer-events: none;
    }
    .ab-hero-inner { max-width: 880px; position: relative; z-index: 1; }
    .ab-eyebrow {
        display: inline-block;
        font-family: var(--font-mono);
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--ath-gold);
        margin-bottom: 22px;
        padding-left: 14px;
        border-left: 4px solid var(--ath-gold);
    }
    .ab-title {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(2.4rem, 6vw, 4.2rem);
        font-weight: 800;
        line-height: 1.05;
        margin-bottom: 26px;
    }
    .ab-gradient {
        background: linear-gradient(135deg, var(--ath-gold), #fff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .ab-lede {
        font-size: 1.15rem;
        line-height: 1.75;
        color: rgba(255,255,255,0.82);
        max-width: 780px;
        margin-bottom: 36px;
    }
    .ab-hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }
    .ab-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        border-radius: 100px;
        font-weight: 700;
        font-size: 0.98rem;
        text-decoration: none;
        transition: all 0.25s ease;
    }
    .ab-btn-primary { background: var(--ath-gold); color: #fff; }
    .ab-btn-primary:hover { background: #fff; color: var(--ath-navy); transform: translateY(-2px); }
    .ab-btn-ghost {
        background: rgba(255,255,255,0.06);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.18);
    }
    .ab-btn-ghost:hover { background: rgba(255,255,255,0.12); }

    .ath-sub {
        display: block;
        font-family: var(--font-mono);
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--ath-gold);
        margin-bottom: 12px;
    }

    /* ── Why we exist ─────────────────────────────────────────────────── */
    .ab-why { padding: 110px 0; background: #fff; }
    .ab-why-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 80px;
        align-items: center;
    }
    .ab-why-copy h2 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(1.8rem, 4vw, 2.6rem);
        color: var(--ath-deep);
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 22px;
    }
    .ab-why-copy p {
        color: var(--ath-text);
        font-size: 1.05rem;
        line-height: 1.75;
        margin-bottom: 16px;
    }
    .ab-why-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }
    .ab-stat-card {
        background: var(--ath-light);
        border: 1px solid rgba(3,139,137,0.1);
        border-radius: 20px;
        padding: 28px 24px;
        transition: border-color 0.3s, transform 0.3s;
    }
    .ab-stat-card:hover { border-color: var(--ath-teal); transform: translateY(-4px); }
    .ab-stat-num {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(2rem, 5vw, 2.8rem);
        font-weight: 800;
        color: var(--ath-teal);
        line-height: 1;
        margin-bottom: 8px;
    }
    .ab-stat-lbl { color: var(--ath-muted); font-size: 0.92rem; line-height: 1.5; }

    /* ── Pillars ─────────────────────────────────────────────────────── */
    .ab-pillars { padding: 110px 0; background: var(--ath-light); }
    .ab-pillars-header { max-width: 780px; margin-bottom: 50px; }
    .ab-pillars-header h2 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(1.8rem, 4vw, 2.6rem);
        color: var(--ath-deep);
        font-weight: 800;
        line-height: 1.15;
    }
    .ab-pillars-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }
    .ab-pillar {
        background: #fff;
        border: 1px solid rgba(3,139,137,0.1);
        border-radius: 24px;
        padding: 36px;
        transition: border-color 0.3s, transform 0.3s, box-shadow 0.3s;
        position: relative;
    }
    .ab-pillar:hover {
        border-color: var(--ath-gold);
        transform: translateY(-4px);
        box-shadow: 0 20px 60px rgba(3,139,137,0.08);
    }
    .ab-pillar-num {
        display: inline-block;
        font-family: var(--font-mono);
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 2px;
        color: var(--ath-gold);
        margin-bottom: 14px;
        padding: 4px 10px;
        border-radius: 100px;
        background: rgba(238,157,29,0.12);
    }
    .ab-pillar h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.4rem;
        color: var(--ath-deep);
        font-weight: 800;
        margin-bottom: 12px;
    }
    .ab-pillar p {
        color: var(--ath-muted);
        line-height: 1.7;
        margin-bottom: 18px;
    }
    .ab-pillar-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--ath-teal);
        font-weight: 700;
        text-decoration: none;
        font-size: 0.95rem;
        transition: gap 0.2s;
    }
    .ab-pillar-link:hover { gap: 14px; color: var(--ath-gold); }

    /* ── Team ────────────────────────────────────────────────────────── */
    .ab-team { padding: 110px 0; background: #fff; }
    .ab-team-header { max-width: 780px; margin-bottom: 50px; }
    .ab-team-header h2 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(1.8rem, 4vw, 2.6rem);
        color: var(--ath-deep);
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 16px;
    }
    .ab-team-header p {
        color: var(--ath-muted);
        font-size: 1.05rem;
        line-height: 1.7;
    }
    .ab-team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 28px;
    }
    .ab-member {
        background: var(--ath-navy);
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(255,255,255,0.08);
        display: flex;
        flex-direction: column;
        transition: transform 0.3s, border-color 0.3s;
    }
    .ab-member:hover { transform: translateY(-6px); border-color: rgba(238,157,29,0.4); }
    .ab-member-photo {
        aspect-ratio: 4 / 5;
        position: relative;
        background-color: #12303c;
        background-size: cover;
        background-position: center top;
    }
    .ab-member-gradient {
        position: absolute; inset: 0;
        background: linear-gradient(180deg, transparent 40%, rgba(10,37,48,0.7) 78%, var(--ath-navy) 100%);
    }
    .ab-member-body {
        padding: 26px 28px 30px;
        margin-top: -10px;
        color: #fff;
    }
    .ab-member-role {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--ath-gold);
        margin-bottom: 10px;
    }
    .ab-member h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 8px;
        display: inline-block;
        border-bottom: 3px solid var(--ath-gold);
        padding-bottom: 2px;
    }
    .ab-member-cred {
        color: rgba(255,255,255,0.75);
        font-size: 0.92rem;
        margin-bottom: 12px;
    }
    .ab-member-bio {
        color: rgba(255,255,255,0.7);
        font-size: 0.92rem;
        line-height: 1.7;
    }

    /* ── Values ──────────────────────────────────────────────────────── */
    .ab-values { padding: 110px 0; background: var(--ath-light); }
    .ab-values-header { max-width: 780px; margin-bottom: 50px; }
    .ab-values-header h2 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(1.8rem, 4vw, 2.6rem);
        color: var(--ath-deep);
        font-weight: 800;
        line-height: 1.15;
    }
    .ab-values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
    }
    .ab-value {
        background: #fff;
        border: 1px solid rgba(3,139,137,0.08);
        border-radius: 20px;
        padding: 28px;
        transition: border-color 0.3s, transform 0.3s;
    }
    .ab-value:hover { border-color: var(--ath-teal); transform: translateY(-3px); }
    .ab-value-icon {
        width: 50px; height: 50px;
        background: rgba(3,139,137,0.1);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ath-teal);
        font-size: 1.25rem;
        margin-bottom: 18px;
    }
    .ab-value h3 {
        font-family: 'Outfit', sans-serif;
        color: var(--ath-deep);
        font-weight: 800;
        font-size: 1.15rem;
        margin-bottom: 8px;
    }
    .ab-value p { color: var(--ath-muted); line-height: 1.7; font-size: 0.95rem; }

    /* ── Timeline ────────────────────────────────────────────────────── */
    .ab-story { padding: 110px 0; background: #fff; }
    .ab-story-header { max-width: 780px; margin-bottom: 50px; }
    .ab-story-header h2 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(1.8rem, 4vw, 2.6rem);
        color: var(--ath-deep);
        font-weight: 800;
        line-height: 1.15;
    }
    .ab-timeline {
        list-style: none;
        padding: 0 0 0 34px;
        margin: 0;
        max-width: 900px;
        position: relative;
    }
    .ab-timeline::before {
        content: '';
        position: absolute;
        left: 0; top: 20px; bottom: 20px;
        width: 3px;
        background: linear-gradient(180deg, var(--ath-teal) 0%, var(--ath-gold) 100%);
        border-radius: 2px;
    }
    .ab-timeline li {
        position: relative;
        padding: 24px 32px 30px;
        margin-bottom: 20px;
        background: var(--ath-light);
        border-radius: 20px;
        border: 1px solid rgba(3,139,137,0.08);
    }
    .ab-timeline li::before {
        content: '';
        position: absolute;
        left: -42px; top: 34px;
        width: 14px; height: 14px;
        background: var(--ath-gold);
        border: 3px solid #fff;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(238,157,29,0.2);
    }
    .ab-time-tag {
        display: inline-block;
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--ath-teal);
        margin-bottom: 8px;
    }
    .ab-timeline h3 {
        font-family: 'Outfit', sans-serif;
        color: var(--ath-deep);
        font-weight: 800;
        font-size: 1.2rem;
        margin-bottom: 8px;
    }
    .ab-timeline p { color: var(--ath-text); line-height: 1.7; margin: 0; }

    /* ── CTA ─────────────────────────────────────────────────────────── */
    .ab-cta { padding: 100px 0; background: var(--ath-light); }
    .ab-cta-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    .ab-cta-card {
        border-radius: 28px;
        padding: 50px;
    }
    .ab-cta-learner { background: linear-gradient(135deg, var(--ath-teal), var(--ath-deep)); color: #fff; }
    .ab-cta-partner { background: var(--ath-navy); color: #fff; position: relative; overflow: hidden; }
    .ab-cta-partner::before {
        content: '';
        position: absolute;
        top: -30%; right: -20%;
        width: 70%; height: 130%;
        background: radial-gradient(closest-side, rgba(238,157,29,0.18), transparent 70%);
        pointer-events: none;
    }
    .ab-cta-card > * { position: relative; z-index: 1; }
    .ab-cta-eyebrow {
        display: inline-block;
        font-family: var(--font-mono);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--ath-gold);
        margin-bottom: 14px;
    }
    .ab-cta-card h3 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(1.6rem, 3vw, 2rem);
        font-weight: 800;
        margin-bottom: 12px;
    }
    .ab-cta-card p {
        color: rgba(255,255,255,0.85);
        line-height: 1.7;
        margin-bottom: 26px;
    }

    /* ── Responsive ──────────────────────────────────────────────────── */
    @media (max-width: 992px) {
        .ab-why-grid { grid-template-columns: 1fr; gap: 50px; }
        .ab-pillars-grid { grid-template-columns: 1fr; }
        .ab-cta-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .ab-hero { padding: 140px 0 80px; }
        .ab-why-stats { grid-template-columns: 1fr; }
        .ab-cta-card { padding: 36px 28px; }
    }
</style>
@endpush

@endsection
