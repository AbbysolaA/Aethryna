<?php

/**
 * What we are, for AI agents.
 *
 * Search crawlers get robots.txt and sitemap.xml. AI assistants and answer
 * engines increasingly look for two further files: /llms.txt, a plain-text
 * summary of the site written for a model rather than a browser, and
 * /.well-known/agents.json, a machine-readable card describing what the site
 * is and how to reach a human.
 *
 * Both are generated from this file by routes in routes/web.php, in the same
 * way robots.txt and sitemap.xml are. The page list is driven by
 * config/services.php > indexnow > urls, so a page added there appears in the
 * sitemap, the next IndexNow push, and llms.txt together. Add a description
 * here when you add a URL there; a page with no entry is listed by path alone.
 */
return [

    /**
     * The one-paragraph answer to "what is this site?".
     *
     * Written for a model summarising the organisation to someone who has
     * never heard of it, so it leads with what we do and who for, not with
     * brand language.
     */
    'summary' => 'Skills Co-op, operated by Aethryna Digital Skills Co-op CIC, is a UK Certified Social Enterprise running a free 25-week AI-native digital skills and employability programme. It is built for people the traditional pipeline was never designed for: young people not in education, employment or training, adults with lived experience of the justice system, migrants and refugees, and women returning to work after time away. Based in Liverpool, open across the UK.',

    /**
     * Facts a model is likely to get wrong by inference, stated plainly.
     * Each becomes a bullet under the summary in llms.txt.
     */
    'facts' => [
        'Training is free to the learner. There are no course fees for eligible participants.',
        'Four pathways: Project and Product Delivery, Data and AI Analytics, Product Design and Marketing, and Software Development.',
        'AI tooling is taught inside every pathway rather than as a separate module, using a verification-first method.',
        'Delivery is online and UK-wide, with the organisation based in Liverpool.',
        'Skills Co-op is the trading name; Aethryna Digital Skills Co-op CIC is the registered legal entity.',
    ],

    /**
     * What an agent can usefully do here. Kept honest: these are things the
     * site actually supports, not an aspirational list.
     */
    'capabilities' => [
        'search',
        'contact',
    ],

    /**
     * Descriptions for the URLs in config/services.php > indexnow > urls.
     * Keyed by the same path string so the two lists cannot drift apart.
     */
    'pages' => [
        '/'                => ['title' => 'Home',                'description' => 'What Skills Co-op is, who it is for, and how to apply.'],
        '/about'           => ['title' => 'About',               'description' => 'The organisation, the CIC behind it, and the people running it.'],
        '/pathway'         => ['title' => 'Pathways',            'description' => 'The four free, AI-integrated learning pathways and what each covers.'],
        '/programs'        => ['title' => 'Programmes',          'description' => 'How training is structured: mentorship, real project work, and routes into freelance and employed work.'],
        '/ai-labs'         => ['title' => 'AI Labs',             'description' => 'How AI is taught — a verification-first method, a community practice space, and the AI Labs Fellowship.'],
        '/impact'          => ['title' => 'Impact',              'description' => 'What we measure and why: completion, confidence, and real routes into work.'],
        '/stories'         => ['title' => 'Stories',             'description' => 'Stories from learners, mentors and partners in the community.'],
        '/sessions'        => ['title' => 'Sessions',            'description' => 'A free monthly public panel series on AI, work and inclusion. Open to everyone.'],
        '/partners'        => ['title' => 'Partner with us',     'description' => 'How funders, employers and delivery partners work with Skills Co-op.'],
        '/mentors'         => ['title' => 'Become a mentor',     'description' => 'Mentoring a learner takes a few hours a month. No teaching experience needed.'],
        '/volunteer/apply' => ['title' => 'Volunteer',           'description' => 'Volunteer roles across mentoring, delivery and outreach.'],
        '/refer'           => ['title' => 'Refer someone',       'description' => 'Refer someone who could benefit from free digital skills training. Consent-first, no pressure.'],
        '/privacy'         => ['title' => 'Privacy Policy',      'description' => 'How personal data is collected, used and protected.'],
        '/terms'           => ['title' => 'Terms of Service',    'description' => 'Terms governing use of the site and services.'],
        '/cookies'         => ['title' => 'Cookie Policy',       'description' => 'Cookies set by the site and how to control them.'],
        '/acceptable-use'  => ['title' => 'Acceptable Use',      'description' => 'What is and is not acceptable use of the site and community spaces.'],
    ],

    /**
     * AI crawlers and assistants allowed by name in robots.txt.
     *
     * `User-agent: *` with `Allow: /` already permits these, but readiness
     * scanners and several operators check for the agent by name and treat
     * its absence as ambiguous. Naming them removes the ambiguity, and makes
     * a future decision to exclude one a single-line edit here.
     */
    'crawlers' => [
        // OpenAI
        'GPTBot',
        'OAI-SearchBot',
        'ChatGPT-User',
        // Anthropic
        'ClaudeBot',
        'Claude-User',
        'Claude-SearchBot',
        'anthropic-ai',
        // Google (AI training and Gemini grounding, separate from Googlebot)
        'Google-Extended',
        // Perplexity
        'PerplexityBot',
        'Perplexity-User',
        // Common Crawl — the dataset most open models are trained from
        'CCBot',
        // Others
        'Applebot-Extended',
        'meta-externalagent',
        'meta-externalfetcher',
        'Amazonbot',
        'Bytespider',
        'cohere-ai',
        'DuckAssistBot',
        'YouBot',
    ],

];
