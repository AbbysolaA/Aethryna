<!-- Navigation -->
<nav id="navbar">
    <div class="nav-container">
        <div class="logo" id="siteLogo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo_white.png') }}" alt="SkillsCo-op" class="default-logo">
                <img src="{{ asset('images/logo_black.png') }}" alt="SkillsCo-op" class="scrolled-logo" style="display: none;">
            </a>
        </div>

        <div class="nav-links">
            <a href="{{ route('about') }}" @class(['is-active' => request()->routeIs('about')])>About</a>
            <a href="{{ route('pathway') }}" @class(['is-active' => request()->routeIs('pathway')])>Pathway</a>
            <a href="{{ route('programs') }}" @class(['is-active' => request()->routeIs('programs')])>Programs</a>
            <a href="{{ route('impact') }}" @class(['is-active' => request()->routeIs('impact')])>Impact</a>
            <a href="{{ route('stories') }}" @class(['is-active' => request()->routeIs('stories')])>Stories</a>
            <a href="{{ route('sessions') }}" @class(['is-active' => request()->routeIs('sessions')])>Sessions</a>
            <a href="{{ route('partners') }}" @class(['is-active' => request()->routeIs('partners')])>Partner with us</a>
        </div>

        <div class="nav-buttons">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Admin Dashboard</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-outline">Dashboard</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-white btn btn-outline">Login</a>
                <a href="{{ route('register') }}" class="text-white btn btn-primary">Get Started</a>
            @endauth
        </div>

        <div class="mobile-menu" id="mobileMenu">
            <i class="fas fa-bars"></i>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div class="mobile-nav-menu" id="mobileNavMenu">
        <!-- Close Button -->
        <button class="mobile-nav-close" id="mobileNavClose" aria-label="Close navigation menu">
            <i class="fas fa-times"></i>
        </button>

        <div class="mobile-nav-links">
            <a href="{{ route('home') }}" @class(['is-active' => request()->routeIs('home')])>Home</a>
            <a href="{{ route('about') }}" @class(['is-active' => request()->routeIs('about')])>About</a>
            <a href="{{ route('pathway') }}" @class(['is-active' => request()->routeIs('pathway')])>Pathway</a>
            <a href="{{ route('programs') }}" @class(['is-active' => request()->routeIs('programs')])>Programs</a>
            <a href="{{ route('impact') }}" @class(['is-active' => request()->routeIs('impact')])>Impact</a>
            <a href="{{ route('stories') }}" @class(['is-active' => request()->routeIs('stories')])>Stories</a>
            <a href="{{ route('sessions') }}" @class(['is-active' => request()->routeIs('sessions')])>Sessions</a>
            <a href="{{ route('partners') }}" @class(['is-active' => request()->routeIs('partners')])>Partner with us</a>
        </div>
        <div class="mobile-nav-buttons">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Admin Dashboard</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-outline">Dashboard</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
            @endauth
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Navbar Scroll Effect
        const navbar = document.getElementById('navbar');
        const defaultLogo = navbar.querySelector('.default-logo');
        const scrolledLogo = navbar.querySelector('.scrolled-logo');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenu');
        const mobileNavMenu = document.getElementById('mobileNavMenu');
        const mobileNavClose = document.getElementById('mobileNavClose');

        function openMobileMenu() {
            mobileNavMenu.classList.add('active');
            const icon = mobileMenuBtn.querySelector('i');
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        }

        function closeMobileMenu() {
            mobileNavMenu.classList.remove('active');
            const icon = mobileMenuBtn.querySelector('i');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        }
        
        if (mobileMenuBtn && mobileNavMenu) {
            // Toggle menu on hamburger click
            mobileMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (mobileNavMenu.classList.contains('active')) {
                    closeMobileMenu();
                } else {
                    openMobileMenu();
                }
            });

            // Close menu on X button click
            if (mobileNavClose) {
                mobileNavClose.addEventListener('click', (e) => {
                    e.stopPropagation();
                    closeMobileMenu();
                });
            }

            // Close menu when clicking links
            mobileNavMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    closeMobileMenu();
                });
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (mobileNavMenu.classList.contains('active') && 
                    !mobileNavMenu.contains(e.target) && 
                    !mobileMenuBtn.contains(e.target)) {
                    closeMobileMenu();
                }
            });
        }
    });
</script>
