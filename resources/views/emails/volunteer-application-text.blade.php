NEW VOLUNTEER APPLICATION

Applicant: {!! $applicantName !!}
Role: {!! $roleTitle !!}
Email: {!! $applicantEmail !!}
@if ($phone)
Phone: {!! $phone !!}
@endif

WHY THIS ROLE
{!! $about !!}

AVAILABILITY
{!! $availability !!}

RELEVANT EXPERIENCE
{!! $experience ?: 'None given' !!}

Open the roster:
{!! $rosterUrl !!}

Applied {!! $appliedAt !!}. Reply to this email to reach them directly.

--
Skills Co-op
skillscoop.org · {!! $supportEmail ?? 'hello@skillscoop.org' !!}
