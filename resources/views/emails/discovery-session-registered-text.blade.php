@if ($waitlisted)YOU ARE ON THE WAITING LIST@else YOUR PLACE IS BOOKED@endif

Hi {{ $firstName }},

@if ($waitlisted)The room is full, so you are on the waiting list. Places come up more often than you would think, and we will email you the moment one does. The details are below so you have them either way.
@else You have a place at the Skills Co-op Community Discovery Session. It is free, there is nothing to bring, and there is nothing to sign up to on the day.
@endif

WHEN
{{ $dayAndDate }}
{{ $startTime }} to {{ $endTime }}

WHERE
{{ $venueName }}
{{ $venueAddress }}
Map: {{ $mapUrl }}
@if ($accessibility)

GETTING IN
{{ $accessibility }} If you need anything else to be able to come, reply to this email and we will sort it.
@endif
@if (count($itinerary))

WHAT HAPPENS ON THE DAY
@foreach ($itinerary as $item)
{{ $item['time'] }}  {{ $item['what'] }}
@endforeach
@endif

Come as you are. You do not need experience, qualifications, or a plan.

If something changes and you cannot make it, just reply and let us know. It frees a place for someone on the waiting list.

Full event details: {{ $eventUrl }}

--
Skills Co-op
{{ $supportEmail }}

{{ $footerNote }}
