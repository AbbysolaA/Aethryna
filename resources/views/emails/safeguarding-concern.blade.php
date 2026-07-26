{{-- Safeguarding concern notification to the named safeguarding lead.
     Extends emails.layout and supplies <tr> rows only. --}}
@extends('emails.layout')

@section('content')

    {{-- Title block --}}
    <tr>
        <td class="sc-pad" style="padding:36px 32px 0 32px;">
            <p style="margin:0 0 10px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:12px; font-weight:700; letter-spacing:1.6px; text-transform:uppercase; color:{{ $isUrgent ? '#b3261e' : '#055860' }};">
                {{ $urgencyLabel }} &middot; {{ $reference }}
            </p>
            <h1 class="sc-h1" style="margin:0; font-family:Georgia,'Times New Roman',serif; font-size:30px; line-height:38px; font-weight:400; color:#055860;">
                Safeguarding concern raised
            </h1>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:18px 0 0 0;">
                <tr><td width="64" height="4" style="width:64px; height:4px; background-color:{{ $isUrgent ? '#b3261e' : '#ee9d1d' }}; font-size:0; line-height:0;">&nbsp;</td></tr>
            </table>
            <p style="margin:20px 0 0 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:26px; color:#2b333a;">
                A concern has been raised and is waiting for your review. It has been recorded as {{ $reference }} regardless of whether this email reached you.
            </p>
        </td>
    </tr>

    @if ($isUrgent)
        <tr>
            <td class="sc-pad" style="padding:20px 32px 0 32px;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#fdecea; border-left:4px solid #b3261e; border-radius:6px;">
                    <tr>
                        <td style="padding:16px 20px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:23px; color:#7a1a13;">
                            <strong>Marked urgent by the person raising it.</strong> If there is an immediate risk to someone's safety, contact the emergency services on 999 first, then follow the safeguarding policy.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    @endif

    {{-- Learner --}}
    <tr>
        <td class="sc-pad" style="padding:24px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#eef6f4; border-left:4px solid #055860; border-radius:6px;">
                <tr>
                    <td style="padding:20px 22px;">
                        <p style="margin:0 0 4px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                            Concern is about
                        </p>
                        <p style="margin:0 0 6px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:20px; line-height:28px; font-weight:700; color:#2b333a;">
                            {{ $learnerName }}
                        </p>
                        @if ($learnerEmail)
                            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#8a8f86;">
                                {{ $learnerEmail }}
                            </p>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- The concern --}}
    <tr>
        <td class="sc-pad" style="padding:26px 32px 0 32px;">
            <p style="margin:0 0 8px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">
                What was reported
            </p>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border:1px solid #ece5d8; border-radius:6px;">
                <tr>
                    <td style="padding:18px 20px; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:25px; color:#2b333a; white-space:pre-wrap;">{{ $concernBody }}</td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Who raised it --}}
    <tr>
        <td class="sc-pad" style="padding:26px 32px 0 32px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td style="padding-bottom:12px; border-bottom:1px solid #ece5d8;">
                        <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Raised by</p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; color:#2b333a;">
                            {{ $raiserName }}
                            @if ($raiserEmail)
                                &nbsp;&middot;&nbsp; <a href="mailto:{{ $raiserEmail }}" class="sc-link" style="color:#055860; text-decoration:underline;">{{ $raiserEmail }}</a>
                            @endif
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:12px 0; border-bottom:1px solid #ece5d8;">
                        <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Their role</p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; color:#2b333a;">{{ $raiserRole }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:12px 0 0 0;">
                        <p style="margin:0 0 3px 0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:11px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#8a8f86;">Raised at</p>
                        <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:16px; line-height:24px; color:#2b333a;">{{ $raisedAt }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Next step --}}
    <tr>
        <td class="sc-pad" style="padding:26px 32px 34px 32px;">
            <p style="margin:0; font-family:'Karla',Arial,Helvetica,sans-serif; font-size:15px; line-height:24px; color:#2b333a;">
                You are the decision maker on this one. Reply to this email to reach {{ $raiserName }} directly, and record what you decide against {{ $reference }} so the trail stays complete.
            </p>
        </td>
    </tr>

@endsection
