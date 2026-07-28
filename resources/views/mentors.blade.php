@extends('layouts.aethryna')

@section('title', 'Become a Mentor | Skills Co-op')

@section('meta_description', 'Mentor someone into digital work. Skills Co-op mentors give a few hours a month to learners facing real barriers to employment. No teaching experience needed.')
@section('og_description', 'Mentor someone into digital work. Skills Co-op mentors give a few hours a month to learners facing real barriers to employment. No teaching experience needed.')

@section('content')

<!-- Hero -->
<section class="mt-hero">
    <div class="ath-container">
        <div class="mt-hero-inner">
            <span class="mt-eyebrow">Mentoring</span>
            <h1 class="mt-title">Someone is one conversation away from a <span class="mt-gradient">different career.</span></h1>
            <p class="mt-lede">Our learners are career changers, young people out of work, refugees, and people rebuilding after prison or illness. Most have never had anyone in the industry to ask. That is the gap mentors fill.</p>
            <div class="mt-hero-actions">
                <a href="#register-interest" class="mt-btn mt-btn-primary">Register your interest</a>
                <a href="{{ route('sessions') }}" class="mt-btn mt-btn-ghost">Come to a panel first</a>
            </div>
        </div>
    </div>
</section>

<!-- What it involves -->
<section class="mt-section" id="what-it-involves">
    <div class="ath-container">
        <div class="mt-section-head">
            <span class="ath-sub">The commitment</span>
            <h2>What mentoring actually involves</h2>
            <p class="mt-section-lede">We are precise about this because vague asks waste your time. Here is the real shape of it.</p>
        </div>
        <div class="mt-grid">
            <div class="mt-card">
                <div class="mt-card-num">01</div>
                <h3>Two hours a month</h3>
                <p>One conversation a fortnight, or one longer session monthly. Video call, whatever time suits you both. We do not ask for evenings and weekends unless you offer them.</p>
            </div>
            <div class="mt-card">
                <div class="mt-card-num">02</div>
                <h3>Across the 25 weeks</h3>
                <p>You are matched with one or two learners for the length of a cohort. Long enough to see them change, short enough to be a commitment you can actually keep.</p>
            </div>
            <div class="mt-card">
                <div class="mt-card-num">03</div>
                <h3>Conversation, not teaching</h3>
                <p>You are not delivering curriculum. That is our job. You answer the questions nobody else can: what the work is really like, how you got in, what you wish you had known.</p>
            </div>
            <div class="mt-card">
                <div class="mt-card-num">04</div>
                <h3>Backed by our team</h3>
                <p>Safeguarding training, a named contact, and a clear escalation route if something worries you. You are never left holding a situation on your own.</p>
            </div>
        </div>
    </div>
</section>

<!-- Who we need -->
<section class="mt-section mt-section-dark">
    <div class="ath-container">
        <div class="mt-section-head">
            <span class="ath-sub">Who we need</span>
            <h2>If you work in digital, you already qualify</h2>
            <p class="mt-section-lede">You do not need to be senior. You need to be a couple of steps ahead of someone starting out, and willing to be honest about how you got there.</p>
        </div>
        <div class="mt-track-grid">
            <div class="mt-track">
                <div class="mt-track-icon"><i class="fas fa-tasks"></i></div>
                <h3>Project and Product Delivery</h3>
                <p>Project coordinators, business analysts, product and delivery managers, scrum practitioners.</p>
            </div>
            <div class="mt-track">
                <div class="mt-track-icon"><i class="fas fa-chart-bar"></i></div>
                <h3>Data and AI Analytics</h3>
                <p>Data and insight analysts, BI developers, anyone working with AI tooling in a real workflow.</p>
            </div>
            <div class="mt-track">
                <div class="mt-track-icon"><i class="fas fa-palette"></i></div>
                <h3>Product Design and Marketing</h3>
                <p>Product and UX designers, brand and content people, digital marketers.</p>
            </div>
            <div class="mt-track">
                <div class="mt-track-icon"><i class="fas fa-code"></i></div>
                <h3>Software Development</h3>
                <p>Developers at any level, QA and automation engineers, technical leads.</p>
            </div>
        </div>
        <p class="mt-track-note">Not in one of these four? We still want to hear from you. Cohort 2 adds IT Support and Operations, Cyber Security, Cloud and DevOps, and Tech Sales and Customer Success.</p>
    </div>
