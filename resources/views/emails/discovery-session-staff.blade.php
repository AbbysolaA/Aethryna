{{-- Internal notification. No layout: this goes to one inbox, is read once,
     and is acted on. Anything decorative would be in the way. --}}
<div style="font-family:-apple-system,Segoe UI,Arial,sans-serif; font-size:15px; line-height:24px; color:#2D353C; max-width:600px;">

    <p style="margin:0 0 18px 0; font-size:13px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:{{ $registration->waitlisted ? '#C77F14' : '#08444A' }};">
        {{ $registration->waitlisted ? 'Waiting list' : 'New registration' }} &middot; Discovery Session
    </p>

    {{-- The notes field leads. It is the only part that might need doing
         something about before the day, and burying it under contact details
         is how an access requirement gets read on the morning. --}}
    @if (filled($registration->notes))
        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#FFF6E0; border-left:4px solid #E8B647; border-radius:4px; margin:0 0 20px 0;">
            <tr>
                <td style="padding:14px 18px;">
                    <p style="margin:0 0 6px 0; font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#C77F14;">They told us</p>
                    <p style="margin:0; white-space:pre-wrap;">{{ $registration->notes }}</p>
                </td>
            </tr>
        </table>
    @endif

    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;">
        <tr>
            <td width="130" style="padding:7px 12px 7px 0; color:#59626A; vertical-align:top;">Name</td>
            <td style="padding:7px 0;"><strong>{{ $registration->name }}</strong></td>
        </tr>
        <tr>
            <td style="padding:7px 12px 7px 0; color:#59626A; vertical-align:top;">Email</td>
            <td style="padding:7px 0;"><a href="mailto:{{ $registration->email }}" style="color:#08444A;">{{ $registration->email }}</a></td>
        </tr>
        <tr>
            <td style="padding:7px 12px 7px 0; color:#59626A; vertical-align:top;">Phone</td>
            <td style="padding:7px 0;">{{ $registration->phone ?: 'Not given' }}</td>
        </tr>
        <tr>
            <td style="padding:7px 12px 7px 0; color:#59626A; vertical-align:top;">Group</td>
            <td style="padding:7px 0;">{{ $registration->audienceLabel() ?: 'Not answered' }}</td>
        </tr>
        <tr>
            <td style="padding:7px 12px 7px 0; color:#59626A; vertical-align:top;">Consented</td>
            <td style="padding:7px 0;">{{ $registration->consented_at?->timezone('Europe/London')->format('j M Y, g.ia') ?: '—' }}</td>
        </tr>
    </table>

    <p style="margin:22px 0 0 0; padding:14px 18px; background:#F7F2E8; border-radius:4px; font-size:14px;">
        @if ($capacity)
            {{-- An expression, not a directive. Blade only compiles @if at a
                 non-word boundary, so "taken@if(...)" renders verbatim. --}}
            <strong>{{ $confirmed }} of {{ $capacity }}</strong> places taken{{ $spacesLeft !== null ? ', '.$spacesLeft.' left' : '' }}.
            @if ($waitlistCount)
                <br>{{ $waitlistCount }} {{ $waitlistCount === 1 ? 'person' : 'people' }} on the waiting list.
            @endif
        @else
            <strong>{{ $confirmed }}</strong> registered.
        @endif
    </p>

    <p style="margin:20px 0 0 0; font-size:13px; color:#59626A;">
        Reply to this email to go straight back to {{ $registration->firstName() }}.
    </p>
</div>
