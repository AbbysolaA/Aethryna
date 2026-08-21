NEW SPEAKER PITCH

{!! $speakerName !!}@if ($affiliation) ({!! $affiliation !!})@endif wants to speak at a session.

CONTACT
{!! $speakerEmail !!}

THE TALK
{!! $talkTitle !!}
@if ($formatLabel)Prefers: {!! strtolower($formatLabel) !!}
@endif
@if ($topics)Tracks: {!! $topics !!}
@endif

{!! $talkSummary !!}

BIO
{!! $bio !!}

Read the full pitch: {!! $adminUrl !!}

--
Skills Co-op
{!! $supportEmail !!}

{!! $footerNote !!}
