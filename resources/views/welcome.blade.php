<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TaskVerse — Freelance Services Marketplace</title>
    <meta name="description" content="Find the perfect freelance services for your business. High quality, vetted talent in web development, design, AI and marketing.">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Google Material Icons Round -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- Production App CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

    <!-- 1. Top Navbar (Fiverr Style Header) -->
    <header class="site-header">
        <div class="navbar-main">
            <div style="display:flex;align-items:center;gap:1rem;">
                <button type="button" class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle Menu">
                    <span class="material-icons-round">menu</span>
                </button>
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="logo-brand">
                    TaskVerse<span class="brand-dot">.</span>
                </a>
            </div>

            <!-- Search in Header -->
            <form action="{{ route('buyer.browse') }}" method="GET" class="header-search">
                <span class="material-icons-round search-icon">search</span>
                <input type="text" name="q" placeholder="What service are you looking for today?">
                <button type="submit">Search</button>
            </form>

            <!-- Navigation Links -->
            <nav class="nav-links" id="siteNavLinks">
                <a href="{{ route('buyer.browse') }}" style="color:var(--dark);">Explore</a>

                @auth
                    @if(auth()->user()->role === 'seller')
                        <a href="{{ route('seller.dashboard') }}" style="color:var(--primary);font-weight:700;">
                            <span class="material-icons-round" style="font-size:18px;">dashboard</span> Seller Hub
                        </a>
                    @else
                        <a href="{{ route('buyer.dashboard') }}" style="color:var(--primary);font-weight:700;">
                            <span class="material-icons-round" style="font-size:18px;">dashboard</span> Dashboard
                        </a>
                        <a href="{{ route('seller.dashboard') }}" style="color:var(--muted);">Become a Seller</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-sm" title="Sign Out">
                            <span class="material-icons-round" style="font-size:18px;">logout</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('register', ['role' => 'seller']) }}" style="color:var(--muted);">Become a Seller</a>
                    <a href="{{ route('login') }}" style="color:var(--dark);">Sign in</a>
                    <a href="{{ route('register') }}" class="btn btn-outline btn-sm" style="border-radius:4px;padding:0.45rem 1.1rem;">Join</a>
                @endauth
            </nav>
        </div>

        @php
            $categories = \App\Models\Category::where('is_active', true)->withCount('gigs')->get();
            $featuredGigs = \App\Models\Gig::where('status', 'active')->with(['seller','category'])->latest()->take(8)->get();
        @endphp

        <!-- 2. Secondary Categories Bar -->
        <div class="categories-bar">
            <ul class="categories-list">
                @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('buyer.browse', ['category' => $cat->id]) }}">
                            <span class="material-icons-round" style="font-size:17px;color:{{ $cat->color }};">{{ $cat->icon }}</span>
                            {{ $cat->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </header>

    <!-- 3. Fiverr-style Hero Section -->
    <section class="hero-fiverr">
        <div class="hero-inner">
            <h1>
                Find the right <em>freelance</em> service, right away
            </h1>

            <!-- Big Search Box -->
            <form action="{{ route('buyer.browse') }}" method="GET" class="hero-search-box">
                <div class="search-prefix">
                    <span class="material-icons-round">search</span>
                </div>
                <input type="text" name="q" placeholder="Search for any service... (e.g. website design, logo, AI agent)">
                <button type="submit">Search</button>
            </form>

            <!-- Popular Search Tags -->
            <div class="hero-popular">
                <span>Popular:</span>
                <a href="{{ route('buyer.browse', ['q' => 'Website']) }}" class="popular-tag">Website Design</a>
                <a href="{{ route('buyer.browse', ['q' => 'Laravel']) }}" class="popular-tag">Laravel</a>
                <a href="{{ route('buyer.browse', ['q' => 'Figma']) }}" class="popular-tag">UI/UX Design</a>
                <a href="{{ route('buyer.browse', ['q' => 'AI']) }}" class="popular-tag">AI Services</a>
                <a href="{{ route('buyer.browse', ['q' => '3D']) }}" class="popular-tag">3D Animation</a>
            </div>
        </div>
    </section>

    <!-- 4. Trusted By Bar -->
    <div class="trust-bar">
        <div class="trust-inner">
            <span>Trusted by:</span>
            <div class="trust-logos">
                <span>Meta</span>
                <span>Google</span>
                <span>NETFLIX</span>
                <span>P&amp;G</span>
                <span>PayPal</span>
            </div>
        </div>
    </div>

    <!-- 5. Popular Categories Showcase -->
    <section style="padding:4.5rem 0 3.5rem;">
        <div class="container">
            <div class="section-title-wrap">
                <h2 class="section-title">Popular services</h2>
                <p class="section-subtitle">Explore the most in-demand professional creative and technical fields</p>
            </div>

            <div class="categories-showcase">
                @foreach($categories as $cat)
                    <a href="{{ route('buyer.browse', ['category' => $cat->id]) }}" class="fiverr-cat-card">
                        <div class="cat-icon" style="background:{{ $cat->color }}18;color:{{ $cat->color }};">
                            <span class="material-icons-round">{{ $cat->icon }}</span>
                        </div>
                        <div>
                            <h4>{{ $cat->name }}</h4>
                            <p>{{ $cat->gigs_count }} {{ Str::plural('service', $cat->gigs_count) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 6. Popular Professional Gigs (Fiverr Architecture) -->
    <section style="padding:1rem 0 5rem;">
        <div class="container">
            <div class="section-title-wrap flex justify-between items-center">
                <div>
                    <h2 class="section-title">Popular professional services</h2>
                    <p class="section-subtitle">Hand-picked services with top ratings and fast delivery</p>
                </div>
                <a href="{{ route('buyer.browse') }}" class="btn btn-secondary btn-sm" style="font-weight:700;">
                    See All Services <span class="material-icons-round" style="font-size:16px;">chevron_right</span>
                </a>
            </div>

            <div class="gigs-grid">
                @foreach($featuredGigs as $gig)
                    <div class="gig-card">
                        <!-- Thumbnail -->
                        <a href="{{ route('buyer.gig.show', $gig) }}" class="gig-thumb-wrap">
                            @if($gig->thumbnail)
                                <img src="{{ asset('storage/' . $gig->thumbnail) }}" alt="{{ $gig->title }}">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f1f5f9,#e2e8f0);color:#64748b;">
                                    <span class="material-icons-round" style="font-size:42px;opacity:0.5;">photo_camera</span>
                                </div>
                            @endif
                            <span class="gig-cat-badge">
                                {{ $gig->category->name ?? 'Service' }}
                            </span>
                        </a>

                        <!-- Content -->
                        <div class="gig-content">
                            <!-- Seller Row -->
                            <div class="gig-seller-row">
                                @if($gig->seller && $gig->seller->avatar)
                                    <img src="{{ asset('storage/' . $gig->seller->avatar) }}" alt="" class="seller-avatar">
                                @else
                                    <div class="seller-avatar-fallback">
                                        {{ strtoupper(substr($gig->seller->name ?? 'S', 0, 1)) }}
                                    </div>
                                @endif
                                <div class="seller-info">
                                    <div class="seller-name">{{ $gig->seller->name ?? 'Verified Seller' }}</div>
                                    <div class="seller-level">Level 2 Seller</div>
                                </div>
                            </div>

                            <!-- Gig Title -->
                            <a href="{{ route('buyer.gig.show', $gig) }}">
                                <h3 class="gig-title">{{ $gig->title }}</h3>
                            </a>

                            <!-- Rating -->
                            <div class="gig-rating-row">
                                <span class="material-icons-round star-icon">star</span>
                                <span class="rating-score">{{ number_format($gig->avg_rating, 1) }}</span>
                                <span class="rating-count">({{ $gig->total_reviews }})</span>
                            </div>

                            <!-- Footer Price -->
                            <div class="gig-footer">
                                <span class="material-icons-round favorite-btn">favorite_border</span>
                                <div class="gig-price-box">
                                    <span class="from-label">From</span>
                                    <span class="price-amount">${{ number_format($gig->price_basic, 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 7. Value Propositions: A whole world of freelance talent -->
    <section class="fiverr-value-section">
        <div class="container">
            <div class="section-title-wrap text-center" style="margin-bottom:3.5rem;">
                <h2 class="section-title">A whole world of freelance talent at your fingertips</h2>
                <p class="section-subtitle">Scale your team with on-demand experts for every project phase</p>
            </div>

            <div class="value-grid">
                <div class="value-card">
                    <h3><span class="material-icons-round" style="color:var(--primary);">category</span> Over 700 categories</h3>
                    <p>Get results from skilled freelancers from all over the world, for every task, at any price point.</p>
                </div>
                <div class="value-card">
                    <h3><span class="material-icons-round" style="color:var(--primary);">price_check</span> Clear, upfront pricing</h3>
                    <p>No hourly rates, just project-based pricing. Payments only released when you approve the completed work.</p>
                </div>
                <div class="value-card">
                    <h3><span class="material-icons-round" style="color:var(--primary);">bolt</span> Quality work done quickly</h3>
                    <p>Filter to find the right freelancer in minutes and get your project delivered fast and error-free.</p>
                </div>
                <div class="value-card">
                    <h3><span class="material-icons-round" style="color:var(--primary);">support_agent</span> 24/7 round-the-clock support</h3>
                    <p>Questions? Our customer success team is available round the clock to assist anytime, anywhere.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. Fiverr Pro Style Enterprise Banner -->
    <div class="container">
        <div class="pro-banner">
            <div>
                <span class="pro-badge">TaskVerse Pro</span>
                <h2>Vetted freelance talent for businesses</h2>
                <p>Connect with top 1% verified creators, dedicated account support, and team collaboration workflows.</p>
            </div>
            <div>
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg" style="border-radius:6px;">
                    Get Started Free
                </a>
            </div>
        </div>
    </div>

    <!-- 9. Clean Production Footer -->
    <footer class="site-footer">
        <div class="footer-cols">
            <div class="footer-col">
                <h5>Categories</h5>
                <ul>
                    @foreach($categories->take(5) as $cat)
                        <li><a href="{{ route('buyer.browse', ['category' => $cat->id]) }}">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="footer-col">
                <h5>About</h5>
                <ul>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Press &amp; News</a></li>
                    <li><a href="#">Partnerships</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h5>Support &amp; Community</h5>
                <ul>
                    <li><a href="#">Help &amp; Support</a></li>
                    <li><a href="#">Trust &amp; Safety</a></li>
                    <li><a href="#">Selling on TaskVerse</a></li>
                    <li><a href="#">Buying on TaskVerse</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h5>Portals</h5>
                <ul>
                    <li><a href="{{ route('login') }}">Member Sign In</a></li>
                    <li><a href="{{ route('register', ['role' => 'seller']) }}">Become a Seller</a></li>
                    <li><a href="{{ route('buyer.browse') }}">Browse Catalog</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div style="display:flex;align-items:center;gap:8px;font-weight:800;font-size:1.15rem;color:var(--dark);">
                TaskVerse<span style="color:var(--primary);font-size:1.4rem;line-height:0;">.</span>
                <span style="font-size:0.85rem;font-weight:400;color:var(--muted);margin-left:12px;">&copy; {{ date('Y') }} TaskVerse International Ltd.</span>
            </div>
            <div style="font-size:0.88rem;color:var(--muted);font-weight:500;">
                Developed by <a href="https://faruk.stsoft.top/" target="_blank" rel="noopener noreferrer" style="color:var(--primary);font-weight:700;text-decoration:none;">FARUK</a>
            </div>
            <div style="display:flex;align-items:center;gap:1.5rem;">
                <span style="display:flex;align-items:center;gap:4px;"><span class="material-icons-round" style="font-size:18px;">language</span> English</span>
                <span style="display:flex;align-items:center;gap:4px;"><span class="material-icons-round" style="font-size:18px;">attach_money</span> USD</span>
            </div>
        </div>
    </footer>

    <!-- Fiverr-Style Mobile Bottom Navigation Bar -->
    <nav class="mobile-bottom-nav">
        <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <span class="material-icons-round">home</span>
            <span>Home</span>
        </a>
        <a href="{{ route('buyer.browse') }}" class="mobile-nav-item {{ request()->routeIs('buyer.browse') ? 'active' : '' }}">
            <span class="material-icons-round">explore</span>
            <span>Explore</span>
        </a>
        @auth
            @if(auth()->user()->role === 'seller')
                <a href="{{ route('seller.dashboard') }}" class="mobile-nav-item">
                    <span class="material-icons-round">dashboard</span>
                    <span>Hub</span>
                </a>
                <a href="{{ route('seller.orders') }}" class="mobile-nav-item">
                    <span class="material-icons-round">receipt_long</span>
                    <span>Orders</span>
                </a>
            @else
                <a href="{{ route('buyer.dashboard') }}" class="mobile-nav-item">
                    <span class="material-icons-round">dashboard</span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('buyer.orders') }}" class="mobile-nav-item">
                    <span class="material-icons-round">shopping_bag</span>
                    <span>Orders</span>
                </a>
            @endif
        @else
            <a href="{{ route('login') }}" class="mobile-nav-item {{ request()->routeIs('login') ? 'active' : '' }}">
                <span class="material-icons-round">login</span>
                <span>Sign In</span>
            </a>
            <a href="{{ route('register') }}" class="mobile-nav-item {{ request()->routeIs('register') ? 'active' : '' }}">
                <span class="material-icons-round">person_add</span>
                <span>Join</span>
            </a>
        @endauth
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleBtn = document.getElementById('mobileMenuBtn');
            var navLinks = document.getElementById('siteNavLinks');
            if (toggleBtn && navLinks) {
                toggleBtn.addEventListener('click', function() {
                    navLinks.classList.toggle('open');
                });
            }
        });
    </script>
</body>
</html>
