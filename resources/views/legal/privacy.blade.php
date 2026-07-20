@extends('layouts.aethryna')

@section('title', 'SkillsCo-op | Privacy Policy')
@section('meta_description', 'Skills-Coop privacy policy. How Aethryna Digital Skills Co-op CIC collects, uses and protects your personal data under UK GDPR.')
@section('og_title', 'Privacy Policy | SkillsCo-op')

@section('content')
    <section class="legal-hero">
        <div class="legal-container">
            <nav class="legal-breadcrumb">
                <a href="/">Home</a><span>/</span>
                <a href="#">Legal</a><span>/</span>
                <span class="current">Privacy Policy</span>
            </nav>
            <h1>Privacy Policy</h1>
            <p class="legal-subtitle">How we collect, use, and protect your personal data. Operated under UK GDPR and the Data Protection Act 2018.</p>
            <div class="legal-meta">
                <span><i class="fas fa-calendar"></i> Last reviewed: June 2026</span>
                <span><i class="fas fa-tag"></i> Version 1.0</span>
                <span><i class="fas fa-building"></i> Aethryna Digital Skills Co-Op CIC</span>
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
        .legal-hero {background: linear-gradient(135deg, var(--deep) 0%, var(--teal) 100%); padding: 80px 0 60px; color: #fff;}
        .legal-container {max-width: 860px; margin: 0 auto; padding: 0 24px;}
        .legal-breadcrumb {display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: rgba(255,255,255,0.7); margin-bottom: 24px; flex-wrap: wrap;}
        .legal-breadcrumb a {color: rgba(255,255,255,0.7); text-decoration: none;}
        .legal-breadcrumb a:hover {color: var(--gold);}
        .legal-breadcrumb span {color: rgba(255,255,255,0.5);}
       legal-breadcrumb .current {color: #fff; font-weight: 600;}
        .legal-hero h1 {font-size: 2.5rem; font-weight: 800; margin: 0 0 12px;}
        .legal-subtitle {font-size: 1.05rem; color: rgba(255,255,255,0.85); margin: 0 0 24px;}
        .legal-meta {display: flex; flex-wrap: wrap; gap: 20px;}
        .legal-meta span {font-size: 0.85rem; color: rgba(255,255,255,0.7); display: flex; align-items: center; gap: 6px;}
        .legal-section {padding: 52px 0 0;}
        .legal-section:last-of-type {padding-bottom: 80px;}
        .legal-badge {display: inline-block; background: var(--bg-teal); color: var(--deep); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; padding: 4px 12px; border-radius: 20px; margin-bottom: 12px;}
        .legal-section h2 {font-size: 1.5rem; font-weight: 700; color: var(--deep); margin: 0 0 16px; padding-bottom: 10px; border-bottom: 2px solid var(--border);}
        .legal-section h3 {font-size: 1.1rem; font-weight: 700; color: var(--teal); margin: 24px 0 10px;}
        .legal-section p, .legal-section li {color: var(--text); line-height: 1.75;}
        .legal-section ul, .legal-section ol {padding-left: 24px; margin: 0 0 14px;}
        .legal-table-wrap {overflow-x: auto; margin: 16px 0 24px; border-radius: 8px; border: 1px solid var(--border);}
        .legal-table {width: 100%; border-collapse: collapse; font-size: 0.9rem;}
        .legal-table th {background: var(--deep); color: #fff; text-align: left; padding: 10px 14px; font-weight: 600;}
        .legal-table td {padding: 9px 14px; border-bottom: 1px solid var(--border); color: var(--text); vertical-align: top;}
        .legal-table tr:nth-child(even) td {background: var(--bg-light);}
        .legal-alert {background: #fff8e6; border-left: 4px solid var(--gold); border-radius: 0 6px 6px 0; padding: 14px 18px; margin: 16px 0; font-size: 0.9rem; color: var(--text);}
        .legal-cta {background: linear-gradient(135deg, var(--deep) 0%, var(--teal) 100%); color: #fff; padding: 48px 24px; text-align: center; margin-top: 60px;}
        .legal-cta h2 {color: #fff; font-size: 1.6rem; margin: 0 0 10px;}
        .legal-cta p {color: rgba(255,255,255,0.85); margin: 0 0 24px;}
        .legal-cta a {background: var(--gold); color: var(--deep); font-weight: 700; padding: 12px 28px; border-radius: 50px; text-decoration: none; display: inline-block;}
        .legal-nav {background: var(--bg-teal); border: 1px solid var(--border); border-radius: 8px; padding: 20px 24px; margin-bottom: 40px;}
        .legal-nav p {font-weight: 700; color: var(--deep); margin: 0 0 10px; font-size: 0.9rem;}
        .legal-nav ul {margin: 0; padding-left: 20px;}
        .legal-nav li {margin-bottom: 4px;}
        .legal-nav a {color: var(--teal); text-decoration: none; font-size: 0.9rem;}
        .legal-nav a:hover {color: var(--deep); text-decoration: underline;}
        .legal-info-box {background: var(--bg-light); border: 1px solid var(--border); border-radius: 10px; padding: 4px 22px; margin: 20px 0;}
        .legal-info-row {display: grid; grid-template-columns: 180px 1fr; gap: 16px; padding: 14px 0; border-bottom: 1px solid var(--border); align-items: baseline;}
        .legal-info-row:last-child {border-bottom: none;}
        .legal-info-label {font-weight: 700; color: var(--muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.06em;}
        .legal-info-value {color: var(--text); font-size: 0.95rem; line-height: 1.55;}
        @media (max-width: 600px) {
            .legal-hero h1 {font-size: 1.8rem;}
            .legal-info-row {grid-template-columns: 1fr; gap: 2px; padding: 12px 0;}
        }
    </style>

    <div class="legal-nav" style="margin-top: 40px;">
        <p>Contents</p>
        <ul>
            <li><a href="#who-we-are">1. Who We Are</a></li>
            <li><a href="#scope">2. Scope of This Policy</a></li>
            <li><a href="#data-collect">3. What Personal Data We Collect</a></li>
            <li><a href="#lawful-basis">4. Our Lawful Basis for Processing</a></li>
            <li><a href="#special-category">5. Special Category Data</a></li>
            <li><a href="#share-data">6. Who We Share Your Data With</a></li>
            <li><a href="#retention">7. How Long We Keep Your Data</a></li>
            <li><a href="#protect">8. How We Protect Your Data</a></li>
            <li><a href="#cookies">9. Cookies and Session Data</a></li>
            <li><a href="#rights">10. Your Rights Under UK GDPR</a></li>
            <li><a href="#children">11. Children and Young People</a></li>
            <li><a href="#international">12. International Data Transfers</a></li>
            <li><a href="#automated">13. Automated Decision-Making</a></li>
            <li><a href="#changes">14. Changes to This Policy</a></li>
            <li><a href="#contact">15. Contact Us</a></li>
        </ul>
    </div>

    <section class="legal-section" id="who-we-are">
        <div class="legal-container">
            <span class="legal-badge">Introduction</span>
            <h2>1. Who We Are</h2>
            <p>SkillsCo-op is the public‑facing brand of <strong>Aethryna Digital Skills Co‑op CIC</strong>, a Community Interest Company registered in England and Wales. We provide free, funded digital skills programmes, career assessments, panel sessions, and mentoring to people facing barriers to employment, including NEET young people, justice‑involved individuals, migrants, refugees, and career changers.</p>
            <p>For the purposes of data protection law, <strong>Aethryna Digital Skills Co‑op CIC</strong> is the <strong>data controller</strong> for all personal data collected through the SkillsCo‑op website and services.</p>
            <div class="legal-info-box">
                <div class="legal-info-row"><span class="legal-info-label">Data controller</span><span class="legal-info-value">Aethryna Digital Skills Co‑op CIC (trading as SkillsCo‑op)</span></div>
                <div class="legal-info-row"><span class="legal-info-label">Company number</span><span class="legal-info-value">17007317</span></div>
                <div class="legal-info-row"><span class="legal-info-label">Registered address</span><span class="legal-info-value">Unit A 82 James Carter Road, Mildenhall, United Kingdom IP28 7DE</span></div>
                <div class="legal-info-row"><span class="legal-info-label">Contact email</span><span class="legal-info-value">info@skillscoop.org</span></div>
                <div class="legal-info-row"><span class="legal-info-label">Applicable law</span><span class="legal-info-value">UK GDPR and the Data Protection Act 2018</span></div>
            </div>
        </div>
    </section>

    <section class="legal-section" id="scope">
        <div class="legal-container">
            <span class="legal-badge">Scope</span>
            <h2>2. Scope of This Policy</h2>
            <p>This policy applies to personal data collected through:</p>
            <ul>
                <li>the <code>skillscoop.org</code> website and any sub‑domain</li>
                <li>account registration and the learner dashboard</li>
                <li>the career assessment tool</li>
                <li>session registration and the panel series wait‑list</li>
                <li>the newsletter and footer sign‑up form</li>
                <li>any email correspondence with us</li>
            </ul>
            <p>It does not apply to third‑party websites we may link to. We are not responsible for the privacy practices of external sites.</p>
        </div>
    </section>

    <section class="legal-section" id="data-collect">
        <div class="legal-container">
            <span class="legal-badge">Data</span>
            <h2>3. What Personal Data We Collect</h2>
            <p>We collect only the data we need to deliver and improve our services. The table below sets out each category, what is collected, its source, and why we collect it.</p>
            <div class="legal-table-wrap">
                <table class="legal-table">
                    <thead>
                        <tr><th>Category</th><th>What is collected</th><th>Source</th><th>Why we collect it</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><strong>Account registration</strong></td><td>Name, email address, hashed password, role (learner, mentor, coach or admin)</td><td>Provided by you</td><td>To create and manage your account and personalise your experience</td></tr>
                        <tr><td><strong>Session registration</strong></td><td>Name, email address, interest type, referral source</td><td>Provided by you</td><td>To confirm your place at a panel session and add you to our mailing list with your consent</td></tr>
                        <tr><td><strong>Newsletter / wait‑list</strong></td><td>Email address</td><td>Provided via the footer sign‑up form</td><td>To send programme updates, panel announcements and learning resources</td></tr>
                        <tr><td><strong>Career assessment</strong></td><td>Your responses to the pathway assessment questions, linked to your account</td><td>Provided by you</td><td>To recommend the most suitable learning track for your goals</td></tr>
                        <tr><td><strong>Mentoring records</strong></td><td>Session date and duration, topics covered, action items, mentor notes, learner engagement rating</td><td>Entered by mentors or coaches (internal staff)</td><td>To track learning progress and provide consistent support across sessions</td></tr>
                        <tr><td><strong>Technical and security data</strong></td><td>Session tokens, password reset tokens, application cache data, server logs</td><td>Generated automatically</td><td>To keep your account secure and the site functioning correctly</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="legal-section" id="lawful-basis">
        <div class="legal-container">
            <span class="legal-badge">Legal Basis</span>
            <h2>4. Our Lawful Basis for Processing</h2>
            <p>We process your personal data on the following lawful bases under UK GDPR Article 6:</p>
            <div class="legal-table-wrap">
                <table class="legal-table">
                    <thead>
                        <tr><th>Processing activity</th><th>Lawful basis (UK GDPR Article 6)</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Creating and managing your account</td><td>Contract (Article 6(1)(b))</td></tr>
                        <tr><td>Sending verification and password reset emails</td><td>Contract (Article 6(1)(b))</td></tr>
                        <tr><td>Adding you to the mailing list</td><td>Consent (Article 6(1)(a))</td></tr>
                        <tr><td>Career assessment and pathway recommendation</td><td>Contract (Article 6(1)(b))</td></tr>
                        <tr><td>Mentoring session notes</td><td>Legitimate interests (Article 6(1)(f))</td></tr>
                        <tr><td>Security and fraud prevention</td><td>Legitimate interests (Article 6(1)(f))</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="legal-section" id="special-category">
        <div class="legal-container">
            <span class="legal-badge">Special Category</span>
            <h2>5. Special Category Data</h2>
            <p>We do not currently request or intentionally collect special category data (Article 9 UK GDPR), such as health information, ethnicity, criminal history, or disability status, through any form or registration process on the website.</p>
            <p>Some of our target beneficiary groups include justice‑involved individuals and people from specific communities. Where any future programme requires collection of such data (for example, to evidence impact for a funder), we will publish a separate specific privacy notice and obtain explicit consent.</p>
        </div>
    </section>

    <section class="legal-section" id="share-data">
        <div class="legal-container">
            <span class="legal-badge">Sharing</span>
            <h2>6. Who We Share Your Data With</h2>
            <p>We do not sell, rent or trade personal data. We share data only with the service providers listed below, and only to the extent necessary to deliver the service.</p>
            <div class="legal-table-wrap">
                <table class="legal-table">
                    <thead>
                        <tr><th>Service provider</th><th>Data shared</th><th>Purpose</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><strong>EmailOctopus</strong> (Sugarbox Ltd, UK)</td><td>Email address, first name (where provided)</td><td>Sending newsletters, panel announcements, and marketing emails. Contacts are tagged by interest type.</td></tr>
                        <tr><td><strong>Resend</strong> (Resend Inc., USA)</td><td>Email address</td><td>Sending transactional system emails: verification, password reset, welcome. Covered by Standard Contractual Clauses.</td></tr>
                    </tbody>
                </table>
            </div>
            <p>We may also disclose personal data where required by law, court order, or regulatory authority.</p>
        </div>
    </section>

    <section class="legal-section" id="retention">
        <div class="legal-container">
            <span class="legal-badge">Retention</span>
            <h2>7. How Long We Keep Your Data</h2>
            <div class="legal-table-wrap">
                <table class="legal-table">
                    <thead>
                        <tr><th>Data type</th><th>Retention period</th><th>Reason</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Account data (name, email, role)</td><td>Duration of account + 12 months after deletion request</td><td>Compliance with contractual obligations and reasonable account recovery period</td></tr>
                        <tr><td>Career assessment responses and results</td><td>Duration of account (deleted when account is deleted)</td><td>Needed for personalised experience</td></tr>
                        <tr><td>Session registration records</td><td>3 years from the date of the session</td><td>Evidence programme activity and community impact reporting</td></tr>
                        <tr><td>Newsletter / wait‑list email</td><td>Until you unsubscribe or withdraw consent</td><td>We only send communications while you have consented</td></tr>
                        <tr><td>Mentoring session notes</td><td>Duration of the mentoring relationship + 2 years</td><td>Provide continuity of support and evidence programme outcomes</td></tr>
                        <tr><td>Password reset tokens</td><td>1 hour (automatically deleted)</td><td>Security – short‑lived one‑time use tokens</td></tr>
                        <tr><td>Server and security logs</td><td>90 days</td><td>Standard practice for security monitoring and debugging</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="legal-section" id="protect">
        <div class="legal-container">
            <span class="legal-badge">Security</span>
            <h2>8. How We Protect Your Data</h2>
            <ul>
                <li>All passwords are hashed using <code>bcrypt</code> before storage. We never store passwords in plain text.</li>
                <li>All data transmitted between your browser and our website is encrypted in transit using HTTPS/TLS.</li>
                <li>Access to personal data within the platform is controlled by role‑based permissions. Learner data is not accessible to other learners.</li>
                <li>Password reset tokens are single‑use, time‑limited (one hour), and invalidated immediately after use.</li>
                <li>Our email verification system confirms ownership of the email address before activation.</li>
                <li>We use a fail‑soft architecture for third‑party integrations: if EmailOctopus or Resend is unavailable, your data is still saved locally and the site continues to function.</li>
            </ul>
            <p>No method of transmission or storage is 100 % secure. If you have concerns about the security of your data, please contact us at <strong>hello@skillscoop.org</strong>.</p>
        </div>
    </section>

    <section class="legal-section" id="cookies">
        <div class="legal-container">
            <span class="legal-badge">Cookies</span>
            <h2>9. Cookies and Session Data</h2>
            <p>The SkillsCo‑op website uses the following types of cookies and session data:</p>
            <ul>
                <li><strong>Session cookie</strong> – Laravel session cookie – Keeps you logged in during your visit. Without it the site cannot remember your authentication state between page loads. Expires at end of browser session.</li>
                <li><strong>CSRF token</strong> – <code>XSRF‑TOKEN</code> – Prevents cross‑site request forgery attacks on form submissions. Expires at end of browser session.</li>
                <li><strong>Remember‑me token</strong> – <code>remember_web</code> (optional) – If you select “remember me” at login, this cookie keeps you logged in for an extended period (up to 90 days).</li>
                <li><strong>Cache</strong> – Server‑side application cache – Improves site performance. Does not leave your browser. Retention varies by content type.</li>
            </ul>
            <p>We do not currently use third‑party tracking cookies, advertising cookies, or analytics platforms (such as Google Analytics). If this changes, we will update this policy and seek your consent where required.</p>
        </div>
    </section>

    <section class="legal-section" id="rights">
        <div class="legal-container">
            <span class="legal-badge">Rights</span>
            <h2>10. Your Rights Under UK GDPR</h2>
            <p>You have the following rights in relation to your personal data. To exercise any right, please email <strong>hello@skillscoop.org</strong>. We will respond within one calendar month.</p>
            <ul>
                <li><strong>Access</strong> – Request a copy of the personal data we hold about you (Subject Access Request).</li>
                <li><strong>Rectification</strong> – Correct any inaccurate or incomplete data. You can also update your name and email directly in your account settings.</li>
                <li><strong>Erasure (right to be forgotten)</strong> – Ask us to delete your personal data, unless we are required to retain it for legal or contractual reasons.</li>
                <li><strong>Restriction</strong> – Request a pause on processing of your data in certain circumstances.</li>
                <li><strong>Portability</strong> – Receive a copy of your data in a structured, machine‑readable format.</li>
                <li><strong>Object</strong> – Object to processing based on legitimate interests.</li>
                <li><strong>Withdraw consent</strong> – Where processing is based on consent (e.g., marketing emails), you can withdraw it at any time. Every marketing email includes an unsubscribe link.</li>
                <li><strong>Lodge a complaint</strong> – If you are not satisfied with how we handle your data, you can complain to the Information Commissioner’s Office (ICO) at <a href="https://ico.org.uk" target="_blank">ico.org.uk</a> or call 0303 123 1113.</li>
            </ul>
        </div>
    </section>

    <section class="legal-section" id="children">
        <div class="legal-container">
            <span class="legal-badge">Young People</span>
            <h2>11. Children and Young People</h2>
            <p>Our programmes are designed for people aged 16 and over. We do not knowingly collect personal data from children under 13 without verifiable parental consent.</p>
            <p>Where we deliver programmes to young people aged 13‑17, we apply enhanced data protection measures consistent with our Safeguarding and Learner Wellbeing Policy.</p>
            <p>If you believe a child under 13 has provided personal data to us without parental consent, please contact <strong>hello@skillscoop.org</strong> and we will delete it promptly.</p>
        </div>
    </section>

    <section class="legal-section" id="international">
        <div class="legal-container">
            <span class="legal-badge">International</span>
            <h2>12. International Data Transfers</h2>
            <p>Some of our service providers are based outside the UK. Where personal data is transferred internationally, we ensure appropriate safeguards are in place:</p>
            <ul>
                <li><strong>EmailOctopus</strong> – Operated by Sugarbox Ltd, UK. Data is stored and processed in accordance with UK GDPR.</li>
                <li><strong>Resend</strong> – Operated by Resend Inc., USA. Transfers are covered by Standard Contractual Clauses approved by the UK Information Commissioner, providing an equivalent level of protection.</li>
            </ul>
            <p>We do not transfer your data to any other country without ensuring adequate protection.</p>
        </div>
    </section>

    <section class="legal-section" id="automated">
        <div class="legal-container">
            <span class="legal-badge">Automation</span>
            <h2>13. Automated Decision‑Making</h2>
            <p>The career assessment tool uses your responses to calculate a pathway score and recommend a learning track. This is automated processing, but it is not fully automated decision‑making in the legal sense: the recommendation is advisory and does not produce a legal or similarly significant effect. You are always free to choose a different pathway or speak to our team.</p>
            <p>We do not use automated profiling to make decisions that significantly affect you without human review.</p>
        </div>
    </section>

    <section class="legal-section" id="changes">
        <div class="legal-container">
            <span class="legal-badge">Updates</span>
            <h2>14. Changes to This Policy</h2>
            <p>We review this policy at least annually. For significant changes, we will notify registered users by email at least 14 days before the change takes effect. The version number and effective date at the top of this page will be updated accordingly.</p>
        </div>
    </section>

    <section class="legal-section" id="contact">
        <div class="legal-container">
            <span class="legal-badge">Contact</span>
            <h2>15. Contact Us</h2>
            <p>If you have any questions, concerns, or requests relating to this privacy policy or your personal data, please contact us:</p>
            <ul>
                <li>Organisation: Aethryna Digital Skills Co‑op CIC (trading as SkillsCo‑op)</li>
                <li>Email: <strong>hello@skillscoop.org</strong></li>
                <li>Website: <a href="https://skillscoop.org" target="_blank">skillscoop.org</a></li>
                <li>Post: Unit A 82 James Carter Road, Mildenhall, United Kingdom IP28 7DE</li>
                <li>Response time: We aim to respond to all data‑related enquiries within 10 working days and Subject Access Requests within one calendar month.</li>
            </ul>
            <p>If you are not satisfied with our response, you have the right to escalate to the Information Commissioner’s Office (ICO) at <a href="https://ico.org.uk" target="_blank">ico.org.uk</a> or call 0303 123 1113.</p>
        </div>
    </section>

    <div class="legal-cta">
        <h2>Questions about your data?</h2>
        <p>Contact us at <strong>hello@skillscoop.org</strong> and we will respond within 10 working days.</p>
        <a href="mailto:hello@skillscoop.org">Contact Us</a>
    </div>
@endsection