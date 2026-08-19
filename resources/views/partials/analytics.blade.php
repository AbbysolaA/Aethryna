{{--
    Cookieless analytics.

    Cloudflare Web Analytics, chosen over Microsoft Clarity because it sets no
    cookies and stores no identifier of any kind. That is not a minor
    difference: cookie-based analytics needs a consent banner under PECR, and a
    banner is a tax paid by every visitor — most of all on a phone, which is
    what this audience uses. Cookieless means the numbers arrive without asking
    anybody to dismiss anything.

    What it cannot do is follow a person between visits, so there is no "unique
    users over 30 days" figure. Page views, referrers, countries and Core Web
    Vitals, and nothing that identifies anyone. For measuring whether the
    assessment gets found, that is enough.

    Two ways to turn it on, and only ever use one:

      1. Cloudflare dashboard → Web Analytics → enable for skillscoop.org.
         The site is proxied through Cloudflare already, so the beacon is
         injected at the edge and nothing here is needed. Leave the token unset.

      2. Set CLOUDFLARE_ANALYTICS_TOKEN in .env and this renders the beacon.
         Use this if automatic injection is off, or if the site ever moves off
         Cloudflare.

    Doing both double-counts every page view.
--}}
@if ($analyticsToken = config('services.cloudflare_analytics.token'))
    <script defer
            src="https://static.cloudflareinsights.com/beacon.min.js"
            data-cf-beacon='{"token": "{{ $analyticsToken }}"}'></script>
@endif
