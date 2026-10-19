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
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <a href="{{ route('home') }}" style="display:flex;align-items:center;gap:2px;font-size:1.45rem;font-weight:800;letter-spacing:-0.03em;color:#fff;text-decoration:none;">
                TaskVerse<span style="color:var(--primary);font-size:1.8rem;line-height:0;">.</span>
            </a>
            <div class="logo-sub">@yield('sidebar-role', 'Marketplace')</div>
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
            <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
            <div class="topbar-actions" style="display:flex;align-items:center;gap:1rem;">
                <a href="{{ route('buyer.browse') }}" class="btn btn-ghost btn-sm" style="font-weight:600;">
                    <span class="material-icons-round" style="font-size:18px;">explore</span> Browse Gigs
                </a>

                @if(auth()->user()->role === 'seller')
                    <a href="{{ route('buyer.browse') }}" class="btn btn-outline btn-sm" style="font-size:0.8rem;border-radius:4px;">
                        Switch to Buying
                    </a>
                @else
                    <a href="{{ route('seller.dashboard') }}" class="btn btn-outline btn-sm" style="font-size:0.8rem;border-radius:4px;">
                        Switch to Selling
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
    </div>
</div>
@stack('scripts')
</body>
</html>
