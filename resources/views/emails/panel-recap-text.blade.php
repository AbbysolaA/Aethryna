WATCH IT BACK

Hi {!! $firstName !!},

"{!! $panelTitle !!}" happened{!! $panelDate ? ' on '.$panelDate : '' !!}, and it did what it said: real builds with AI on screen, prompts, wrong turns and judgement calls included, then an honest Q&A. If you were in the room, thank you for coming. If you missed it, the whole thing is up now.

@if ($recordingUrl)
WATCH THE RECORDING: {!! $recordingUrl !!}

@endif
@if ($speakers->isNotEmpty())
WHO BUILT
@foreach ($speakers as $speaker)
- {!! $speaker->name !!}{!! $speaker->roleLine() ? ' ('.$speaker->roleLine().')' : '' !!}{!! $speaker->pivot?->topic ? ': '.$speaker->pivot->topic : '' !!}

@endforeach
@endif
@if ($youtubeUrl)
The full demo videos are going up on YouTube, each build as its own video. Subscribe and you will get an alert the moment they land: {!! $youtubeUrl !!}

@endif
@if ($nextTagline)
And Panel 4 is not finished: {!! $nextTagline !!}{!! $nextDate ? ', '.$nextDate.' UK' : '' !!}, free and online as always. See the lineup and register: {!! $nextUrl !!}

@endif
The recording also lives at {!! $panelUrl !!}, speakers and all, if you want to send it to someone.

--
Skills Co-op
{!! $supportEmail !!}

{!! $footerNote !!}