</section>

<!-- What you get -->
<section class="mt-section">
    <div class="ath-container">
        <div class="mt-section-head">
            <span class="ath-sub">Being honest about both directions</span>
            <h2>What you get out of it</h2>
        </div>
        <div class="mt-value-grid">
            <div class="mt-value">
                <i class="fas fa-users"></i>
                <h3>People management practice, without the payroll</h3>
                <p>Coaching someone through a real career decision is the same muscle as leading a team. Mentors regularly tell us it sharpened their day job.</p>
            </div>
            <div class="mt-value">
                <i class="fas fa-network-wired"></i>
                <h3>A network of practitioners</h3>
                <p>You join a mentor community alongside the panel speakers and advisers already working with us across data, design, delivery, and engineering.</p>
            </div>
            <div class="mt-value">
                <i class="fas fa-seedling"></i>
                <h3>A pipeline you helped build</h3>
                <p>If your organisation hires junior digital talent, you will have watched these people work for six months before anyone else sees a CV.</p>
            </div>
            <div class="mt-value">
                <i class="fas fa-heart"></i>
                <h3>The obvious one</h3>
                <p>Someone gets into work who would not otherwise have got in. That is the whole point, and it is worth saying plainly.</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="mt-section mt-section-light">
    <div class="ath-container">
        <div class="mt-section-head">
            <span class="ath-sub">Common questions</span>
            <h2>Before you decide</h2>
        </div>
        <div class="mt-faq">
            <details class="mt-faq-item">
                <summary>I have never mentored anyone. Is that a problem?</summary>
                <div class="mt-faq-body">
                    <p>No. Most of our mentors have not either. We run an induction that covers what good mentoring looks like, what is in scope, and what to do when something is not. If you can have an honest conversation about your own career, you can do this.</p>
                </div>
            </details>
            <details class="mt-faq-item">
                <summary>What if I cannot commit for the full cohort?</summary>
                <div class="mt-faq-body">
                    <p>Tell us upfront and we will match accordingly, or use you for one-off sessions and portfolio reviews instead. A mentor who disappears halfway is worse for a learner than one who never started, so we would rather know.</p>
                </div>
            </details>
            <details class="mt-faq-item">
                <summary>Do I need a DBS check?</summary>
                <div class="mt-faq-body">
                    <p>For mentoring adults over 18 in a group setting, usually not. Where a mentoring relationship is one to one, or where a learner is under 18, we arrange the appropriate check and cover the cost. We will tell you which applies before you are matched.</p>
                </div>
            </details>
            <details class="mt-faq-item">
                <summary>What happens if a learner tells me something worrying?</summary>
                <div class="mt-faq-body">
                    <p>You raise it with us, and we take it from there. Every mentor gets a named safeguarding contact and a written escalation route during induction. You are never expected to handle a disclosure or make a judgement call on your own.</p>
                </div>
            </details>
            <details class="mt-faq-item">
                <summary>When would I start?</summary>
                <div class="mt-faq-body">
                    <p>Cohort 1 begins in January 2027, so mentor matching happens in the weeks before that. Register now and we will be in touch as induction dates are set. In the meantime, the monthly panel sessions are the easiest way to meet the team.</p>
                </div>
            </details>
        </div>
    </div>
</section>

