@extends('layouts.aethryna')

@section('title', 'Partner with SkillsCo-op')

@section('meta_description', 'Partner with SkillsCo-op to provide real project briefs for our learners and help shape the next generation of digital talent. Find out how our employer partnership model works.')
@section('og_description', 'Partner with SkillsCo-op to provide real project briefs for our learners and help shape the next generation of digital talent.')

@section('content')

@if(session('enquiry_sent'))
<div style="background:#038b89;color:#fff;text-align:center;padding:1.5rem;font-weight:700;font-size:1.1rem;">
    Thank you. We will be in touch within three working days.
</div>
@endif

<!-- Hero -->
<section class="partners-hero">
    <div class="ath-container">
        <div class="ph-inner reveal-fade-up">
            <span class="ath-sub">Employer and Organisation Partnerships</span>
            <h1 class="ath-title">Partner with <span class="ath-gradient-text">SkillsCo-op.</span></h1>
            <p class="ph-lead">Our learners need real briefs to build real skills. You get a motivated cohort, a community of practice, and the opportunity to recruit from a pool of talent that the traditional pipeline misses entirely.</p>
            <a href="#get-in-touch" class="ath-btn ath-btn-primary">Send us a brief</a>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="partners-section" id="how-it-works">
    <div class="ath-container">
        <div class="section-label">
            <span class="ath-sub">The model</span>
            <h2>How it works</h2>
        </div>
        <div class="how-grid">
            <div class="how-step">
                <div class="step-num">01</div>
                <h3>You share a brief</h3>
                <p>Send us a real challenge your organisation is facing: a process to document, a dataset to explore, a campaign to plan, or a system to support. It does not need to be fully formed.</p>
            </div>
            <div class="how-step">
                <div class="step-num">02</div>
                <h3>We match it to a track</h3>
                <p>We assess the brief and match it to the most relevant pilot track. Learners tackle it as a live project during the Project Period (weeks 13 to 24 of the programme).</p>
            </div>
            <div class="how-step">
                <div class="step-num">03</div>
                <h3>You receive the outputs</h3>
                <p>You get a presentation of findings, a portfolio of work, and the opportunity to meet the learners. There is no cost, no legal obligation to hire, and no minimum commitment.</p>
            </div>
        </div>
    </div>
</section>

<!-- What we ask -->
<section class="partners-section partners-ask">
    <div class="ath-container">
        <div class="ask-grid">
            <div class="ask-text">
                <span class="ath-sub">Your commitment</span>
                <h2>What we ask of partners</h2>
                <ul class="ask-list">
                    <li><i class="fas fa-check-circle"></i> A real brief or challenge (nothing confidential needs to be shared)</li>
                    <li><i class="fas fa-check-circle"></i> One 30-minute brief conversation at the start of the project period</li>
                    <li><i class="fas fa-check-circle"></i> Attendance at a final presentation (virtual is fine)</li>
                    <li><i class="fas fa-check-circle"></i> Honest feedback on the outputs to help learners grow</li>
                </ul>
                <p class="ask-note">That is it. We handle the rest. No invoices, no contracts, no NDAs required unless you choose to use them.</p>
            </div>
            <div class="ask-card">
                <div class="ac-inner">
                    <i class="fas fa-handshake"></i>
                    <h3>Zero cost. Real impact.</h3>
                    <p>Partnering with SkillsCo-op is entirely free. Our programme is funded, and we ask only for your time and a genuine brief.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What partners gain -->
<section class="partners-section">
    <div class="ath-container">
        <div class="section-label">
            <span class="ath-sub">The value</span>
            <h2>What you gain</h2>
        </div>
        <div class="gain-grid">
            <div class="gain-card">
                <i class="fas fa-users"></i>
                <h3>A diverse talent pipeline</h3>
                <p>Our cohorts include people the traditional graduate pipeline rarely reaches: career changers, people from refugee backgrounds, those returning from periods out of work. You meet candidates before anyone else does.</p>
            </div>
            <div class="gain-card">
                <i class="fas fa-lightbulb"></i>
                <h3>A fresh perspective on your challenge</h3>
                <p>Learners approach briefs without preconceptions. They often surface ideas that experienced staff overlook. The work has genuine value, not just training value.</p>
            </div>
            <div class="gain-card">
                <i class="fas fa-award"></i>
                <h3>Community recognition</h3>
                <p>Partners are acknowledged in our programme materials and on our website. Being an early partner of a funded pre-pilot programme carries weight in the social value and ESG landscape.</p>
            </div>
            <div class="gain-card">
                <i class="fas fa-graduation-cap"></i>
                <h3>Optional recruitment pathway</h3>
                <p>There is no obligation to hire, but many partners choose to. You will have already seen what the learner can do before you make any decision.</p>
            </div>
        </div>
    </div>
