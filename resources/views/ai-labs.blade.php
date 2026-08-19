@extends('layouts.aethryna')

@section('title', 'AI Labs | Skills Co-op')

@section('meta_description', 'How Skills Co-op teaches AI: a verification-first method embedded in every pathway, a community practice space, and a flight path to the AI Labs Fellowship.')
@section('og_description', 'How Skills Co-op teaches AI: a verification-first method embedded in every pathway, a community practice space, and a flight path to the AI Labs Fellowship.')

@section('content')

<!-- Hero -->
<section class="ailabs-hero">
    <div class="ath-container">
        <div class="ah-inner reveal-fade-up">
            <span class="ath-sub">AI at Skills Co-op</span>
            <h1 class="ath-title">AI <span class="ath-gradient-text">Labs.</span></h1>
            <p class="ah-subhead">How Skills Co-op teaches AI. And where we are taking it.</p>
            <p class="ah-lead">Every Skills Co-op learner graduates confident with AI tools, whatever their pathway. Not because we bolt an AI module onto a traditional course, but because AI runs through everything we teach, with a discipline most programmes skip: verification. This page explains our method, the community that practises it, and the flight path ahead.</p>
        </div>
    </div>
</section>

<!-- Section 1: The AI Method -->
<section class="ailabs-section" id="method">
    <div class="ath-container">
        <div class="section-label">
            <span class="ath-sub">Our approach</span>
            <h2>The Skills Co-op AI Method</h2>
            <p class="method-intro">Our method fits in one sentence: AI does the bulk of the work; humans provide checks, judgement, verification, direction, and ethics.</p>
        </div>
        <div class="method-grid">
            <div class="method-card">
                <div class="mc-icon"><i class="fas fa-location-arrow"></i></div>
                <h3>Direct</h3>
                <p>Learners use AI to accelerate real work from week one: drafting requirements, writing queries, generating designs, triaging tickets. AI is a working tool, not a topic.</p>
            </div>
            <div class="method-card">
                <div class="mc-icon"><i class="fas fa-check-double"></i></div>
                <h3>Verify</h3>
                <p>Every AI-assisted task carries a graded verification step. Learners check outputs against sources of truth, spot hallucinations and bias, and document what they accepted, rejected, and why.</p>
            </div>
            <div class="method-card">
                <div class="mc-icon"><i class="fas fa-hand-paper"></i></div>
                <h3>Override</h3>
                <p>When AI is wrong, and it often is, learners correct it. "Debug what the AI got wrong" is a recurring exercise in every pathway. Judgement is the skill; AI is the amplifier.</p>
            </div>
            <div class="method-card">
                <div class="mc-icon"><i class="fas fa-book"></i></div>
                <h3>Document</h3>
                <p>Every learner keeps an AI Use Log across their whole programme: prompt, output, decision, reasoning. By graduation it is evidence of a capability employers now actively look for.</p>
            </div>
        </div>
        <p class="method-closing">We call the graduates this produces AI-native judgement workers: people who can direct AI, verify its output, override it when it fails, and explain the decision to a stakeholder. In a labour market where routine execution is being automated, judgement is the durable skill.</p>
    </div>
</section>

<!-- Section 2: The Practice Space -->
<section class="ailabs-section ailabs-practice" id="practice-space">
    <div class="ath-container">
        <div class="section-label">
            <span class="ath-sub">The community</span>
            <h2>The Practice Space</h2>
            <p class="practice-intro">The AI Labs Practice Space is where the method becomes a habit. It lives inside our learner and alumni community and runs on a simple rhythm:</p>
        </div>
        <ul class="rhythm-list">
            <li>
                <h4>Prompt of the week.</h4>
                <code class="ps-tag">#ai-labs-prompt-of-the-week</code>
                <p>A small, doable AI challenge every Monday. Members post attempts through the week.</p>
            </li>
            <li>
                <h4>Tool of the month.</h4>
                <code class="ps-tag">#ai-labs-tool-of-the-month</code>
                <p>One AI tool explored in depth, with a starter guide, every month.</p>
            </li>
            <li>
                <h4>Verification Corner.</h4>
                <code class="ps-tag">#ai-labs-verification-corner</code>
                <p>Members post AI failures they have caught, written up as short teaching cases. Our favourite channel, because AI getting it wrong is the most useful thing to study.</p>
            </li>
            <li>
                <h4>Quarterly Showcase.</h4>
                <p>A live session where members demo what they have built or discovered. Open to mentors and employer partners.</p>
            </li>
        </ul>
        <p class="practice-closing">Membership is automatic for every learner and every graduate. The community does not end when the cohort does.</p>
    </div>
