{{--
    Internal notification: someone applied to volunteer.
    Extends emails.layout — supplies <tr> rows only.

    Data expected (see App\Mail\VolunteerApplicationReceived):
        applicantName, applicantEmail, phone, roleTitle, about, availability,
        experience, appliedAt, rosterUrl
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
                New volunteer application
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>

    {{-- Applicant panel --}}
    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                <tr>
                    <td style="padding:20px 22px;">
                        <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                            Applicant
                        </p>
                        <p style="margin:0 0 14px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:20px; line-height:28px; font-weight:700; color:#2b333a;">
                            {{ $applicantName }}
                        </p>

                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 16px 0;">
                            <tr>
                                <td style="background-color:#b9d8d3; border-radius:100px; padding:6px 14px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:0.6px; text-transform:uppercase; color:#055860;">
                                    {{ $roleTitle }}
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; color:#2b333a;">
                            <a href="mailto:{{ $applicantEmail }}" class="sc-link" style="color:#055860; text-decoration:underline;">{{ $applicantEmail }}</a>
                            @if ($phone)
                                <br /><span style="color:#8a8f86;">{{ $phone }}</span>
                            @endif
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Answers --}}
    <tr>
        <td class="sc-pad" style="padding:26px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td style="padding-bottom:12px; border-bottom:1px solid #ece5d8;">
                        <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Why this role</p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a; white-space:pre-wrap;">{{ $about }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:12px 0; border-bottom:1px solid #ece5d8;">
                        <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Availability</p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; color:#2b333a;">{{ $availability }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:12px 0 0 0;">
                        <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Relevant experience</p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:{{ $experience ? '#2b333a' : '#8a8f86' }}; white-space:pre-wrap;">{{ $experience ?: 'None given' }}</p>
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
                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $rosterUrl }}" style="height:46px;v-text-anchor:middle;width:220px;" arcsize="13%" stroke="f" fillcolor="#055860">
                            <w:anchorlock/>
                            <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:15px;font-weight:bold;">Open the roster</center>
                        </v:roundrect>
                        <![endif]-->
                        <!--[if !mso]><!-- -->
                        <a href="{{ $rosterUrl }}" style="display:inline-block; padding:14px 30px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:6px; background-color:#055860;">
                            Open the roster
                        </a>
                        <!--<![endif]-->
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Submitted meta --}}
    <tr>
        <td class="sc-pad" style="padding:26px 32px 34px 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:13px; line-height:20px; color:#8a8f86;">
                Applied {{ $appliedAt }}. Reply to this email to reach them directly.
            </p>
        </td>
    </tr>

@endsection
