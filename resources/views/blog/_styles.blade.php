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
    .bl-card h2, .bl-card h3 {
        font-family: 'Fraunces', Georgia, serif;
        font-weight: 600;
        font-size: 1.45rem;
        line-height: 1.3;
        margin: 0 0 10px;
    }
    .bl-card h2 a, .bl-card h3 a { color: #08444A; text-decoration: none; }
    .bl-card h2 a:hover, .bl-card h3 a:hover { color: #038b89; text-decoration: underline; }
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
    /* A divider (--- in Markdown), drawn as Substack draws theirs: a small
       ornament rather than a rule the width of the page. */
    /* overflow visible and a real height: browsers hide overflow on hr, and
       a borderless hr is zero-height, which together swallow the ornament. */
    .bl-prose hr { border: none; text-align: center; margin: 2.2em 0; height: 1.6rem; overflow: visible; }
    .bl-prose hr::after {
        content: '\00B7 \00A0 \00B7 \00A0 \00B7';
        color: #EE9D1D;
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 0.2em;
    }
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

    /* Byline with the author's face, or their initials until a photo named
       after them lands in images/authors. */
    .bl-byline { display: flex; align-items: center; gap: 12px; margin: 0 0 16px; }
    .bl-byline .bl-meta { margin: 0; line-height: 1.45; }
    .bl-byline strong { color: #08444A; }
    .bl-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        flex: none;
    }
    .bl-avatar-initials {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #08444A;
        color: #E8B647;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
    }

    /* The mid-post subscribe form, summoned by [subscribe] on its own line. */
    .bl-subscribe-inline {
        margin: 1.8em 0;
        padding: 18px 20px;
        border: 1px solid rgba(8, 68, 74, 0.16);
        border-radius: 12px;
        background: #F7F2E8;
        text-align: center;
    }
    .bl-subscribe-inline form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
    }
    .bl-subscribe-inline input[type="email"] {
        flex: 1 1 200px;
        max-width: 320px;
        padding: 10px 14px;
        border: 1px solid rgba(8, 68, 74, 0.25);
        border-radius: 9px;
        font: inherit;
        background: #fff;
    }
    .bl-subscribe-inline button {
        padding: 10px 22px;
        border: none;
        border-radius: 9px;
        background: #EE9D1D;
        color: #08444A;
        font: inherit;
        font-weight: 700;
        cursor: pointer;
    }
    .bl-subscribe-inline button:hover { background: #E8B647; }
    .bl-subscribe-inline-note {
        margin: 10px 0 0;
        font-size: 0.82rem;
        color: #7a838b;
    }
    .bl-subscribe-inline .bl-subscribed { color: #055860; margin: 0; }

    /* Share row */
    .bl-share {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin: 0 0 24px;
    }
    .bl-prose + .bl-share { margin: 28px 0 0; }
    .bl-share-label {
        font-family: var(--font-mono, ui-monospace, monospace);
        font-size: 0.68rem;
        letter-spacing: 1.6px;
        text-transform: uppercase;
        color: #7a838b;
        margin-right: 4px;
    }
    .bl-share a, .bl-share button {
        display: inline-block;
        padding: 6px 14px;
        border: 1px solid rgba(8, 68, 74, 0.22);
        border-radius: 100px;
        background: none;
        font: inherit;
        font-size: 0.85rem;
        font-weight: 600;
        color: #08444A;
        text-decoration: none;
        cursor: pointer;
    }
    .bl-share a:hover, .bl-share button:hover {
        background: rgba(3, 139, 137, 0.08);
        border-color: #038b89;
    }

    /* Subscribe card */
    .bl-subscribe {
        background: #08444A;
        color: #F7F2E8;
        border-radius: 14px;
        padding: 30px 32px;
        margin: 28px 0;
        scroll-margin-top: 90px;
    }
    .bl-subscribe h2 {
        font-family: 'Fraunces', Georgia, serif;
        font-weight: 600;
        font-size: 1.4rem;
        margin: 0 0 8px;
    }
    .bl-subscribe p { margin: 0 0 16px; line-height: 1.65; color: rgba(247, 242, 232, 0.85); }
    .bl-subscribe-form { display: flex; flex-wrap: wrap; gap: 10px; }
    .bl-subscribe-form input[type="email"] {
        flex: 1 1 220px;
        padding: 12px 16px;
        border: 1px solid rgba(247, 242, 232, 0.35);
        border-radius: 10px;
        background: rgba(247, 242, 232, 0.08);
        color: #F7F2E8;
        font: inherit;
    }
    .bl-subscribe-form input[type="email"]::placeholder { color: rgba(247, 242, 232, 0.55); }
    .bl-subscribe-form button {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        background: #EE9D1D;
        color: #08444A;
        font: inherit;
        font-weight: 700;
        cursor: pointer;
    }
    .bl-subscribe-form button:hover { background: #E8B647; }
    .bl-subscribed { font-weight: 600; color: #E8B647; }
    .bl-subscribe-error { margin: 10px 0 0; color: #ffd9a8; font-size: 0.9rem; }

    /* Off-screen, matching the honeypot convention on the other forms. */
    .bl-ref { position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden; }
    .bl-sr-only {
        position: absolute;
        width: 1px; height: 1px;
        margin: -1px; padding: 0; border: 0;
        clip: rect(0 0 0 0);
        overflow: hidden;
        white-space: nowrap;
    }

    /* More from the blog */
    .bl-more { margin-top: 40px; }
    .bl-more > h2 {
        font-family: 'Fraunces', Georgia, serif;
        font-weight: 600;
        font-size: 1.5rem;
        color: #08444A;
        margin: 0 0 18px;
    }
</style>
