@extends('layouts.aethryna')

@section('title', 'Skills Co-op | Acceptable Use Policy')
@section('meta_description', 'Skills Co-op acceptable use policy. The conduct expected of all learners, mentors, partners and visitors on the platform.')
@section('og_title', 'Acceptable Use Policy | Skills Co-op')

@section('content')


    <section class="legal-hero">
        <div class="legal-container">
            <nav class="legal-breadcrumb"><a href="/">Home</a><span>/</span><span>Legal</span><span>/</span><span class="current">Acceptable Use Policy</span></nav>
            <h1>Acceptable Use Policy</h1>
            <p class="legal-subtitle">The conduct expected of all learners, mentors, partners, and visitors on the Skills Co-op platform.</p>
            <div class="legal-meta">
                <span><i class="fas fa-calendar"></i> Last reviewed: June 2026</span>
                <span><i class="fas fa-tag"></i> Version 1.0</span>
                <span><i class="fas fa-building"></i> Aethryna Digital Skills Co-op CIC</span>
            </div>
        </div>
    </section>


    <style>
        :root {
            --teal: #038b89;
            --deep: #055860;
            --gold: #ee9d1d;
            --text: #404952;
            --muted: #6b7480;
            --bg-light: #f8fafb;
            --bg-teal: #eaf5f5;
            --border: #d4e8e8;
        }
        .legal-hero {
            background: linear-gradient(135deg, var(--deep) 0%, var(--teal) 100%);
            padding: 80px 0 60px;
            color: #fff;
        }
        .legal-container { max-width: 860px; margin: 0 auto; padding: 0 24px; }
        .legal-breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: rgba(255,255,255,0.7); margin-bottom: 24px; flex-wrap: wrap; }
        .legal-breadcrumb a { color: rgba(255,255,255,0.7); text-decoration: none; }
        .legal-breadcrumb a:hover { color: var(--gold); }
        .legal-breadcrumb span { color: rgba(255,255,255,0.5); }
        .legal-breadcrumb .current { color: #fff; font-weight: 600; }
        .legal-hero h1 { font-size: 2.5rem; font-weight: 800; margin: 0 0 12px; color: #fff; }
        .legal-subtitle { font-size: 1.05rem; color: rgba(255,255,255,0.85); margin: 0 0 24px; }
        .legal-meta { display: flex; flex-wrap: wrap; gap: 20px; }
        .legal-meta span { font-size: 0.85rem; color: rgba(255,255,255,0.7); display: flex; align-items: center; gap: 6px; }
        .legal-section { padding: 52px 0 0; }
        .legal-section:last-of-type { padding-bottom: 80px; }
        .legal-badge { display: inline-block; background: var(--bg-teal); color: var(--deep); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; padding: 4px 12px; border-radius: 20px; margin-bottom: 12px; }
        .legal-section h2 { font-size: 1.5rem; font-weight: 700; color: var(--deep); margin: 0 0 16px; padding-bottom: 10px; border-bottom: 2px solid var(--border); }
        .legal-section h3 { font-size: 1.1rem; font-weight: 700; color: var(--teal); margin: 24px 0 10px; }
        .legal-section p { color: var(--text); line-height: 1.75; margin: 0 0 14px; }
        .legal-section ul, .legal-section ol { color: var(--text); line-height: 1.75; padding-left: 24px; margin: 0 0 14px; }
        .legal-section li { margin-bottom: 6px; }
        .legal-table-wrap { overflow-x: auto; margin: 16px 0 24px; border-radius: 8px; border: 1px solid var(--border); }
        .legal-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        .legal-table th { background: var(--deep); color: #fff; text-align: left; padding: 10px 14px; font-weight: 600; }
        .legal-table td { padding: 9px 14px; border-bottom: 1px solid var(--border); color: var(--text); vertical-align: top; }
        .legal-table tr:last-child td { border-bottom: none; }
        .legal-table tr:nth-child(even) td { background: var(--bg-light); }
        .legal-info-box { background: var(--bg-teal); border: 1px solid var(--border); border-radius: 8px; padding: 20px 24px; margin: 16px 0 24px; }
        .legal-info-row { display: flex; gap: 16px; padding: 6px 0; border-bottom: 1px solid var(--border); font-size: 0.9rem; }
        .legal-info-row:last-child { border-bottom: none; }
        .legal-info-label { font-weight: 700; color: var(--deep); min-width: 160px; }
        .legal-info-value { color: var(--text); }
        .legal-alert { background: #fff8e6; border-left: 4px solid var(--gold); border-radius: 0 6px 6px 0; padding: 14px 18px; margin: 16px 0; font-size: 0.9rem; color: var(--text); }
        .legal-cta { background: linear-gradient(135deg, var(--deep) 0%, var(--teal) 100%); color: #fff; padding: 48px 24px; text-align: center; margin-top: 60px; }
        .legal-cta h2 { color: #fff; font-size: 1.6rem; margin: 0 0 10px; }
        .legal-cta p { color: rgba(255,255,255,0.85); margin: 0 0 24px; }
        .legal-cta a { background: var(--gold); color: var(--deep); font-weight: 700; padding: 12px 28px; border-radius: 50px; text-decoration: none; display: inline-block; }
        .legal-nav { background: var(--bg-teal); border: 1px solid var(--border); border-radius: 8px; padding: 20px 24px; margin-bottom: 40px; }
        .legal-nav p { font-weight: 700; color: var(--deep); margin: 0 0 10px; font-size: 0.9rem; }
        .legal-nav ul { margin: 0; padding-left: 20px; }
        .legal-nav li { margin-bottom: 4px; }
        .legal-nav a { color: var(--teal); text-decoration: none; font-size: 0.9rem; }
        .legal-nav a:hover { color: var(--deep); text-decoration: underline; }
        @media (max-width: 600px) {
            .legal-hero h1 { font-size: 1.8rem; }
            .legal-info-row { flex-direction: column; gap: 2px; }
            .legal-info-label { min-width: unset; }
        }
    </style>

    <div style="max-width: 860px; margin: 0 auto; padding: 0 24px;">

        {-- Table of contents --}
        <div class="legal-nav" style="margin-top: 40px;">
            <p>Contents</p>
            <ul><li><a href="#purpose">1. Purpose</a></li><li><a href="#who-it-covers">2. Who This Policy Covers</a></li><li><a href="#expected-conduct">3. Expected Conduct</a></li><li><a href="#prohibited-conduct">4. Prohibited Conduct</a></li><li><a href="#safeguarding">5. Safeguarding</a></li><li><a href="#reporting">6. Reporting a Breach</a></li><li><a href="#consequences">7. Consequences of Breach</a></li><li><a href="#updates">8. Updates</a></li></ul>
        </div>


    <section class="legal-section" id="purpose"><div class="legal-container">
        <span class="legal-badge">Purpose</span><h2>1. Purpose</h2>
        <p>Skills Co-op is a community built on respect, dignity, and belonging. This Acceptable Use Policy sets out the conduct expected of every person who uses the Skills Co-op platform, attends our events, or participates in our programmes.</p>
        <p>This policy exists to protect our learners, many of whom have faced significant barriers and may be particularly vulnerable. It also protects mentors, coaches, staff, and the integrity of our community.</p>
        <div class="legal-alert">Breaches of this policy may result in removal from a session, suspension or termination of your account, and, where conduct is serious or unlawful, referral to the appropriate authorities.</div>
    </div></section>

    <section class="legal-section" id="who-it-covers"><div class="legal-container">
        <span class="legal-badge">Scope</span><h2>2. Who This Policy Covers</h2>
        <p>This policy applies to all users of the Skills Co-op platform and services, including:</p>
        <ul>
            <li>Learners enrolled in a programme or track</li>
            <li>Mentors and coaches providing support</li>
            <li>Partners and organisational representatives</li>
            <li>Visitors to the public website</li>
            <li>Any person attending a Skills Co-op panel session or event</li>
        </ul>
        <p>It covers all interactions on the platform, in sessions (virtual or in-person), in email correspondence, and in any online space where you are representing or engaging with Skills Co-op.</p>
    </div></section>

    <section class="legal-section" id="expected-conduct"><div class="legal-container">
        <span class="legal-badge">Standards</span><h2>3. Expected Conduct</h2>
        <p>All users are expected to:</p>
        <ul>
            <li>treat every person they interact with through Skills Co-op with dignity, courtesy, and respect,</li>
            <li>be honest about their identity, qualifications, and experience,</li>
            <li>respect the confidentiality of information shared by other users in sessions or on the platform,</li>
            <li>engage constructively and in good faith with the learning process,</li>
            <li>attend registered sessions, or give reasonable advance notice if they cannot, and</li>
            <li>report any conduct they believe breaches this policy to <strong>hello@skillscoop.org</strong>.</li>
        </ul>
    </div></section>

    <section class="legal-section" id="prohibited-conduct"><div class="legal-container">
        <span class="legal-badge">Prohibited Conduct</span><h2>4. Prohibited Conduct</h2>
        <p>The following conduct is not permitted on the Skills Co-op platform or in connection with any of our programmes or events.</p>

        <h3>4.1 Harassment and Harm</h3>
        <ul>
            <li>Bullying, intimidation, or harassment of any kind, including on the basis of age, disability, gender, race, religion, sexual orientation, or any other protected characteristic.</li>
            <li>Threatening, abusive, or offensive language or behaviour, whether directed at a specific person or group.</li>
            <li>Conduct that creates a hostile, unsafe, or unwelcoming environment for any user.</li>
        </ul>

        <h3>4.2 Deception and Misrepresentation</h3>
        <ul>
            <li>Providing false or misleading information when registering or completing the career assessment.</li>
            <li>Impersonating another person or organisation.</li>
            <li>Creating multiple accounts without our express permission.</li>
            <li>Misrepresenting your professional credentials or experience to a learner.</li>
        </ul>

        <h3>4.3 Inappropriate Relationships</h3>
        <ul>
            <li>Attempting to form a personal, romantic, or financial relationship with a learner outside of the Skills Co-op programme structure.</li>
            <li>Sharing personal contact details with a learner (phone numbers, personal email, social media) to bypass the platform.</li>
            <li>Any conduct that could constitute grooming of a young person or vulnerable adult.</li>
        </ul>

        <h3>4.4 Illegal Activity</h3>
        <ul>
            <li>Using the platform to facilitate or promote any unlawful activity.</li>
            <li>Uploading, sharing, or linking to content that is illegal, obscene, or defamatory.</li>
            <li>Attempting to gain unauthorised access to our systems or other users' accounts.</li>
            <li>Introducing viruses, malware, or any other malicious code to the platform.</li>
        </ul>

        <h3>4.5 Spam and Commercial Solicitation</h3>
        <ul>
            <li>Sending unsolicited commercial messages or promotions to other users.</li>
            <li>Using contact details obtained through Skills Co-op to market products or services without our prior written consent.</li>
        </ul>
    </div></section>

    <section class="legal-section" id="safeguarding"><div class="legal-container">
        <span class="legal-badge">Safeguarding</span><h2>5. Safeguarding</h2>
        <p>Skills Co-op works with young people aged 16 and over and adults who may have experienced trauma, exclusion, or marginalisation. Safeguarding is central to how we operate, not an afterthought.</p>
        <p>If you are a mentor or coach, you must:</p>
        <ul>
            <li>complete any safeguarding induction we require before working with learners,</li>
            <li>report any safeguarding concern to us immediately, without investigating it yourself,</li>
            <li>maintain appropriate professional boundaries at all times, and</li>
            <li>never meet a learner outside an organised Skills Co-op session without prior agreement from our team.</li>
        </ul>
        <div class="legal-alert">To report a safeguarding concern, contact <strong>hello@skillscoop.org</strong> with the subject line SAFEGUARDING. In an emergency, contact the emergency services (999).</div>
    </div></section>

    <section class="legal-section" id="reporting"><div class="legal-container">
        <span class="legal-badge">Reporting</span><h2>6. Reporting a Breach</h2>
        <p>If you witness or experience conduct that breaches this policy, you can report it by:</p>
        <ul>
            <li>emailing <strong>hello@skillscoop.org</strong> with a description of what happened, when, and who was involved,</li>
            <li>raising it directly with the facilitator during a live session, or</li>
            <li>using the contact form at <a href="{{ route('sessions') }}">skillscoop.org/sessions</a>.</li>
        </ul>
        <p>All reports will be treated confidentially, investigated promptly, and handled with care. We will not tolerate retaliation against anyone who makes a report in good faith.</p>
    </div></section>

    <section class="legal-section" id="consequences"><div class="legal-container">
        <span class="legal-badge">Consequences</span><h2>7. Consequences of Breach</h2>
        <p>Depending on the nature and severity of the conduct, we may:</p>
        <ul>
            <li>issue a formal warning,</li>
            <li>remove the person from a session or event,</li>
            <li>suspend or permanently terminate their account,</li>
            <li>ban them from future Skills Co-op programmes and events, and/or</li>
            <li>refer the matter to the police or other statutory authorities if the conduct is unlawful or involves risk to a child or vulnerable adult.</li>
        </ul>
    </div></section>

    <section class="legal-section" id="updates"><div class="legal-container">
        <span class="legal-badge">Updates</span><h2>8. Updates to This Policy</h2>
        <p>We review this policy at least annually or following any significant incident. The version number and effective date above will be updated when changes are made. Continued use of Skills Co-op following an update constitutes acceptance of the revised policy.</p>
    </div></section>

    </div>

    {-- CTA --}
    <div class="legal-cta">
        <h2>Have a safeguarding or conduct concern?</h2>
        <p>Contact us at <strong>hello@skillscoop.org</strong> and we will respond within 10 working days.</p>
        <a href="mailto:hello@skillscoop.org">Report It</a>
    </div>

@endsection