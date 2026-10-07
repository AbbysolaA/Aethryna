{{--
    A hand-written reply to a speaker pitch, sent from the admin pitch page.

    Data expected (see App\Mail\SpeakerPitchReply):
        subject, talkTitle, paragraphs
--}}
@extends('emails.layout')

@section('content')

    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                Your pitch: {{ $talkTitle }}
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                {{ $subject }}
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 40px 32px;">
            @foreach ($paragraphs as $paragraph)
                <p style="margin:0 0 16px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">{!! nl2br(e($paragraph)) !!}</p>
            @endforeach
            <p style="margin:8px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:14px; line-height:23px; color:#6a737b;">
                You can reply to this email directly; it reaches
                <a href="mailto:hello@skillscoop.org" style="color:#055860;">hello@skillscoop.org</a>.
            </p>
        </td>
    </tr>

@endsection
