{{-- Assessment results email, sent once the pathway assessment is completed.
     Extends emails.layout and supplies <tr> rows only. --}}
@extends('emails.layout')

@section('content')

    {{-- Title block --}}
    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                Assessment complete
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                Your pathway match
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
            <p style="margin:22px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Hi {{ $firstName }},
            </p>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Thanks for taking the assessment. Here is what your answers pointed to. This is a starting point for a conversation, not a verdict. If it does not feel right, tell us and we will talk it through.
            </p>
        </td>
    </tr>

    {{-- Primary match --}}
    @if ($primary && $primary->pathway)
        <tr>
            <td class="sc-pad" style="padding:24px 32px 0 32px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                    <tr>
                        <td style="padding:20px 22px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 12px 0;">
                                <tr>
                                    <td style="background-color:#b9d8d3; border-radius:100px; padding:6px 14px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:0.6px; text-transform:uppercase; color:#055860;">
                                        Closest match
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:22px; line-height:30px; font-weight:700; color:#2b333a;">
                                {{ $primary->pathway->name }}
                            </p>

                            @if ($primary->recommendation_text)
                                <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                                    {{ $primary->recommendation_text }}
                                </p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif

    {{-- Secondary match --}}
    @if ($secondary && $secondary->pathway)
        <tr>
            <td class="sc-pad" style="padding:16px 32px 0 32px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border:1px solid #ece5d8; border-radius:6px;">
                    <tr>
                        <td style="padding:18px 22px;">
                            <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                                Also worth a look
                            </p>
                            <p style="margin:0 0 8px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:17px; line-height:25px; font-weight:700; color:#2b333a;">
                                {{ $secondary->pathway->name }}
                            </p>
                            @if ($secondary->recommendation_text)
                                <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#8a8f86;">
                                    {{ $secondary->recommendation_text }}
                                </p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif

    {{-- What happens next --}}
    <tr>
        <td class="sc-pad" style="padding:28px 32px 0 32px;">
            <p style="margin:0 0 12px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                What happens next
            </p>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td style="padding:6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:23px; color:#2b333a;">
                        &middot;&nbsp; Read how the 25 weeks are structured, and what you earn at each stage.
                    </td>
                </tr>
                <tr>
                    <td style="padding:6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:23px; color:#2b333a;">
                        &middot;&nbsp; Come to a free panel session and meet people already working in the field.
                    </td>
                </tr>
                <tr>
                    <td style="padding:6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:23px; color:#2b333a;">
                        &middot;&nbsp; Applications for the January 2027 founding cohort are open. Thirty places.
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
                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $resultsUrl }}" style="height:46px;v-text-anchor:middle;width:230px;" arcsize="13%" stroke="f" fillcolor="#055860">
                            <w:anchorlock/>
                            <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:15px;font-weight:bold;">View your full results</center>
                        </v:roundrect>
                        <![endif]-->
                        <!--[if !mso]><!-- -->
                        <a href="{{ $resultsUrl }}" style="display:inline-block; padding:14px 30px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:6px; background-color:#055860;">
                            View your full results
                        </a>
                        <!--<![endif]-->
                    </td>
                </tr>
            </table>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#8a8f86;">
                Or <a href="{{ $pathwayUrl }}" class="sc-link" style="color:#055860; text-decoration:underline;">see the 25-week pathway</a> and <a href="{{ $sessionsUrl }}" class="sc-link" style="color:#055860; text-decoration:underline;">book a panel session</a>.
            </p>
        </td>
    </tr>

    {{-- Sign-off --}}
    <tr>
        <td class="sc-pad" style="padding:26px 32px 34px 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                Not sure the match fits? Reply to this email and tell us what you were expecting. We would rather get you on the right track than the tidy one.
            </p>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                Abby<br />
                <span style="color:#8a8f86;">Founder, Skills Co-op</span>
            </p>
        </td>
    </tr>

@endsection
