{{--
    Content partial for the volunteer offer email, sent when a volunteer is
    selected for a role and needs to accept or decline.

    Extends emails.layout — supplies <tr> rows only.

    Data expected (see App\Mail\VolunteerOffer):
        firstName, role, startsOn, endsOn, respondUrl, respondBy
    Layout data:
        subject, preheader, logoUrl, supportEmail, footerNote, year
--}}
@extends('emails.layout')

@section('content')

    {{-- Title block --}}
    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                Volunteering
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                Your volunteer offer
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
            <p style="margin:22px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Hi {{ $firstName }},
            </p>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                We would like you to join Skills Co-op. Thank you for applying, and for the time you gave to the conversation.
            </p>
        </td>
    </tr>

    {{-- Role panel --}}
    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                <tr>
                    <td style="padding:20px 22px;">
                        <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                            Role
                        </p>
                        <p style="margin:0 0 16px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:20px; line-height:28px; font-weight:700; color:#2b333a;">
                            {{ $role }}
                        </p>

                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td class="sc-stack" width="50%" valign="top" style="width:50%; padding-right:12px;">
                                    <p class="sc-label" style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                                        Starts
                                    </p>
                                    <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; color:#2b333a;">
                                        {{ $startsOn }}
                                    </p>
                                </td>
                                <td class="sc-stack" width="50%" valign="top" style="width:50%;">
                                    <p class="sc-label" style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                                        Ends
                                    </p>
                                    <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; color:#2b333a;">
                                        {{ $endsOn }}
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- How to respond. Volunteers reach us from partner referrals and word of
         mouth as well as the website, so the landing page has to offer account
         creation, not just sign-in. --}}
    <tr>
        <td class="sc-pad" style="padding:26px 32px 0 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Use the button below to accept or decline. You will be asked to sign in first. If you have not used the site before, you can create an account on the same page with the address this email was sent to.
            </p>
        </td>
    </tr>

    {{-- CTA --}}
    <tr>
        <td class="sc-pad" style="padding:26px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="sc-btn">
                <tr>
                    <td align="center" bgcolor="#055860" style="border-radius:6px;">
                        <!--[if mso]>
                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $respondUrl }}" style="height:46px;v-text-anchor:middle;width:230px;" arcsize="13%" stroke="f" fillcolor="#055860">
                            <w:anchorlock/>
                            <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:15px;font-weight:bold;">Respond to your offer</center>
                        </v:roundrect>
                        <![endif]-->
                        <!--[if !mso]><!-- -->
                        <a href="{{ $respondUrl }}" style="display:inline-block; padding:14px 30px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:6px; background-color:#055860;">
                            Respond to your offer
                        </a>
                        <!--<![endif]-->
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Deadline --}}
    <tr>
        <td class="sc-pad" style="padding:22px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:1px solid #ece5d8;">
                <tr>
                    <td style="padding-top:16px;">
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                            <strong style="color:#055860;">Please respond by {{ $respondBy }}.</strong>
                            If you need longer, or the dates do not work for you, reply to this email and we will sort something out.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Sign-off --}}
    <tr>
        <td class="sc-pad" style="padding:24px 32px 34px 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                Abby<br />
                <span style="color:#8a8f86;">Founder, Skills Co-op</span>
            </p>
        </td>
    </tr>

@endsection
