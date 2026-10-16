{!! $stage === 'day' ? 'SEE YOU '.strtoupper($when) : 'ONE WEEK TO GO' !!}

Hi {!! $firstName !!},

@if ($stage === 'day')
Your seat at "{!! $panelTitle !!}" is booked for {!! $when !!}. It is free, online and starts on time.
@else
You are registered for "{!! $panelTitle !!}", one week from now. It is free, online and open to everyone, so feel welcome to bring someone along.
@endif

{!! $panelTitle !!}
{!! $panelDate !!}@if ($panelTime), {!! $panelTime !!}@endif - {!! $panelFormat !!}

@if ($speakers->isNotEmpty())
On the panel:
@foreach ($speakers as $speaker)
- {!! $speaker->name !!}@if ($speaker->roleLine()) ({!! $speaker->roleLine() !!})@endif

@endforeach
@endif

@if ($joinUrl)
JOIN THE SESSION: {!! $joinUrl !!}

@endif
Add it to your calendar: {!! $calendarUrl !!}
(The calendar file is attached to this email too.)

Everything about the panel, speakers included: {!! $panelUrl !!}
That link is the one to send a friend who should be in the room.

Can no longer make it? No need to do anything, the recording lands on the same page afterwards.

--
Skills Co-op
{!! $supportEmail !!}

{!! $footerNote !!}
