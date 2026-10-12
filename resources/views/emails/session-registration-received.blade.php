{{--
    Internal notification: a panel registration arrived.

    Data expected (see App\Mail\SessionRegistrationReceived):
        name, email, interestLabel, heardAbout, campaign, wantsToSpeak,
        speakerTopic, panelTagline, panelDate, panelCount, adminUrl
--}}
@extends('emails.layout')

@section('content')

    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                The Sessions
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                New registration
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#ee9d1d; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                <strong>{{ $name }}</strong> ({{ $interestLabel }}) registered for
                <strong>{{ $panelTagline ?: 'the next panel' }}</strong>@if ($panelDate), {{ $panelDate }}@endif.
                @if ($panelCount)
                    That takes it to {{ $panelCount }} {{ $panelCount === 1 ? 'registration' : 'registrations' }}.
                @endif
            </p>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:20px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                <tr>
                    <td style="padding:18px 22px;">
                        <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                            <strong>Email:</strong> <a href="mailto:{{ $email }}" style="color:#055860;">{{ $email }}</a>
                        </p>
                        @if ($heardAbout)
                            <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                                <strong>Heard about us:</strong> {{ $heardAbout }}
                            </p>
                        @endif
                        @if ($campaign)
                            <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                                <strong>Campaign:</strong> {{ $campaign }}
                            </p>
                        @endif
                        @if ($wantsToSpeak)
                            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                                <strong>Offered to speak on a future panel.</strong>
                                @if ($speakerTopic) Topic: {{ $speakerTopic }}@endif
                            </p>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 40px 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:14px; line-height:23px; color:#6a737b;">
                Replying to this email writes to {{ $name }} directly. The full list is at
                <a href="{{ $adminUrl }}" style="color:#055860;">{{ $adminUrl }}</a>.
            </p>
        </td>
    </tr>

@endsection