<!-- Register interest -->
<section class="mt-section mt-cta-section" id="register-interest">
    <div class="ath-container">
        <div class="mt-cta">
            <span class="mt-cta-eyebrow">Register your interest</span>
            <h2>Tell us what you do and we will take it from there</h2>
            <p>No form to labour over. Email us with your role, your track, and roughly how much time you can give. We will reply within three working days with what happens next.</p>
            <div class="mt-cta-actions">
                <a href="mailto:hello@skillscoop.org?subject=Mentor%20interest&body=Hi%20Skills%20Co-op%2C%0A%0AI%20would%20like%20to%20mentor.%0A%0AMy%20role%3A%20%0AMy%20track%20(Project%20and%20Product%20Delivery%20%2F%20Data%20and%20AI%20Analytics%20%2F%20Product%20Design%20and%20Marketing%20%2F%20Software%20Development)%3A%20%0ATime%20I%20can%20give%20per%20month%3A%20%0ALinkedIn%20(optional)%3A%20%0A%0AThanks" class="mt-btn mt-btn-primary">
                    <i class="far fa-envelope"></i> Email us about mentoring
                </a>
                <span class="mt-cta-alt">or write to <strong>hello@skillscoop.org</strong></span>
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
.ath-container { max-width: 1200px; margin: 0 auto; padding: 0 5%; }

