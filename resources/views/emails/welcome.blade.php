{{-- Welcome email sent when a new account is created.
     Extends emails.layout and supplies <tr> rows only. --}}
@extends('emails.layout')

@section('content')

    {{-- Title block --}}
    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                Welcome
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                Your account is live
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
            <p style="margin:22px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Hi {{ $firstName }},
            </p>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Thanks for signing up. Skills Co-op is a funded 25-week digital skills programme for people the traditional pipeline was never built for. Our founding cohort starts in January 2027 with thirty places.
            </p>
        </td>
    </tr>

    {{-- Next steps --}}
    <tr>
        <td class="sc-pad" style="padding:28px 32px 0 32px;">
            <p style="margin:0 0 14px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                Three things to do first
            </p>

            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td style="padding:14px 0; border-bottom:1px solid #ece5d8;">
                        <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; font-weight:700; color:#055860;">
                            1. Take the pathway assessment
                        </p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:23px; color:#2b333a;">
                            Fifteen questions, about two minutes. It matches you to one of our four pilot tracks based on how you actually like to work.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:14px 0; border-bottom:1px solid #ece5d8;">
                        <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; font-weight:700; color:#055860;">
                            2. Read how the 25 weeks work
                        </p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:23px; color:#2b333a;">
                            Foundations, then specialised training, then a project period where teams build and launch something real. Three certificates along the way.
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:14px 0 0 0;">
                        <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; font-weight:700; color:#055860;">
                            3. Come to a panel session
                        </p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:23px; color:#2b333a;">
                            @if ($panelTitle && $panelDate)
                                Our next one is {{ $panelTitle }} on {{ $panelDate }}. Free, online, and open to everyone.
                            @else
                                We run a free online panel every month with practitioners, researchers, and community leaders. Open to everyone.
                            @endif
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- CTA --}}
    <tr>
        <td class="sc-pad" style="padding:28px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="sc-btn">
                <tr>
                    <td align="center" bgcolor="#055860" style="border-radius:6px;">
                        <!--[if mso]>
                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $assessmentUrl }}" style="height:46px;v-text-anchor:middle;width:240px;" arcsize="13%" stroke="f" fillcolor="#055860">
                            <w:anchorlock/>
                            <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:15px;font-weight:bold;">Start the assessment</center>
                        </v:roundrect>
                        <![endif]-->
                        <!--[if !mso]><!-- -->
                        <a href="{{ $assessmentUrl }}" style="display:inline-block; padding:14px 30px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:6px; background-color:#055860;">
                            Start the assessment
                        </a>
                        <!--<![endif]-->
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Sign-off --}}
    <tr>
        <td class="sc-pad" style="padding:28px 32px 34px 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                If anything is unclear, or you want to talk through whether this is right for you, reply to this email. A real person reads it.
            </p>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                Abby<br />
                <span style="color:#8a8f86;">Founder, Skills Co-op</span>
            </p>
        </td>
    </tr>

@endsection
