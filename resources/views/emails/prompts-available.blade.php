{{--
    The prompts behind the live builds are on the site: one email covering
    every session named, sent once per address via panels:prompts-email.

    Data expected (see App\Mail\PromptsAvailable):
        firstName, sessions (tagline, date, builders, promptsUrl, pageUrl,
        hasRecording), moreToCome
--}}
@extends('emails.layout')

@section('content')

    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                The Skills Co-op Sessions
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                Build it yourself
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <p style="margin:0 0 16px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                Hi {{ $firstName }},
            </p>
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                At our live build sessions, speakers built working tools with AI on screen. You can now
                build the same things yourself: the exact prompts they used are on our website, free.
                Enter your name and email on the page and the prompts appear straight away.
            </p>
        </td>
    </tr>

    @foreach ($sessions as $session)
        <tr>
            <td class="sc-pad" style="padding:24px 32px 0 32px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                    <tr>
                        <td style="padding:20px 22px;">
                            <p style="margin:0 0 4px 0; font-family:Georgia,'Times New Roman',serif; font-size:20px; line-height:28px; color:#055860;">
                                {{ $session['tagline'] }}
                            </p>
                            @if ($session['date'])
                                <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#6a737b;">
                                    {{ $session['date'] }}
                                </p>
                            @endif
                            @if ($session['builders'])
                                <p style="margin:0 0 16px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                                    Prompts from {{ $session['builders'] }}.
                                </p>
                            @endif
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="sc-btn">
                                <tr>
                                    <td align="center" bgcolor="#ee9d1d" style="border-radius:6px;">
                                        <a href="{{ $session['promptsUrl'] }}" style="display:inline-block; padding:14px 28px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; font-weight:700; color:#1d2b2a; text-decoration:none; border-radius:6px; background-color:#ee9d1d;">
                                            Get the prompts
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            @if ($session['hasRecording'])
                                <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#2b333a;">
                                    <a href="{{ $session['pageUrl'] }}" style="color:#055860; font-weight:700;">Watch the recording</a> on the same page.
                                </p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endforeach

    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:25px; color:#2b333a;">
                @if ($moreToCome)
                    More prompts from the other speakers are on the way, on the same pages.
                @endif
                Every build used fictional or sample data. If you use your own details, read your AI
                tool's privacy terms first.
            </p>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 40px 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                Abisola Areola<br>
                <span style="color:#8a8f86;">Founder and Executive Director, Skills Co-op</span>
            </p>
        </td>
    </tr>

@endsection