</section>

<!-- Section 3: The flight path -->
<section class="ailabs-section" id="flight-path">
    <div class="ath-container">
        <div class="section-label">
            <span class="ath-sub">The roadmap</span>
            <h2>Where AI Labs is going</h2>
            <p class="flight-intro">AI Labs is designed to grow in stages, each one earned by the stage before it. Here is the flight path.</p>
        </div>
        <div class="flight-infographic" id="flight-infographic">
            <svg class="fi-svg" viewBox="0 0 1000 360" role="img" aria-labelledby="fi-title" preserveAspectRatio="xMidYMid meet">
                <title id="fi-title">The AI Labs flight path: the Method and Practice Space now, the Fellowship in 2027, and a full AI Operations pathway in 2028</title>
                <defs>
                    <marker id="fi-arrow" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse">
                        <path d="M 0 0 L 10 5 L 0 10 z" fill="#ee9d1d"/>
                    </marker>
                </defs>
                <path class="fi-line fi-line-now" d="M 90 300 C 240 300 300 190 470 185" pathLength="1"/>
                <path class="fi-line fi-line-future" d="M 470 185 C 640 180 700 75 900 62" pathLength="1" marker-end="url(#fi-arrow)"/>
                <g class="fi-node fi-node-1">
                    <circle class="fi-halo" cx="90" cy="300" r="14"/>
                    <circle class="fi-dot fi-dot-live" cx="90" cy="300" r="9"/>
                </g>
                <g class="fi-node fi-node-2">
                    <circle class="fi-dot fi-dot-next" cx="470" cy="185" r="9"/>
                </g>
                <g class="fi-node fi-node-3">
                    <circle class="fi-dot fi-dot-next" cx="900" cy="62" r="9"/>
                </g>
                <text class="fi-year" x="90" y="340">NOW</text>
                <text class="fi-year" x="470" y="228">2027</text>
                <text class="fi-year" x="900" y="105">2028</text>
            </svg>
            <div class="fi-stages">
                <div class="fi-stage">
                    <span class="fi-chip fi-chip-live">Live from Cohort 1</span>
                    <h3>The Method and the Practice Space</h3>
                    <p>The verification-first method runs through every pathway in our pilot cohort. The Practice Space runs inside our community. Both are live from Cohort 1.</p>
                </div>
                <div class="fi-stage">
                    <span class="fi-chip">Launches with Cohort 2</span>
                    <h3>The AI Labs Fellowship</h3>
                    <p>A six-month, part-time programme for Skills Co-op graduates who want AI as their primary craft. Fellows build and ship a real AI-augmented product, service, or tool under mentorship, and earn the AI Labs Fellow credential on shipping, not attendance. Launching with our second cohort.</p>
                </div>
                <div class="fi-stage">
                    <span class="fi-chip">The destination</span>
                    <h3>A full AI Operations pathway</h3>
                    <p>The Fellowship's curriculum and evidence base will seed a complete pathway in AI Operations and Prompt Engineering, taking learners from foundations to professional AI-operations roles. This is the destination the earlier stages are building towards.</p>
                </div>
            </div>
        </div>
        <p class="flight-honesty">We publish this flight path before we publish outcomes, in the same spirit as everything we do: the plan is public, the progress will be too, and later stages only launch when the earlier ones have earned them.</p>
    </div>
