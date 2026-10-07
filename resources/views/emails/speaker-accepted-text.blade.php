WE WANT THIS TALK

Hi {!! $firstName !!},

Thank you for pitching "{!! $talkTitle !!}". It is accepted, and you are on our speakers list.

@if ($panelTagline)
It has a home already. Here is where you are speaking:

{!! $panelTagline !!}
{!! $panelDate !!}@if ($panelFormat) - {!! $panelFormat !!}@endif


We will follow up with the joining details and the running order closer to the day. If the date does not work for you, reply to {!! $supportEmail !!} as soon as you can and we will find the talk another home.
@else
We are matching the talk to the right session now. We will come back to you with the session, the date and the practical details as soon as it is planned, and you do not need to do anything in the meantime. If your availability changes, reply to {!! $supportEmail !!} and we will work around it.
@endif

--
Skills Co-op
{!! $supportEmail !!}

{!! $footerNote !!}
