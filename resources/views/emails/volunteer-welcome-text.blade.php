Hi {{ $firstName }},

Thank you for joining Skills Co-op as our {{ $role }}. I am really glad to
have you on board.

We deliver free, AI-integrated digital skills and employability pathways to
three groups of people who are usually last in the queue for this kind of
training: young people not in education, employment or training, adults with
lived experience of the justice system, and women returning to work after time
away. Our pilot cohort launches in January 2027, and the next few months are
about getting everything ready for that.
@isset($firstCommitments)

{{ $firstCommitments }}
@endisset
@if (! empty($actions))

BEFORE ANYTHING ELSE
@foreach ($actions as $i => $action)
{{ $i + 1 }}. {{ strip_tags($action) }}
@endforeach

Once those are done I will set up your access to Todoist, Notion, Eventbrite
and the shared folders, and we will book your weekly check-in.
@endif

YOUR ONBOARDING PACK
Everything you need is here. Please take some time to read through it.
@foreach ($documents as $doc)

{{ $doc['label'] }}
{{ $doc['note'] }}
{{ $doc['url'] }}
@endforeach

If anything in the pack is unclear, or you spot something that looks wrong,
tell me. You are coming in with fresh eyes and that is genuinely useful to me
right now.

Looking forward to working with you.

Abby
Founder, Skills Co-op

--
Skills Co-op
skillscoop.org · {{ $supportEmail ?? 'hello@skillscoop.org' }}
