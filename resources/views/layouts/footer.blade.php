<footer class="site-footer">
    <div class="footer-top">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Brand Info -->
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="{{ asset('images/logo_white.png') }}" alt="SkillsCo-op">
                    </div>
                    <p class="brand-desc">
                        Widening access to digital skills and meaningful career progression for underserved communities.
                        Join us in widening access to digital careers.
                    </p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/share/1VF3yxZ4dR/?mibextid=wwXIfr" class="social-icon"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="https://www.tiktok.com/@aethryna?_r=1&_t=ZN-96qSoF24sJ4" class="social-icon"><i
                                class="fab fa-tiktok"></i></a>
                        <a href="https://www.linkedin.com/company/theskillscoop/" class="social-icon"><i
                                class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.instagram.com/aethrynafoundation?igsh=MWh1YmpwNGd6Nnc0NQ=="
                            class="social-icon"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('pathway') }}">Transformation Pathway</a></li>
                        <li><a href="{{ route('ai-labs') }}">AI Labs</a></li>
                        <li><a href="{{ route('impact') }}">Our Impact</a></li>
                        <li><a href="{{ route('stories') }}">Success Stories</a></li>
                        <li><a href="{{ route('sessions') }}">Sessions & Events</a></li>
                    </ul>
                </div>

                <!-- Programs -->
                <div class="footer-links">
                    <h4>Learning Tracks</h4>
                    <ul>
                        <li><a href="{{ route('programs') }}#project-product">Project and Product Delivery</a></li>
                        <li><a href="{{ route('programs') }}#data-ai">Data and AI Analytics</a></li>
                        <li><a href="{{ route('programs') }}#digital-design">Digital Design and Marketing</a></li>
                        <li><a href="{{ route('programs') }}#software-dev">Software Development</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-contact">
                    <h4>Contact Us</h4>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>hello@skillscoop.org</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-globe"></i>
                        <span>skillscoop.org</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Liverpool, United Kingdom</span>
                    </div>
                    <div class="newsletter">
                        <div class="newsletter-label">
                            <i class="fas fa-paper-plane"></i>
                            <p>Join our waitlist</p>
                        </div>
                        <form class="newsletter-form" action="{{ route('waitlist.store') }}" method="POST">
                            @csrf
                            <input type="email" name="email" placeholder="Your email address" required>
                            <button type="submit"><i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-container">
            <p class="company-details">
                SkillsCo-op is the trading name of <strong>Aethryna Digital Skills Co-op CIC</strong>, a Community Interest Company registered in England and Wales. Company No. <strong>17007317</strong>. Registered office: Unit A 82 James Carter Road, Mildenhall, United Kingdom IP28 7DE.
            </p>
            <div class="bottom-content">
                <p>&copy; {{ date('Y') }} SkillsCo-op. All Rights Reserved.</p>
                <div class="legal-links">
                    <a href="{{ route('privacy') }}">Privacy Policy</a>
                    <a href="{{ route('terms') }}">Terms of Service</a>
                    <a href="{{ route('cookies') }}">Cookie Policy</a>
                    <a href="{{ route('acceptable-use') }}">Acceptable Use</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .site-footer {
        background: var(--black);
        color: var(--light);
        padding-top: 5rem;
        position: relative;
        z-index: 10;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1.5fr;
        gap: 4rem;
        padding-bottom: 4rem;
    }

    .footer-logo img {
        height: 50px;
        margin-bottom: 1.5rem;
    }

    .brand-desc {
        color: rgba(245, 245, 245, 0.7);
        line-height: 1.8;
        margin-bottom: 2rem;
        font-size: 0.95rem;
    }

    .social-links {
        display: flex;
        gap: 1rem;
    }

    .social-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: var(--white);
        text-decoration: none;
        transition: var(--transition);
    }

    .social-icon:hover {
        background: var(--gold);
        color: var(--black);
        transform: translateY(-5px);
    }

    .footer-links h4,
    .footer-contact h4 {
        color: var(--gold);
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 2rem;
        position: relative;
    }

    .footer-links h4::after,
    .footer-contact h4::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 30px;
        height: 2px;
        background: var(--teal);
    }

    .footer-links ul {
        list-style: none;
        padding: 0;
    }

    .footer-links li {
        margin-bottom: 1rem;
    }

    .footer-links a {
        color: rgba(245, 245, 245, 0.7);
        text-decoration: none;
        transition: var(--transition);
        font-size: 0.95rem;
    }

    .footer-links a:hover {
        color: var(--teal);
        padding-left: 5px;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        color: rgba(245, 245, 245, 0.7);
    }

    .contact-item i {
        color: var(--teal);
    }

    .newsletter {
        margin-top: 2rem;
    }

    .newsletter-label {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        color: rgba(245, 245, 245, 0.9);
        font-family: 'Outfit', sans-serif;
    }

    .newsletter-label i {
        color: var(--teal);
        width: 16px;
        text-align: center;
        font-size: 0.9rem;
    }

    .newsletter-label p {
        margin: 0;
        font-weight: 600;
        font-size: 1rem;
        line-height: 1;
        letter-spacing: 0.5px;
    }

    .newsletter-form {
        display: flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0.3rem;
        border-radius: 50px;
    }

    .newsletter-form input {
        background: transparent;
        border: none;
        padding: 0.6rem 1rem;
        color: white;
        width: 100%;
        outline: none;
    }

    .newsletter-form button {
        background: var(--teal);
        color: white;
        border: none;
        width: 45px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .newsletter-form button:hover {
        background: var(--gold);
        color: var(--black);
    }

    .footer-bottom {
        background: rgba(0, 0, 0, 0.2);
        padding: 2rem 0;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .company-details {
        color: rgba(245, 245, 245, 0.55);
        font-size: 0.85rem;
        line-height: 1.7;
        max-width: 900px;
        margin: 0 auto 1.5rem;
        text-align: center;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .company-details strong {
        color: rgba(245, 245, 245, 0.8);
        font-weight: 700;
    }

    .bottom-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        color: rgba(245, 245, 245, 0.5);
    }

    .legal-links {
        display: flex;
        gap: 2rem;
    }

    .legal-links a {
        color: rgba(245, 245, 245, 0.5);
        text-decoration: none;
        transition: var(--transition);
    }

    .legal-links a:hover {
        color: var(--teal);
    }

    @media (max-width: 992px) {
        .footer-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 576px) {
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 3rem;
        }

        .bottom-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }
</style>
