{{--
    Shared between the careers listing and a single vacancy.

    Uses the site's own tokens rather than the Discovery Session palette: that
    one is scoped to the event because its printed material uses it, and a job
    page has no reason to look like a different organisation.
--}}
<style>
    .cr { --cr-teal: #055860; --cr-teal-bright: #038b89; --cr-gold: #E8B647;
          --cr-ink: #2D353C; --cr-slate: #59626A; --cr-line: #e2e6e6; }

    .cr-hero {
        background: linear-gradient(135deg, #04353c 0%, var(--cr-teal) 60%, #067076 100%);
        color: #fff;
        padding: clamp(40px, 5.5vw, 76px) 0 clamp(44px, 6vw, 84px);
    }
    .cr-eyebrow {
        font-size: 12px; letter-spacing: .26em; text-transform: uppercase;
        font-weight: 800; color: var(--cr-gold); margin: 0;
    }
    .cr-hero h1 {
        font-size: clamp(2rem, 4.6vw, 3.2rem); font-weight: 800; line-height: 1.12;
        margin: 14px 0 0; color: #fff; text-wrap: balance;
    }
    .cr-hero p.cr-lede {
        font-size: clamp(1rem, 1.5vw, 1.15rem); line-height: 1.6;
        color: #b9d6d4; margin: 16px 0 0; max-width: 60ch;
    }

    .cr-body { padding: clamp(40px, 5vw, 72px) 0; }
    .cr-wrap { max-width: 900px; margin: 0 auto; padding: 0 24px; }

    /* Listing */
    .cr-list { list-style: none; margin: 0; padding: 0; display: grid; gap: 18px; }
    .cr-card {
        border: 1px solid var(--cr-line); border-left: 4px solid var(--cr-gold);
        border-radius: 12px; padding: 24px 26px; background: #fff;
    }
    .cr-card h2 { font-size: 1.3rem; font-weight: 700; color: var(--cr-teal); margin: 0 0 6px; }
    .cr-card p { color: var(--cr-slate); line-height: 1.65; margin: 0 0 14px; }

    /* The facts someone scans before reading a word of the description. */
    .cr-facts { display: flex; flex-wrap: wrap; gap: 8px 10px; margin: 0 0 16px; padding: 0; list-style: none; }
    .cr-fact {
        font-size: 13px; font-weight: 700; color: var(--cr-teal);
        background: #eef6f6; border-radius: 100px; padding: 5px 13px;
    }
    .cr-hero .cr-facts { margin-top: 22px; }
    .cr-hero .cr-fact { background: rgba(255,255,255,.14); color: #fff; }

    .cr-empty { border: 1px dashed var(--cr-line); border-radius: 12px; padding: 32px; text-align: center; color: var(--cr-slate); }
    .cr-empty p { margin: 0 0 8px; }

    /* Vacancy body */
    .cr-section { margin: 0 0 34px; }
    .cr-section h2 { font-size: 1.25rem; font-weight: 700; color: var(--cr-teal); margin: 0 0 12px; }
    .cr-section ul { margin: 0; padding-left: 22px; color: var(--cr-ink); line-height: 1.75; }
    .cr-section li { margin-bottom: 7px; }
    .cr-prose { color: var(--cr-ink); line-height: 1.75; font-size: 1.02rem; }

    .cr-apply {
        border: 1px solid var(--cr-line); border-top: 4px solid var(--cr-gold);
        border-radius: 12px; padding: 26px 28px; background: #fbfaf7; margin: 36px 0 0;
    }
    .cr-apply h2 { font-size: 1.2rem; font-weight: 700; color: var(--cr-teal); margin: 0 0 10px; }
    .cr-apply p { color: var(--cr-ink); line-height: 1.7; margin: 0 0 16px; }

    .cr-closed {
        background: #fdf3f3; border: 1px solid #f0d5d5; border-radius: 10px;
        padding: 16px 20px; color: #7a2b2b; line-height: 1.6; margin: 0 0 26px;
    }

    .cr-back { display: inline-block; margin: 26px 0 0; color: var(--cr-teal-bright); font-weight: 600; }

    @media (max-width: 640px) {
        .cr-card, .cr-apply { padding: 20px; }
    }
</style>
