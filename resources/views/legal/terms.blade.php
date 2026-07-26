@extends('layouts.aethryna')

@section('title', 'SkillsCo-op | Terms of Service')
@section('meta_description', 'SkillsCo-op terms of service. The agreement between you and Aethryna Digital Skills Co-op CIC governing use of the SkillsCo-op platform.')
@section('og_title', 'Terms of Service | SkillsCo-op')

@section('content')


    <section class="legal-hero">
        <div class="legal-container">
            <nav class="legal-breadcrumb"><a href="/">Home</a><span>/</span><span>Legal</span><span>/</span><span class="current">Terms of Service</span></nav>
            <h1>Terms of Service</h1>
            <p class="legal-subtitle">The agreement governing your use of the SkillsCo-op platform and services.</p>
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
            <ul><li><a href="#about">1. About These Terms</a></li><li><a href="#eligibility">2. Who Can Use SkillsCo-op</a></li><li><a href="#account">3. Your Account</a></li><li><a href="#services">4. Our Services</a></li><li><a href="#responsibilities">5. Your Responsibilities</a></li><li><a href="#ip">6. Intellectual Property</a></li><li><a href="#liability">7. Limitation of Liability</a></li><li><a href="#safeguarding">8. Safeguarding</a></li><li><a href="#governing-law">9. Governing Law</a></li></ul>
        </div>


    <section class="legal-section" id="about"><div class="legal-container">
        <span class="legal-badge">Introduction</span><h2>1. About These Terms</h2>
        <p>These Terms of Service govern your use of the SkillsCo-op website (skillscoop.org) and any services accessible through it. They form a legally binding agreement between you and <strong>Aethryna Digital Skills Co-op CIC</strong>, a Community Interest Company registered in England and Wales (referred to as 'SkillsCo-op', 'we', 'us', or 'our').</p>
        <p>By creating an account, registering for a session, or using the platform in any way, you confirm that you have read, understood, and agree to be bound by these Terms. If you do not agree, you must not use our services.</p>
        <p>We may update these Terms from time to time. We will notify registered users by email at least 14 days before a material change takes effect. Continued use after that date constitutes acceptance of the updated Terms.</p>
    </div></section>

    <section class="legal-section" id="eligibility"><div class="legal-container">
        <span class="legal-badge">Eligibility</span><h2>2. Who Can Use SkillsCo-op</h2>
        <p>You may use SkillsCo-op if:</p>
        <ul>
            <li>you are aged 16 or over (or have parental or guardian consent if aged 13 to 15),</li>
            <li>you provide accurate and truthful information when registering, and</li>
            <li>you are not prohibited from using our services by any applicable law.</li>
        </ul>
        <p>We reserve the right to refuse access or suspend accounts where we reasonably believe the eligibility conditions are not met.</p>
    </div></section>

    <section class="legal-section" id="account"><div class="legal-container">
        <span class="legal-badge">Your Account</span><h2>3. Your Account</h2>
        <p>You are responsible for keeping your login credentials confidential. Do not share your password. You are responsible for all activity under your account.</p>
        <p>Notify us immediately at <strong>hello@skillscoop.org</strong> if you suspect unauthorised access to your account.</p>
        <p>We reserve the right to suspend or terminate your account if you breach these Terms or behave in a way that is harmful to other users or our team. We will give you reasonable notice before doing so unless the breach is serious.</p>
    </div></section>

    <section class="legal-section" id="services"><div class="legal-container">
        <span class="legal-badge">Our Services</span><h2>4. Our Services</h2>
        <h3>4.1 What We Offer</h3>
        <p>SkillsCo-op provides free, funded digital skills programmes, a career assessment tool, panel sessions, and a mentoring platform. All learner places are provided at no cost to participants.</p>
        <h3>4.2 Service Availability</h3>
        <p>We aim to keep the platform available at all times but cannot guarantee uninterrupted access. We may take the site down for maintenance or security reasons and will provide advance notice where possible.</p>
        <h3>4.3 No Guarantee of Outcomes</h3>
        <p>SkillsCo-op provides educational support and skills development. We do not guarantee employment, earnings, certification, or any specific outcome. Progress depends on individual effort and external factors beyond our control.</p>
        <h3>4.4 Programme Changes</h3>
        <p>We may modify, pause, or discontinue a programme at any time. Where a programme you are enrolled in is discontinued, we will give you as much notice as reasonably possible and signpost you to alternative opportunities where we can.</p>
    </div></section>

    <section class="legal-section" id="responsibilities"><div class="legal-container">
        <span class="legal-badge">Conduct</span><h2>5. Your Responsibilities</h2>
        <p>You agree to use SkillsCo-op honestly and respectfully. You must:</p>
        <ul>
            <li>provide accurate information when registering or completing the assessment,</li>
            <li>treat other learners, mentors, coaches, and staff with dignity and respect,</li>
            <li>not share another person's personal data without their consent,</li>
            <li>attend registered sessions or give reasonable notice if you cannot, and</li>
            <li>comply with our <a href="{{ route('acceptable-use') }}">Acceptable Use Policy</a>.</li>
        </ul>
        <p>Mentors additionally agree to act in the best interests of learners, maintain appropriate professional boundaries, and follow our safeguarding procedures.</p>
    </div></section>

    <section class="legal-section" id="ip"><div class="legal-container">
        <span class="legal-badge">Intellectual Property</span><h2>6. Intellectual Property</h2>
        <p>All platform content, including curriculum materials and assessment tools, is owned by or licensed to Aethryna Digital Skills Co-op CIC. You may use our content for personal learning only. You may not reproduce or distribute it commercially without our prior written permission.</p>
        <p>By submitting content to the platform, you grant us a non-exclusive, royalty-free licence to use it for delivering and improving our services. You retain ownership of your own work.</p>
    </div></section>

    <section class="legal-section" id="liability"><div class="legal-container">
        <span class="legal-badge">Liability</span><h2>7. Limitation of Liability</h2>
        <p>To the fullest extent permitted by law:</p>
        <ul>
            <li>SkillsCo-op is provided on an 'as is' basis. We make no warranties about the accuracy or reliability of our platform or content.</li>
            <li>We are not liable for any indirect or consequential loss arising from your use of SkillsCo-op.</li>
            <li>Our total liability for any direct loss is limited to £100.</li>
        </ul>
        <div class="legal-alert">Nothing in these Terms limits our liability for death or personal injury caused by our negligence, or any liability that cannot be excluded under English law.</div>
    </div></section>

    <section class="legal-section" id="safeguarding"><div class="legal-container">
        <span class="legal-badge">Safeguarding</span><h2>8. Safeguarding</h2>
        <p>The safety and wellbeing of our learners is our highest priority. If you have a safeguarding concern, contact us immediately at <strong>hello@skillscoop.org</strong>. Any conduct that threatens the safety or dignity of a learner or staff member may result in immediate account suspension and referral to relevant authorities.</p>
    </div></section>

    <section class="legal-section" id="governing-law"><div class="legal-container">
        <span class="legal-badge">Governing Law</span><h2>9. Governing Law</h2>
        <p>These Terms are governed by the laws of England and Wales. Any disputes will be subject to the exclusive jurisdiction of the courts of England and Wales. We will always try to resolve disputes informally first: contact us at <strong>hello@skillscoop.org</strong>.</p>
    </div></section>

    </div>

    {-- CTA --}
    <div class="legal-cta">
        <h2>Questions about these Terms?</h2>
        <p>Contact us at <strong>hello@skillscoop.org</strong> and we will respond within 10 working days.</p>
        <a href="mailto:hello@skillscoop.org">Get in Touch</a>
    </div>

@endsection