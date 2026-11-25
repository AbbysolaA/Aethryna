# Universal Product Design & Engineering Standards
Version 1.0 · 25 November 2026

## Scope and precedence
Apply this baseline to all new work and proportionately to existing code. Product requirements extend these rules; they must not silently weaken security, accessibility or data integrity. Record exceptions, reasons, risks, owner and review date. Use this file as a root AGENTS.md in each repository, merging rather than overwriting existing project instructions. Do not claim implementation or tests without evidence.

## 1. Design before development
- Establish product purpose, user journeys, information architecture, art direction, wireframes, mobile/desktop high-fidelity screens, and interaction states before major UI implementation. Use an approved design reference or prototype; review against it.
- Select the most suitable palette from the owner's Colour Palette Bank with exact hex values; explain if none fits. Use 60/30/10 or 60/25/10/5 proportion as a guide, subject-specific accents, and reserve reward colours for rewards. Do not default to cream/sage/forest/gold. Maintain distinct identities across products.
- Tokenise colour, typography, spacing, radius, elevation, layout and motion. Reuse components and semantic tokens; no unexplained one-off styling. Use light/dark themes where appropriate and test both.
- Avoid indiscriminate AI-style patterns: arrow on every button, eyebrow heading, gratuitous italics, sparkles, all-card layouts, three identical icon cards, decorative gradients/glows, coloured left borders, pulsing dots, heading pills, generic AI copy, fabricated statistics, gratuitous scroll animation and default Instrument Serif. Use any such convention only with an explicit functional or art-direction reason.

## 2. User psychology and workflow
Apply the 20 design laws as contextual heuristics, not rigid scientific rules:
1. Aesthetic-usability: refine appearance without sacrificing function.
2. Proximity: group related controls and information.
3. Von Restorff: distinguish the primary action.
4. Zeigarnik: preserve and surface unfinished work.
5. Tesler: absorb avoidable complexity in the system.
6. Prägnanz: favour recognisable simple structures.
7. Serial position: put important navigation in memorable positions.
8. Progressive disclosure: reveal advanced options when needed.
9. Common region: use meaningful boundaries.
10. Goal-gradient: show real progress, never fabricated urgency.
11. Similarity: consistent appearance for consistent functions.
12. Recognition over recall: visible choices, recent options and suggestions.
13. Pareto: prioritise frequent and valuable workflows.
14. Occam: simplest adequate solution.
15. Parkinson: reduce unnecessary task friction, not impose arbitrary deadlines.
16. Uniform connectedness: visually connect related elements.
17. Paradox of active user: discoverable UI without compulsory lengthy tours.
18. Cognitive load: reduce choices and mental effort.
19. Default effect: safe, transparent, reversible defaults; no preselected consent.
20. Peak-end: meaningful, clear completion and recovery feedback.

## 3. Visual and interaction craft
- Kerning, tracking, leading and x-height: tune typography for real readability. Use optical centring and avoid awkward widows where practical. Use tabular numbers for comparable numeric data (`font-variant-numeric: tabular-nums`). Target comfortable reading widths (roughly 45–75 characters for long prose).
- Prevent layout shift: reserve image, skeleton and asynchronous-content dimensions. Target CLS <= 0.1 where measurable; do not conceal errors behind loading states.
- Use OKLCH for perceptual colour scales if useful; preserve approved brand colours and verify contrast in rendered UI. Never communicate status by colour alone.
- Make affordances clear. Use accessible searchable comboboxes for long selections; support keyboard, screen reader, focus and escape behaviour. Respect mobile safe-area insets; aim for 44 CSS px touch targets for frequent controls, and meet WCAG 2.2 AA target-size criteria.
- Optimistic updates only for low-risk reversible operations, with pending state, rollback and error recovery. Never label unconfirmed financial, security, payment or destructive operations successful.
- Provide origin-aware, interruptible and shared-element transitions when useful; use stagger sparingly. Prefer stable layouts over decorative movement.

## 4. Motion and UX principles
Use squash/stretch, anticipation, staging, pose-to-pose, follow-through, ease-in/out, arcs, secondary action, snappy timing, proportionate emphasis, coherent depth and overall appeal only when useful. Apply Fitts's law, large hit areas, Hick's law, Miller's law, Doherty threshold (prompt feedback), Postel's law (safe normalisation plus strict server validation), Jakob's law and frequency-of-use (frequent actions animate less). Never delay a task to play an animation. Respect prefers-reduced-motion.
Suggested tokens (adapt with evidence): press 80–120 ms; hover/focus 120–150 ms; popover 150–200 ms; modal/sheet 200–300 ms; navigation 200–250 ms; chart reveal 300–500 ms; rare milestone 500–800 ms. Frequent entry: instant/minimal.