</section>

<!-- What briefs work -->
<section class="partners-section partners-briefs">
    <div class="ath-container">
        <div class="section-label">
            <span class="ath-sub">Scope</span>
            <h2>What kinds of brief work well</h2>
        </div>
        <div class="briefs-grid">
            <div class="brief-track">
                <div class="bt-icon"><i class="fas fa-project-diagram"></i></div>
                <h3>Project and Product Delivery</h3>
                <p>Process documentation, project planning exercises, stakeholder mapping, workflow redesign, sprint planning simulations.</p>
            </div>
            <div class="brief-track">
                <div class="bt-icon"><i class="fas fa-chart-bar"></i></div>
                <h3>Data and AI Analytics</h3>
                <p>Cleaning and visualising a dataset you already hold, building a simple dashboard, exploring patterns in customer or operational data.</p>
            </div>
            <div class="brief-track">
                <div class="bt-icon"><i class="fas fa-paint-brush"></i></div>
                <h3>Digital Design and Marketing</h3>
                <p>Redesigning a page or leaflet, building a social campaign, creating brand assets, writing and structuring digital content.</p>
            </div>
            <div class="brief-track">
                <div class="bt-icon"><i class="fas fa-code"></i></div>
                <h3>Software Development</h3>
                <p>A small web app or internal tool, automating a manual process, or building a prototype you have not had the capacity to test.</p>
            </div>
        </div>
        <p class="briefs-note">Not sure if your challenge fits? Send it anyway. We will tell you honestly whether it is a good match and suggest adjustments if needed.</p>
    </div>
</section>

<!-- FAQ -->
<section class="partners-section partners-faq">
    <div class="ath-container">
        <div class="section-label">
            <span class="ath-sub">Common questions</span>
            <h2>FAQ</h2>
        </div>
        <div class="faq-list">
            <details class="faq-item">
                <summary>Do learners sign NDAs?</summary>
                <div class="faq-answer">
                    <p>Not by default. If your brief involves genuinely sensitive information, we can arrange a simple confidentiality agreement. Most briefs do not require one: we ask partners to share only what they are comfortable sharing.</p>
                </div>
            </details>
            <details class="faq-item">
                <summary>Can a small charity or community organisation partner with you?</summary>
                <div class="faq-answer">
                    <p>Yes, and we actively encourage it. A brief from a local food bank, housing association, or community interest company is as valuable to our learners as a corporate one — often more so, because the mission context adds meaning.</p>
                </div>
            </details>
            <details class="faq-item">
                <summary>What if the work is not good enough to use?</summary>
                <div class="faq-answer">
                    <p>The outputs are a learning vehicle, not a deliverable with a quality guarantee. We ask for honest feedback, not unconditional praise. Some outputs will be work in progress; others will exceed expectations. The value is in the process on both sides.</p>
                </div>
            </details>
            <details class="faq-item">
                <summary>When does the first cohort begin?</summary>
                <div class="faq-answer">
                    <p>Our founding cohort is planned for January 2027. If you submit a brief now, we will be in touch in advance of the project period (weeks 13 to 24) to confirm scope and timing.</p>
                </div>
            </details>
            <details class="faq-item">
                <summary>Can we partner for the long term?</summary>
                <div class="faq-answer">
                    <p>We would welcome that. Our aim is to build long-term relationships with a small number of employers and organisations who share our values. Early partners will have first option on project briefs in future cohorts.</p>
                </div>
            </details>
        </div>
    </div>
</section>

