YOU ARE REGISTERED
==================

Hi {!! $firstName !!},

Thanks for signing up for the next Skills Co-op Sessions panel. Save the date, and we will email you closer to the time with the join link.

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

@if ($eventbriteUrl)
Also add to Eventbrite: {!! $eventbriteUrl !!}

@endif
Any questions before then, reply straight to this email or write to {!! $supportEmail ?? 'hello@skillscoop.org' !!}. See you soon.

Abby
Founder, Skills Co-op

--
Skills Co-op
https://skillscoop.org
{!! $supportEmail ?? 'hello@skillscoop.org' !!}
