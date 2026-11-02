{{--
    To a panel registrant who ticked the "open to speaking" box, inviting
    them to turn the tick into a pitch.

    Data expected (see App\Mail\SpeakerPitchInvite):
        firstName, topic (nullable), pitchUrl
--}}
@extends('emails.layout')

@section('content')

    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                Speaking at Skills Co-op
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                You offered to speak. We would love that
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
            <p style="margin:0 0 16px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                When you registered for one of our panel sessions you ticked the box
                saying you would be open to speaking at a future one. Thank you. Offers
                like yours are how most of our panels get cast.
            </p>
            @if ($topic)
                <p style="margin:0 0 16px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                    You mentioned: <em>&ldquo;{{ $topic }}&rdquo;</em>
                </p>
            @endif
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                The next step is a short pitch, so we have more than a tick to plan
                with: who you are, a working title and what you would cover. It takes
                about five minutes, and it is how we match talks to panels.
            </p>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="sc-btn">
                <tr>
                    <td align="center" bgcolor="#ee9d1d" style="border-radius:6px;">
                        <a href="{{ $pitchUrl }}" style="display:inline-block; padding:16px 34px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; font-weight:700; color:#1d2b2a; text-decoration:none; border-radius:6px; background-color:#ee9d1d;">
                            Pitch your talk
                        </a>
                    </td>
                </tr>
            </table>
            <p style="margin:12px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:13px; line-height:20px; color:#5a6671;">
                Or copy this link: <a href="{{ $pitchUrl }}" style="color:#055860;">{{ $pitchUrl }}</a>
            </p>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 40px 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                <tr>
                    <td style="padding:18px 22px;">
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:25px; color:#2b333a;">
                            You do not need to have spoken anywhere before. Plenty of our
                            best panellists were first-timers, and we help with prep
                            before the event. If you would rather talk it through first,
                            just reply to this email.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

@endsection
