{{--
    Content partial for the internal "new referral received" notification.
    Extends emails.layout — supplies <tr> rows only.

    Data expected (see App\Mail\ReferralReceived):
        referredName, cohort, contact, contactHref, contactIsEmail,
        contactConsented, referrerName, referrerEmail, organisation,
        role, context, submittedAt, dashboardUrl (nullable)
    Layout data:
        subject, preheader, logoUrl, supportEmail, footerNote, year
--}}
@extends('emails.layout')

@section('content')

    {{-- Title block --}}
    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                Referral
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                {{ ($isSelfReferral ?? false) ? 'Someone signed themselves up' : 'New referral received' }}
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
            <p style="margin:20px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                @if ($isSelfReferral ?? false)
                    Somebody has put themselves forward through the referral form. They gave their own consent to be contacted, so reply to them directly.
                @else
                    Someone has been referred to Skills Co-op. Details are below.
                @endif
            </p>
        </td>
    </tr>

    {{-- Referred person panel --}}
    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                <tr>
                    <td style="padding:20px 22px;">
                        <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                            Referred person
                        </p>
                        <p style="margin:0 0 14px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:20px; line-height:28px; font-weight:700; color:#2b333a;">
                            {{ $referredName }}
                        </p>

                        @isset($cohort)
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 16px 0;">
                                <tr>
                                    <td style="background-color:#b9d8d3; border-radius:100px; padding:6px 14px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:0.6px; text-transform:uppercase; color:#055860;">
                                        Cohort: {{ $cohort }}
                                    </td>
                                </tr>
                            </table>
                        @endisset

                        {{-- Consent-gated contact display. HANDOVER §6: only render
                             when consent was recorded; otherwise show a suppression
                             notice so the reader knows contact exists but is withheld. --}}
                        @if ($contactConsented && !empty($contact))
                            <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                                Contact &middot; consent given
                            </p>
                            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; color:#2b333a;">
                                <a href="{{ $contactHref }}" class="sc-link" style="color:#055860; text-decoration:underline;">{{ $contact }}</a>
                            </p>
                        @else
                            <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                                Contact
                            </p>
                            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; font-style:italic; color:#8a8f86;">
                                Contact details withheld, no consent recorded. Reach the referred person via the referrer below.
                            </p>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Referrer details --}}
    <tr>
        <td class="sc-pad" style="padding:26px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                {{-- Omitted on a self-referral: the referrer and the referred
                     person are the same, and their contact is already in the
                     panel above. Repeating it reads as two people. --}}
                @unless ($isSelfReferral ?? false)
                    <tr>
                        <td style="padding-bottom:12px; border-bottom:1px solid #ece5d8;">
                            <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Referrer</p>
                            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; color:#2b333a;">
                                {{ $referrerName }} &nbsp;&middot;&nbsp; <a href="mailto:{{ $referrerEmail }}" class="sc-link" style="color:#055860; text-decoration:underline;">{{ $referrerEmail }}</a>
                            </p>
                        </td>
                    </tr>
                @endunless
                <tr>
                    <td style="padding:12px 0; border-bottom:1px solid #ece5d8;">
                        <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Organisation</p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; color:{{ $organisation ? '#2b333a' : '#8a8f86' }};">
                            {{ $organisation ?: 'Not provided' }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:12px 0; border-bottom:1px solid #ece5d8;">
                        <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Role</p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; color:{{ $role ? '#2b333a' : '#8a8f86' }};">
                            {{ $role ?: 'Not provided' }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:12px 0 0 0;">
                        <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Context</p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:{{ $context ? '#2b333a' : '#8a8f86' }}; white-space:pre-wrap;">{{ $context ?: 'None provided' }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- CTA (omit whole row when no dashboardUrl) --}}
    @isset($dashboardUrl)
        <tr>
            <td class="sc-pad" style="padding:28px 32px 0 32px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="sc-btn">
                    <tr>
                        <td align="center" bgcolor="#055860" style="border-radius:6px;">
                            <!--[if mso]>
                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $dashboardUrl }}" style="height:46px;v-text-anchor:middle;width:220px;" arcsize="13%" stroke="f" fillcolor="#055860">
                                <w:anchorlock/>
                                <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:15px;font-weight:bold;">View this referral</center>
                            </v:roundrect>
                            <![endif]-->
                            <!--[if !mso]><!-- -->
                            <a href="{{ $dashboardUrl }}" style="display:inline-block; padding:14px 30px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:6px; background-color:#055860;">
                                View this referral
                            </a>
                            <!--<![endif]-->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endisset

    {{-- Submitted meta --}}
    <tr>
        <td class="sc-pad" style="padding:26px 32px 34px 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:13px; line-height:20px; color:#8a8f86;">
                Submitted {{ $submittedAt }}.
            </p>
        </td>
    </tr>

@endsection
