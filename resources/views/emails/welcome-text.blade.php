WELCOME TO SKILLS CO-OP
=======================

Hi {!! $firstName !!},

Thanks for signing up. Skills Co-op is a funded 25-week digital skills programme for people the traditional pipeline was never built for. Our founding cohort starts in January 2027 with thirty places.

THREE THINGS TO DO FIRST

1. Take the pathway assessment
Fifteen questions, about two minutes. It matches you to one of our four pilot tracks based on how you actually like to work.
{!! $assessmentUrl !!}

2. Read how the 25 weeks work
Foundations, then specialised training, then a project period where teams build and launch something real. Three certificates along the way.
{!! $pathwayUrl !!}

3. Come to a panel session
@if ($panelTitle && $panelDate)
Our next one is {!! $panelTitle !!} on {!! $panelDate !!}. Free, online, and open to everyone.
@else
We run a free online panel every month with practitioners, researchers, and community leaders. Open to everyone.
@endif
{!! $sessionsUrl !!}

If anything is unclear, or you want to talk through whether this is right for you, reply to this email. A real person reads it.

Abby
Founder, Skills Co-op

--
Skills Co-op
https://skillscoop.org
{!! $supportEmail ?? 'hello@skillscoop.org' !!}
