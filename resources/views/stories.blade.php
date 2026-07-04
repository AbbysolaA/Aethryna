@extends('layouts.aethryna')

@section('title', 'Stories from the Journey - SkillsCo-op')

@section('meta_description', 'We are pre-pilot. Read about the foundations we are laying: our panel series, our adviser network, and the curriculum milestones that will define our first cohort.')
@section('og_description', 'We are pre-pilot. Read about the foundations we are laying: our panel series, adviser network, and curriculum milestones.')

@section('content')
<!-- Hero -->
<section class="stories-hero">
    <div class="ath-container">
        <div class="hero-narrative">
            <div class="narrative-content reveal-fade-up">
                <span class="ath-badge">Pre-pilot</span>
                <h1 class="ath-title">Stories from the <span class="ath-gradient-text">journey so far.</span></h1>
                <p>Cohort 1 launches January 2027. Until it does, our stories are the foundations we are laying: the conversations we have had, the network we are building, and the curriculum we have designed before a single learner joins.</p>
                <a href="#milestones" class="hero-scroll-invite" style="text-decoration: none; color: inherit; cursor: pointer;">
                    <span>Read the journey</span>
                    <div class="scroll-dot"></div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Honesty note -->
<div class="honesty-banner">
    <div class="ath-container">
        <p><strong>A note on authenticity:</strong> We will not publish learner outcome stories until we have real learner outcomes to share. What you read here is what has actually happened. We think that matters.</p>
    </div>
</div>

<!-- Story Journal / Milestones -->
<main class="story-journal" id="milestones">
    <div class="ath-container">

        <!-- Milestone 1: Panel Series -->
        <article class="journal-entry">
            <div class="entry-visual entry-visual--milestone">
                <div class="milestone-icon"><i class="fas fa-microphone-alt"></i></div>
                <div class="milestone-tag">Panel Series</div>
            </div>
            <div class="entry-story">
                <span class="entry-num">01</span>
                <span class="ath-sub" style="text-align: left; display: block;">Building in public</span>
                <h2>The panel series that started it all</h2>
                <div class="story-text">
                    <p>Before we built a curriculum, we asked questions in public. Our panel series brought together practitioners, educators, and people with lived experience of barriers to employment to explore what a genuinely different kind of skills programme could look like.</p>
                    <p>The insights from those sessions are baked into every part of the SkillsCo-op model: the 25-week structure, the AI-embedded curriculum, the decision to focus on people the traditional pipeline misses.</p>
                </div>
                <a href="{{ route('sessions') }}" class="story-link">Watch the panel recordings <i class="fas fa-arrow-right"></i></a>
            </div>
        </article>

        <!-- Milestone 2: Adviser Network -->
        <article class="journal-entry entry-reverse">
            <div class="entry-visual entry-visual--milestone">
                <div class="milestone-icon"><i class="fas fa-users"></i></div>
                <div class="milestone-tag">Adviser Network</div>
            </div>
            <div class="entry-story">
                <span class="entry-num">02</span>
                <span class="ath-sub" style="text-align: left; display: block;">The people behind the programme</span>
                <h2>Building a network before building a cohort</h2>
                <div class="story-text">
                    <p>We did not start with a product. We started with relationships. Our adviser network includes practitioners from across the four pilot tracks, people with experience of trauma-informed education, and employers who want to be part of a different kind of talent pipeline.</p>
                    <p>These are the people who will mentor, guest-teach, and eventually recruit from our founding cohort. We are building the ecosystem before we recruit a single learner.</p>
                </div>
                <a href="{{ route('about') }}" class="story-link">Meet the team behind SkillsCo-op <i class="fas fa-arrow-right"></i></a>
            </div>
        </article>

        <!-- Milestone 3: Curriculum Design -->
        <article class="journal-entry">
            <div class="entry-visual entry-visual--milestone">
                <div class="milestone-icon"><i class="fas fa-book-open"></i></div>
                <div class="milestone-tag">Curriculum</div>
            </div>
            <div class="entry-story">
                <span class="entry-num">03</span>
                <span class="ath-sub" style="text-align: left; display: block;">25 weeks by design, not default</span>
                <h2>A curriculum built around how people actually learn</h2>
                <div class="story-text">
                    <p>The 25-week structure is not arbitrary. Foundations (weeks 0 to 4) build confidence and baseline digital fluency. Specialised Training (weeks 5 to 12) goes deep on the chosen track. The Project Period (weeks 13 to 24) applies everything to a real brief from a real employer.</p>
                    <p>AI tools are embedded throughout, not added on at the end. Every learner earns three certificates and builds a portfolio they can walk into a job with.</p>
                </div>
                <a href="{{ route('pathway') }}" class="story-link">See the full pathway <i class="fas fa-arrow-right"></i></a>
            </div>
        </article>

    </div>
