{!! $registration->waitlisted ? 'WAITING LIST' : 'NEW REGISTRATION' !!} · DISCOVERY SESSION
@if (filled($registration->notes))

THEY TOLD US
{!! $registration->notes !!}
@endif

Name      {!! $registration->name !!}
Email     {!! $registration->email !!}
Phone     {!! $registration->phone ?: 'Not given' !!}
Group     {!! $registration->audienceLabel() ?: 'Not answered' !!}
Consented {!! $registration->consented_at?->timezone('Europe/London')->format('j M Y, g.ia') ?: '-' !!}

@if ($capacity)
{!! $confirmed !!} of {!! $capacity !!} places taken{!! $spacesLeft !== null ? ', '.$spacesLeft.' left' : '' !!}.
@if ($waitlistCount){!! $waitlistCount !!} {!! $waitlistCount === 1 ? 'person' : 'people' !!} on the waiting list.
@endif
@else
{!! $confirmed !!} registered.
@endif

Reply to this email to go straight back to {!! $registration->firstName() !!}.
