@extends('layouts.aethryna')

@section('title', 'SkillsCo-op | Cookie Policy')
@section('meta_description', 'SkillsCo-op cookie policy. We use only strictly necessary cookies and one optional functional cookie. No tracking or advertising cookies.')
@section('og_title', 'Cookie Policy | SkillsCo-op')

@section('content')


    <section class="legal-hero">
        <div class="legal-container">
            <nav class="legal-breadcrumb"><a href="/">Home</a><span>/</span><span>Legal</span><span>/</span><span class="current">Cookie Policy</span></nav>
            <h1>Cookie Policy</h1>
            <p class="legal-subtitle">We use only strictly necessary cookies and one optional functional cookie. No tracking, no advertising.</p>
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
            <ul><li><a href="#what-are-cookies">1. What Are Cookies?</a></li><li><a href="#cookies-we-use">2. Cookies We Use</a></li><li><a href="#what-we-dont-use">3. What We Do Not Use</a></li><li><a href="#your-choices">4. Your Choices</a></li><li><a href="#changes">5. Changes to This Policy</a></li></ul>
        </div>


    <section class="legal-section" id="what-are-cookies"><div class="legal-container">
        <span class="legal-badge">Basics</span><h2>1. What Are Cookies?</h2>
        <p>Cookies are small text files stored on your device when you visit a website. They allow the site to remember information about your visit, such as whether you are logged in. Some cookies are essential for the site to work; others are optional.</p>
        <p>We explain exactly which cookies SkillsCo-op uses below. We do not use advertising or tracking cookies.</p>
    </div></section>

    <section class="legal-section" id="cookies-we-use"><div class="legal-container">
        <span class="legal-badge">Our Cookies</span><h2>2. Cookies We Use</h2>
        <div class="legal-table-wrap"><table class="legal-table">
            <thead><tr><th>Cookie</th><th>Type</th><th>Purpose</th><th>Duration</th></tr></thead>
            <tbody>
                <tr><td><strong>Laravel session cookie</strong></td><td>Strictly necessary</td><td>Keeps you logged in during your visit. Without this cookie the site cannot remember that you are authenticated as you move between pages.</td><td>Deleted when you close your browser</td></tr>
                <tr><td><strong>XSRF-TOKEN</strong> (CSRF token)</td><td>Strictly necessary</td><td>Protects you against cross-site request forgery attacks when you submit forms. This is a security requirement, not a tracking cookie.</td><td>Deleted when you close your browser</td></tr>
                <tr><td><strong>remember_web</strong></td><td>Functional (optional)</td><td>Only set if you select 'Remember me' when logging in. Keeps you logged in for an extended period so you do not have to sign in each visit.</td><td>Up to 90 days</td></tr>
            </tbody>
        </table></div>
    </div></section>

    <section class="legal-section" id="what-we-dont-use"><div class="legal-container">
        <span class="legal-badge">What We Avoid</span><h2>3. What We Do Not Use</h2>
        <p>We do not currently use any of the following:</p>
        <ul>
            <li>Google Analytics or any other web analytics platform</li>
            <li>Facebook Pixel or social media tracking cookies</li>
            <li>Advertising or retargeting cookies</li>
            <li>Third-party marketing cookies</li>
        </ul>
        <p>If this changes in the future, we will update this policy and, where required, seek your consent before setting any new cookies.</p>
    </div></section>

    <section class="legal-section" id="your-choices"><div class="legal-container">
        <span class="legal-badge">Your Control</span><h2>4. Your Choices</h2>
        <h3>Strictly Necessary Cookies</h3>
        <p>The session cookie and CSRF token are essential for the website to function. You cannot opt out of these without disabling the site entirely. They do not track you and do not require consent under the Privacy and Electronic Communications Regulations (PECR).</p>
        <h3>The Remember Me Cookie</h3>
        <p>This is entirely optional. It is only set if you actively choose 'Remember me' when logging in. You can remove it at any time by logging out, which deletes it immediately.</p>
        <h3>Browser Settings</h3>
        <p>You can also control and delete cookies through your browser settings:</p>
        <div class="legal-table-wrap"><table class="legal-table">
            <thead><tr><th>Browser</th><th>Cookie settings location</th></tr></thead>
            <tbody>
                <tr><td>Google Chrome</td><td>Settings > Privacy and security > Cookies and other site data</td></tr>
                <tr><td>Mozilla Firefox</td><td>Settings > Privacy &amp; Security > Cookies and Site Data</td></tr>
                <tr><td>Safari (macOS)</td><td>Preferences > Privacy > Manage Website Data</td></tr>
                <tr><td>Microsoft Edge</td><td>Settings > Cookies and site permissions</td></tr>
                <tr><td>Safari (iOS)</td><td>Settings > Safari > Privacy &amp; Security</td></tr>
            </tbody>
        </table></div>
        <div class="legal-alert">Blocking strictly necessary cookies will prevent you from logging in to SkillsCo-op.</div>
    </div></section>

    <section class="legal-section" id="changes"><div class="legal-container">
        <span class="legal-badge">Updates</span><h2>5. Changes to This Policy</h2>
        <p>We will update this policy if we introduce new cookies or change how existing ones are used. The version number and effective date above will be updated, and we will notify registered users by email before any material change takes effect.</p>
        <p>You can also contact us at <strong>hello@skillscoop.org</strong> with any questions, or complain to the <strong>Information Commissioner's Office (ICO)</strong> at <strong>ico.org.uk</strong> or on <strong>0303 123 1113</strong>.</p>
    </div></section>

    </div>

    {-- CTA --}
    <div class="legal-cta">
        <h2>Questions about cookies?</h2>
        <p>Contact us at <strong>hello@skillscoop.org</strong> and we will respond within 10 working days.</p>
        <a href="mailto:hello@skillscoop.org">Contact Us</a>
    </div>

@endsection