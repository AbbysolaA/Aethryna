BUILD IT YOURSELF

Hi {!! $firstName !!},

At our live build sessions, speakers built working tools with AI on screen. You can now build the same things yourself: the exact prompts they used are on our website, free. Enter your name and email on the page and the prompts appear straight away.

@foreach ($sessions as $session)
{!! $session['tagline'] !!}
@if ($session['date'])
{!! $session['date'] !!}
@endif
@if ($session['builders'])
Prompts from {!! $session['builders'] !!}.
@endif
Get the prompts: {!! $session['promptsUrl'] !!}
@if ($session['hasRecording'])
Watch the recording: {!! $session['pageUrl'] !!}
@endif

@endforeach
@if ($moreToCome)
More prompts from the other speakers are on the way, on the same pages.
@endif
Every build used fictional or sample data. If you use your own details, read your AI tool's privacy terms first.

Abisola Areola
Founder and Executive Director, Skills Co-op

--
Skills Co-op
{!! $supportEmail !!}

{!! $footerNote !!}
