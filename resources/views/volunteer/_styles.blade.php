{{-- Shared styles for the volunteer area. Included inside @push('styles') by
     claim, offer-unavailable, index and show, so the vocabulary stays in one
     place. Mirrors the tokens and shapes used on the referral page. --}}
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
.ath-container { max-width: 1100px; margin: 0 auto; padding: 0 5%; }

/* ── Shared bits ─────────────────────────────────────────────────────────── */
.vl-eyebrow { display: inline-block; font-family: var(--font-mono); font-size: 0.82rem; font-weight: 600; letter-spacing: 3px; text-transform: uppercase; color: var(--ath-gold); margin-bottom: 22px; padding-left: 14px; border-left: 4px solid var(--ath-gold); }
.vl-title { font-family: 'Outfit', sans-serif; font-size: clamp(2rem, 4.5vw, 3.2rem); font-weight: 800; line-height: 1.08; margin-bottom: 20px; }
.vl-gradient { background: linear-gradient(135deg, var(--ath-gold), #fff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.vl-lede { font-size: 1.12rem; line-height: 1.75; opacity: 0.92; max-width: 620px; }
.vl-note { font-size: 0.88rem; color: var(--ath-muted); line-height: 1.7; margin-top: 24px; }
.vl-note a { color: var(--ath-teal); font-weight: 700; }

.vl-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    padding: 13px 28px; border: 2px solid transparent; border-radius: 100px;
    font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.98rem;
    cursor: pointer; text-decoration: none; transition: background 0.2s, color 0.2s, transform 0.2s;
}
.vl-btn-primary { background: var(--ath-gold); color: #fff; }
.vl-btn-primary:hover { background: var(--ath-teal); transform: translateY(-2px); }
.vl-btn-outline { background: transparent; color: var(--ath-deep); border-color: var(--ath-deep); }
.vl-btn-outline:hover { background: var(--ath-deep); color: #fff; transform: translateY(-2px); }
.vl-btn-ghost { background: transparent; color: var(--ath-muted); border-color: rgba(0,0,0,0.15); }
.vl-btn-ghost:hover { color: #b91c1c; border-color: #b91c1c; }
.vl-btn-block { width: 100%; margin-top: 18px; }

.vl-error { color: #b91c1c; font-size: 0.85rem; margin-top: 6px; }
.vl-opt { font-weight: 500; color: var(--ath-muted); font-size: 0.85rem; }

.vl-flash { padding: 14px 20px; border-radius: 12px; font-size: 0.95rem; margin-bottom: 24px; }
.vl-flash-ok { background: rgba(3,139,137,0.09); border-left: 4px solid var(--ath-teal); color: var(--ath-deep); }
.vl-flash-err { background: rgba(185,28,28,0.07); border-left: 4px solid #b91c1c; color: #b91c1c; }

/* ── Claim / unavailable ─────────────────────────────────────────────────── */
.vl-claim { padding: 150px 0 90px; background: var(--ath-deep); color: #fff; position: relative; overflow: hidden; min-height: 60vh; }
.vl-claim::after { content: ''; position: absolute; top: -20%; right: -10%; width: 60%; height: 130%; background: radial-gradient(closest-side, rgba(238,157,29,0.14), transparent 70%); pointer-events: none; }
.vl-claim-inner { max-width: 780px; position: relative; z-index: 1; }
.vl-claim-narrow { max-width: 620px; }
.vl-claim .vl-note { color: rgba(255,255,255,0.75); }
.vl-claim .vl-note a, .vl-claim .vl-note strong { color: #fff; }
/* The outline button is teal-on-white by default, which all but vanishes on
   the deep teal hero. Flip it to white wherever it sits on that ground. */
.vl-claim .vl-btn-outline { border-color: #fff; color: #fff; }
.vl-claim .vl-btn-outline:hover { background: #fff; color: var(--ath-deep); }

.vl-offer-card { background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.14); border-radius: 20px; padding: 30px 32px; margin: 34px 0; }
.vl-label { font-family: var(--font-mono); font-size: 0.7rem; font-weight: 600; letter-spacing: 1.6px; text-transform: uppercase; color: var(--ath-gold); margin: 0 0 6px; }
.vl-role { font-family: 'Outfit', sans-serif; font-size: 1.5rem; font-weight: 800; margin: 0 0 8px; }
.vl-role-summary { font-size: 0.98rem; line-height: 1.7; opacity: 0.85; margin: 0; }
.vl-offer-dates { display: flex; flex-wrap: wrap; gap: 34px; margin-top: 26px; padding-top: 22px; border-top: 1px solid rgba(255,255,255,0.14); }
.vl-value { font-size: 1.02rem; font-weight: 600; margin: 0; }

.vl-auth-choice { display: grid; grid-template-columns: 1fr auto 1fr; gap: 28px; align-items: center; margin-top: 34px; }
.vl-auth-single { display: block; margin-top: 30px; }
.vl-auth-option { background: #fff; border-radius: 20px; padding: 28px 30px; color: var(--ath-text); }
.vl-auth-option h2 { font-family: 'Outfit', sans-serif; font-size: 1.1rem; font-weight: 800; color: var(--ath-deep); margin: 0 0 8px; }
.vl-auth-option p { font-size: 0.92rem; line-height: 1.65; color: var(--ath-muted); margin: 0 0 18px; }
.vl-auth-divider { position: relative; color: rgba(255,255,255,0.55); font-family: var(--font-mono); font-size: 0.78rem; text-transform: uppercase; letter-spacing: 2px; }

/* ── Engagement + index ──────────────────────────────────────────────────── */
.vl-engagement { padding: 140px 0 90px; background: var(--ath-light); min-height: 70vh; }
.vl-back { display: inline-block; font-size: 0.9rem; font-weight: 700; color: var(--ath-teal); text-decoration: none; margin-bottom: 22px; }
.vl-back::before { content: '\2039'; margin-right: 7px; }
.vl-back:hover { color: var(--ath-gold); }
.vl-engagement-head { margin-bottom: 28px; }
.vl-engagement-head .vl-eyebrow { margin-bottom: 16px; }
.vl-engagement-title { font-family: 'Outfit', sans-serif; font-size: clamp(1.9rem, 4vw, 2.8rem); font-weight: 800; color: var(--ath-navy); line-height: 1.1; margin: 0 0 12px; }
.vl-engagement-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; font-size: 0.94rem; color: var(--ath-muted); }
.vl-meta-sep { opacity: 0.5; }

.vl-badge { display: inline-block; padding: 5px 14px; border-radius: 100px; font-size: 0.76rem; font-weight: 700; letter-spacing: 0.4px; text-transform: uppercase; }
.vl-badge-open { background: rgba(238,157,29,0.16); color: #8a5a06; }
.vl-badge-active { background: rgba(3,139,137,0.14); color: var(--ath-deep); }
.vl-badge-done { background: rgba(3,139,137,0.14); color: var(--ath-deep); }
.vl-badge-muted { background: rgba(0,0,0,0.07); color: var(--ath-muted); }

.vl-engagement-grid { display: grid; grid-template-columns: 1fr 340px; gap: 26px; align-items: start; }
.vl-panel { background: #fff; border: 1px solid rgba(3,139,137,0.1); border-radius: 20px; padding: 30px 32px; box-shadow: 0 12px 40px rgba(0,0,0,0.04); }
.vl-panel + .vl-panel { margin-top: 22px; }
.vl-panel-alert { border-left: 4px solid var(--ath-gold); }
.vl-panel-title { font-family: 'Outfit', sans-serif; font-size: 1.08rem; font-weight: 800; color: var(--ath-deep); margin: 0 0 20px; }
.vl-side-note { font-size: 0.9rem; color: var(--ath-muted); line-height: 1.65; margin: 0; }
.vl-side-note a { color: var(--ath-teal); font-weight: 700; }

/* Timeline */
.vl-timeline { list-style: none; margin: 0; padding: 0; }
.vl-step { position: relative; display: flex; gap: 18px; padding-bottom: 26px; }
.vl-step:last-child { padding-bottom: 0; }
/* Connector runs between markers, stopping short of the last one. */
.vl-step:not(:last-child)::before { content: ''; position: absolute; left: 17px; top: 36px; bottom: 2px; width: 2px; background: rgba(3,139,137,0.18); }
.vl-step-marker { flex: 0 0 36px; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.92rem; position: relative; z-index: 1; }
.vl-step-done .vl-step-marker { background: var(--ath-teal); color: #fff; }
.vl-step-current .vl-step-marker { background: #fff; color: var(--ath-deep); border: 2px solid var(--ath-gold); box-shadow: 0 0 0 5px rgba(238,157,29,0.16); }
.vl-step-upcoming .vl-step-marker { background: rgba(0,0,0,0.05); color: var(--ath-muted); }
.vl-step-body { padding-top: 4px; }
.vl-step-label { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.02rem; color: var(--ath-navy); margin: 0 0 4px; }
.vl-step-upcoming .vl-step-label { color: var(--ath-muted); }
.vl-step-detail { font-size: 0.93rem; line-height: 1.65; color: var(--ath-muted); margin: 0; }
.vl-decision { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 16px; }

/* Hours */
.vl-hours-total { font-family: 'Outfit', sans-serif; font-size: 1.9rem; font-weight: 800; color: var(--ath-deep); margin: 0 0 16px; line-height: 1; }
.vl-hours-total span { display: block; font-size: 0.82rem; font-weight: 600; color: var(--ath-muted); letter-spacing: 0.3px; margin-top: 6px; }
.vl-hours-list { list-style: none; margin: 0 0 8px; padding: 0; }
.vl-hours-list li { display: flex; flex-wrap: wrap; align-items: baseline; gap: 8px; padding: 10px 0; border-top: 1px solid rgba(0,0,0,0.06); font-size: 0.9rem; }
.vl-hours-date { color: var(--ath-muted); }
.vl-hours-qty { font-weight: 700; color: var(--ath-deep); margin-left: auto; font-variant-numeric: tabular-nums; }
.vl-hours-note { flex-basis: 100%; color: var(--ath-muted); font-size: 0.85rem; }

.vl-hours-form { margin-top: 18px; border-top: 1px solid rgba(0,0,0,0.08); padding-top: 16px; }
.vl-hours-form summary { font-weight: 700; color: var(--ath-teal); cursor: pointer; font-size: 0.95rem; list-style: none; }
.vl-hours-form summary::-webkit-details-marker { display: none; }
.vl-hours-form summary::before { content: '+'; margin-right: 8px; font-weight: 800; }
.vl-hours-form[open] summary::before { content: '\2212'; }
.vl-hours-form form { margin-top: 18px; }
.vl-field { margin-bottom: 14px; }
.vl-field-row { display: grid; grid-template-columns: 1fr 100px; gap: 12px; }
.vl-field label { display: block; font-weight: 700; color: var(--ath-deep); margin-bottom: 7px; font-size: 0.9rem; }
.vl-field input {
    width: 100%; padding: 11px 14px; border: 1.5px solid rgba(0,0,0,0.1); border-radius: 10px;
    font-size: 0.95rem; font-family: inherit; color: var(--ath-text); background: #fff;
    transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box; outline: none;
}
.vl-field input:focus { border-color: var(--ath-teal); box-shadow: 0 0 0 4px rgba(3,139,137,0.1); }
.vl-check { display: flex; align-items: flex-start; gap: 11px; font-size: 0.87rem; color: var(--ath-text); line-height: 1.6; margin-top: 14px; cursor: pointer; }
.vl-check input { margin-top: 3px; flex-shrink: 0; accent-color: var(--ath-teal); }

.vl-outstanding { list-style: none; margin: 0 0 14px; padding: 0; }
.vl-outstanding li { padding: 8px 0 8px 22px; position: relative; font-size: 0.93rem; color: var(--ath-text); border-top: 1px solid rgba(0,0,0,0.06); }
.vl-outstanding li:first-child { border-top: none; }
.vl-outstanding li::before { content: ''; position: absolute; left: 4px; top: 15px; width: 7px; height: 7px; border-radius: 50%; background: var(--ath-gold); }

/* Index list */
.vl-engagement-list { list-style: none; margin: 0; padding: 0; display: grid; gap: 14px; }
.vl-engagement-item a { display: flex; flex-wrap: wrap; align-items: center; gap: 18px; background: #fff; border: 1px solid rgba(3,139,137,0.1); border-radius: 18px; padding: 24px 28px; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s; }
.vl-engagement-item a:hover { transform: translateY(-2px); box-shadow: 0 16px 40px rgba(0,0,0,0.07); }
.vl-item-main { flex: 1 1 260px; }
.vl-item-title { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.08rem; color: var(--ath-navy); margin: 0 0 5px; }
.vl-item-summary { font-size: 0.9rem; color: var(--ath-muted); line-height: 1.6; margin: 0; }
.vl-item-side { display: flex; flex-direction: column; align-items: flex-end; gap: 8px; }
.vl-item-hours { font-size: 0.83rem; color: var(--ath-muted); font-variant-numeric: tabular-nums; }
.vl-empty { text-align: center; }
.vl-empty p { margin: 0 0 8px; color: var(--ath-text); }

@media (max-width: 900px) {
    .vl-engagement-grid { grid-template-columns: 1fr; }
    .vl-auth-choice { grid-template-columns: 1fr; gap: 16px; }
    .vl-auth-divider { text-align: center; }
}
@media (max-width: 640px) {
    .vl-claim { padding: 120px 0 70px; }
    .vl-engagement { padding: 115px 0 70px; }
    .vl-panel, .vl-auth-option { padding: 24px 22px; }
    .vl-offer-card { padding: 24px 22px; }
    .vl-offer-dates { gap: 22px; }
}
</style>
