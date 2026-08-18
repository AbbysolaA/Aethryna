{{--
    Flash messages for admin screens that had none.

    Several controllers redirect with a message that no view rendered: deleting
    a user, a pathway, a question or an assessment sent back "…deleted
    successfully" and the page simply reloaded looking unchanged, so the only
    way to know whether the action worked was to look for the row.

    Included by screens that do not already render their own flash block.
--}}
@if (session('status') || session('success') || session('error'))
    <div class="ath-container ad-flash-wrap">
        @if (session('status') || session('success'))
            <div class="ad-flash ad-flash-ok" role="status">
                {{ session('status') ?? session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="ad-flash ad-flash-err" role="alert">
                {{ session('error') }}
            </div>
        @endif
    </div>

    @push('styles')
    <style>
        .ad-flash-wrap { margin-top: 20px; }
        .ad-flash {
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 12px;
        }
        .ad-flash-ok {
            background: rgba(3, 139, 137, 0.1);
            border: 1px solid rgba(3, 139, 137, 0.25);
            color: #055860;
        }
        .ad-flash-err {
            background: rgba(200, 60, 40, 0.08);
            border: 1px solid rgba(200, 60, 40, 0.25);
            color: #9b2c1c;
        }
    </style>
    @endpush
@endif
