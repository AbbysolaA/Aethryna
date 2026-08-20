{{-- Confirmation for the Community Discovery Session.
     Extends emails.layout and supplies <tr> rows only.

     Written to be read once, on a phone, possibly on the bus there. The
     address, the day and the time come before anything warm, because that is
     what the person opening it is looking for. --}}
@extends('emails.layout')

@section('content')

    {{-- Title block --}}
    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#08444A;">
                @if ($waitlisted) You are on the waiting list @else Your place is booked @endif
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#08444A;">
                @if ($waitlisted)
                    We will let you know
                @else
                    See you on {{ $dayAndDate }}
                @endif
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:#E8B647; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
            <p style="margin:22px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2D353C;">
                Hi {{ $firstName }},
            </p>
            @if ($waitlisted)
                <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2D353C;">
                    The room is full, so you are on the waiting list. Places come up more often than you would think, and we will email you the moment one does. The details are below so you have them either way.
                </p>
            @else
                <p style="margin:14px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2D353C;">
                    You have a place at the Discovery Session. It is free, there is nothing to bring, and there is nothing to sign up to on the day.
                </p>
            @endif
        </td>
    </tr>

    {{-- Where and when --}}
    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#F7F2E8; border-left:4px solid #08444A; border-radius:6px;">
                <tr>
                    <td style="padding:20px 22px;">
                        <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#59626A;">
                            When
                        </p>
                        <p style="margin:0 0 16px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:17px; line-height:25px; font-weight:700; color:#2D353C;">
                            {{ $dayAndDate }}<br>
                            {{ $startTime }} to {{ $endTime }}
                        </p>

                        <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#59626A;">
                            Where
                        </p>
                        <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:17px; line-height:25px; font-weight:700; color:#2D353C;">
                            {{ $venueName }}
                        </p>
                        <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:23px; color:#414A52;">
                            {{ $venueAddress }}
                        </p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:23px;">
                            <a href="{{ $mapUrl }}" style="color:#08444A; font-weight:700;">Open in maps &rarr;</a>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Access. Its own block, because for some people this is the only part
         of the email that decides whether they come. --}}
    @if ($accessibility)
        <tr>
            <td class="sc-pad" style="padding:16px 32px 0 32px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-radius:6px;">
                    <tr>
                        <td style="padding:16px 22px;">
                            <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#59626A;">
                                Getting in
                            </p>
                            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:23px; color:#2D353C;">
                                {{ $accessibility }} If you need anything else to be able to come, reply to this email and we will sort it.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif

    {{-- The shape of the afternoon --}}
    @if (count($itinerary))
        <tr>
            <td class="sc-pad" style="padding:28px 32px 0 32px;">
                <p style="margin:0 0 14px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:#08444A;">
                    What happens on the day
                </p>
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                    @foreach ($itinerary as $item)
                        <tr>
                            <td width="80" valign="top" style="padding:0 12px 12px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; font-weight:700; color:#C77F14; white-space:nowrap;">
                                {{ $item['time'] }}
                            </td>
                            <td valign="top" style="padding:0 0 12px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:22px; color:#2D353C;">
                                <strong>{{ $item['what'] }}</strong>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    @endif

    {{-- Close --}}
    <tr>
        <td class="sc-pad" style="padding:24px 32px 8px 32px;">
            <p style="margin:0 0 14px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2D353C;">
                Come as you are. You do not need experience, qualifications, or a plan.
            </p>
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2D353C;">
                If something changes and you cannot make it, just reply and let us know. It frees a place for someone on the waiting list.
            </p>
            <p style="margin:22px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px;">
                <a href="{{ $eventUrl }}" style="color:#08444A; font-weight:700;">Full event details &rarr;</a>
            </p>
        </td>
    </tr>

@endsection
