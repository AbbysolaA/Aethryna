<!-- Navigation -->
<nav id="navbar">
    <div class="nav-container">
        <div class="logo" id="siteLogo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo_white.png') }}" alt="Skills Co-op" class="default-logo">
                <img src="{{ asset('images/logo_black.png') }}" alt="Skills Co-op" class="scrolled-logo" style="display: none;">
            </a>
        </div>

        {{--
            Ordered by the questions a learner actually asks, in the order they
            ask them, rather than by how the organisation is structured.

              Find your track  — the one action, first, where the eye lands.
                                 The assessment used to be in neither the nav
                                 nor the home page: reachable only if you
                                 happened to open Pathway, Programs or Stories
                                 and spotted a link inside.
              Programs         — "what would I actually learn?"
              Pathway          — "how does the 25 weeks work?"
              Sessions         — "can I meet you before committing?"
              Stories          — "has this worked for someone like me?"
              About            — "who are you?"

            Impact moved into Get involved. It is written for funders and
            partners deciding whether to back the work, which is exactly who
            that menu is for, and it kept a learner-facing nav to six items.
        --}}
        <div class="nav-links">
            <a href="{{ route('assessment.index') }}"
               @class(['nav-link-action', 'is-active' => request()->routeIs('assessment.*')])>Find your track</a>
            <a href="{{ route('programs') }}" @class(['is-active' => request()->routeIs('programs')])>Programs</a>
            <a href="{{ route('pathway') }}" @class(['is-active' => request()->routeIs('pathway')])>Pathway</a>
            <a href="{{ route('sessions') }}" @class(['is-active' => request()->routeIs('sessions')])>Sessions</a>
            <a href="{{ route('stories') }}" @class(['is-active' => request()->routeIs('stories')])>Stories</a>
            <a href="{{ route('about') }}" @class(['is-active' => request()->routeIs('about')])>About</a>

            @php
                $involvedActive = request()->routeIs('partners')
                    || request()->routeIs('mentors')
                    || request()->routeIs('volunteer.apply*')
                    || request()->routeIs('referral.*')
                    || request()->routeIs('impact');
            @endphp
            <div class="nav-dropdown" data-nav-dropdown>
                <button type="button"
                        class="nav-dropdown-toggle @if($involvedActive) is-active @endif"
                        aria-expanded="false"
                        aria-controls="nav-get-involved">
                    Get involved
                    <svg class="nav-dropdown-caret" width="10" height="6" viewBox="0 0 10 6" aria-hidden="true" focusable="false">
                        <path d="M1 1l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="nav-dropdown-menu" id="nav-get-involved" hidden>
                    <a href="{{ route('partners') }}" @class(['is-active' => request()->routeIs('partners')])>
                        <strong>Partner with us</strong>
                        <span>Share a brief or fund a place</span>
                    </a>
                    <a href="{{ route('mentors') }}" @class(['is-active' => request()->routeIs('mentors')])>
                        <strong>Become a mentor</strong>
                        <span>Two hours a month, real impact</span>
                    </a>
                    <a href="{{ route('volunteer.apply') }}" @class(['is-active' => request()->routeIs('volunteer.apply*')])>
                        <strong>Volunteer with us</strong>
                        <span>Delivery, outreach and more</span>
                    </a>
                    {{-- Only while something is actually open. A "Jobs" link to
                         an empty page is a worse answer than no link, and this
                         menu is already five items long. --}}
                    @if (\App\Models\VolunteerRole::paid()->acceptingApplications()->exists())
                        <a href="{{ route('careers.index') }}" @class(['is-active' => request()->routeIs('careers.*')])>
                            <strong>Work with us</strong>
                            <span>Open roles on our team</span>
                        </a>
                    @endif
                    <a href="{{ route('referral.create') }}" @class(['is-active' => request()->routeIs('referral.*')])>
                        <strong>Refer someone</strong>
                        <span>Point us to someone who would benefit</span>
                    </a>
                    <a href="{{ route('impact') }}" @class(['is-active' => request()->routeIs('impact')])>
                        <strong>Our impact</strong>
                        <span>What the programme has changed so far</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="nav-buttons">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Admin Dashboard</a>
                @elseif(auth()->user()->isSafeguardingLead())
                    <a href="{{ route('admin.safeguarding.index') }}" class="btn btn-outline">Safeguarding</a>
                @elseif(auth()->user()->isCoach())
                    <a href="{{ route('coach.dashboard') }}" class="btn btn-outline">Coach Dashboard</a>
                @elseif(auth()->user()->isMentor())
                    <a href="{{ route('mentor.dashboard') }}" class="btn btn-outline">Mentor Dashboard</a>
                @elseif(auth()->user()->isVolunteer())
                    <a href="{{ route('volunteer.index') }}" class="btn btn-outline">My Volunteering</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-outline">Dashboard</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-white btn btn-outline">Login</a>
                <a href="{{ route('register') }}" class="text-white btn btn-primary">Join the cohort</a>
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
            {{-- Same order as the desktop nav. See the note there. --}}
            <a href="{{ route('home') }}" @class(['is-active' => request()->routeIs('home')])>Home</a>
            <a href="{{ route('assessment.index') }}"
               @class(['nav-link-action', 'is-active' => request()->routeIs('assessment.*')])>Find your track</a>
            <a href="{{ route('programs') }}" @class(['is-active' => request()->routeIs('programs')])>Programs</a>
            <a href="{{ route('pathway') }}" @class(['is-active' => request()->routeIs('pathway')])>Pathway</a>
            <a href="{{ route('sessions') }}" @class(['is-active' => request()->routeIs('sessions')])>Sessions</a>
            <a href="{{ route('stories') }}" @class(['is-active' => request()->routeIs('stories')])>Stories</a>
            <a href="{{ route('about') }}" @class(['is-active' => request()->routeIs('about')])>About</a>

            {{-- On mobile there is vertical room, so show the group inline
                 rather than hiding it behind another tap. --}}
            <span class="mobile-nav-heading">Get involved</span>
            <a href="{{ route('partners') }}" @class(['is-active' => request()->routeIs('partners')])>Partner with us</a>
            <a href="{{ route('mentors') }}" @class(['is-active' => request()->routeIs('mentors')])>Become a mentor</a>
            <a href="{{ route('volunteer.apply') }}" @class(['is-active' => request()->routeIs('volunteer.apply*')])>Volunteer with us</a>
            <a href="{{ route('referral.create') }}" @class(['is-active' => request()->routeIs('referral.*')])>Refer someone</a>
            <a href="{{ route('impact') }}" @class(['is-active' => request()->routeIs('impact')])>Our impact</a>
        </div>
        <div class="mobile-nav-buttons">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Admin Dashboard</a>
                @elseif(auth()->user()->isSafeguardingLead())
                    <a href="{{ route('admin.safeguarding.index') }}" class="btn btn-outline">Safeguarding</a>
                @elseif(auth()->user()->isCoach())
                    <a href="{{ route('coach.dashboard') }}" class="btn btn-outline">Coach Dashboard</a>
                @elseif(auth()->user()->isMentor())
                    <a href="{{ route('mentor.dashboard') }}" class="btn btn-outline">Mentor Dashboard</a>
                @elseif(auth()->user()->isVolunteer())
                    <a href="{{ route('volunteer.index') }}" class="btn btn-outline">My Volunteering</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-outline">Dashboard</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Join the cohort</a>
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

        // Desktop "Get involved" dropdown.
        // Opens on click and on hover for mouse users, but click and keyboard
        // are the source of truth so it works without a pointer.
        document.querySelectorAll('[data-nav-dropdown]').forEach(function (dropdown) {
            const toggle = dropdown.querySelector('.nav-dropdown-toggle');
            const menu = dropdown.querySelector('.nav-dropdown-menu');
            if (!toggle || !menu) return;

            let hoverTimer = null;

            function open() {
                menu.hidden = false;
                toggle.setAttribute('aria-expanded', 'true');
                dropdown.classList.add('is-open');
            }

            function close() {
                menu.hidden = true;
                toggle.setAttribute('aria-expanded', 'false');
                dropdown.classList.remove('is-open');
            }

            function isOpen() {
                return toggle.getAttribute('aria-expanded') === 'true';
            }

            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                isOpen() ? close() : open();
            });

            // Hover, with a small close delay so the pointer can cross the gap
            // between the toggle and the panel without it snapping shut.
            dropdown.addEventListener('mouseenter', function () {
                if (window.matchMedia('(hover: hover)').matches) {
                    clearTimeout(hoverTimer);
                    open();
                }
            });
            dropdown.addEventListener('mouseleave', function () {
                if (window.matchMedia('(hover: hover)').matches) {
                    hoverTimer = setTimeout(close, 180);
                }
            });

            // Escape closes and returns focus to the toggle.
            dropdown.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && isOpen()) {
                    close();
                    toggle.focus();
                }
            });

            // Close when focus leaves the dropdown entirely (tabbing past it).
            dropdown.addEventListener('focusout', function (e) {
                if (!dropdown.contains(e.relatedTarget)) close();
            });

            // Close on any click outside.
            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target) && isOpen()) close();
            });
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
