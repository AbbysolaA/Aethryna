<?php

/**
 * Who we are, for machines.
 *
 * Feeds the JSON-LD structured data in the site layout and on the about page.
 * Search engines use this to work out that Skills Co-op is an organisation,
 * that Aethryna Digital Skills Co-op CIC is its legal name, and that the
 * people named on the about page are connected to it.
 *
 * The `same_as` arrays are the important part and the part most often left
 * empty. They are how a search engine decides that the "Abisola Areola" on
 * this site is the same person as a LinkedIn profile, rather than a different
 * person with the same name. Structured data alone asserts a relationship;
 * same_as is what corroborates it against sources already trusted.
 */
return [

    'legal_name' => 'Aethryna Digital Skills Co-op CIC',
    'name'       => 'Skills Co-op',
    'url'        => 'https://skillscoop.org',
    'logo'       => 'https://skillscoop.org/email/skills-coop-mark.png',
    'email'      => 'hello@skillscoop.org',

    // There are unrelated organisations trading on variations of this name.
    // The legal name is unique where the trading name is not, so it carries
    // the disambiguation and is declared everywhere the brand name appears.
    'alternate_name' => 'Aethryna Digital Skills Co-op',

    // The organisation predates the CIC registration.
    'founding_date' => '2025',

    'description' => 'Skills Co-op, operated by Aethryna Digital Skills Co-op CIC, is a UK Certified Social Enterprise running a funded 25-week AI-native digital skills and employability programme for people the traditional pipeline was never designed for: young people not in education, employment or training, adults with lived experience of the justice system, migrants and refugees, and women returning to work after time away.',

    // ISO code rather than prose: this is a field machines read, and it is one
    // of the signals separating this organisation from same-name sites
    // operating in other countries.
    'area_served' => 'GB',
    'locality'    => 'Liverpool',
    'country'     => 'GB',

    // Profiles that already represent the organisation elsewhere.
    'same_as' => [
        'https://www.linkedin.com/company/theskillscoop/',
        'https://www.instagram.com/aethrynafoundation',
        'https://www.facebook.com/share/1VF3yxZ4dR/',
    ],

    /**
     * People named publicly on the about page.
     *
     * `founder` is flagged because a founder relationship is a stronger and
     * more durable signal than employment: it survives a change of job title
     * and search engines weight it accordingly.
     *
     * Add each person's own LinkedIn or profile URLs to their same_as. Without
     * at least one, a search engine has this site's word and nothing else, and
     * a name query has no reason to connect the two.
     */
    'people' => [
        [
            'name'           => 'Abisola Areola',
            'alternate_name' => 'Abby Areola',
            'job_title'      => 'Founder & Executive Director',
            'founder'        => true,
            // Personal site. Declared as the canonical URL for the person, so
            // the two domains describe one entity rather than two.
            'url'            => 'https://abisolaareola.com',
            'image'          => 'https://skillscoop.org/images/team/abisola.jpg',
            'description'    => 'Data analytics and project management professional who designed the Skills Co-op model: the curriculum, pathways, and delivery architecture that widens access to digital skills and meaningful progression for underserved communities.',
            'knows_about'    => [
                'AI transformation',
                'Digital skills',
                'Workforce development',
                'Data analytics',
                'Social enterprise',
            ],
            'same_as'        => [
                'https://www.linkedin.com/in/abisolaareola',
                'https://abisolaareola.com',
                'https://buildsnotes.substack.com',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | The cohort people are applying to
    |--------------------------------------------------------------------------
    |
    | Read by the learner dashboard to tell an applicant where they stand and
    | what happens next. It lives here rather than in the Blade so the answer
    | can change without a deploy, and so the site only ever makes one promise
    | in one place.
    |
    | 'decision_note' is deliberately nullable. Until a real timetable exists,
    | the dashboard says something true and vague rather than inventing a date
    | nobody has committed to — and a learner reading a promise that is then
    | missed is worse than a learner reading no promise at all.
    |
    */
    'cohort' => [
        'name'          => 'Cohort 1',
        'starts'        => 'January 2027',
        'places'        => 30,

        // e.g. 'We review applications every month. You will hear from us by
        // the end of the month after you apply.' Set it once you know.
        'decision_note' => null,

        // Set when applications actually close, e.g. '30 November 2026'.
        'closes'        => null,
    ],

];
