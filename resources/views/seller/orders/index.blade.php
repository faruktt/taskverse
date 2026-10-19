@extends('layouts.app')
@section('title','Orders')
@section('page-title','Seller Orders')
@section('sidebar-role','Seller Workspace')

@section('sidebar-nav')
<div class="nav-section-label">Overview</div>
<a href="{{ route('seller.dashboard') }}" class="nav-link"><span class="material-icons-round">dashboard</span> Dashboard</a>
<div class="nav-section-label">Business</div>
<a href="{{ route('seller.gigs') }}" class="nav-link"><span class="material-icons-round">work</span> My Gigs</a>
<a href="{{ route('seller.orders') }}" class="nav-link active"><span class="material-icons-round">receipt_long</span> Orders</a>
<div class="nav-section-label">Account</div>
<a href="{{ route('seller.profile') }}" class="nav-link"><span class="material-icons-round">manage_accounts</span> Profile</a>
@endsection

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>Orders Management</h1>
        <p>Track your active project deliverables, milestones, and client communications</p>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Gig</th>
                        <th>Client</th>
                        <th>Package</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Due Date</th>
                        <th style="text-align:right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <span style="font-family:monospace;font-weight:700;color:var(--dark);">#{{ $order->order_number }}</span>
                            </td>
                            <td>
                                <div style="font-weight:600;font-size:0.88rem;max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $order->gig->title ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:700;font-size:0.88rem;color:var(--dark);">{{ $order->buyer->name ?? 'Client' }}</div>
                            </td>
                            <td>
                                <span class="badge" style="background:#f1f5f9;color:#475569;">{{ ucfirst($order->package) }}</span>
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
                            <td>
                                <span class="badge {{ $order->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td style="font-size:0.85rem;color:var(--muted);white-space:nowrap;">
                                {{ $order->due_at ? $order->due_at->format('M d, Y') : '—' }}
                            </td>
                            <td style="text-align:right;">
                                <a href="{{ route('seller.order.show', $order) }}" class="btn btn-secondary btn-sm" style="font-weight:600;">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center" style="padding:4rem;color:var(--muted);">
                                No incoming orders yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div style="padding:1rem;border-top:1px solid var(--border);">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