</section>

<!-- Section 4: For partners and funders -->
<section class="ailabs-section ailabs-backers">
    <div class="ath-container">
        <div class="backers-card">
            <h2>Interested in backing this?</h2>
            <p>The Fellowship and the AI Operations pathway will need funding partners, mentors from industry, and employers willing to host AI-focused briefs and placements. If that could be you, we would like to hear from you early.</p>
            <div class="backers-actions">
                <a href="{{ route('partners') }}" class="ath-btn ath-btn-primary">Partner with us</a>
                <span class="backers-alt">Or email <a href="mailto:hello@skillscoop.org">hello@skillscoop.org</a></span>
            </div>
        </div>
    </div>
</section>

<!-- Section 5: For prospective learners -->
<section class="ailabs-section ailabs-learners">
    <div class="ath-container">
        <div class="learners-inner">
            <h2>Want to learn this way?</h2>
            <p>Every pilot track includes the AI Method and the operator core as standard: you learn your craft, and you learn to sell it, support it, and run it with AI doing the heavy lifting. There is no separate AI course to buy and no prerequisite to meet. Start with the track that fits you.</p>
            <a href="{{ route('programs') }}" class="ath-btn ath-btn-primary">Explore our tracks</a>
        </div>
    </div>
</section>

@push('styles')
<style>
/* Hallmark · redesign (section-scope) · pre-emit critique: P4 H4 E4 S4 R5 V4
 * scope: flight-path SVG infographic + hero blueprint texture + mono accents
 * theme: existing brand tokens preserved (teal/gold/deep, Outfit + Figtree, +IBM Plex Mono for labels)
 * motion: path draw-on-scroll · node pulse · staged fades (reduced-motion collapses all) */
:root {
    --ath-teal: #038b89;
    --ath-gold: #ee9d1d;
    --ath-deep: #055860;
    --ath-light: #F8FBFB;
    --ath-text: #404952;
    --ath-muted: #57616a;
    --ath-trans: all 0.4s ease;
    --ath-radius: 32px;
    --font-mono: 'IBM Plex Mono', 'Courier New', monospace;
}

.ath-container { max-width: 1200px; margin: 0 auto; padding: 0 5%; }

/* Hero */
.ailabs-hero {
    padding: 160px 0 100px;
    background: var(--ath-deep);
    color: #fff;
    position: relative;
    overflow: hidden;
}
.ailabs-hero::after {
    content: '';
    position: absolute;
    top: -30%;
    right: -10%;
    width: 60%;
    height: 130%;
    background: radial-gradient(closest-side, rgba(238,157,29,0.13), transparent 70%);
    pointer-events: none;
}
.ah-inner { max-width: 820px; position: relative; z-index: 1; }
.ah-subhead { font-size: 1.4rem; font-weight: 700; margin: 1.5rem 0 1rem; color: var(--ath-gold); font-family: 'Outfit', sans-serif; }
.ah-lead { font-size: 1.15rem; line-height: 1.75; opacity: 0.9; max-width: 720px; }

/* Section layout */
.ailabs-section { padding: 100px 0; border-bottom: 1px solid rgba(0,0,0,0.04); background: #fff; }
.section-label { margin-bottom: 50px; }
.section-label h2 { font-size: clamp(2rem, 4vw, 2.8rem); color: var(--ath-deep); font-weight: 800; font-family: 'Outfit', sans-serif; margin: 8px 0 20px; }
.method-intro, .practice-intro, .flight-intro { font-size: 1.15rem; color: var(--ath-text); line-height: 1.7; max-width: 760px; }

/* Method cards: 2x2 grid */
.method-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 50px;
}
.method-card {
    padding: 40px;
    border: 1px solid rgba(0,0,0,0.06);
    border-radius: var(--ath-radius);
    transition: var(--ath-trans);
    background: #fff;
}
.method-card:hover { border-color: var(--ath-teal); box-shadow: 0 20px 60px rgba(3,139,137,0.08); transform: translateY(-4px); }
.mc-icon {
    width: 50px; height: 50px;
    background: rgba(3,139,137,0.1);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ath-teal);
    font-size: 1.2rem;
    margin-bottom: 18px;
}
.method-card h3 { font-size: 1.35rem; color: var(--ath-deep); font-weight: 800; margin-bottom: 10px; font-family: 'Outfit', sans-serif; }
.method-card p { color: var(--ath-muted); line-height: 1.7; margin: 0; }
.method-closing { font-size: 1.1rem; color: var(--ath-text); line-height: 1.75; max-width: 820px; border-left: 4px solid var(--ath-gold); padding-left: 24px; }

