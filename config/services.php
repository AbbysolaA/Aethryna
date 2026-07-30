<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'emailoctopus' => [
        'key' => env('EMAILOCTOPUS_API_KEY'),
        'list_id' => env('EMAILOCTOPUS_LIST_ID'),
        'base_url' => env('EMAILOCTOPUS_BASE_URL', 'https://emailoctopus.com/api/1.6'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'linkedin-openid' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect' => env('LINKEDIN_REDIRECT_URI'),
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'indexnow' => [
        // Set INDEXNOW_KEY in .env to a random hex string (16-128 chars).
        // Generate one with: php -r "echo bin2hex(random_bytes(16));"
        // Leave unset to disable IndexNow entirely (artisan command becomes a no-op).
        'key'      => env('INDEXNOW_KEY'),
        'endpoint' => env('INDEXNOW_ENDPOINT', 'https://api.indexnow.org/indexnow'),
        'host'     => env('INDEXNOW_HOST', 'skillscoop.org'),

        // Public URLs to submit when the artisan command runs without args.
        // Add new pages here when you create them.
        'urls' => [
            '/',
            '/about',
            '/pathway',
            '/programs',
            '/impact',
            '/stories',
            '/sessions',
            '/partners',
            '/mentors',
            '/volunteer/apply',
            '/refer',
            '/ai-labs',
            '/privacy',
            '/terms',
            '/cookies',
            '/acceptable-use',
        ],
    ],
];
