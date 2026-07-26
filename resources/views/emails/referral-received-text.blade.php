NEW REFERRAL RECEIVED
=====================

Someone has been referred to Skills Co-op.

REFERRED PERSON
{{ $referredName }}
@isset($cohort)
Cohort: {{ $cohort }}
@endisset

@if ($contactConsented && !empty($contact))
Contact (consent given): {{ $contact }}
@else
Contact details withheld, no consent recorded. Reach the referred person via the referrer below.
@endif

REFERRER
{{ $referrerName }} ({{ $referrerEmail }})
Organisation: {{ $organisation ?: 'Not provided' }}
Role: {{ $role ?: 'Not provided' }}

CONTEXT
{{ $context ?: 'None provided' }}

@isset($dashboardUrl)
View this referral: {{ $dashboardUrl }}
@endisset

Submitted {{ $submittedAt }}.

--
Skills Co-op
https://skillscoop.org
{{ $supportEmail ?? 'hello@skillscoop.org' }}
