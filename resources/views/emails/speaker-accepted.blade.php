{{--
    To a speaker whose pitch is accepted.

    Data expected (see App\Mail\SpeakerAccepted):
        firstName, talkTitle, panelTagline (nullable), panelDate, panelFormat
--}}
@extends('emails.layout')

@section('content')

    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                Your pitch
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                We want this talk
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
                Thank you for pitching <strong>{{ $talkTitle }}</strong>. It is accepted,
                and you are on our speakers list.
            </p>
            @if ($panelTagline)
                <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                    It has a home already. Here is where you are speaking:
                </p>
            @else
                <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                    We are matching the talk to the right session now. We will come back to
                    you with the session, the date and the practical details as soon as it
                    is planned, and you do not need to do anything in the meantime. If your
                    availability changes, reply to
                    <a href="mailto:hello@skillscoop.org" style="color:#055860;">hello@skillscoop.org</a>
                    and we will work around it.
                </p>
            @endif
        </td>
    </tr>

    @if ($panelTagline)
        <tr>
            <td class="sc-pad" style="padding:24px 32px 0 32px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                    <tr>
                        <td style="padding:18px 22px;">
                            <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:25px; font-weight:700; color:#055860;">
                                {{ $panelTagline }}
                            </p>
                            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:25px; color:#2b333a;">
                                {{ $panelDate }}@if ($panelFormat) &middot; {{ $panelFormat }}@endif
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="sc-pad" style="padding:24px 32px 40px 32px;">
                <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                    We will follow up with the joining details and the running order closer
                    to the day. If the date does not work for you, reply to
                    <a href="mailto:hello@skillscoop.org" style="color:#055860;">hello@skillscoop.org</a>
                    as soon as you can and we will find the talk another home.
                </p>
            </td>
        </tr>
    @else
        <tr>
            <td class="sc-pad" style="padding:0 32px 40px 32px;">&nbsp;</td>
        </tr>
    @endif

@endsection