</main>

<!-- What comes next -->
<section class="next-section">
    <div class="ath-container">
        <div class="next-inner">
            <span class="ath-sub">Coming soon</span>
            <h2>The stories we are waiting to tell</h2>
            <p>Cohort 1 begins in January 2027. When learners complete the programme, we will publish their stories here, with their permission and in their own words. Until then, you can help us write those stories.</p>
            <div class="next-actions">
                <a href="{{ route('register') }}" class="ath-btn ath-btn-primary">Join the founding cohort</a>
                <a href="{{ route('partners') }}" class="ath-btn ath-btn-outline">Become a partner</a>
            </div>
        </div>
    </div>
</section>

<!-- Footer CTA -->
<section class="stories-footer-cta">
    <div class="ath-container">
        <div class="cta-card">
            <h2>Your story is <span class="ath-gradient-text">waiting to be written.</span></h2>
            <p>Applications for our founding cohort open ahead of January 2027. Register your interest now.</p>
            <div class="cta-actions">
                <a href="{{ route('register') }}" class="btn btn-primary">Register your interest</a>
                <a href="{{ route('assessment.index') }}" class="btn btn-outline-white">Take the 2-minute assessment</a>
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
    --ath-radius: 40px;
}

.ath-container {
    max-width: 1250px;
    margin: 0 auto;
    padding: 0 5%;
}

/* Stories Hero */
.stories-hero {
    min-height: 60vh;
    background: var(--ath-deep);
    display: flex;
    align-items: center;
    padding: 160px 0 100px;
    position: relative;
    color: #fff;
}

.hero-narrative {
    max-width: 800px;
    z-index: 5;
}

.ath-badge {
    display: inline-block;
    padding: 10px 20px;
    background: rgba(255,255,255,0.1);
    border-radius: 100px;
    font-size: 0.9rem;
    font-weight: 800;
    margin-bottom: 30px;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.ath-title {
    font-size: clamp(2.5rem, 6vw, 4rem);
    font-weight: 800;
    line-height: 1.1;
    margin-bottom: 24px;
    font-family: 'Outfit', sans-serif;
}

.stories-hero p {
    font-size: 1.25rem;
    opacity: 0.9;
    max-width: 640px;
    line-height: 1.7;
}

.hero-scroll-invite {
    margin-top: 50px;
    display: inline-flex;
    align-items: center;
    gap: 15px;
    font-weight: 700;
}

.scroll-dot {
    width: 10px; height: 10px;
    background: var(--ath-gold);
    border-radius: 50%;
    animation: bounce-y 2s infinite;
}

@keyframes bounce-y { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(10px); } }

/* Honesty banner */
.honesty-banner {
    background: rgba(3,139,137,0.07);
    border-top: 3px solid var(--ath-teal);
    padding: 20px 0;
}
.honesty-banner p { margin: 0; color: var(--ath-text); font-size: 0.95rem; line-height: 1.6; }

