YOU ARE REGISTERED
==================

Hi {!! $firstName !!},

Thanks for signing up for the next Skills Co-op Sessions panel.@if ($joinUrl) Your joining link is below; keep this email and use it on the night.@else Save the date, and we will email you closer to the time with the join link.@endif

PANEL
{!! $panelTitle !!}

Date:     {!! $panelDate ?: 'To be confirmed' !!}
Time:     {!! $panelTime !!}
Format:   {!! $panelFormat !!}
Duration: {!! $panelDuration !!}

@if ($speakers && $speakers->isNotEmpty())
ON THE PANEL
@foreach ($speakers as $speaker)
- {!! $speaker->name !!}@if ($speaker->title), {!! $speaker->title !!}@endif

@endforeach
@endif

WHAT TO EXPECT
- Real practitioners, not slide decks
- Honest Q&A, hard questions welcome
- A community that keeps going after the session

@if ($joinUrl)
JOIN THE SESSION: {!! $joinUrl !!}

@endif
@if ($calendarUrl)
Add it to your calendar: {!! $calendarUrl !!}
(The calendar file is attached to this email too.)

The panel is free and open to everyone, so bring someone: send them {!! $panelUrl !!} and they can register in a minute.

@endif
@if ($eventbriteUrl)
Also add to Eventbrite: {!! $eventbriteUrl !!}

@endif
@if (! empty($followLinks))
Between sessions we share speaker announcements, community wins and what is coming next here, so come and say hello:
@foreach ($followLinks as $name => $url)
- {!! $name !!}: {!! $url !!}
@endforeach

@endif
Any questions before then, reply straight to this email or write to {!! $supportEmail ?? 'hello@skillscoop.org' !!}. See you soon.

Abby
Founder, Skills Co-op

--
Skills Co-op
https://skillscoop.org
{!! $supportEmail ?? 'hello@skillscoop.org' !!}
