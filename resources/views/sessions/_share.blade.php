{{-- Share row for a panel. Expects $session; optional $label.
     Plain share links on purpose: no tracking scripts, and the URL being
     passed around is the panel's own page, which keeps working as the
     archive after the event. --}}
@php
    $shareUrl  = $session->url();
    $shareText = $session->tagline.' | a free online panel from Skills Co-op';
@endphp
<div class="ss-share">
    <span class="ss-share-lbl">{{ $label ?? 'Share this panel' }}</span>
    <div class="ss-share-row">
        <a class="ss-share-btn" target="_blank" rel="noopener"
           href="https://wa.me/?text={{ urlencode($shareText.' '.$shareUrl) }}">
            <i class="fab fa-whatsapp"></i> WhatsApp
        </a>
        <a class="ss-share-btn" target="_blank" rel="noopener"
           href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}">
            <i class="fab fa-linkedin-in"></i> LinkedIn
        </a>
        <a class="ss-share-btn" target="_blank" rel="noopener"
           href="https://twitter.com/intent/tweet?text={{ urlencode($shareText) }}&url={{ urlencode($shareUrl) }}">
            <i class="fab fa-x-twitter"></i> X
        </a>
        <a class="ss-share-btn"
           href="mailto:?subject={{ rawurlencode('Join me at '.$session->tagline) }}&body={{ rawurlencode('I am going to this free online panel from Skills Co-op and thought of you: '.$session->tagline.'. The speakers and the registration form are here: '.$shareUrl) }}">
            <i class="far fa-envelope"></i> Invite a friend
        </a>
        <button type="button" class="ss-share-btn ss-share-copy" data-share-url="{{ $shareUrl }}">
            <i class="far fa-copy"></i> Copy link
        </button>
    </div>
</div>

@once
    @push('styles')
    <style>
        .ss-share { margin-top: 22px; }
        .ss-share-strip { padding: 6px 0 36px; }
        .ss-share-lbl {
            display: block; margin-bottom: 10px;
            font-family: var(--font-mono, ui-monospace, monospace);
            font-size: 0.68rem; letter-spacing: 1.4px; text-transform: uppercase;
            color: #8a939c;
        }
        .ss-share-row { display: flex; flex-wrap: wrap; gap: 10px; }
        .ss-share-btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 16px; border-radius: 100px;
            border: 1px solid rgba(3, 139, 137, 0.3); background: transparent;
            color: var(--ath-deep, #055860); font: inherit; font-size: 0.88rem;
            font-weight: 600; text-decoration: none; cursor: pointer;
            transition: background 0.15s ease;
        }
        .ss-share-btn:hover { background: rgba(3, 139, 137, 0.08); }
    </style>
    @endpush
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.ss-share-copy').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var done = function () {
                        var original = btn.innerHTML;
                        btn.innerHTML = 'Copied';
                        setTimeout(function () { btn.innerHTML = original; }, 1600);
                    };
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(btn.dataset.shareUrl).then(done, done);
                    } else {
                        window.prompt('Copy this link:', btn.dataset.shareUrl);
                    }
                });
            });
        });
    </script>
    @endpush
@endonce
