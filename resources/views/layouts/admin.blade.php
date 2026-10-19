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
    <!-- Sidebar Backdrop for Mobile -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <aside class="sidebar" id="appSidebar">
        <div class="sidebar-logo">
            <div>
                <div class="logo-text">TaskVerse</div>
                <div class="logo-sub" style="color:#fd79a8;">Admin Panel</div>
            </div>
            <button type="button" class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="Close Sidebar">
                <span class="material-icons-round">close</span>
            </button>
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
            <a href="{{ route('admin.withdrawals') }}" class="nav-link {{ request()->routeIs('admin.withdrawals*') ? 'active' : '' }}">
                <span class="material-icons-round">account_balance_wallet</span> Withdrawals
                @php
                    $pendingWithdrawalsCount = \App\Models\Withdrawal::where('status', 'pending')->count();
                @endphp
                @if($pendingWithdrawalsCount > 0)
                    <span class="badge badge-warning" style="margin-left:auto;font-size:0.7rem;padding:0.15rem 0.45rem;">{{ $pendingWithdrawalsCount }}</span>
                @endif
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
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Open Sidebar">
                    <span class="material-icons-round">menu</span>
                </button>
                <div class="topbar-title">@yield('page-title', 'Admin')</div>
            </div>
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

        <footer style="padding:1.25rem 2rem;border-top:1px solid var(--border);text-align:center;font-size:0.82rem;color:var(--muted);background:#ffffff;margin-top:auto;">
            &copy; {{ date('Y') }} TaskVerse Admin. &bull; Developed by <a href="https://faruk.stsoft.top/" target="_blank" rel="noopener noreferrer" style="color:var(--primary);font-weight:700;text-decoration:none;">FARUK</a>
        </footer>
    </div>
</div>

<!-- Admin Mobile Bottom Navigation Bar -->
<nav class="mobile-bottom-nav">
    <a href="{{ route('admin.dashboard') }}" class="mobile-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <span class="material-icons-round">dashboard</span>
        <span>Overview</span>
    </a>
    <a href="{{ route('admin.users') }}" class="mobile-nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
        <span class="material-icons-round">people</span>
        <span>Users</span>
    </a>
    <a href="{{ route('admin.orders') }}" class="mobile-nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
        <span class="material-icons-round">receipt_long</span>
        <span>Orders</span>
    </a>
    <a href="{{ route('admin.withdrawals') }}" class="mobile-nav-item {{ request()->routeIs('admin.withdrawals*') ? 'active' : '' }}">
        <span class="material-icons-round">account_balance_wallet</span>
        <span>Payouts</span>
        @php
            $pendingWCount = \App\Models\Withdrawal::where('status', 'pending')->count();
        @endphp
        @if($pendingWCount > 0)
            <span style="position:absolute;top:6px;right:calc(50% - 16px);width:8px;height:8px;border-radius:50%;background:#ef4444;"></span>
        @endif
    </a>
    <a href="javascript:void(0)" class="mobile-nav-item" id="adminMobileDrawerBtn">
        <span class="material-icons-round">menu</span>
        <span>Menu</span>
    </a>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggleBtn = document.getElementById('sidebarToggleBtn');
        var closeBtn = document.getElementById('sidebarCloseBtn');
        var sidebar = document.getElementById('appSidebar');
        var backdrop = document.getElementById('sidebarBackdrop');
        var mobileDrawerBtn = document.getElementById('adminMobileDrawerBtn');

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
