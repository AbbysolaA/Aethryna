{{--
    Site-wide structured data.

    Declares the organisation once, on every page, and names its founder. The
    founder link is the point: a name query has no reason to surface Skills
    Co-op unless something machine-readable connects the two, and a person is
    far more likely to be searched for by name than the organisation is.

    Kept to Organization and WebSite here. Person detail lives on the about
    page, where there is actual content about each person to back it up;
    asserting it site-wide on pages with no such content is the kind of thing
    search engines discount.
--}}
@php
    $org    = config('organisation');
    $people = collect($org['people'] ?? []);
    $founder = $people->firstWhere('founder', true);
@endphp

<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type'    => ['Organization', 'EducationalOrganization'],
    '@id'      => $org['url'] . '/#organisation',
    'name'          => $org['name'],
    'legalName'     => $org['legal_name'],
    'alternateName' => $org['alternate_name'] ?? null,
    'foundingDate'  => $org['founding_date'] ?? null,
    'url'           => $org['url'],
    'logo'          => $org['logo'],
    'email'         => $org['email'],
    'description'   => $org['description'],
    'areaServed'    => $org['area_served'],
    'address'      => array_filter([
        '@type'           => 'PostalAddress',
        'addressLocality' => $org['locality'] ?? null,
        'addressCountry'  => $org['country'] ?? null,
    ]),
    'sameAs'  => $org['same_as'] ?? [],
    'founder' => $founder ? array_filter([
        '@type'         => 'Person',
        '@id'           => $org['url'] . '/about#' . \Illuminate\Support\Str::slug($founder['name']),
        'name'          => $founder['name'],
        'alternateName' => $founder['alternate_name'] ?? null,
        'jobTitle'      => $founder['job_title'] ?? null,
        'url'           => $founder['url'] ?? null,
        'sameAs'        => $founder['same_as'] ?? [],
    ]) : null,
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'WebSite',
    '@id'      => $org['url'] . '/#website',
    'url'      => $org['url'],
    'name'     => $org['name'],
    'inLanguage' => 'en-GB',
    'publisher' => ['@id' => $org['url'] . '/#organisation'],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

{{--
    The page itself.

    Organization and WebSite say who publishes this site; neither says what
    the page in front of the reader is about. An assistant summarising a
    single page has the title and meta description in the head already, but
    nothing tying them to the site entity — this node is that link, and it
    reuses the same @yield values rather than restating them, so the schema
    cannot contradict the meta tags.
--}}
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context'    => 'https://schema.org',
    '@type'       => 'WebPage',
    '@id'         => url()->current() . '#webpage',
    'url'         => url()->current(),
    'name'        => trim($__env->yieldContent('title', $org['name'])) ?: $org['name'],
    'description' => trim($__env->yieldContent('meta_description', $org['description'])) ?: $org['description'],
    'inLanguage'  => 'en-GB',
    'isPartOf'    => ['@id' => $org['url'] . '/#website'],
    'about'       => ['@id' => $org['url'] . '/#organisation'],
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

@stack('structured-data')
