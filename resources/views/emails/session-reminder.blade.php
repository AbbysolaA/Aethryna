{{--
    Reminder to a panel registrant, a week out ('week') or the day before ('day').

    Data expected (see App\Mail\SessionReminder):
        stage, firstName, panelTitle, panelDate, panelTime, panelFormat,
        panelUrl, calendarUrl, speakers
--}}
@extends('emails.layout')

@section('content')

    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#055860;">
                The Skills Co-op Sessions
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                {{ $stage === 'day' ? 'See you tomorrow' : 'One week to go' }}
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
                @if ($stage === 'day')
                    Your seat at <strong>{{ $panelTitle }}</strong> is booked for tomorrow.
                    It is free, online and starts on time.
                @else
                    You are registered for <strong>{{ $panelTitle }}</strong>, one week from now.
                    It is free, online and open to everyone, so feel welcome to bring someone along.
                @endif
            </p>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                <tr>
                    <td style="padding:18px 22px;">
                        <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:25px; font-weight:700; color:#055860;">
                            {{ $panelTitle }}
                        </p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:25px; color:#2b333a;">
                            {{ $panelDate }}@if ($panelTime), {{ $panelTime }}@endif &middot; {{ $panelFormat }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    @if ($speakers->isNotEmpty())
        <tr>
            <td class="sc-pad" style="padding:24px 32px 0 32px;">
                <p style="margin:0 0 8px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; font-weight:700; color:#055860;">
                    On the panel
                </p>
                @foreach ($speakers as $speaker)
                    <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:23px; color:#2b333a;">
                        <strong>{{ $speaker->name }}</strong>@if ($speaker->title || $speaker->company) &middot; {{ trim($speaker->title.($speaker->title && $speaker->company ? ', ' : '').$speaker->company) }}@endif
                    </p>
                @endforeach
            </td>
        </tr>
    @endif

    <tr>
        <td class="sc-pad" style="padding:28px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" class="sc-btn">
                <tr>
                    <td align="center" bgcolor="#055860" style="border-radius:6px;">
                        <a href="{{ $calendarUrl }}" style="display:inline-block; padding:14px 30px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:6px; background-color:#055860;">
                            Add to calendar
                        </a>
                    </td>
                </tr>
            </table>
            <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:14px; line-height:23px; color:#6a737b;">
                The calendar file is attached to this email too. Everything about the panel,
                speakers included, is at <a href="{{ $panelUrl }}" style="color:#055860;">{{ $panelUrl }}</a>,
                and that link is the one to send a friend who should be in the room.
            </p>
        </td>
    </tr>

    <tr>
        <td class="sc-pad" style="padding:24px 32px 40px 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                Can no longer make it? No need to do anything, the recording lands on the same
                page afterwards. See you {{ $stage === 'day' ? 'tomorrow' : 'there' }}.
            </p>
        </td>
    </tr>

@endsection