<!-- Get in touch form -->
<section class="partners-section partners-form" id="get-in-touch">
    <div class="ath-container">
        <div class="form-layout">
            <div class="form-intro">
                <span class="ath-sub">Get in touch</span>
                <h2>Send us a brief or ask a question</h2>
                <p>Fill in the form and we will come back to you within three working days. You are not committing to anything at this stage.</p>
                <div class="form-contact-alt">
                    <p>Prefer email? Write to us directly at <strong>hello@skillscoop.org</strong></p>
                </div>
            </div>
            <div class="form-card">
                <form action="{{ route('partners.enquiry') }}" method="POST" class="partner-form">
                    @csrf

                    @if($errors->any())
                        <div class="form-errors">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="organisation">Organisation name</label>
                        <input type="text" id="organisation" name="organisation" value="{{ old('organisation') }}" required placeholder="e.g. Merseyside Community Trust">
                    </div>
                    <div class="form-group">
                        <label for="contact_name">Your name</label>
                        <input type="text" id="contact_name" name="contact_name" value="{{ old('contact_name') }}" required placeholder="First and last name">
                    </div>
                    <div class="form-group">
                        <label for="email">Your email address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="name@organisation.org">
                    </div>
                    <div class="form-group">
                        <label for="brief_type">Which track does your brief best fit?</label>
                        <select id="brief_type" name="brief_type" required>
                            <option value="" disabled {{ old('brief_type') ? '' : 'selected' }}>Select a track</option>
                            <option value="Project and Product Delivery" {{ old('brief_type') === 'Project and Product Delivery' ? 'selected' : '' }}>Project and Product Delivery</option>
                            <option value="Data and AI Analytics" {{ old('brief_type') === 'Data and AI Analytics' ? 'selected' : '' }}>Data and AI Analytics</option>
                            <option value="Digital Design and Marketing" {{ old('brief_type') === 'Digital Design and Marketing' ? 'selected' : '' }}>Digital Design and Marketing</option>
                            <option value="Software Development" {{ old('brief_type') === 'Software Development' ? 'selected' : '' }}>Software Development</option>
                            <option value="Not sure" {{ old('brief_type') === 'Not sure' ? 'selected' : '' }}>Not sure yet</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message">Tell us about your brief or question</label>
                        <textarea id="message" name="message" rows="6" required placeholder="Describe the challenge, dataset, or question you have in mind. No more than a paragraph is fine at this stage.">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="ath-btn ath-btn-primary form-submit">Send enquiry</button>
                </form>
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
    --ath-text: #404952;
    --ath-muted: #57616a;
    --ath-trans: all 0.4s ease;
    --ath-radius: 32px;
}

.ath-container { max-width: 1200px; margin: 0 auto; padding: 0 5%; }

/* Hero */
.partners-hero {
    padding: 160px 0 100px;
    background: var(--ath-deep);
    color: #fff;
}
.ph-inner { max-width: 800px; }
.ph-lead { font-size: 1.25rem; line-height: 1.7; opacity: 0.9; margin: 1.5rem 0 2rem; max-width: 680px; }

/* Section layout */
.partners-section { padding: 100px 0; border-bottom: 1px solid rgba(0,0,0,0.04); }
.section-label { margin-bottom: 60px; }
.section-label h2 { font-size: clamp(2rem, 4vw, 2.8rem); color: var(--ath-deep); font-weight: 800; font-family: 'Outfit', sans-serif; margin-top: 8px; }

/* How it works */
.how-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px; }
.how-step { padding: 40px; background: var(--ath-light); border-radius: var(--ath-radius); }
.step-num { font-size: 3rem; font-weight: 900; color: rgba(3,139,137,0.15); font-family: 'Outfit', sans-serif; line-height: 1; margin-bottom: 16px; }
.how-step h3 { font-size: 1.3rem; color: var(--ath-deep); font-weight: 800; margin-bottom: 12px; }
.how-step p { color: var(--ath-muted); line-height: 1.7; }