/* Practice Space rhythm list */
.ailabs-practice { background: var(--ath-light); }
.rhythm-list { list-style: none; padding: 0; margin: 0 0 40px; display: grid; gap: 28px; max-width: 760px; }
.rhythm-list li { padding-left: 28px; border-left: 3px solid var(--ath-teal); }
.rhythm-list h4 { font-size: 1.15rem; color: var(--ath-deep); font-weight: 800; margin-bottom: 6px; font-family: 'Outfit', sans-serif; }
.rhythm-list p { color: var(--ath-muted); line-height: 1.7; margin: 0; }
.ps-tag {
    display: inline-block;
    font-family: var(--font-mono);
    font-size: 0.78rem;
    color: var(--ath-teal);
    background: rgba(3,139,137,0.08);
    padding: 2px 10px;
    border-radius: 6px;
    margin-bottom: 8px;
}
.practice-closing { font-size: 1.05rem; color: var(--ath-text); font-weight: 600; }

/* Flight path infographic */
.flight-infographic { margin-bottom: 40px; }
.fi-svg { width: 100%; height: auto; display: block; margin-bottom: 16px; }
.fi-line { fill: none; stroke-width: 3; }
.fi-line-now { stroke: var(--ath-teal); }
.fi-line-future { stroke: var(--ath-gold); stroke-dasharray: 0.022 0.014; }
.fi-dot-live { fill: var(--ath-teal); }
.fi-dot-next { fill: #fff; stroke: var(--ath-gold); stroke-width: 3; }
.fi-halo { fill: rgba(3,139,137,0.18); transform-origin: 90px 300px; animation: fi-pulse 2.4s ease-in-out infinite; }
.fi-year {
    font-family: var(--font-mono);
    font-size: 15px;
    font-weight: 600;
    fill: var(--ath-muted);
    text-anchor: middle;
    letter-spacing: 2px;
}
@keyframes fi-pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.5); opacity: 0.4; }
}