/* Journal Entries */
.story-journal { padding: 100px 0; background: #fff; }

.journal-entry {
    display: grid;
    grid-template-columns: 1fr 1.3fr;
    gap: 80px;
    align-items: center;
    margin-bottom: 120px;
}

.entry-reverse { grid-template-columns: 1.3fr 1fr; }
.entry-reverse .entry-visual { order: 2; }
.entry-reverse .entry-story { order: 1; }

/* Milestone visual (no image) */
.entry-visual--milestone {
    background: var(--ath-light);
    border-radius: var(--ath-radius);
    aspect-ratio: 4/3;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;
    border: 2px solid rgba(3,139,137,0.1);
}

.milestone-icon {
    width: 80px; height: 80px;
    background: var(--ath-deep);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: var(--ath-gold);
}

.milestone-tag {
    display: inline-block;
    padding: 8px 20px;
    background: var(--ath-teal);
    color: #fff;
    border-radius: 100px;
    font-weight: 800;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.entry-story { position: relative; }

.entry-num {
    position: absolute;
    top: -50px; left: 0;
    font-size: 8rem;
    font-weight: 900;
    color: rgba(3,139,137,0.05);
    line-height: 1;
    z-index: -1;
    font-family: 'Outfit', sans-serif;
    pointer-events: none;
}

.entry-reverse .entry-num { left: 0; }

.ath-sub {
    display: block;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--ath-gold);
    margin-bottom: 8px;
}

.entry-story h2 {
    font-size: 2.2rem;
    color: var(--ath-deep);
    margin: 12px 0 24px;
    font-weight: 800;
    font-family: 'Outfit', sans-serif;
    line-height: 1.2;
}

.story-text p {
    font-size: 1.1rem;
    line-height: 1.75;
    color: var(--ath-text);
    margin-bottom: 16px;
}

.story-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--ath-teal);
    font-weight: 800;
    text-decoration: none;
    margin-top: 8px;
    transition: gap 0.2s;
}
.story-link:hover { gap: 14px; }

/* What comes next */
.next-section {
    background: var(--ath-light);
    padding: 100px 0;
}
.next-inner { max-width: 700px; }
.next-inner h2 { font-size: clamp(1.8rem, 4vw, 2.8rem); color: var(--ath-deep); font-weight: 800; font-family: 'Outfit', sans-serif; margin: 8px 0 16px; }
.next-inner p { color: var(--ath-muted); line-height: 1.7; margin-bottom: 36px; font-size: 1.05rem; }
.next-actions { display: flex; gap: 16px; flex-wrap: wrap; }

/* Buttons */
.ath-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 15px 32px;
    border-radius: 100px;
    font-weight: 800;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s;
}
.ath-btn-primary { background: var(--ath-gold); color: #fff; }
.ath-btn-primary:hover { background: var(--ath-teal); }
.ath-btn-outline { border: 2px solid var(--ath-deep); color: var(--ath-deep); }
.ath-btn-outline:hover { background: var(--ath-deep); color: #fff; }

/* Stories Footer CTA */
.stories-footer-cta { padding: 100px 0; }
.cta-card {
    background: var(--ath-deep);
    padding: 80px;
    border-radius: var(--ath-radius);
    text-align: center;
    color: #fff;
}

.ath-gradient-text {
    background: linear-gradient(135deg, var(--ath-teal), var(--ath-gold));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.cta-card h2 { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 800; margin-bottom: 20px; font-family: 'Outfit', sans-serif; }
.cta-card p { font-size: 1.15rem; margin-bottom: 40px; opacity: 0.9; }
.cta-actions { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; }

.btn-outline-white {
    border: 2px solid #fff;
    color: #fff;
    padding: 15px 35px;
    border-radius: 100px;
    text-decoration: none;
    font-weight: 800;
    transition: var(--ath-trans);
}
.btn-outline-white:hover { background: #fff; color: var(--ath-deep); }

@media (max-width: 992px) {
    .journal-entry { grid-template-columns: 1fr; gap: 50px; }
    .entry-reverse .entry-visual { order: 1; }
    .entry-reverse .entry-story { order: 2; }
    .entry-visual--milestone { aspect-ratio: 3/2; }
}

@media (max-width: 768px) {
    .stories-hero { padding: 120px 0 80px; }
    .story-journal { padding: 70px 0; }
    .journal-entry { margin-bottom: 80px; }
    .cta-card { padding: 60px 30px; }
    .cta-actions { flex-direction: column; align-items: center; }
}
</style>
@endpush
@endsection