/* Hero */
.mt-hero {
    padding: 160px 0 100px;
    background: linear-gradient(180deg, var(--ath-deep) 0%, var(--ath-navy) 100%);
    color: #fff;
    position: relative;
    overflow: hidden;
}
.mt-hero::after {
    content: '';
    position: absolute;
    top: -25%; right: -10%;
    width: 58%; height: 140%;
    background: radial-gradient(closest-side, rgba(238,157,29,0.15), transparent 70%);
    pointer-events: none;
}
.mt-hero-inner { max-width: 840px; position: relative; z-index: 1; }
.mt-eyebrow {
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
.mt-title {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(2.3rem, 5.5vw, 3.8rem);
    font-weight: 800;
    line-height: 1.08;
    margin-bottom: 24px;
}
.mt-gradient {
    background: linear-gradient(135deg, var(--ath-gold), #fff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.mt-lede { font-size: 1.15rem; line-height: 1.75; opacity: 0.88; max-width: 700px; margin-bottom: 36px; }
.mt-hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }

/* Buttons */
.mt-btn {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 14px 30px;
    border-radius: 100px;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    border: 2px solid transparent;
    transition: all 0.25s ease;
}
.mt-btn-primary { background: var(--ath-gold); color: #fff; }
.mt-btn-primary:hover { background: #fff; color: var(--ath-navy); transform: translateY(-2px); }
.mt-btn-ghost { background: rgba(255,255,255,0.07); color: #fff; border-color: rgba(255,255,255,0.2); }
.mt-btn-ghost:hover { background: rgba(255,255,255,0.14); }

/* Sections */
.mt-section { padding: 100px 0; background: #fff; }
.mt-section-light { background: var(--ath-light); }
.mt-section-dark { background: var(--ath-navy); color: #fff; }
.mt-section-head { max-width: 780px; margin-bottom: 50px; }
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
.mt-section-head h2 {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(1.9rem, 4vw, 2.7rem);
    font-weight: 800;
    line-height: 1.15;
    color: var(--ath-deep);
    margin-bottom: 16px;
}
.mt-section-dark .mt-section-head h2 { color: #fff; }
.mt-section-lede { font-size: 1.08rem; line-height: 1.75; color: var(--ath-muted); }
.mt-section-dark .mt-section-lede { color: rgba(255,255,255,0.82); }

/* Commitment cards */
.mt-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.mt-card {
    background: var(--ath-light);
    border: 1px solid rgba(3,139,137,0.1);
    border-radius: 22px;
    padding: 34px;
    transition: transform 0.25s, border-color 0.25s;
}
.mt-card:hover { transform: translateY(-4px); border-color: var(--ath-teal); }
.mt-card-num {
    font-family: var(--font-mono);
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 2px;
    color: var(--ath-gold);
    background: rgba(238,157,29,0.12);
    display: inline-block;
    padding: 4px 11px;
    border-radius: 100px;
    margin-bottom: 16px;
}
.mt-card h3 {
    font-family: 'Outfit', sans-serif;
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--ath-deep);
    margin-bottom: 12px;
}
.mt-card p { color: var(--ath-muted); line-height: 1.75; margin: 0; }

/* Tracks */
.mt-track-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; margin-bottom: 30px; }
.mt-track {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 20px;
    padding: 30px;
}
.mt-track-icon {
    width: 46px; height: 46px;
    background: rgba(238,157,29,0.2);
    color: var(--ath-gold);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    margin-bottom: 16px;
}
.mt-track h3 { font-family: 'Outfit', sans-serif; font-size: 1.12rem; font-weight: 800; margin-bottom: 8px; }
.mt-track p { opacity: 0.8; line-height: 1.7; font-size: 0.95rem; margin: 0; }
.mt-track-note { color: rgba(255,255,255,0.72); font-style: italic; font-size: 0.97rem; max-width: 780px; }

/* Value */
.mt-value-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.mt-value {
    padding: 30px;
    border: 1px solid rgba(0,0,0,0.07);
    border-radius: 20px;
    transition: border-color 0.25s, box-shadow 0.25s, transform 0.25s;
}
.mt-value:hover {
    border-color: var(--ath-teal);
    box-shadow: 0 18px 50px rgba(3,139,137,0.08);
    transform: translateY(-3px);
}
.mt-value i { font-size: 1.7rem; color: var(--ath-teal); margin-bottom: 16px; display: block; }
.mt-value h3 {
    font-family: 'Outfit', sans-serif;
    font-size: 1.14rem;
    font-weight: 800;
    color: var(--ath-deep);
    margin-bottom: 10px;
}
.mt-value p { color: var(--ath-muted); line-height: 1.75; margin: 0; }

/* FAQ */
.mt-faq { display: grid; gap: 12px; max-width: 820px; }
.mt-faq-item { border: 1px solid rgba(0,0,0,0.08); border-radius: 16px; overflow: hidden; background: #fff; }
.mt-faq-item summary {
    padding: 22px 26px;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: 1.03rem;
    color: var(--ath-deep);
    cursor: pointer;
    list-style: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
}
.mt-faq-item summary::-webkit-details-marker { display: none; }
.mt-faq-item summary::after {
    content: '+';
    font-size: 1.4rem;
    color: var(--ath-teal);
    transition: transform 0.3s;
    flex-shrink: 0;
}
.mt-faq-item[open] summary::after { transform: rotate(45deg); }
.mt-faq-body { padding: 0 26px 22px; }
.mt-faq-body p { color: var(--ath-muted); line-height: 1.75; margin: 0; }

/* CTA */
.mt-cta-section { background: #fff; }
.mt-cta {
    background: linear-gradient(135deg, var(--ath-deep), var(--ath-navy));
    border-radius: 30px;
    padding: 60px;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.mt-cta::before {
    content: '';
    position: absolute;
    top: -30%; right: -15%;
    width: 60%; height: 140%;
    background: radial-gradient(closest-side, rgba(238,157,29,0.18), transparent 70%);
    pointer-events: none;
}
.mt-cta > * { position: relative; z-index: 1; }
.mt-cta-eyebrow {
    display: inline-block;
    font-family: var(--font-mono);
    font-size: 0.78rem;
    font-weight: 600;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--ath-gold);
    margin-bottom: 16px;
}
.mt-cta h2 {
    font-family: 'Outfit', sans-serif;
    font-size: clamp(1.7rem, 3.6vw, 2.4rem);
    font-weight: 800;
    line-height: 1.18;
    margin-bottom: 16px;
}
.mt-cta p { font-size: 1.06rem; line-height: 1.75; color: rgba(255,255,255,0.85); margin-bottom: 30px; max-width: 640px; }
.mt-cta-actions { display: flex; gap: 20px; align-items: center; flex-wrap: wrap; }
.mt-cta-alt { color: rgba(255,255,255,0.75); font-size: 0.95rem; }
.mt-cta-alt strong { color: var(--ath-gold); font-weight: 700; }

@media (max-width: 992px) {
    .mt-grid, .mt-track-grid, .mt-value-grid { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
    .mt-hero { padding: 130px 0 80px; }
    .mt-section { padding: 70px 0; }
    .mt-cta { padding: 40px 28px; }
    .mt-card, .mt-track, .mt-value { padding: 26px 22px; }
}
</style>
@endpush

@endsection
