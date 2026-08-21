{{--
    Internal notification: someone pitched a talk.

    Data expected (see App\Mail\SpeakerApplicationReceived):
        speakerName, speakerEmail, affiliation, talkTitle, talkSummary, bio, adminUrl
--}}
@extends('emails.layout')

@section('content')

    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                Sessions
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                New speaker pitch
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                <tr>
                    <td style="padding:20px 22px;">
                        <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                            Speaker
                        </p>
                        <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:20px; line-height:28px; font-weight:700; color:#2b333a;">
                            {{ $speakerName }}
                        </p>
                        @if ($affiliation)
                            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#8a8f86;">
                                {{ $affiliation }}
                            </p>
                        @endif
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px;">
                            <a href="mailto:{{ $speakerEmail }}" class="sc-link" style="color:#055860; text-decoration:underline;">{{ $speakerEmail }}</a>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 8px 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td style="padding:0 0 18px 0;">
                        <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">The talk</p>
                        <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:18px; line-height:26px; font-weight:700; color:#2b333a;">{{ $talkTitle }}</p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a; white-space:pre-wrap;">{{ $talkSummary }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 0 8px 0;">
                        <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Bio</p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a; white-space:pre-wrap;">{{ $bio }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:10px 32px 40px 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="background-color:#ee9d1d; border-radius:100px;">
                        <a href="{{ $adminUrl }}" style="display:inline-block; padding:13px 30px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; font-weight:700; color:#08444A; text-decoration:none;">
                            Read the full pitch
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

@endsection
