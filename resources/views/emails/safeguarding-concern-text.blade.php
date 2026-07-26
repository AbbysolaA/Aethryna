SAFEGUARDING CONCERN RAISED
===========================
{{ $urgencyLabel }} | Reference {{ $reference }}

A concern has been raised and is waiting for your review. It has been recorded as {{ $reference }} regardless of whether this email reached you.

@if ($isUrgent)
** MARKED URGENT BY THE PERSON RAISING IT **
If there is an immediate risk to someone's safety, contact the emergency services on 999 first, then follow the safeguarding policy.

@endif
CONCERN IS ABOUT
{{ $learnerName }}@if ($learnerEmail) ({{ $learnerEmail }})@endif


WHAT WAS REPORTED
{{ $concernBody }}

RAISED BY
{{ $raiserName }}@if ($raiserEmail) ({{ $raiserEmail }})@endif

Their role: {{ $raiserRole }}
Raised at:  {{ $raisedAt }}

You are the decision maker on this one. Reply to this email to reach {{ $raiserName }} directly, and record what you decide against {{ $reference }} so the trail stays complete.

--
Skills Co-op
https://skillscoop.org
This message contains personal data. Do not forward it outside the safeguarding process.
