<?php

/**
 * Volunteer onboarding.
 *
 * The document pack itself is no longer configured here. It is uploaded and
 * ordered under Admin > Onboarding pack (volunteer_documents), because the
 * files have to live somewhere the site can actually serve them and a config
 * array could only ever hold URLs to files hosted elsewhere.
 *
 * Source documents: OneDrive > Projects > aethryna > aethryna content > Legal
 * > Onboarding.zip. Upload them at /admin/volunteer-documents.
 */
return [

    // Days a volunteer has to accept or decline before the offer lapses.
    // Overridable per offer when it is extended.
    'offer_response_days' => 14,

    // Where signed agreements come back to. Separate from the general
    // hello@ contact address in the email footer, because these carry
    // signatures and personal details and belong with whoever handles
    // volunteer records rather than in the general inbox.
    //
    // This must be a real, monitored mailbox. An address that does not
    // exist will drop returns silently.
    'returns_inbox' => env('VOLUNTEER_RETURNS_INBOX', 'hr@skillscoop.org'),

];
