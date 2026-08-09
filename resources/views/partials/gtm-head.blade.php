{{--
    Google Tag Manager — container loader.

    Goes as high in <head> as it can, which is why it sits above the meta
    block: GTM measures page load, so anything queued ahead of it is time it
    cannot see.

    The ID lives in config/services.php so a local or staging environment can
    empty GOOGLE_TAG_MANAGER_ID and get no container at all, rather than
    polluting production analytics with development traffic.

    Note on cookies: this snippet on its own sets none. Cookies come from the
    tags configured inside the container, so consent obligations attach when a
    GA4 or advertising tag is published, not here. If one is added, Consent
    Mode belongs in front of this script and the cookie and privacy policies
    need updating — both currently state that no analytics platform is used.
--}}
@if ($gtmId = config('services.gtm.id'))
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{{ $gtmId }}');</script>
<!-- End Google Tag Manager -->
@endif
