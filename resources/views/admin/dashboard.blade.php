@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page-title', 'Platform Overview')

@section('content')
<div class="page-header">
    <h1>Platform Analytics</h1>
    <p>Real-time marketplace activity and operational statistics</p>
</div>

@if(($stats['pending_withdrawals'] ?? 0) > 0)
    <div style="background:#fffbeb;border:1.5px solid #fde047;border-radius:8px;padding:1rem 1.25rem;margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <span class="material-icons-round" style="color:#d97706;font-size:28px;">account_balance_wallet</span>
            <div>
                <strong style="color:#92400e;font-size:0.95rem;">Pending Freelancer Withdrawals</strong>
                <div style="font-size:0.82rem;color:#b45309;">There are {{ $stats['pending_withdrawals'] }} pending payout request(s) awaiting review and approval.</div>
            </div>
        </div>
        <a href="{{ route('admin.withdrawals', ['status' => 'pending']) }}" class="btn btn-primary btn-sm" style="background:#d97706;border-color:#d97706;font-weight:700;">
            Review Withdrawals
        </a>
    </div>
@endif

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(108,99,255,0.15);color:var(--primary);"><span class="material-icons-round">payments</span></div>
        <div class="stat-value">${{ number_format($stats['revenue'], 2) }}</div>
        <div class="stat-label">Platform Volume</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(0,206,201,0.15);color:var(--accent);"><span class="material-icons-round">shopping_bag</span></div>
        <div class="stat-value">{{ $stats['total_orders'] }}</div>
        <div class="stat-label">Total Orders</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(253,121,168,0.15);color:var(--secondary);"><span class="material-icons-round">work</span></div>
        <div class="stat-value">{{ $stats['total_gigs'] }}</div>
        <div class="stat-label">Published Gigs</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(255,170,0,0.15);color:var(--warning);"><span class="material-icons-round">pending_actions</span></div>
        <div class="stat-value">{{ $stats['pending_gigs'] }}</div>
        <div class="stat-label">Gigs Pending Review</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(99,102,241,0.15);color:#818cf8;"><span class="material-icons-round">group</span></div>
        <div class="stat-value">{{ $stats['total_users'] }}</div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(16,185,129,0.15);color:var(--success);"><span class="material-icons-round">storefront</span></div>
        <div class="stat-value">{{ $stats['total_sellers'] }}</div>
        <div class="stat-label">Active Sellers</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(6,182,212,0.15);color:#38bdf8;"><span class="material-icons-round">shopping_cart</span></div>
        <div class="stat-value">{{ $stats['total_buyers'] }}</div>
        <div class="stat-label">Active Buyers</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(239,68,68,0.15);color:var(--error);"><span class="material-icons-round">block</span></div>
        <div class="stat-value">{{ $stats['banned_users'] }}</div>
        <div class="stat-label">Inactive Users</div>
    </div>
</div>

<div class="grid-2col" style="margin-top:2rem;">
    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <span><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">receipt_long</span> Recent Orders</span>
            <a href="{{ route('admin.orders') }}" class="btn btn-ghost btn-sm">View All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Buyer</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <div style="font-weight:600;">#{{ $order->order_number }}</div>
                                    <div style="font-size:0.75rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:140px;">{{ $order->gig->title ?? 'N/A' }}</div>
                                </td>
                                <td>{{ $order->buyer->name ?? 'User' }}</td>
                                <td style="font-weight:700;color:var(--accent);">${{ number_format($order->price, 2) }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($order->status) {
                                            'completed'   => 'badge-success',
                                            'in_progress' => 'badge-primary',
                                            'delivered'   => 'badge-info',
                                            'revision'    => 'badge-warning',
                                            'cancelled'   => 'badge-error',
                                            default       => 'badge-warning'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_',' ',$order->status)) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center" style="padding:2rem;color:var(--text-muted);">No orders yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <span><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">people</span> Recent Users</span>
            <a href="{{ route('admin.users') }}" class="btn btn-ghost btn-sm">Manage Users</a>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <div style="width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;">
                                            {{ strtoupper(substr($user->name,0,1)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight:600;font-size:0.85rem;">{{ $user->name }}</div>
                                            <div style="font-size:0.75rem;color:var(--text-muted);">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $user->role === 'seller' ? 'badge-primary' : 'badge-info' }}">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td style="font-size:0.8rem;color:var(--text-muted);">{{ $user->created_at->diffForHumans() }}</td>
                                <td>
                                    @if($user->is_banned)
                                        <span class="badge badge-error">Inactive</span>
                                    @else
                                        <span class="badge badge-success">Active</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center" style="padding:2rem;color:var(--text-muted);">No users registered yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
