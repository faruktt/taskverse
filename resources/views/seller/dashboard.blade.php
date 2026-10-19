@extends('layouts.app')
@section('title', 'Seller Dashboard')
@section('sidebar-role', 'Seller Workspace')
@section('page-title', 'Seller Dashboard')

@section('sidebar-nav')
<div class="nav-section-label">Overview</div>
<a href="{{ route('seller.dashboard') }}" class="nav-link active"><span class="material-icons-round">dashboard</span> Dashboard</a>
<div class="nav-section-label">Business</div>
<a href="{{ route('seller.gigs') }}" class="nav-link"><span class="material-icons-round">work</span> My Gigs</a>
<a href="{{ route('seller.orders') }}" class="nav-link"><span class="material-icons-round">receipt_long</span> Orders</a>
<div class="nav-section-label">Account</div>
<a href="{{ route('seller.profile') }}" class="nav-link"><span class="material-icons-round">manage_accounts</span> Profile</a>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:300px 1fr;gap:1.75rem;align-items:start;">
    <!-- Left Column: Seller Profile & Metrics -->
    <div>
        <div class="card mb-4">
            <div class="card-body" style="text-align:center;padding:2rem 1.5rem 1.5rem;">
                <div style="position:relative;display:inline-block;margin-bottom:0.75rem;">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="avatar avatar-lg" style="width:84px;height:84px;border-radius:50%;margin:0 auto;object-fit:cover;">
                    @else
                        <div style="width:84px;height:84px;border-radius:50%;background:linear-gradient(135deg,var(--dark),#475569);color:#fff;font-size:2rem;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <h3 style="font-size:1.15rem;font-weight:800;color:var(--dark);margin-bottom:2px;">{{ auth()->user()->name }}</h3>
                <div style="font-size:0.8rem;color:var(--muted);margin-bottom:0.75rem;">{{ auth()->user()->email }}</div>
                <span class="badge badge-primary" style="margin-bottom:1.5rem;">
                    <span class="material-icons-round" style="font-size:14px;">verified</span> Level 2 Seller
                </span>

                <!-- Performance Metrics (Fiverr Standard) -->
                <div style="border-top:1px solid var(--border-light);padding-top:1.25rem;text-align:left;display:flex;flex-direction:column;gap:0.75rem;font-size:0.85rem;">
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--muted);">Response rate</span>
                        <strong style="color:var(--primary-dark);">100%</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--muted);">Delivered on time</span>
                        <strong style="color:var(--primary-dark);">100%</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--muted);">Order completion</span>
                        <strong style="color:var(--primary-dark);">100%</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;border-top:1px dashed var(--border-light);padding-top:0.75rem;">
                        <span style="color:var(--muted);">Earned in total</span>
                        <strong style="color:var(--dark);font-size:1rem;">${{ number_format($stats['earnings'], 2) }}</strong>
                    </div>
                </div>

                <!-- 4 Gigs Policy Badge -->
                <div style="margin-top:1.25rem;padding:0.75rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;text-align:left;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;font-size:0.82rem;font-weight:700;">
                        <span>Gigs Limit:</span>
                        <span style="color:{{ $stats['total_gigs'] >= 4 ? '#b45309' : 'var(--primary-dark)' }};">
                            {{ $stats['total_gigs'] }} / 4 Used
                        </span>
                    </div>
                    <div style="width:100%;height:6px;background:#e2e8f0;border-radius:999px;overflow:hidden;">
                        <div style="width:{{ ($stats['total_gigs'] / 4) * 100 }}%;height:100%;background:{{ $stats['total_gigs'] >= 4 ? '#f59e0b' : 'var(--primary)' }};"></div>
                    </div>
                    <div style="font-size:0.72rem;color:var(--muted);margin-top:4px;">
                        Max limit: 4 published gigs for freelancers.
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" style="background:#fafafa;font-size:0.9rem;">
                <span class="material-icons-round" style="font-size:18px;">lightbulb</span> Seller Tips
            </div>
            <div class="card-body" style="font-size:0.85rem;color:var(--muted);line-height:1.6;">
                Quickly responding to incoming buyer inquiries increases your chances of securing higher-ticket orders by 40%.
            </div>
        </div>
    </div>

    <!-- Right Column: Stats & Active Orders -->
    <div>
        <!-- Stats Row -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--primary-light);color:var(--primary-dark);"><span class="material-icons-round">payments</span></div>
                <div class="stat-value">${{ number_format($stats['earnings'], 2) }}</div>
                <div class="stat-label">Net Income</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#fef3c7;color:#b45309;"><span class="material-icons-round">pending_actions</span></div>
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-label">Pending Acceptance</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#e0f2fe;color:#0369a1;"><span class="material-icons-round">timelapse</span></div>
                <div class="stat-value">{{ $stats['in_progress'] }}</div>
                <div class="stat-label">Active Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#dcfce7;color:#166534;"><span class="material-icons-round">check_circle</span></div>
                <div class="stat-value">{{ $stats['completed'] }}</div>
                <div class="stat-label">Completed Deliveries</div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="card">
            <div class="card-header flex justify-between items-center" style="background:#fafafa;">
                <span><span class="material-icons-round" style="color:var(--primary);">receipt_long</span> Manage Orders</span>
                <a href="{{ route('seller.orders') }}" class="btn btn-secondary btn-sm" style="font-weight:600;">View All Orders</a>
            </div>

            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Buyer</th>
                                <th>Gig Title</th>
                                <th>Due Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th style="text-align:right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <div style="font-weight:700;color:var(--dark);">{{ $order->buyer->name ?? 'Buyer' }}</div>
                                        <div style="font-size:0.75rem;color:var(--muted);">#{{ $order->order_number }}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight:600;font-size:0.88rem;max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $order->gig->title ?? 'N/A' }}
                                        </div>
                                        <div style="font-size:0.75rem;color:var(--muted);">{{ ucfirst($order->package) }} package</div>
                                    </td>
                                    <td style="font-size:0.85rem;color:var(--muted);white-space:nowrap;">
                                        {{ $order->due_at ? $order->due_at->format('M d, Y') : '—' }}
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
                                    <td style="text-align:right;">
                                        <a href="{{ route('seller.order.show', $order) }}" class="btn btn-secondary btn-sm" style="font-weight:600;">
                                            Open Order
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center" style="padding:3.5rem;color:var(--muted);">
                                        <span class="material-icons-round" style="font-size:40px;color:#cbd5e1;display:block;margin-bottom:0.5rem;">receipt</span>
                                        No active orders currently
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
