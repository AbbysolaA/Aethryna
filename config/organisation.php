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

    'description' => 'Skills Co-op is a funded 25-week digital skills and employability programme for people the traditional pipeline was never designed for: young people not in education, employment or training, adults with lived experience of the justice system, and women returning to work after time away.',

    // Where the organisation operates. Helps disambiguate from similarly
    // named organisations elsewhere.
    'area_served' => 'United Kingdom',
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
            'image'          => 'https://skillscoop.org/images/team/abisola.jpg',
            'description'    => 'Data analytics and project management professional who designed the Skills Co-op model: the curriculum, pathways, and delivery architecture that widens access to digital skills and meaningful progression for underserved communities.',
            'same_as'        => [
                'https://www.linkedin.com/in/abisolaareola',
            ],
        ],
    ],

];
