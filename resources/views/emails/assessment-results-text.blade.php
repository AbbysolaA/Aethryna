YOUR PATHWAY MATCH
==================

Hi {!! $firstName !!},

Thanks for taking the assessment. Here is what your answers pointed to. This is a starting point for a conversation, not a verdict. If it does not feel right, tell us and we will talk it through.

@if ($primary && $primary->pathway)
CLOSEST MATCH
{!! $primary->pathway->name !!}
@if ($primary->recommendation_text)

{!! $primary->recommendation_text !!}
@endif
@endif

@if ($secondary && $secondary->pathway)
ALSO WORTH A LOOK
{!! $secondary->pathway->name !!}
@if ($secondary->recommendation_text)
{!! $secondary->recommendation_text !!}
@endif
@endif

WHAT HAPPENS NEXT
- Read how the 25 weeks are structured, and what you earn at each stage.
- Come to a free panel session and meet people already working in the field.
- Applications for the January 2027 founding cohort are open. Thirty places.

View your full results: {!! $resultsUrl !!}
See the 25-week pathway: {!! $pathwayUrl !!}
Book a panel session: {!! $sessionsUrl !!}

Not sure the match fits? Reply to this email and tell us what you were expecting. We would rather get you on the right track than the tidy one.

Abby
Founder, Skills Co-op

--
Skills Co-op
https://skillscoop.org
{!! $supportEmail ?? 'hello@skillscoop.org' !!}
