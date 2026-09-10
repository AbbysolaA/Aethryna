{{--
    Share row for a post. Plain intent links, no platform SDKs: the share
    buttons Substack renders are exactly these URLs underneath, and script
    versions would put Facebook and friends on a site whose cookie policy
    promises they are not here.

    Expects: $post
--}}
@php
    $shareUrl   = $post->url();
    $shareTitle = $post->title;
@endphp
<div class="bl-share" role="group" aria-label="Share this post">
    <span class="bl-share-label">Share</span>
    <a href="https://twitter.com/intent/tweet?text={{ urlencode($shareTitle) }}&url={{ urlencode($shareUrl) }}"
       target="_blank" rel="noopener">X</a>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}"
       target="_blank" rel="noopener">Facebook</a>
    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}"
       target="_blank" rel="noopener">LinkedIn</a>
    <a href="https://wa.me/?text={{ urlencode($shareTitle.' '.$shareUrl) }}"
       target="_blank" rel="noopener">WhatsApp</a>
    <a href="mailto:?subject={{ rawurlencode($shareTitle) }}&body={{ rawurlencode($shareUrl) }}">Email</a>
    <button type="button" class="bl-copy" data-copy="{{ $shareUrl }}" hidden>Copy link</button>
</div>

@once
    <script>
        // The copy button only exists where the clipboard does, so no-JS and
        // ancient browsers see a complete share row rather than a dead button.
        // Deferred to DOMContentLoaded because this row renders twice on a
        // post and the script tags along with the first one.
        document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.bl-copy').forEach(function (btn) {
            if (!navigator.clipboard) return;
            btn.hidden = false;
            btn.addEventListener('click', function () {
                navigator.clipboard.writeText(btn.dataset.copy).then(function () {
                    var label = btn.textContent;
                    btn.textContent = 'Copied';
                    setTimeout(function () { btn.textContent = label; }, 1600);
                });
            });
        });
        });
    </script>
@endonce
