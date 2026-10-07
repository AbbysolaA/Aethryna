{{--
    To a speaker whose pitch is kept for a future session.

    Data expected (see App\Mail\SpeakerFutureSession):
        firstName, talkTitle
--}}
@extends('emails.layout')

@section('content')

    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                Your pitch
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                We want this for a future session
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
                Thank you for pitching <strong>{{ $talkTitle }}</strong>. We read it properly,
                and this is not a no. The session we are casting right now is not the right
                home for it, and the talk deserves the right home rather than a squeeze.
            </p>
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Your pitch stays on file and we will come to you when the session it fits is
                being planned. You do not need to do anything, and you do not need to pitch
                again. If your availability or details change in the meantime, reply to
                <a href="mailto:hello@skillscoop.org" style="color:#055860;">hello@skillscoop.org</a>
                and we will update what we hold.
            </p>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 40px 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                <tr>
                    <td style="padding:18px 22px;">
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:25px; color:#2b333a;">
                            Our sessions run monthly and each one is cast around a theme, so
                            the wait is usually weeks, not a polite forever.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

@endsection
