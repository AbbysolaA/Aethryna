{{--
    Watch-it-back recap after a panel, sent once to the registrations bank.

    Data expected (see App\Mail\PanelRecap):
        firstName, panelTitle, panelDate, recordingUrl, panelUrl, speakers,
        youtubeUrl, nextTagline, nextDate, nextUrl
--}}
@extends('emails.layout')

@section('content')

    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                The Skills Co-op Sessions
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                Watch it back
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <p style="margin:0 0 16px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Hi {{ $firstName }},
            </p>
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                <strong>{{ $panelTitle }}</strong> happened{{ $panelDate ? ' on '.$panelDate : '' }},
                and it did what it said: real builds with AI on screen, prompts, wrong turns
                and judgement calls included, then an honest Q&amp;A. If you were in the room,
                thank you for coming. If you missed it, the whole thing is up now.
            </p>
        </td>
    </tr>

    @if ($recordingUrl)
        <tr>
            <td class="sc-pad" style="padding:24px 32px 0 32px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="sc-btn">
                    <tr>
                        <td align="center" bgcolor="#ee9d1d" style="border-radius:6px;">
                            <a href="{{ $recordingUrl }}" style="display:inline-block; padding:16px 34px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; font-weight:700; color:#1d2b2a; text-decoration:none; border-radius:6px; background-color:#ee9d1d;">
                                Watch the recording
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif

    @if ($speakers->isNotEmpty())
        <tr>
            <td class="sc-pad" style="padding:24px 32px 0 32px;">
                <p style="margin:0 0 8px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; font-weight:700; color:#055860;">
                    Who built
                </p>
                @foreach ($speakers as $speaker)
                    <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:23px; color:#2b333a;">
                        <strong>{{ $speaker->name }}</strong>@if ($speaker->roleLine()) &middot; {{ $speaker->roleLine() }}@endif
                        @if ($speaker->pivot?->topic)<br><span style="color:#6a737b;">{{ $speaker->pivot->topic }}</span>@endif
                    </p>
                @endforeach
            </td>
        </tr>
    @endif

    @if ($youtubeUrl)
        <tr>
            <td class="sc-pad" style="padding:24px 32px 0 32px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                    <tr>
                        <td style="padding:18px 22px;">
                            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:25px; color:#2b333a;">
                                <strong>The full demo videos are going up on YouTube</strong>, each build
                                as its own video.
                                <a href="{{ $youtubeUrl }}" style="color:#055860; font-weight:700;">Subscribe to the channel</a>
                                and you will get an alert the moment they land.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif

    @if ($nextTagline)
        <tr>
            <td class="sc-pad" style="padding:24px 32px 0 32px;">
                <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:25px; color:#2b333a;">
                    And Panel 4 is not finished: <strong>{{ $nextTagline }}</strong>{{ $nextDate ? ', '.$nextDate.' UK' : '' }},
                    free and online as always.
                    <a href="{{ $nextUrl }}" style="color:#055860; font-weight:700;">See the lineup and register &rarr;</a>
                </p>
            </td>
        </tr>
    @endif

    <tr>
        <td class="sc-pad" style="padding:24px 32px 40px 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:14px; line-height:23px; color:#6a737b;">
                The recording also lives at <a href="{{ $panelUrl }}" style="color:#055860;">{{ $panelUrl }}</a>,
                speakers and all, if you want to send it to someone.
            </p>
        </td>
    </tr>

@endsection
