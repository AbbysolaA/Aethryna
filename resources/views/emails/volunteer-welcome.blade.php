{{--
    Content partial for the volunteer welcome email, sent once an offer has
    been accepted. Carries the onboarding pack.

    Extends emails.layout — supplies <tr> rows only.

    Data expected (see App\Mail\VolunteerWelcome):
        firstName, role, firstCommitments (nullable), actions (array),
        documents (array of ['label','note','url'])
    Layout data:
        subject, preheader, logoUrl, supportEmail, footerNote, year
--}}
@extends('emails.layout')

@section('content')

    {{-- Title block --}}
    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                Welcome
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                Welcome to the team
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
            <p style="margin:22px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Hi {{ $firstName }},
            </p>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Thank you for joining Skills Co-op as our {{ $role }}. I am really glad to have you on board.
            </p>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                We deliver free, AI-integrated digital skills and employability pathways to three groups of people who are usually last in the queue for this kind of training: young people not in education, employment or training, adults with lived experience of the justice system, and women returning to work after time away. Our pilot cohort launches in January 2027, and the next few months are about getting everything ready for that.
            </p>
            @isset($firstCommitments)
                <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                    {{ $firstCommitments }}
                </p>
            @endisset
        </td>
    </tr>

    {{-- Immediate actions --}}
    @if (! empty($actions))
        <tr>
            <td class="sc-pad" style="padding:26px 32px 0 32px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                    <tr>
                        <td style="padding:20px 22px;">
                            <p style="margin:0 0 12px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                                Before anything else
                            </p>
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                                @foreach ($actions as $i => $action)
                                    <tr>
                                        <td valign="top" width="26" style="width:26px; padding:{{ $i === 0 ? '0' : '10px' }} 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; font-weight:700; color:#ee9d1d;">
                                            {{ $i + 1 }}.
                                        </td>
                                        <td style="padding:{{ $i === 0 ? '0' : '10px' }} 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; color:#2b333a;">
                                            {!! $action !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#8a8f86;">
                                Once those are done I will set up your access to Todoist, Notion, Eventbrite and the shared folders, and we will book your weekly check-in.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif

    {{-- Onboarding pack --}}
    <tr>
        <td class="sc-pad" style="padding:30px 32px 0 32px;">
            <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                Your onboarding pack
            </p>
            <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                Everything you need is here. Please take some time to read through it.
            </p>

            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                @foreach ($documents as $doc)
                    <tr>
                        <td style="padding:14px 0; {{ $loop->last ? '' : 'border-bottom:1px solid #ece5d8;' }}">
                            <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; font-weight:700;">
                                <a href="{{ $doc['url'] }}" class="sc-link" style="color:#055860; text-decoration:underline;">{{ $doc['label'] }}</a>
                            </p>
                            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:23px; color:#2b333a;">
                                {{ $doc['note'] }}
                            </p>
                        </td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>

    {{-- How to send things back. The pack asks for signed documents, so the
         email has to say where they go rather than leaving it to be guessed. --}}
    <tr>
        <td class="sc-pad" style="padding:28px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#fdf6e9; border-left:4px solid #ee9d1d; border-radius:6px;">
                <tr>
                    <td style="padding:20px 22px;">
                        <p style="margin:0 0 8px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a5a06;">
                            Sending things back
                        </p>
                        <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                            Only the documents marked <strong>sign and return</strong> need to come back. Everything else is for reading.
                        </p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                            Send them to
                            <a href="mailto:{{ $returnsEmail }}" class="sc-link" style="color:#055860; text-decoration:underline; font-weight:700;">{{ $returnsEmail }}</a>.
                            A clear photo or a scan is fine. It does not need to be a formal e-signature.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    @isset($engagementUrl)
        <tr>
            <td class="sc-pad" style="padding:24px 32px 0 32px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="sc-btn">
                    <tr>
                        <td align="center" bgcolor="#055860" style="border-radius:6px;">
                            <!--[if mso]>
                            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $engagementUrl }}" style="height:46px;v-text-anchor:middle;width:250px;" arcsize="13%" stroke="f" fillcolor="#055860">
                                <w:anchorlock/>
                                <center style="color:#ffffff;font-family:Arial,sans-serif;font-size:15px;font-weight:bold;">See what is outstanding</center>
                            </v:roundrect>
                            <![endif]-->
                            <!--[if !mso]><!-- -->
                            <a href="{{ $engagementUrl }}" style="display:inline-block; padding:14px 30px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:6px; background-color:#055860;">
                                See what is outstanding
                            </a>
                            <!--<![endif]-->
                        </td>
                    </tr>
                </table>
                <p style="margin:12px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:13px; line-height:20px; color:#8a8f86;">
                    Your volunteering page tracks what we have received and what is still to come.
                </p>
            </td>
        </tr>
    @endisset

    {{-- Sign-off --}}
    <tr>
        <td class="sc-pad" style="padding:26px 32px 34px 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                If anything in the pack is unclear, or you spot something that looks wrong, tell me. You are coming in with fresh eyes and that is genuinely useful to me right now.
            </p>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                Looking forward to working with you.
            </p>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                Abby<br />
                <span style="color:#8a8f86;">Founder, Skills Co-op</span>
            </p>
        </td>
    </tr>

@endsection
