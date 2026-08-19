{{-- Link back into an unfinished pathway assessment. Sent either because the
     person asked us to save their place, or once as a nudge if they stopped.
     Extends emails.layout and supplies <tr> rows only. --}}
@extends('emails.layout')

@section('content')

    {{-- Title block --}}
    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                {{ $isNudge ? 'Still saved' : 'Your place is saved' }}
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                @if ($isNudge)
                    Pick up where you left off
                @else
                    Finish whenever you are ready
                @endif
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
            <p style="margin:22px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Hi {{ $firstName }},
            </p>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                @if ($isNudge)
                    You started the pathway assessment on skillscoop.org and stopped part way. Nothing is lost — your answers are exactly where you left them, and the link below drops you straight back in.
                @else
                    Here is the link back into your pathway assessment. Your answers are saved, so it will open on the next question rather than starting you over.
                @endif
            </p>
        </td>
    </tr>

    {{-- Progress --}}
    @if ($total > 0)
        <tr>
            <td class="sc-pad" style="padding:24px 32px 0 32px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                    <tr>
                        <td style="padding:20px 22px;">
                            <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                                Where you got to
                            </p>
                            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:20px; line-height:28px; font-weight:700; color:#2b333a;">
                                {{ $answered }} of {{ $total }} questions answered
                            </p>
                            @if ($left > 0)
                                <p style="margin:8px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:22px; color:#2b333a;">
                                    {{ $left }} {{ $left === 1 ? 'question' : 'questions' }} to go — about {{ max(1, (int) ceil($left / 6)) }} {{ max(1, (int) ceil($left / 6)) === 1 ? 'minute' : 'minutes' }}.
                                </p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif

    {{-- Call to action --}}
    <tr>
        <td class="sc-pad" style="padding:28px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="sc-btn">
                <tr>
                    <td align="center" bgcolor="#055860" style="border-radius:6px;">
                        <!--[if mso]>
                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $resumeUrl }}" style="height:46px;v-text-anchor:middle;width:230px;" arcsize="13%" stroke="f" fillcolor="#055860">
                            <w:anchorlock/>
                            <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:15px;font-weight:bold;">Carry on where I stopped</center>
                        </v:roundrect>
                        <![endif]-->
                        <!--[if !mso]><!-- -->
                        <a href="{{ $resumeUrl }}" style="display:inline-block; padding:14px 30px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:6px; background-color:#055860;">
                            Carry on where I stopped
                        </a>
                        <!--<![endif]-->
                    </td>
                </tr>
            </table>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#8a8f86;">
                If the button does not work, paste this into your browser:<br />
                <a href="{{ $resumeUrl }}" class="sc-link" style="color:#055860; text-decoration:underline; word-break:break-all;">{{ $resumeUrl }}</a>
            </p>
        </td>
    </tr>

    {{-- Reassurance --}}
    <tr>
        <td class="sc-pad" style="padding:28px 32px 0 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                There are no right answers and nothing to revise for. It exists to point you at a starting track, and you can change track later.
            </p>
            @if ($isNudge)
                <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                    Not for you after all? Ignore this and you will not hear from us again about it, or
                    <a href="{{ $unsubscribeUrl }}" class="sc-link" style="color:#055860; text-decoration:underline;">tell us to stop</a>
                    and we will delete what you started.
                </p>
            @else
                <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                    If you do not come back to it, we will send one reminder and nothing after that. You can
                    <a href="{{ $unsubscribeUrl }}" class="sc-link" style="color:#055860; text-decoration:underline;">turn that off now</a>
                    if you would rather we did not.
                </p>
            @endif
            <p style="margin:20px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Abby<br />
                <span style="color:#8a8f86;">Founder, Skills Co-op</span>
            </p>
        </td>
    </tr>

@endsection
