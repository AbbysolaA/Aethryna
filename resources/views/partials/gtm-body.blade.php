{{--
    Google Tag Manager — noscript fallback. Must be the first thing after the
    opening <body> tag. Only fires for visitors with JavaScript disabled.
--}}
@if ($gtmId = config('services.gtm.id'))
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@endif
