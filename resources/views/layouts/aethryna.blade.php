<!DOCTYPE html>
<html lang="en-GB">
<head>
    @include('partials.gtm-head')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary Meta Tags -->
    <title>@yield('title', 'Skills Co-op | Digital Skills for Real Careers')</title>
    <meta name="title" content="@yield('meta_title', 'Skills Co-op | Digital Skills for Real Careers')">
    <meta name="description" content="@yield('meta_description', 'Skills Co-op is a funded 25-week digital skills programme for people facing barriers to employment. Four pilot tracks: Project and Product Delivery, Data and AI Analytics, Product Design and Marketing, and Software Development. Based in Liverpool, open across the UK.')">
    <meta name="keywords" content="@yield('meta_keywords', 'digital skills training, funded programme, Liverpool, career change, NEET, IT support, digital design, data analytics, project management, AI skills, underserved communities')">
    <meta name="author" content="Skills Co-op">
    {{-- Overridable so pages reached only from a link in an email — an
         unsubscribe confirmation, say — can keep themselves out of the index
         rather than inviting search traffic to a one-person URL. --}}
    <meta name="robots" content="@yield('meta_robots', 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1')">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Skills Co-op | Digital Skills for Real Careers')">
    <meta property="og:description" content="@yield('og_description', 'A funded 25-week programme with AI tools embedded throughout. Four tracks, three certificates, one cohort. Applications open for January 2027.')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Skills Co-op">
    <meta property="og:locale" content="en_GB">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('twitter_title', 'Skills Co-op | Digital Skills for Real Careers')">
    <meta property="twitter:description" content="@yield('twitter_description', 'A funded 25-week programme with AI tools embedded throughout. Four tracks, three certificates, one cohort. Applications open for January 2027.')">
    <meta property="twitter:image" content="@yield('twitter_image', asset('images/og-image.png'))">

    <!-- Additional SEO Meta Tags -->
    <meta name="theme-color" content="#038b89">
    <meta name="msapplication-TileColor" content="#038b89">
    <meta name="apple-mobile-web-app-title" content="Skills Co-op">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Structured data: who the organisation is, and who founded it. --}}
    @include('partials.structured-data')

    {{--
        Everything below is on the critical path, so it is kept deliberately
        short. Each separate host costs a DNS lookup, a TCP connection and a
        TLS handshake before a single byte of CSS arrives — on a phone that is
        a few hundred milliseconds each, paid before anything can be painted.

        This used to reach five hosts: fonts.bunny.net, fonts.googleapis.com,
        cdnjs, unpkg and cdn.tailwindcss.com. It is now two.
    --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

    {{--
        One request for every family the site uses.

        Karla is the body typeface — the stylesheets ask for it 163 times — and
        it was never actually being fetched by any page. Only the email layout
        loaded it, so the whole site had been rendering in whatever the browser
        fell back to. Outfit is the display face, IBM Plex Mono the label face;
        both were loaded per-page by seventeen views between them.
    --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&family=Inter:wght@300;400;600;700;800&display=swap">

    {{-- One Font Awesome, not two. Four pages loaded 6.5.1 on top of the
         6.0.0 loaded here, so those pages fetched two complete icon sets. --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{--
        Compiled Tailwind, not the Play CDN.

        This used to be <script src="https://cdn.tailwindcss.com"></script>:
        a render-blocking script that ships a compiler to the browser and
        generates the stylesheet on the visitor's device, every visit. Tailwind
        documents it as a development convenience and says not to ship it.

        The project has always built the real thing with Vite and never served
        it. The compiled file is a fraction of the size and needs no work from
        the phone that downloads it.
    --}}
    @vite(['resources/css/app.css', 'resources/js/alpine.js'])

    {{-- The site's own stylesheet. Not the Vite copy of the same name, which is
         a different and older file — this is the one in use. --}}
    <link rel="stylesheet" href="{{ asset('css/aethryna.css') }}?v={{ @filemtime(public_path('css/aethryna.css')) ?: '1' }}">

    @stack('styles')
</head>
<body class="font-sans antialiased">
    @include('partials.gtm-body')
    {{-- The nav carries its own <nav>; the banner landmark around it is what
         tells a parser where the page masthead ends and the content begins. --}}
    <header role="banner">
        @include('layouts.navigation')
    </header>

    <main id="main-content" class="py-20">
        @yield('content')
    </main>

    @include('layouts.footer')

    {{-- Alpine is bundled by Vite above. It used to come from unpkg at a
         version range, which had to be resolved before the file could be
         fetched at all. --}}
    @stack('scripts')

    {{-- Cookieless analytics, deferred and last: measurement should never be
         ahead of the page in the queue. Renders nothing unless configured. --}}
    @include('partials.analytics')
</body>
</html>
