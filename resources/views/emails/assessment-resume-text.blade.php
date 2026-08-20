@if ($isNudge)
PICK UP WHERE YOU LEFT OFF
==========================
@else
YOUR PLACE IS SAVED
===================
@endif

Hi {!! $firstName !!},

@if ($isNudge)
You started the pathway assessment on skillscoop.org and stopped part way. Nothing is lost - your answers are exactly where you left them, and the link below drops you straight back in.
@else
Here is the link back into your pathway assessment. Your answers are saved, so it will open on the next question rather than starting you over.
@endif

@if ($total > 0)
WHERE YOU GOT TO
{{-- Written as an expression, not @if/@endif: Blade only compiles a directive
     at a non-word boundary, so "answered@if(...)" would print verbatim. --}}
{!! $answered !!} of {!! $total !!} questions answered{!! $left > 0 ? ', '.$left.' to go' : '' !!}

@endif
Carry on here:
{!! $resumeUrl !!}

There are no right answers and nothing to revise for. It exists to point you at a starting track, and you can change track later.
@if ($isNudge)

Not for you after all? Ignore this and you will not hear from us again about it. To have us delete what you started:
{!! $unsubscribeUrl !!}
@else

If you do not come back to it, we will send one reminder and nothing after that. To turn that off now:
{!! $unsubscribeUrl !!}
@endif

Abby
Founder, Skills Co-op

--
Skills Co-op
https://skillscoop.org
{!! $supportEmail ?? 'hello@skillscoop.org' !!}
