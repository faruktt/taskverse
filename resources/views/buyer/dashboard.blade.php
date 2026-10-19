@extends('layouts.app')
@section('title','Buyer Dashboard')
@section('sidebar-role','Buyer Workspace')
@section('page-title','Buyer Dashboard')

@section('sidebar-nav')
<div class="nav-section-label">Overview</div>
<a href="{{ route('buyer.dashboard') }}" class="nav-link active"><span class="material-icons-round">dashboard</span> Dashboard</a>
<div class="nav-section-label">Marketplace</div>
<a href="{{ route('buyer.browse') }}" class="nav-link"><span class="material-icons-round">search</span> Browse Gigs</a>
<a href="{{ route('buyer.orders') }}" class="nav-link"><span class="material-icons-round">receipt_long</span> My Orders</a>
<div class="nav-section-label">Account</div>
<a href="{{ route('buyer.profile') }}" class="nav-link"><span class="material-icons-round">manage_accounts</span> Profile</a>
@endsection

@section('content')
<div class="page-header flex justify-between items-center" style="margin-bottom:1.75rem;">
    <div>
        <h1>Welcome, {{ auth()->user()->name }}!</h1>
        <p>Monitor your active projects, deliverables, and orders</p>
    </div>
    <a href="{{ route('buyer.browse') }}" class="btn btn-primary">
        <span class="material-icons-round">search</span> Find Services
    </a>
</div>

<!-- Stats Row -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#e0f2fe;color:#0369a1;"><span class="material-icons-round">shopping_bag</span></div>
        <div class="stat-value">{{ $stats['total_orders'] }}</div>
        <div class="stat-label">Total Orders Placed</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;color:#b45309;"><span class="material-icons-round">hourglass_empty</span></div>
        <div class="stat-value">{{ $stats['pending'] }}</div>
        <div class="stat-label">Awaiting Acceptance</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--primary-light);color:var(--primary-dark);"><span class="material-icons-round">timelapse</span></div>
        <div class="stat-value">{{ $stats['in_progress'] }}</div>
        <div class="stat-label">In Progress</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#166534;"><span class="material-icons-round">check_circle</span></div>
        <div class="stat-value">{{ $stats['completed'] }}</div>
        <div class="stat-label">Completed Deliveries</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#f1f5f9;color:var(--dark);"><span class="material-icons-round">payments</span></div>
        <div class="stat-value">${{ number_format($stats['total_spent'], 2) }}</div>
        <div class="stat-label">Total Investments</div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="card">
    <div class="card-header flex justify-between items-center" style="background:#fafafa;">
        <span style="font-weight:700;"><span class="material-icons-round" style="color:var(--primary);">receipt_long</span> Recent Orders</span>
        <a href="{{ route('buyer.orders') }}" class="btn btn-secondary btn-sm" style="font-weight:600;">View All Orders</a>
    </div>

    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Gig Service</th>
                        <th>Seller</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="text-align:right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td>
                                <span style="font-family:monospace;font-weight:700;color:var(--dark);">#{{ $order->order_number }}</span>
                            </td>
                            <td>
                                <div style="font-weight:600;font-size:0.9rem;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $order->gig->title ?? 'N/A' }}
                                </div>
                                <div style="font-size:0.75rem;color:var(--muted);">{{ ucfirst($order->package) }} package</div>
                            </td>
                            <td>
                                <div style="font-weight:700;color:var(--dark);">{{ $order->seller->name ?? 'Freelancer' }}</div>
                                <div style="font-size:0.75rem;color:var(--muted);">Level 2 Seller</div>
                            </td>
                            <td>
                                <span style="font-weight:800;color:var(--dark);">${{ number_format($order->price, 2) }}</span>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($order->status) {
                                        'completed'   => 'badge-success',
                                        'in_progress' => 'badge-info',
                                        'delivered'   => 'badge-primary',
                                        'revision'    => 'badge-warning',
                                        'cancelled'   => 'badge-error',
                                        default       => 'badge-warning'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_',' ',$order->status)) }}</span>
                            </td>
                            <td style="font-size:0.85rem;color:var(--muted);white-space:nowrap;">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                            <td style="text-align:right;">
                                <a href="{{ route('buyer.order.show', $order) }}" class="btn btn-secondary btn-sm" style="font-weight:600;">
                                    View Order
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center" style="padding:4rem;color:var(--muted);">
                                <span class="material-icons-round" style="font-size:42px;color:#cbd5e1;display:block;margin-bottom:0.5rem;">shopping_bag</span>
                                No orders yet. <a href="{{ route('buyer.browse') }}" style="color:var(--primary);font-weight:700;">Explore top gigs</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
