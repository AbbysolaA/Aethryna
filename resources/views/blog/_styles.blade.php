<style>
    /* Blog. bl- prefix. Follows the careers pages: deep teal hero, cream body,
       cards that are mostly typography. */
    .bl { background: #F7F2E8; }
    .bl-wrap { max-width: 760px; margin: 0 auto; padding: 0 20px; }

    .bl-hero {
        background: #08444A;
        color: #F7F2E8;
        padding: 72px 0 56px;
    }
    .bl-eyebrow {
        font-family: var(--font-mono, ui-monospace, monospace);
        font-size: 0.7rem;
        letter-spacing: 2.2px;
        text-transform: uppercase;
        color: #E8B647;
        margin: 0 0 14px;
    }
    .bl-hero h1 {
        font-family: 'Fraunces', Georgia, serif;
        font-weight: 600;
        font-size: clamp(2rem, 5vw, 2.9rem);
        line-height: 1.15;
        margin: 0 0 16px;
    }
    .bl-lede {
        font-size: 1.05rem;
        line-height: 1.7;
        color: rgba(247, 242, 232, 0.85);
        max-width: 56ch;
        margin: 0;
    }

    .bl-body { padding: 56px 0 72px; }

    /* Index cards */
    .bl-list { list-style: none; margin: 0; padding: 0; display: grid; gap: 22px; }
    .bl-card {
        background: #fff;
        border: 1px solid rgba(8, 68, 74, 0.1);
        border-radius: 14px;
        padding: 28px 30px;
    }
    .bl-card h2 {
        font-family: 'Fraunces', Georgia, serif;
        font-weight: 600;
        font-size: 1.45rem;
        line-height: 1.3;
        margin: 0 0 10px;
    }
    .bl-card h2 a { color: #08444A; text-decoration: none; }
    .bl-card h2 a:hover { color: #038b89; text-decoration: underline; }
    .bl-card p { color: #444d54; line-height: 1.7; margin: 0 0 6px; }
    .bl-meta {
        font-size: 0.82rem;
        color: #7a838b;
        margin: 0;
    }
    .bl-empty {
        background: #fff;
        border: 1px solid rgba(8, 68, 74, 0.1);
        border-radius: 14px;
        padding: 32px;
        color: #444d54;
        line-height: 1.7;
    }
    .bl-pagination { margin-top: 30px; }

    /* Article */
    .bl-article {
        background: #fff;
        border: 1px solid rgba(8, 68, 74, 0.1);
        border-radius: 14px;
        padding: clamp(26px, 5vw, 48px);
    }
    .bl-prose { color: #2b333a; font-size: 1.03rem; line-height: 1.8; }
    .bl-prose h2, .bl-prose h3 {
        font-family: 'Fraunces', Georgia, serif;
        font-weight: 600;
        color: #08444A;
        line-height: 1.3;
        margin: 1.8em 0 0.6em;
    }
    .bl-prose h2 { font-size: 1.5rem; }
    .bl-prose h3 { font-size: 1.2rem; }
    .bl-prose p { margin: 0 0 1.1em; }
    .bl-prose a { color: #038b89; }
    /* The site-wide reset strips list markers, so they are restored here:
       a bulleted answer with no bullets reads as a run of orphan lines. */
    .bl-prose ul, .bl-prose ol { margin: 0 0 1.1em; padding-left: 1.3em; }
    .bl-prose ul { list-style: disc; }
    .bl-prose ol { list-style: decimal; }
    .bl-prose li { display: list-item; }
    .bl-prose li { margin-bottom: 0.4em; }
    .bl-prose blockquote {
        margin: 1.4em 0;
        padding: 4px 0 4px 20px;
        border-left: 4px solid #E8B647;
        color: #59626A;
    }
    .bl-prose img { max-width: 100%; height: auto; border-radius: 10px; }
    .bl-prose code {
        background: #f1ece1;
        border-radius: 5px;
        padding: 1px 6px;
        font-size: 0.92em;
    }
    .bl-prose pre {
        background: #08444A;
        color: #F7F2E8;
        border-radius: 10px;
        padding: 18px 20px;
        overflow-x: auto;
    }
    .bl-prose pre code { background: none; padding: 0; }
    .bl-prose hr { border: none; border-top: 1px solid rgba(8, 68, 74, 0.14); margin: 2em 0; }
    .bl-video {
        aspect-ratio: 16 / 9;
        margin: 1.4em 0;
        border-radius: 10px;
        overflow: hidden;
        background: #08444A;
    }
    .bl-video iframe { width: 100%; height: 100%; border: 0; display: block; }

    .bl-back {
        display: inline-block;
        margin-bottom: 22px;
        color: #038b89;
        text-decoration: none;
        font-weight: 600;
    }
    .bl-back:hover { text-decoration: underline; }

    .bl-draft {
        background: #fdf1d7;
        border: 1px solid #E8B647;
        border-radius: 10px;
        padding: 12px 18px;
        margin-bottom: 22px;
        color: #6b5210;
        font-size: 0.92rem;
    }

    .bl-foot {
        margin-top: 34px;
        padding-top: 22px;
        border-top: 1px solid rgba(8, 68, 74, 0.12);
        color: #59626A;
        line-height: 1.7;
    }
    .bl-foot p { margin: 0 0 8px; }
</style>
