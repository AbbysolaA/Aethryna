<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary Meta Tags -->
    <title>@yield('title', 'SkillsCo-op | Digital Skills for Real Careers')</title>
    <meta name="title" content="@yield('meta_title', 'SkillsCo-op | Digital Skills for Real Careers')">
    <meta name="description" content="@yield('meta_description', 'SkillsCo-op is a funded 25-week digital skills programme for people facing barriers to employment. Four pilot tracks: Project and Product Delivery, Data and AI Analytics, Product Design and Marketing, and Software Development. Based in Liverpool, open across the UK.')">
    <meta name="keywords" content="@yield('meta_keywords', 'digital skills training, funded programme, Liverpool, career change, NEET, IT support, digital design, data analytics, project management, AI skills, underserved communities')">
    <meta name="author" content="SkillsCo-op">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'SkillsCo-op | Digital Skills for Real Careers')">
    <meta property="og:description" content="@yield('og_description', 'A funded 25-week programme with AI tools embedded throughout. Four tracks, three certificates, one cohort. Applications open for January 2027.')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.png'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="SkillsCo-op">
    <meta property="og:locale" content="en_GB">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('twitter_title', 'SkillsCo-op | Digital Skills for Real Careers')">
    <meta property="twitter:description" content="@yield('twitter_description', 'A funded 25-week programme with AI tools embedded throughout. Four tracks, three certificates, one cohort. Applications open for January 2027.')">
    <meta property="twitter:image" content="@yield('twitter_image', asset('images/og-image.png'))">

    <!-- Additional SEO Meta Tags -->
    <meta name="theme-color" content="#038b89">
    <meta name="msapplication-TileColor" content="#038b89">
    <meta name="apple-mobile-web-app-title" content="SkillsCo-op">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ath-teal': '#038b89',
                        'ath-gold': '#ee9d1d',
                        'ath-deep': '#055860',
                    }
                }
            }
        }
    </script>

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/aethryna.css') }}">

    <style>
        /* Active-page nav indicator (site-wide) */
        .nav-links a.is-active { color: #ee9d1d; position: relative; }
        .nav-links a.is-active::after {
            content: '';
            position: absolute;
            left: 0; right: 0;
            bottom: -6px;
            height: 2px;
            background: #ee9d1d;
            border-radius: 2px;
        }
        #navbar.scrolled .nav-links a.is-active { color: #ee9d1d; }
        .mobile-nav-links a.is-active {
            color: #ee9d1d;
            border-left: 3px solid #ee9d1d;
            padding-left: 12px;
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased">
    @include('layouts.navigation')

    <main class="py-20">
        @yield('content')
    </main>

    @include('layouts.footer')

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
</html>