/* Draw-on-scroll: hidden states only apply once JS adds .fi-anim, so no-JS users see everything */
.fi-anim .fi-line-now { stroke-dasharray: 1; stroke-dashoffset: 1; }
.fi-anim .fi-line-future, .fi-anim .fi-node-2, .fi-anim .fi-node-3 { opacity: 0; }
.fi-anim.in-view .fi-line-now { animation: fi-draw 800ms cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.fi-anim.in-view .fi-line-future { animation: fi-fade 600ms ease-out 650ms forwards; }
.fi-anim.in-view .fi-node-2 { animation: fi-fade 400ms ease-out 550ms forwards; }
.fi-anim.in-view .fi-node-3 { animation: fi-fade 400ms ease-out 1000ms forwards; }
@keyframes fi-draw { to { stroke-dashoffset: 0; } }
@keyframes fi-fade { to { opacity: 1; } }
@media (prefers-reduced-motion: reduce) {
    .fi-anim .fi-line-now { stroke-dasharray: none; stroke-dashoffset: 0; animation: none; }
    .fi-anim .fi-line-future, .fi-anim .fi-node-2, .fi-anim .fi-node-3 { opacity: 1; animation: none; }
    .fi-halo { animation: none; }
}

.fi-stages { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 30px; }
.fi-stage h3 { font-size: 1.25rem; color: var(--ath-deep); font-weight: 800; margin-bottom: 8px; font-family: 'Outfit', sans-serif; }
.fi-stage p { color: var(--ath-muted); line-height: 1.7; margin: 0; }
.fi-chip {
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
    margin-bottom: 14px;
}
.fi-chip-live { background: rgba(3,139,137,0.12); color: var(--ath-teal); }
.flight-honesty { font-size: 1rem; color: var(--ath-muted); font-style: italic; max-width: 760px; }

/* Backers */
.ailabs-backers { background: var(--ath-deep); color: #fff; border-bottom: none; }
.backers-card { max-width: 720px; }
.backers-card h2 { font-size: clamp(1.8rem, 4vw, 2.5rem); font-weight: 800; font-family: 'Outfit', sans-serif; margin-bottom: 16px; }
.backers-card p { opacity: 0.9; line-height: 1.75; font-size: 1.1rem; margin-bottom: 32px; }
.backers-actions { display: flex; align-items: center; gap: 24px; flex-wrap: wrap; }
.backers-alt { opacity: 0.85; }
.backers-alt a { color: var(--ath-gold); font-weight: 700; text-decoration: none; }
.backers-alt a:hover { text-decoration: underline; }

/* Learners */
.ailabs-learners { border-bottom: none; }
.learners-inner { max-width: 720px; }
.learners-inner h2 { font-size: clamp(1.8rem, 4vw, 2.5rem); color: var(--ath-deep); font-weight: 800; font-family: 'Outfit', sans-serif; margin-bottom: 16px; }
.learners-inner p { color: var(--ath-muted); line-height: 1.75; font-size: 1.1rem; margin-bottom: 32px; }

/* Shared button */
.ath-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 15px 32px;
    border-radius: 100px;
    font-weight: 800;
    font-size: 1rem;
    text-decoration: none;
    transition: var(--ath-trans);
    cursor: pointer;
    border: none;
}
.ath-btn-primary { background: var(--ath-gold); color: #fff; }
.ath-btn-primary:hover { background: var(--ath-teal); color: #fff; }

/* Gradient text */
.ath-gradient-text {
    background: linear-gradient(135deg, var(--ath-teal), var(--ath-gold));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* ath-title */
.ath-title {
    font-size: clamp(2.5rem, 6vw, 4rem);
    font-weight: 800;
    line-height: 1.1;
    font-family: 'Outfit', sans-serif;
    margin-bottom: 0;
}

/* ath-sub */
.ath-sub {
    display: block;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--ath-gold);
    margin-bottom: 8px;
}
.ailabs-hero .ath-sub { color: rgba(255,255,255,0.7); }

@media (max-width: 768px) {
    .ailabs-hero { padding: 120px 0 80px; }
    .ailabs-section { padding: 70px 0; }
    .method-grid { grid-template-columns: 1fr; }
    .fi-svg { display: none; }
    .fi-stages { grid-template-columns: 1fr; gap: 28px; }
    .fi-stage { border-left: 3px solid var(--ath-teal); padding-left: 20px; }
    .fi-stage::before {
        display: block;
        font-family: var(--font-mono);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 2px;
        color: var(--ath-muted);
        margin-bottom: 6px;
    }
    .fi-stage:nth-child(1)::before { content: 'NOW'; }
    .fi-stage:nth-child(2)::before { content: '2027'; }
    .fi-stage:nth-child(3)::before { content: '2028'; }
    .backers-actions { flex-direction: column; align-items: flex-start; }
}
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var fi = document.getElementById('flight-infographic');
        if (!fi) return;
        fi.classList.add('fi-anim');
        if (!('IntersectionObserver' in window)) {
            fi.classList.add('in-view');
            return;
        }
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    fi.classList.add('in-view');
                    obs.disconnect();
                }
            });
        }, { threshold: 0.35 });
        obs.observe(fi);
    });
</script>
@endpush

@endsection