/* What we ask */
.partners-ask { background: var(--ath-light); }
.ask-grid { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 80px; align-items: center; }
.ask-text h2 { font-size: clamp(1.8rem, 4vw, 2.5rem); color: var(--ath-deep); font-weight: 800; font-family: 'Outfit', sans-serif; margin: 8px 0 24px; }
.ask-list { list-style: none; padding: 0; display: grid; gap: 16px; margin-bottom: 24px; }
.ask-list li { display: flex; align-items: flex-start; gap: 14px; color: var(--ath-text); line-height: 1.5; }
.ask-list i { color: var(--ath-teal); margin-top: 3px; flex-shrink: 0; }
.ask-note { font-size: 0.95rem; color: var(--ath-muted); font-style: italic; }
.ask-card { background: var(--ath-deep); border-radius: var(--ath-radius); overflow: hidden; }
.ac-inner { padding: 50px; color: #fff; text-align: center; }
.ac-inner i { font-size: 3rem; color: var(--ath-gold); margin-bottom: 20px; display: block; }
.ac-inner h3 { font-size: 1.6rem; font-weight: 800; margin-bottom: 12px; }
.ac-inner p { opacity: 0.85; line-height: 1.6; }

/* What you gain */
.gain-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
.gain-card { padding: 40px; border: 1px solid rgba(0,0,0,0.06); border-radius: var(--ath-radius); transition: var(--ath-trans); }
.gain-card:hover { border-color: var(--ath-teal); box-shadow: 0 20px 60px rgba(3,139,137,0.08); transform: translateY(-4px); }
.gain-card i { font-size: 2rem; color: var(--ath-teal); margin-bottom: 16px; display: block; }
.gain-card h3 { font-size: 1.2rem; color: var(--ath-deep); font-weight: 800; margin-bottom: 10px; }
.gain-card p { color: var(--ath-muted); line-height: 1.7; }

/* Briefs */
.partners-briefs { background: var(--ath-deep); color: #fff; }
.partners-briefs .section-label h2 { color: #fff; }
.partners-briefs .ath-sub { opacity: 0.7; }
.briefs-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 40px; }
.brief-track { background: rgba(255,255,255,0.06); border-radius: 24px; padding: 36px; border: 1px solid rgba(255,255,255,0.08); }
.bt-icon { width: 50px; height: 50px; background: rgba(238,157,29,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--ath-gold); font-size: 1.2rem; margin-bottom: 16px; }
.brief-track h3 { font-size: 1.15rem; font-weight: 800; margin-bottom: 10px; }
.brief-track p { opacity: 0.8; line-height: 1.7; font-size: 0.95rem; }
.briefs-note { color: rgba(255,255,255,0.7); font-style: italic; font-size: 0.95rem; }

/* FAQ */
.faq-list { display: grid; gap: 12px; max-width: 800px; }
.faq-item { border: 1px solid rgba(0,0,0,0.08); border-radius: 16px; overflow: hidden; }
.faq-item summary {
    padding: 24px 28px;
    font-weight: 700;
    color: var(--ath-deep);
    cursor: pointer;
    list-style: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 1.05rem;
}
.faq-item summary::-webkit-details-marker { display: none; }
.faq-item summary::after { content: '+'; font-size: 1.4rem; color: var(--ath-teal); transition: transform 0.3s; flex-shrink: 0; }
.faq-item[open] summary::after { transform: rotate(45deg); }
.faq-item[open] { background: var(--ath-light); }
.faq-answer { padding: 0 28px 24px; }
.faq-answer p { color: var(--ath-muted); line-height: 1.7; margin: 0; }

/* Form */
.form-layout { display: grid; grid-template-columns: 0.8fr 1.2fr; gap: 80px; align-items: start; }
.form-intro h2 { font-size: clamp(1.8rem, 4vw, 2.5rem); color: var(--ath-deep); font-weight: 800; font-family: 'Outfit', sans-serif; margin: 8px 0 16px; }
.form-intro p { color: var(--ath-muted); line-height: 1.7; margin-bottom: 24px; }
.form-contact-alt { background: var(--ath-light); padding: 20px 24px; border-radius: 16px; }
.form-contact-alt p { margin: 0; color: var(--ath-text); font-size: 0.95rem; }
.form-card { background: #fff; border: 1px solid rgba(0,0,0,0.07); border-radius: var(--ath-radius); padding: 50px; }
.form-group { margin-bottom: 24px; }
.form-group label { display: block; font-weight: 700; color: var(--ath-deep); margin-bottom: 8px; font-size: 0.95rem; }
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 14px 18px;
    border: 1.5px solid rgba(0,0,0,0.12);
    border-radius: 12px;
    font-size: 1rem;
    font-family: inherit;
    color: var(--ath-text);
    background: #fff;
    transition: border-color 0.2s;
    box-sizing: border-box;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus { outline: none; border-color: var(--ath-teal); }
.form-group textarea { resize: vertical; min-height: 120px; }
.form-submit { width: 100%; justify-content: center; margin-top: 8px; font-size: 1.05rem; }
.form-errors { background: #fef2f2; border: 1px solid #fca5a5; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; }
.form-errors ul { margin: 0; padding-left: 20px; color: #b91c1c; }

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
.partners-hero .ath-sub { color: rgba(255,255,255,0.7); }

@media (max-width: 992px) {
    .how-grid { grid-template-columns: 1fr; }
    .ask-grid { grid-template-columns: 1fr; gap: 40px; }
    .gain-grid { grid-template-columns: 1fr; }
    .briefs-grid { grid-template-columns: 1fr; }
    .form-layout { grid-template-columns: 1fr; gap: 50px; }
}

@media (max-width: 768px) {
    .partners-hero { padding: 120px 0 80px; }
    .partners-section { padding: 70px 0; }
    .form-card { padding: 30px 24px; }
}
</style>
@endpush

@endsection
