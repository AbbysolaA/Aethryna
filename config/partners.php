<?php

/**
 * Partner logo wall.
 *
 * Only flip a partner's `active` flag to true once written permission to
 * display their logo is on file. The <x-partner-wall /> component renders
 * nothing at all if no partner is active, so this config can be merged and
 * deployed before any permission is confirmed.
 *
 * Logo file: place inside public/partners/. SVG preferred with transparent
 * background; PNG is the fallback.
 */
return [
    [
        'name'   => 'Tattoos and Tears CIC',
        'logo'   => 'tattoos-and-tears.png',
        'url'    => 'https://www.tattoosandtears.co.uk',
        'active' => false,
    ],
    [
        'name'   => 'Lifechurch Wirral',
        'logo'   => 'lifechurch-wirral.png',
        'url'    => null,
        'active' => false,
    ],
];
