NEW REGISTRATION

{!! $name !!} ({!! $interestLabel !!}) registered for {!! $panelTagline ?: 'the next panel' !!}@if ($panelDate), {!! $panelDate !!}@endif.
@if ($panelCount)That takes it to {!! $panelCount !!} registration@if ($panelCount !== 1)s@endif.
@endif

Email: {!! $email !!}
@if ($heardAbout)Heard about us: {!! $heardAbout !!}
@endif
@if ($campaign)Campaign: {!! $campaign !!}
@endif
@if ($wantsToSpeak)Offered to speak on a future panel.@if ($speakerTopic) Topic: {!! $speakerTopic !!}@endif
@endif

Replying to this email writes to {!! $name !!} directly.
The full list: {!! $adminUrl !!}

--
Skills Co-op
{!! $supportEmail !!}

{!! $footerNote !!}
