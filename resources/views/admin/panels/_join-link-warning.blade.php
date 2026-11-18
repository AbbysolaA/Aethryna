{{--
    Online sessions starting within three days with no joining link.

    The day-before reminder carries the link and goes out whether or not
    one is set. Shown on the dashboard and the panels list, the two screens
    opened most, so the gap is seen before the reminder fires.
--}}
@php($missingJoinLink = \App\Models\PanelSession::missingJoinLinkSoon())

@if ($missingJoinLink->isNotEmpty())
    <div class="jl-warn" role="alert">
        @foreach ($missingJoinLink as $panel)
            <p>
                <strong>{{ $panel->tagline }}</strong> starts {{ $panel->event_date->format('l j F, g.ia') }}
                and has no joining link. The day-before reminder goes out at 5.40pm the day before,
                with or without one.
                <a href="{{ route('admin.panels.edit', $panel) }}">Add the link now</a>
            </p>
        @endforeach
    </div>

    @once
        @push('styles')
        <style>
            .jl-warn {
                margin: 20px 0;
                padding: 14px 18px;
                border-radius: 12px;
                border: 1px solid #e0a63a;
                border-left: 5px solid #ee9d1d;
                background: #fff6e5;
                color: #3d2c06;
            }
            .jl-warn p { margin: 0; line-height: 1.6; }
            .jl-warn p + p { margin-top: 10px; }
            .jl-warn a { color: #055860; font-weight: 700; margin-left: 4px; }
        </style>
        @endpush
    @endonce
@endif
