<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TaskVerse') — Freelance Marketplace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div class="app-shell">
    <!-- Sidebar Backdrop for Mobile -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="appSidebar">
        <div class="sidebar-logo">
            <div>
                <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:2px;font-size:1.45rem;font-weight:800;letter-spacing:-0.03em;color:#fff;text-decoration:none;">
                    TaskVerse<span style="color:var(--primary);font-size:1.8rem;line-height:0;">.</span>
                </a>
                <div class="logo-sub">@yield('sidebar-role', 'Marketplace')</div>
            </div>
            <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Close Sidebar">
                <span class="material-icons-round">close</span>
            </button>
        </div>
        <nav class="sidebar-nav">
            @yield('sidebar-nav')
        </nav>
        <div class="sidebar-footer">
            <div class="user-pill">
                <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" class="avatar avatar-sm">
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm w-full" style="justify-content:center;color:#94a3b8!important;">
                    <span class="material-icons-round">logout</span> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Open Sidebar">
                    <span class="material-icons-round">menu</span>
                </button>
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
            </div>
            <div class="topbar-actions" style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                <a href="{{ route('buyer.browse') }}" class="btn btn-ghost btn-sm" style="font-weight:600;">
                    <span class="material-icons-round" style="font-size:18px;">explore</span> <span class="hide-mobile">Browse Gigs</span>
                </a>

                @if(auth()->user()->role === 'seller')
                    <a href="{{ route('buyer.browse') }}" class="btn btn-outline btn-sm" style="font-size:0.8rem;border-radius:4px;">
                        <span class="hide-mobile">Switch to Buying</span>
                        <span class="show-mobile-only">Buying</span>
                    </a>
                @else
                    <a href="{{ route('seller.dashboard') }}" class="btn btn-outline btn-sm" style="font-size:0.8rem;border-radius:4px;">
                        <span class="hide-mobile">Switch to Selling</span>
                        <span class="show-mobile-only">Selling</span>
                    </a>
                @endif
                @yield('topbar-actions')
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content">
            @if(session('success'))
                <div class="alert alert-success fade-up">
                    <span class="material-icons-round">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error fade-up">
                    <span class="material-icons-round">error</span>
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>

        <footer style="padding:1.25rem 2rem;border-top:1px solid var(--border);text-align:center;font-size:0.82rem;color:var(--muted);background:#ffffff;margin-top:auto;">
            &copy; {{ date('Y') }} TaskVerse. All rights reserved. &bull; Developed by <a href="https://faruk.stsoft.top/" target="_blank" rel="noopener noreferrer" style="color:var(--primary);font-weight:700;text-decoration:none;">FARUK</a>
        </footer>
    </div>
</div>

<!-- Fiverr-Style Mobile Bottom Navigation Bar -->
@auth
<nav class="mobile-bottom-nav">
    @if(auth()->user()->role === 'seller')
        <a href="{{ route('seller.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
            <span class="material-icons-round">dashboard</span>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('seller.gigs') }}" class="mobile-nav-item {{ request()->routeIs('seller.gigs*') ? 'active' : '' }}">
            <span class="material-icons-round">work</span>
            <span>My Gigs</span>
        </a>
        <a href="{{ route('seller.orders') }}" class="mobile-nav-item {{ request()->routeIs('seller.orders*') || request()->routeIs('seller.order.*') ? 'active' : '' }}">
            <span class="material-icons-round">receipt_long</span>
            <span>Orders</span>
        </a>
        <a href="{{ route('buyer.browse') }}" class="mobile-nav-item {{ request()->routeIs('buyer.browse') ? 'active' : '' }}">
            <span class="material-icons-round">explore</span>
            <span>Market</span>
        </a>
        <a href="javascript:void(0)" class="mobile-nav-item" id="mobileNavDrawerBtn">
            <span class="material-icons-round">menu</span>
            <span>Menu</span>
        </a>
    @else
        <a href="{{ route('buyer.browse') }}" class="mobile-nav-item {{ request()->routeIs('buyer.browse') ? 'active' : '' }}">
            <span class="material-icons-round">explore</span>
            <span>Explore</span>
        </a>
        <a href="{{ route('buyer.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}">
            <span class="material-icons-round">dashboard</span>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('buyer.orders') }}" class="mobile-nav-item {{ request()->routeIs('buyer.orders*') || request()->routeIs('buyer.order.*') ? 'active' : '' }}">
            <span class="material-icons-round">shopping_bag</span>
            <span>Orders</span>
        </a>
        <a href="{{ route('seller.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('seller.*') ? 'active' : '' }}">
            <span class="material-icons-round">storefront</span>
            <span>Selling</span>
        </a>
        <a href="javascript:void(0)" class="mobile-nav-item" id="mobileNavDrawerBtn">
            <span class="material-icons-round">menu</span>
            <span>Menu</span>
        </a>
    @endif
</nav>
@endauth

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggleBtn = document.getElementById('sidebarToggleBtn');
        var closeBtn = document.getElementById('sidebarCloseBtn');
        var sidebar = document.getElementById('appSidebar');
        var backdrop = document.getElementById('sidebarBackdrop');
        var mobileDrawerBtn = document.getElementById('mobileNavDrawerBtn');

        function openSidebar() {
            if (sidebar) sidebar.classList.add('open');
            if (backdrop) backdrop.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            if (sidebar) sidebar.classList.remove('open');
            if (backdrop) backdrop.classList.remove('open');
            document.body.style.overflow = '';
        }

        if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (backdrop) backdrop.addEventListener('click', closeSidebar);
        if (mobileDrawerBtn) mobileDrawerBtn.addEventListener('click', openSidebar);
    });
</script>
@stack('scripts')
</body>
</html>
