<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta name="format-detection" content="telephone=no,address=no,email=no,date=no,url=no" />
    <meta name="color-scheme" content="light" />
    <meta name="supported-color-schemes" content="light" />
    <title>{{ $subject ?? 'Skills Co-op' }}</title>

    <!--[if mso]>
    <noscript><xml><o:OfficeDocumentSettings>
        <o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings></xml></noscript>
    <![endif]-->

    <style type="text/css">
        /* --------------------------------------------------------------
           SKILLS CO-OP EMAIL TOKENS (source: Canva brand kit, do not edit
           without updating the brand doc).
             Teal   #055860   primary — header, headings, links, buttons
             Amber  #ee9d1d   accent — rules, emphasis, badges
             Mint   #b9d8d3   soft tint — pills
             Mint wash #eef6f4 panel fill
             Cream  #f7f2e8   page background, footer
             Ink    #2b333a   body copy
             Muted  #8a8f86   meta, legal, captions
           -------------------------------------------------------------- */

        @import url('https://fonts.googleapis.com/css2?family=Karla:wght@400;500;700&display=swap');

        html, body { margin:0 !important; padding:0 !important; height:100% !important; width:100% !important; }
        * { -ms-text-size-adjust:100%; -webkit-text-size-adjust:100%; }
        table, td { mso-table-lspace:0pt !important; mso-table-rspace:0pt !important; border-collapse:collapse !important; }
        img { -ms-interpolation-mode:bicubic; border:0; height:auto; line-height:100%; outline:none; text-decoration:none; }
        a { text-decoration:none; }

        /* Force the Outlook/Word renderer onto safe fonts */
        /*[if mso]><style>* { font-family: Arial, sans-serif !important; }</style><![endif]*/

        .sc-link { color:#055860; text-decoration:underline; }
        .sc-link:hover { color:#ee9d1d; }

        @media screen and (max-width:600px) {
            .sc-card  { width:100% !important; max-width:100% !important; }
            .sc-pad   { padding-left:24px !important; padding-right:24px !important; }
            .sc-h1    { font-size:26px !important; line-height:34px !important; }
            .sc-stack { display:block !important; width:100% !important; }
            .sc-label { padding-bottom:2px !important; }
            .sc-btn a { display:block !important; width:auto !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; width:100%; background-color:#f7f2e8;">

    {{-- Preheader: first line shown in the inbox list. Keep under ~90 chars. --}}
    <div style="display:none; font-size:1px; line-height:1px; max-height:0; max-width:0; opacity:0; overflow:hidden; mso-hide:all; font-family:Arial,sans-serif;">
        {{ $preheader ?? '' }}
    </div>
    <div style="display:none; max-height:0; overflow:hidden;">&#8199;&#65279;&#847; &#8199;&#65279;&#847; &#8199;&#65279;&#847; &#8199;&#65279;&#847; &#8199;&#65279;&#847; &#8199;&#65279;&#847; &#8199;&#65279;&#847; &#8199;&#65279;&#847; &#8199;&#65279;&#847;</div>

    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f7f2e8;">
        <tr>
            <td align="center" style="padding:32px 12px 40px 12px;">

                <!--[if mso]><table role="presentation" align="center" cellpadding="0" cellspacing="0" border="0" width="600"><tr><td><![endif]-->
                <table role="presentation" class="sc-card" cellpadding="0" cellspacing="0" border="0" width="600" style="width:600px; max-width:600px; background-color:#ffffff; border-radius:12px; overflow:hidden;">

                    {{-- ============ HEADER ============ --}}
                    <tr>
                        <td style="background-color:#055860; padding:20px 32px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    @isset($logoUrl)
                                        <td align="left" valign="middle" width="40" style="width:40px;">
                                            <img src="{{ $logoUrl }}" width="36" height="30" alt="Skills Co-op" style="display:block; width:36px; height:30px; border:0;" />
                                        </td>
                                        <td align="left" valign="middle" style="padding-left:12px;">
                                            <span style="font-family:'Karla',Arial,Helvetica,sans-serif; font-size:17px; font-weight:700; color:#ffffff; letter-spacing:0.2px;">Skills&nbsp;Co-op</span>
                                        </td>
                                    @else
                                        <td align="left" valign="middle">
                                            <span style="font-family:'Karla',Arial,Helvetica,sans-serif; font-size:17px; font-weight:700; color:#ffffff; letter-spacing:0.2px;">Skills&nbsp;Co-op</span>
                                        </td>
                                    @endisset
                                    <td align="right" valign="middle">
                                        <a href="https://skillscoop.org" style="font-family:'Karla',Arial,Helvetica,sans-serif; font-size:13px; color:#f7f2e8; text-decoration:none; letter-spacing:0.4px;">skillscoop.org</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Amber hairline under the header --}}
                    <tr><td style="background-color:#ee9d1d; font-size:0; line-height:0; height:4px;">&nbsp;</td></tr>

                    {{-- ============ CONTENT SLOT ============ --}}
                    @yield('content')

                    {{-- ============ FOOTER ============ --}}
                    <tr>
                        <td class="sc-pad" style="background-color:#f7f2e8; padding:24px 32px 28px 32px; border-top:1px solid #e6ddcd;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td style="font-family:'Karla',Arial,Helvetica,sans-serif; font-size:13px; line-height:20px; color:#8a8f86;">
                                        <strong style="color:#055860;">Skills Co-op</strong><br />
                                        <a href="https://skillscoop.org" class="sc-link" style="color:#055860; text-decoration:underline;">skillscoop.org</a>
                                        &nbsp;·&nbsp;
                                        <a href="mailto:{{ $supportEmail ?? 'hello@skillscoop.org' }}" class="sc-link" style="color:#055860; text-decoration:underline;">{{ $supportEmail ?? 'hello@skillscoop.org' }}</a>
                                    </td>
                                </tr>
                                @isset($footerNote)
                                    <tr>
                                        <td style="padding-top:12px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; line-height:17px; color:#8a8f86;">
                                            {{ $footerNote }}
                                        </td>
                                    </tr>
                                @endisset
                                <tr>
                                    <td style="padding-top:10px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; line-height:17px; color:#8a8f86;">
                                        &copy; {{ $year ?? date('Y') }} Skills Co-op. All rights reserved.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
                <!--[if mso]></td></tr></table><![endif]-->

            </td>
        </tr>
    </table>
</body>
</html>