## 5. Accessibility and responsive design
- Target WCAG 2.2 AA, including 4.5:1 normal text and 3:1 large text contrast, applicable non-text contrast, semantic structure, visible focus, keyboard navigation, accessible names, meaningful error messages, and announcements for important status updates.
- Design mobile and desktop individually; test narrow mobile, tablet, desktop, zoom/reflow, keyboard and device safe areas. Never ship a merely stretched mobile view.
- Design applicable states: empty, loading, populated, pending, success, error, offline, stale/partial data and permission denied. Show last sync for stale data. Provide recovery and retry without duplication. All visible production controls work; feature-flag unfinished work; explicitly label demo data.

## 6. Privacy-first assets and third parties
- Self-host licensed fonts and static assets where practical. Use local WOFF2 via @font-face, retain required licence notices, subset only when licence permits, preload only critical fonts and select suitable font-display/fallback metrics. No runtime Google Fonts stylesheet/font requests or unapproved CDN assets.
- Inventory outbound endpoints, scripts, SDKs, embeds and analytics; review necessity, data flows, purpose, retention, processors, consent/lawful basis and transfer requirements where applicable. Default to data minimisation; block nonessential trackers pending valid consent where required. Self-hosting fonts is a privacy measure, not a legal compliance guarantee.
- Verify built output and browser network logs for unapproved external requests. Use a restrictive, tested Content Security Policy where feasible; allow only necessary destinations. Do not disclose sensitive data to external AI without appropriate informed user choice and safeguards.

## 7. Engineering, security and data
- Single source of truth; clear domain boundaries; consistent validation; idempotent imports, retries and writes. Avoid unnecessary dependencies and duplicate implementations.
- Use exact money arithmetic (integer minor units or exact decimal); explicit currency/rounding rules; reconcile balances and audit changes where relevant. Test transfer/deduplication and separation of personal/business/household records where applicable.
- Server-side authorisation and least privilege, secure authentication/session handling, tenant/workspace isolation, input validation, safe uploads, rate limits and secret management. Never commit secrets or sensitive real test fixtures. Avoid sensitive data in logs and caches. Do not cache private API responses in PWA service workers by default.
- Design data export/deletion and retention. Encrypt data in transit and appropriate storage/backups; test restoration. Use versioned migrations and rollback/recovery plans. Audit dependency and asset licences, pin versions/lockfiles and scan vulnerabilities.
- Provide useful performance feedback; measure performance on representative devices. Do not introduce external AI services or paid dependencies unless justified and approved.

## 8. Release gates and evidence
Before marking a feature done:
- [ ] Approved design, functional acceptance criteria and product-specific rules satisfied.
- [ ] Mobile/tablet/desktop, themes, safe areas and relevant lifecycle states tested.
- [ ] All visible controls work; no fake data outside explicit demo mode; no unconfirmed success.
- [ ] Keyboard, screen-reader, contrast, touch targets and reduced-motion checks completed as applicable.
- [ ] Unit and integration tests for domain logic, end-to-end tests for critical journeys, visual regression and automated accessibility checks; manual QA where automation cannot establish quality.
- [ ] Idempotency, authorisation, workspace isolation and data integrity tested as applicable.
- [ ] Font/network/privacy checks, dependency/secret scanning and build/lint/type checks pass.
- [ ] CI prevents release on failed mandatory checks; any exception explicitly documented and reviewed.
- [ ] Documentation, migration notes, decision log and rollback procedures updated as appropriate.

Suggested repository documentation: docs/PRD.md, DESIGN_SYSTEM.md, ANTI_AI_SLOP.md, UX_AND_MOTION.md, ENGINEERING_STANDARDS.md, SECURITY_PRIVACY.md, ACCEPTANCE_CRITERIA.md, DECISIONS.md, ROADMAP.md, and domain-specific rules (e.g. FINANCIAL_RULES.md). Maintain versioned references and keep the root AGENTS.md authoritative for coding agents.

## Source notes
Consolidates the owner's supplied Nov 25 2026 recordings: 20 AI Design Slop Indicators; design-first/visual references and 20 UX & Animation principles; 20 Design Laws to Give Claude; Google Fonts/privacy warning; 20 Design Terms You Should Know. Engineering controls and numerical targets are implementation recommendations added to operationalise the references, not direct claims from the recordings.

---

## Project notes: Aethryna
Project-specific additions to the universal standards above. They extend those standards and must not weaken them.

- **Colour Palette Bank:** `design/palettes/COLOUR-BANK.md` (readable summary), `design/palettes/palettes.json` (source of truth) and `design/palettes/palettes.css` (tokens). Choose palettes from here, as section 1 requires.
- **Visual libraries bank:** `design/libraries/README.md`. Check the licence, bundle size and browser support before using one, and always provide a static or reduced-motion fallback.

### Known exceptions (open)
| Rule | Current state | Risk | Owner | Review by |
|---|---|---|---|---|
| §6 Self-host fonts, no runtime Google Fonts | 12 files in `resources/views` load fonts from fonts.googleapis.com or fonts.bunny.net (Inter, Fraunces, Mulish, Karla, Outfit, IBM Plex Mono, Figtree) | Visitors' IP addresses go to a third party; external dependency; inconsistent typography | Project owner | Before next production release |
