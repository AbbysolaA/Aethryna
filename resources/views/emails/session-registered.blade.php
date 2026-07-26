{{-- Content partial for the panel-session registration confirmation.
     Extends emails.layout and supplies <tr> rows only. --}}
@extends('emails.layout')

@section('content')

    {{-- Title block --}}
    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                You are registered
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                @if ($shortDate)
                    See you on {{ $shortDate }}
                @else
                    Thanks for registering
                @endif
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
            <p style="margin:22px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Hi {{ $firstName }},
            </p>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Thanks for signing up for the next Skills Co-op Sessions panel. Save the date, and we will email you closer to the time with the join link.
            </p>
        </td>
    </tr>

    {{-- Panel details --}}
    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                <tr>
                    <td style="padding:20px 22px;">
                        <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                            Panel
                        </p>
                        <p style="margin:0 0 18px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:20px; line-height:28px; font-weight:700; color:#2b333a;">
                            {{ $panelTitle }}
                        </p>

                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td class="sc-stack" width="50%" style="padding-right:12px; padding-bottom:12px; vertical-align:top;">
                                    <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Date</p>
                                    <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:22px; color:#2b333a;">{{ $panelDate ?: 'To be confirmed' }}</p>
                                </td>
                                <td class="sc-stack" width="50%" style="padding-bottom:12px; vertical-align:top;">
                                    <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Time</p>
                                    <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:22px; color:#2b333a;">{{ $panelTime }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="sc-stack" width="50%" style="padding-right:12px; vertical-align:top;">
                                    <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Format</p>
                                    <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:22px; color:#2b333a;">{{ $panelFormat }}</p>
                                </td>
                                <td class="sc-stack" width="50%" style="vertical-align:top;">
                                    <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Duration</p>
                                    <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:22px; color:#2b333a;">{{ $panelDuration }}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Speakers (only if the lineup is confirmed) --}}
    @if ($speakers && $speakers->isNotEmpty())
        <tr>
            <td class="sc-pad" style="padding:28px 32px 0 32px;">
                <p style="margin:0 0 12px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">On the panel</p>
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                    @foreach ($speakers as $speaker)
                        <tr>
                            <td style="padding:10px 0; border-bottom:1px solid #ece5d8;">
                                <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:22px; color:#2b333a;">
                                    <strong style="color:#055860;">{{ $speaker->name }}</strong>
                                    @if ($speaker->title)
                                        &nbsp;&middot;&nbsp; <span style="color:#8a8f86;">{{ $speaker->title }}</span>
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    @endif

    {{-- What to expect --}}
    <tr>
        <td class="sc-pad" style="padding:28px 32px 0 32px;">
            <p style="margin:0 0 12px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">What to expect</p>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr><td style="padding:6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:22px; color:#2b333a;">&middot;&nbsp; Real practitioners, not slide decks</td></tr>
                <tr><td style="padding:6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:22px; color:#2b333a;">&middot;&nbsp; Honest Q&amp;A &mdash; hard questions welcome</td></tr>
                <tr><td style="padding:6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:22px; color:#2b333a;">&middot;&nbsp; A community that keeps going after the session</td></tr>
            </table>
        </td>
    </tr>

    {{-- CTA (only when Eventbrite URL exists) --}}
    @if ($eventbriteUrl)
        <tr>
            <td class="sc-pad" style="padding:28px 32px 0 32px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="sc-btn">
                    <tr>
                        <td align="center" bgcolor="#055860" style="border-radius:6px;">
                            <!--[if mso]>
                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $eventbriteUrl }}" style="height:46px;v-text-anchor:middle;width:260px;" arcsize="13%" stroke="f" fillcolor="#055860">
                                <w:anchorlock/>
                                <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:15px;font-weight:bold;">Also add to Eventbrite</center>
                            </v:roundrect>
                            <![endif]-->
                            <!--[if !mso]><!-- -->
                            <a href="{{ $eventbriteUrl }}" style="display:inline-block; padding:14px 30px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:6px; background-color:#055860;">
                                Also add to Eventbrite
                            </a>
                            <!--<![endif]-->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif

    {{-- Sign-off --}}
    <tr>
        <td class="sc-pad" style="padding:28px 32px 34px 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                Any questions before then, reply straight to this email or write to <a href="mailto:hello@skillscoop.org" class="sc-link" style="color:#055860; text-decoration:underline;">hello@skillscoop.org</a>. See you soon.
            </p>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                Abby<br />
                <span style="color:#8a8f86;">Founder, Skills Co-op</span>
            </p>
        </td>
    </tr>

@endsection
