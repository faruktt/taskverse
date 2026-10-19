<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — TaskVerse Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-text">TaskVerse</div>
            <div class="logo-sub" style="color:#fd79a8;">Admin Panel</div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section-label">Management</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="material-icons-round">dashboard</span> Dashboard
            </a>
            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <span class="material-icons-round">people</span> Users
            </a>
            <a href="{{ route('admin.gigs') }}" class="nav-link {{ request()->routeIs('admin.gigs') ? 'active' : '' }}">
                <span class="material-icons-round">work</span> Gigs
            </a>
            <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <span class="material-icons-round">receipt_long</span> Orders
            </a>
            <a href="{{ route('admin.categories') }}" class="nav-link {{ request()->routeIs('admin.categories') ? 'active' : '' }}">
                <span class="material-icons-round">category</span> Categories
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="user-pill">
                <img src="{{ 'https://ui-avatars.com/api/?name=' . urlencode(auth('admin')->user()->name) . '&background=fd79a8&color=fff' }}" alt="" class="avatar avatar-sm">
                <div>
                    <div class="user-name">{{ auth('admin')->user()->name }}</div>
                    <div class="user-role" style="color:#fd79a8;">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm w-full" style="justify-content:center;">
                    <span class="material-icons-round">logout</span> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="topbar-title">@yield('page-title', 'Admin')</div>
            <div class="topbar-actions">@yield('topbar-actions')</div>
        </header>
        <main class="page-content">
            @if(session('success'))
                <div class="alert alert-success fade-up"><span class="material-icons-round">check_circle</span>{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error fade-up"><span class="material-icons-round">error</span>{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
