<!DOCTYPE html>
<html lang="en-GB">
<head>
    @include('partials.gtm-head')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary Meta Tags -->
    <title>@yield('title', 'Skills Co-op | Empowering Youth Through Digital Skills & Mentorship')</title>
    <meta name="title" content="@yield('meta_title', 'Skills Co-op | Empowering Youth Through Digital Skills & Mentorship')">
    <meta name="description" content="@yield('meta_description', 'Skills Co-op is a funded 25-week digital skills programme for people facing barriers to employment. Four pilot tracks, three certificates, and a founding cohort of thirty places from January 2027.')">
    <meta name="keywords" content="@yield('meta_keywords', 'youth empowerment, digital skills training, tech mentorship, career development, nonprofit organization, coding bootcamp, web development, IT support, digital design')">
    <meta name="author" content="Skills Co-op">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        teal: '#055860',
                        gold: '#ee9d1d',
                        'dark-gray': '#404952',
                        light: '#F8FBFB',
                    }
                }
            }
        }
    </script>

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) ?: '1' }}">
    <link rel="stylesheet" href="{{ asset('css/aethryna.css') }}?v={{ @filemtime(public_path('css/aethryna.css')) ?: '1' }}">

    @stack('styles')

</head>
<body class="font-sans antialiased bg-dark-gray text-light">
    @include('partials.gtm-body')
    <!-- Navigation -->
    @include('layouts.navigation')
    <div class="pt-20"></div>
    <!-- Auth Page Hero Section -->
    <section class="min-h-screen bg-gradient-to-b from-[#055860] to-[#038b89] flex items-center justify-center relative overflow-hidden pt-20 text-light py-10">

        <!-- Floating Shapes -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute w-20 h-20 rounded-full bg-gradient-to-br from-teal to-gold opacity-10 animate-float" style="top: 10%; left: 10%; animation-delay: 0s;"></div>
            <div class="absolute w-15 h-15 rounded-full bg-gradient-to-br from-teal to-gold opacity-10 animate-float" style="top: 20%; right: 15%; animation-delay: 2s;"></div>
            <div class="absolute w-25 h-25 rounded-full bg-gradient-to-br from-teal to-gold opacity-10 animate-float" style="bottom: 15%; left: 20%; animation-delay: 4s;"></div>
        </div>

        <div class="auth-hero-content w-full">
            <div class="auth-container max-w-6xl mx-auto px-2 w-full">
                <div class="auth-wrapper grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-16 items-stretch min-h-[calc(100vh-80px)] w-full">

                    <!-- Auth Form Section -->
                    <div class="auth-form-section flex justify-center items-center">
                        <div class="auth-card bg-dark-gray/95 backdrop-blur-custom rounded-xl p-12 shadow-lg border border-white/10 w-full max-w-md h-fit flex flex-col">
                            <div class="auth-header text-center mb-8">
                                <h1 class="text-4xl font-extrabold mb-4 text-gradient-gold">@yield('auth-title', 'Welcome Back')</h1>
                                <p class="text-white text-lg leading-relaxed">@yield('auth-subtitle', 'Sign in to your account to continue your journey')</p>
                            </div>

                            @yield('auth-content')
                        </div>
                    </div>

                    <!-- Auth Caption Section -->
                    <div class="auth-caption-section flex justify-center items-center py-8 lg:order-2">
                        <div class="auth-caption text-center text-light max-w-md">
                            <h2 class="text-5xl font-extrabold mb-6 text-gradient">@yield('caption-title', 'Your Place in the Founding Cohort')</h2>
                            <p class="text-xl leading-relaxed text-white mb-8">@yield('caption-text', 'Skills Co-op is a fully funded 25-week programme for people the traditional pipeline was never designed for. Cohort 1 launches January 2027 with thirty founding places.')</p>
                            <div class="caption-stats flex justify-around gap-8 mt-8">
                                <div class="stat-item text-center">
                                    <span class="stat-number block text-4xl font-extrabold text-gold mb-2" style="text-shadow: 0 0 20px rgba(238, 157, 29, 0.3);">25</span>
                                    <span class="stat-label text-sm text-white/90 font-medium">weeks, three certificates</span>
                                </div>
                                <div class="stat-item text-center">
                                    <span class="stat-number block text-4xl font-extrabold text-gold mb-2" style="text-shadow: 0 0 20px rgba(238, 157, 29, 0.3);">4</span>
                                    <span class="stat-label text-sm text-white/90 font-medium">pilot tracks</span>
                                </div>
                                <div class="stat-item text-center">
                                    <span class="stat-number block text-4xl font-extrabold text-gold mb-2" style="text-shadow: 0 0 20px rgba(238, 157, 29, 0.3);">100%</span>
                                    <span class="stat-label text-sm text-white/90 font-medium">funded places</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br/>
<br />
    </section>

    @include('layouts.footer')

    @stack('scripts')
</body>
</html>
