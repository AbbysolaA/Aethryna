{{-- Styles for the sessions index and the per-panel pages. Extracted so
     /sessions and /sessions/{slug} render from one copy rather than two
     that drift apart. --}}
<link href="https://fonts.bunny.net/css?family=ibm-plex-mono:500,600&display=swap" rel="stylesheet">
<style>
    :root {
        --ath-teal: #038b89;
        --ath-gold: #ee9d1d;
        --ath-deep: #055860;
        --ath-navy: #0a2530;
        --ath-navy-2: #0e2f3c;
        --ath-light: #F8FBFB;
        --ath-text: #404952;
        --ath-muted: #57616a;
        --font-mono: 'IBM Plex Mono', 'Courier New', monospace;
    }

    .ath-container { max-width: 1250px; margin: 0 auto; padding: 0 5%; }

    /* ── Hero ─────────────────────────────────────────────────────────── */
    .ss-hero {
        padding: 180px 0 100px;
        background: radial-gradient(1200px 500px at 80% 20%, rgba(238, 157, 29, 0.16), transparent 60%),
                    radial-gradient(900px 500px at 20% 80%, rgba(3, 139, 137, 0.16), transparent 60%),
                    linear-gradient(180deg, var(--ath-navy) 0%, var(--ath-navy-2) 100%);
        color: #fff;
    }
    .ss-hero-inner { max-width: 900px; }
    .ss-eyebrow {
        display: inline-block;
        font-family: var(--font-mono);
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--ath-gold);
        margin-bottom: 24px;
        position: relative;
        padding-left: 14px;
    }
    .ss-eyebrow::before {
        content: '';
        position: absolute;
        left: 0; top: 3px; bottom: 3px;
        width: 4px;
        background: var(--ath-gold);
        border-radius: 2px;
    }
    .ss-title {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(2.5rem, 6vw, 4.4rem);
        font-weight: 800;
        line-height: 1.05;
        margin-bottom: 24px;
    }
    .ss-gradient {
        background: linear-gradient(135deg, var(--ath-gold), #fff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .ss-lede {
        font-size: 1.2rem;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.82);
        max-width: 680px;
        margin-bottom: 40px;
    }
    .ss-next-strip {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 18px;
        padding: 14px 22px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 100px;
        margin-bottom: 32px;
    }
    .ss-next-label {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--ath-gold);
        padding-right: 14px;
        border-right: 1px solid rgba(255, 255, 255, 0.15);
    }
    .ss-next-topic { font-weight: 700; font-size: 1rem; color: #fff; }
    .ss-next-date, .ss-next-time {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.75);
    }
    .ss-next-date i, .ss-next-time i { color: var(--ath-teal); margin-right: 6px; }
    .ss-hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }

    /* ── Buttons ──────────────────────────────────────────────────────── */
    .ss-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        border-radius: 100px;
        font-weight: 700;
        font-size: 0.98rem;
        text-decoration: none;
        transition: all 0.25s ease;
        cursor: pointer;
        border: none;
        font-family: inherit;
    }
    .ss-btn-primary { background: var(--ath-gold); color: #fff; }
    .ss-btn-primary:hover { background: #fff; color: var(--ath-navy); transform: translateY(-2px); }
    .ss-btn-ghost {
        background: rgba(255, 255, 255, 0.06);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
    .ss-btn-ghost:hover { background: rgba(255, 255, 255, 0.12); }
    .ss-btn-full { width: 100%; justify-content: center; }
    .ss-btn-sm { padding: 10px 18px; font-size: 0.88rem; }

    /* ── Featured Panel ───────────────────────────────────────────────── */
    .ss-featured {
        padding: 100px 0;
        background: linear-gradient(180deg, var(--ath-navy-2) 0%, var(--ath-navy) 100%);
        color: #fff;
    }
    .ss-panel-header {
        text-align: left;
        max-width: 900px;
        margin: 0 auto 60px;
    }
    .ss-panel-num {
        display: inline-block;
        font-family: var(--font-mono);
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--ath-teal);
        margin-bottom: 12px;
    }
    .ss-panel-topic {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(2rem, 5vw, 3.2rem);
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 22px;
        color: #fff;
        border-left: 4px solid var(--ath-gold);
        padding-left: 20px;
    }
    /* Descriptions are stored as plain text and can run to more than one
       paragraph. pre-line keeps the blank lines the copy was written with,
       without having to render the field as unescaped HTML. */
    .ss-panel-desc, .ss-past-desc {
        white-space: pre-line;
    }
    .ss-panel-desc {
        font-size: 1.1rem;
        line-height: 1.75;
        color: rgba(255, 255, 255, 0.82);
        margin-bottom: 36px;
    }
    .ss-panel-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        padding: 20px 24px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
    }
    .ss-meta-cell {
        display: flex;
        flex-direction: column;
        gap: 3px;
        padding-right: 24px;
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        min-width: 90px;
    }
    .ss-meta-cell:last-child { border-right: none; }
    .ss-meta-lbl {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.6);
    }
    .ss-meta-val { font-weight: 700; font-size: 0.95rem; color: #fff; }

    /* ── Speaker cards ────────────────────────────────────────────────── */
    .ss-speakers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 28px;
    }
    .ss-speaker-card {
        background: var(--ath-navy);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s;
    }
    .ss-speaker-card:hover {
        transform: translateY(-6px);
        border-color: rgba(238, 157, 29, 0.35);
    }
    .ss-speaker-portrait {
        aspect-ratio: 4 / 5;
        position: relative;
        background-color: #12303c;
        background-size: cover;
        background-position: center top;
    }
    .ss-portrait-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 40%, rgba(10, 37, 48, 0.65) 75%, var(--ath-navy) 100%);
    }
    .ss-portrait-fallback {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: rgba(255, 255, 255, 0.15);
    }
    .ss-speaker-body {
        padding: 28px 30px 32px;
        margin-top: -10px;
        position: relative;
    }
    .ss-speaker-tag {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--ath-teal);
        margin-bottom: 12px;
    }
    .ss-tag-bar { display: inline-block; width: 4px; height: 14px; background: var(--ath-gold); border-radius: 2px; }
    .ss-speaker-name {
        font-family: 'Outfit', sans-serif;
        font-size: 1.55rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 6px;
        display: inline-block;
        border-bottom: 3px solid var(--ath-gold);
        padding-bottom: 2px;
    }
    .ss-speaker-role {
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.98rem;
        line-height: 1.5;
        margin-bottom: 14px;
    }
    .ss-role-sep { color: rgba(255, 255, 255, 0.35); margin: 0 4px; }
    .ss-speaker-topic {
        font-size: 0.9rem;
        color: var(--ath-gold);
        font-weight: 600;
        margin-bottom: 14px;
    }
    .ss-speaker-topic i { margin-right: 6px; }
    .ss-speaker-bio {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.92rem;
        line-height: 1.7;
        margin-bottom: 16px;
    }
    .ss-speaker-linkedin {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--ath-teal);
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
    }
    .ss-speaker-linkedin:hover { color: var(--ath-gold); }

    /* ── What to Expect ───────────────────────────────────────────────── */
    .ss-expect { padding: 100px 0; background: #fff; }
    .ss-expect-header { text-align: left; max-width: 700px; margin-bottom: 50px; }
    .ath-sub {
        display: block;
        font-family: var(--font-mono);
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--ath-gold);
        margin-bottom: 10px;
    }
    .ss-expect-header h2 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(2rem, 4vw, 2.6rem);
        color: var(--ath-deep);
        font-weight: 800;
    }
    .ss-expect-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
    }
    .ss-expect-card {
        background: var(--ath-light);
        border: 1px solid rgba(3, 139, 137, 0.08);
        border-radius: 20px;
        padding: 32px;
        transition: transform 0.3s, border-color 0.3s;
    }
    .ss-expect-card:hover {
        transform: translateY(-4px);
        border-color: var(--ath-teal);
    }
    .ss-expect-icon {
        width: 52px; height: 52px;
        background: rgba(3, 139, 137, 0.1);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ath-teal);
        font-size: 1.3rem;
        margin-bottom: 18px;
    }
    .ss-expect-card h3 {
        font-family: 'Outfit', sans-serif;
        color: var(--ath-deep);
        font-weight: 800;
        font-size: 1.15rem;
        margin-bottom: 8px;
    }
    .ss-expect-card p { color: var(--ath-muted); line-height: 1.7; font-size: 0.95rem; }

    /* ── Registration ─────────────────────────────────────────────────── */
    .ss-register { padding: 100px 0; background: var(--ath-light); }
    .ss-register-grid {
        display: grid;
        grid-template-columns: 0.85fr 1.15fr;
        gap: 60px;
        align-items: start;
    }
    /* The form runs taller than the intro beside it. Letting the intro travel
       with it on desktop keeps the two sides reading as a pair instead of
       leaving a column of empty space. */
    @media (min-width: 993px) {
        .ss-register-info { position: sticky; top: 110px; }
    }
    .ss-register-info h2 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(1.8rem, 4vw, 2.4rem);
        color: var(--ath-deep);
        font-weight: 800;
        margin-bottom: 16px;
    }
    .ss-register-info > p {
        font-size: 1.05rem;
        color: var(--ath-muted);
        line-height: 1.7;
        margin-bottom: 20px;
    }
    .ss-register-alt { font-size: 0.95rem; color: var(--ath-muted); }
    .ss-register-alt a { color: var(--ath-teal); font-weight: 700; text-decoration: none; }
    .ss-register-alt a:hover { color: var(--ath-gold); }

    /* The left column was three paragraphs of prose against a tall form, which
       left a column of dead space beside it. It now carries the same badge and
       white-card language as the archive below, which balances the two sides
       and keeps one visual vocabulary across the page. */
    .ss-register-pills { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 28px; }
    .ss-register-pill {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--ath-teal);
        background: rgba(3, 139, 137, 0.08);
        border: 1px solid rgba(3, 139, 137, 0.15);
        border-radius: 100px;
        padding: 7px 16px;
    }

    .ss-register-cards { display: grid; gap: 14px; }
    .ss-register-card {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        background: #fff;
        border: 1px solid rgba(3, 139, 137, 0.1);
        border-radius: 18px;
        padding: 20px 22px;
    }
    .ss-register-card-icon {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(3, 139, 137, 0.1);
        color: var(--ath-teal);
    }
    .ss-register-card strong {
        display: block;
        color: var(--ath-deep);
        font-size: 0.98rem;
        margin-bottom: 4px;
    }
    .ss-register-card p { font-size: 0.92rem; color: var(--ath-muted); line-height: 1.6; }
    .ss-register-card a { color: var(--ath-teal); font-weight: 700; text-decoration: none; }
    .ss-register-card a:hover { color: var(--ath-gold); }
    .ss-register-card-speak .ss-register-card-icon { background: rgba(238, 157, 29, 0.14); color: var(--ath-gold); }

    /* Speaking is optional and separate from attending, so the checkbox and its
       topic field sit in their own tinted block rather than loose in the field
       stack. The label needs to out-specify `.ss-form-group label`, which sets
       display:block and would otherwise stack the box above its own text. */
    .ss-speaker-block {
        background: rgba(3, 139, 137, 0.05);
        border: 1px solid rgba(3, 139, 137, 0.12);
        border-radius: 16px;
        padding: 18px 20px;
        margin-bottom: 24px;
    }
    .ss-form label.ss-check {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 0;
        font-size: 0.92rem;
        font-weight: 600;
        line-height: 1.45;
        cursor: pointer;
    }
    /* Aligned to the first line, not the centre of the block: the label wraps
       to two lines in the narrower column and a centred box floats between
       them. */
    .ss-form label.ss-check input {
        flex: 0 0 auto;
        width: 18px;
        height: 18px;
        margin: 2px 0 0;
        accent-color: var(--ath-teal);
    }
    .ss-speaker-topic { margin-top: 16px; margin-bottom: 0; }

    .ss-form {
        background: #fff;
        padding: 40px;
        border-radius: 24px;
        border: 1px solid rgba(3, 139, 137, 0.1);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.06);
    }
    .ss-form-group { margin-bottom: 20px; }
    .ss-form-group label {
        display: block;
        font-weight: 700;
        color: var(--ath-deep);
        margin-bottom: 8px;
        font-size: 0.92rem;
    }
    .ss-form-opt { font-weight: 500; color: var(--ath-muted); font-size: 0.85rem; }

    #speaker-topic-group textarea {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid rgba(3, 139, 137, 0.2);
        border-radius: 12px;
        font-family: inherit;
        font-size: 1rem;
        resize: vertical;
    }
    #speaker-topic-group textarea:focus { outline: none; border-color: var(--ath-teal); }
    .ss-form-group input,
    .ss-form-group select {
        width: 100%;
        padding: 13px 16px;
        border: 1.5px solid rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        font-size: 0.98rem;
        font-family: inherit;
        color: var(--ath-text);
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
        box-sizing: border-box;
    }
    .ss-form-group input:focus,
    .ss-form-group select:focus {
        border-color: var(--ath-teal);
        box-shadow: 0 0 0 4px rgba(3, 139, 137, 0.1);
    }
    .ss-form-error { color: #b91c1c; font-size: 0.85rem; margin-top: 6px; display: block; }
    .ss-success {
        background: #fff;
        padding: 50px 40px;
        border-radius: 24px;
        border: 2px solid var(--ath-teal);
        text-align: center;
    }
    .ss-success i { font-size: 3rem; color: var(--ath-teal); margin-bottom: 16px; }
    .ss-success h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.6rem;
        color: var(--ath-deep);
        font-weight: 800;
        margin-bottom: 10px;
    }
    .ss-success p { color: var(--ath-muted); line-height: 1.6; margin-bottom: 20px; }

    /* ── Call for Speakers ────────────────────────────────────────────── */
    .ss-cfs { padding: 100px 0; background: #fff; }
    .ss-cfs-card {
        background: var(--ath-navy);
        border-radius: 32px;
        padding: 60px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .ss-cfs-card::before {
        content: '';
        position: absolute;
        top: -20%; right: -10%;
        width: 60%;
        height: 130%;
        background: radial-gradient(closest-side, rgba(238, 157, 29, 0.18), transparent 70%);
        pointer-events: none;
    }
    .ss-cfs-header { max-width: 700px; margin-bottom: 30px; position: relative; z-index: 1; }
    .ss-cfs-eyebrow {
        display: inline-block;
        font-family: var(--font-mono);
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: var(--ath-gold);
        margin-bottom: 16px;
        padding-left: 14px;
        border-left: 4px solid var(--ath-gold);
    }
    .ss-cfs-header h2 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(1.8rem, 4vw, 2.6rem);
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 16px;
    }
    .ss-cfs-header p {
        font-size: 1.1rem;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.85);
    }
    .ss-cfs-list {
        list-style: none;
        padding: 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px 24px;
        margin: 30px 0;
        position: relative;
        z-index: 1;
    }
    .ss-cfs-list li {
        display: flex;
        align-items: center;
        gap: 14px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.98rem;
    }
    .ss-cfs-list i {
        width: 36px; height: 36px;
        background: rgba(238, 157, 29, 0.15);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ath-gold);
        font-size: 0.9rem;
        flex-shrink: 0;
    }
    .ss-cfs-actions {
        display: flex;
        gap: 20px;
        align-items: center;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    .ss-cfs-contact { color: rgba(255, 255, 255, 0.75); font-size: 0.95rem; }
    .ss-cfs-contact strong { color: var(--ath-gold); font-weight: 700; }

    /* ── Past sessions ────────────────────────────────────────────────── */
    .ss-past { padding: 100px 0; background: var(--ath-light); }
    .ss-past-header { max-width: 700px; margin-bottom: 40px; }
    .ss-past-header h2 {
        font-family: 'Outfit', sans-serif;
        font-size: clamp(2rem, 4vw, 2.4rem);
        color: var(--ath-deep);
        font-weight: 800;
        margin-bottom: 8px;
    }
    .ss-past-header p { color: var(--ath-muted); font-size: 1.05rem; }
    .ss-past-card {
        background: #fff;
        border: 1px solid rgba(3, 139, 137, 0.1);
        border-radius: 24px;
        padding: 36px;
        margin-bottom: 28px;
    }
    .ss-past-head {
        display: flex;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .ss-past-badge {
        display: inline-block;
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        background: rgba(3, 139, 137, 0.1);
        color: var(--ath-teal);
        padding: 5px 12px;
        border-radius: 100px;
        margin-bottom: 10px;
    }
    .ss-past-head h3 {
        font-family: 'Outfit', sans-serif;
        font-size: 1.35rem;
        color: var(--ath-deep);
        font-weight: 800;
        margin-bottom: 8px;
    }
    .ss-past-head p { color: var(--ath-text); line-height: 1.65; }
    .ss-past-videos {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    .ss-video iframe { width: 100%; aspect-ratio: 16 / 9; border-radius: 12px; border: 0; }
    .ss-caption { font-size: 0.87rem; color: var(--ath-muted); margin: 8px 0 0; line-height: 1.5; }
    .ss-past-speakers-lbl {
        font-family: var(--font-mono);
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--ath-muted);
        margin-bottom: 12px;
    }
    .ss-past-chips { display: flex; flex-wrap: wrap; gap: 12px; }
    .ss-past-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--ath-light);
        border: 1px solid rgba(3, 139, 137, 0.15);
        border-radius: 100px;
        padding: 5px 16px 5px 5px;
    }
    .ss-past-chip img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
    .ss-past-chip strong { color: var(--ath-deep); font-size: 0.9rem; display: block; }
    .ss-past-chip span { font-size: 0.78rem; color: var(--ath-muted); }
    .ss-past-photos {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
        margin-top: 24px;
    }
    .ss-past-photo img { width: 100%; height: 180px; object-fit: cover; border-radius: 12px; }

    /* ── Responsive ───────────────────────────────────────────────────── */
    @media (max-width: 992px) {
        .ss-register-grid { grid-template-columns: 1fr; gap: 40px; }
        .ss-cfs-list { grid-template-columns: 1fr; }
        .ss-cfs-card { padding: 40px 30px; }
    }
    @media (max-width: 768px) {
        .ss-hero { padding: 140px 0 80px; }
        .ss-next-strip { padding: 12px 18px; gap: 12px; }
        .ss-next-label { padding-right: 12px; }
        .ss-panel-meta { flex-direction: column; padding: 16px; }
        .ss-meta-cell { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.08); padding-right: 0; padding-bottom: 10px; }
        .ss-meta-cell:last-child { border-bottom: none; padding-bottom: 0; }
        .ss-past-head { flex-direction: column; }
        .ss-past-videos { grid-template-columns: 1fr; }
        .ss-form { padding: 28px 22px; }
    }

    .ss-past-head h3 a { color: inherit; text-decoration: none; }
    .ss-past-head h3 a:hover { color: var(--ath-teal); }
    .ss-panel-permalink {
        display: inline-block;
        margin-bottom: 14px;
        font-size: 0.9rem;
        font-weight: 700;
        color: rgba(255,255,255,0.75);
        text-decoration: none;
    }
    .ss-panel-permalink:hover { color: var(--ath-gold); }
</style>
