@extends('layouts.admin')
@section('title', 'Manage Orders')
@section('page-title', 'Platform Orders')

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>Orders Management</h1>
        <p>Monitor transactions, deliveries, and completion status across the marketplace</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
            <a href="{{ route('admin.orders') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-secondary' }} btn-sm">All Orders</a>
            <a href="{{ route('admin.orders', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'btn-primary' : 'btn-secondary' }} btn-sm">Pending</a>
            <a href="{{ route('admin.orders', ['status' => 'in_progress']) }}" class="btn {{ request('status') == 'in_progress' ? 'btn-primary' : 'btn-secondary' }} btn-sm">In Progress</a>
            <a href="{{ route('admin.orders', ['status' => 'delivered']) }}" class="btn {{ request('status') == 'delivered' ? 'btn-primary' : 'btn-secondary' }} btn-sm">Delivered</a>
            <a href="{{ route('admin.orders', ['status' => 'completed']) }}" class="btn {{ request('status') == 'completed' ? 'btn-primary' : 'btn-secondary' }} btn-sm">Completed</a>
            <a href="{{ route('admin.orders', ['status' => 'cancelled']) }}" class="btn {{ request('status') == 'cancelled' ? 'btn-primary' : 'btn-secondary' }} btn-sm">Cancelled</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Gig Service</th>
                        <th>Buyer</th>
                        <th>Seller</th>
                        <th>Package</th>
                        <th>Price</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>
                                <span style="font-weight:700;font-family:monospace;color:var(--primary-light);">#{{ $order->order_number }}</span>
                            </td>
                            <td>
                                <div style="font-weight:600;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $order->gig->title ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div style="font-size:0.85rem;font-weight:500;">{{ $order->buyer->name ?? 'Buyer' }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $order->buyer->email ?? '' }}</div>
                            </td>
                            <td>
                                <div style="font-size:0.85rem;font-weight:500;">{{ $order->seller->name ?? 'Seller' }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $order->seller->email ?? '' }}</div>
                            </td>
                            <td>
                                <span class="badge" style="background:rgba(255,255,255,0.06);">{{ ucfirst($order->package) }}</span>
                            </td>
                            <td>
                                <span style="font-weight:700;color:var(--accent);">${{ number_format($order->price, 2) }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $order->payment_status === 'paid' ? 'badge-success' : 'badge-warning' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
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
                            <td style="font-size:0.8rem;color:var(--text-muted);white-space:nowrap;">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center" style="padding:3rem;color:var(--text-muted);">
                                <span class="material-icons-round" style="font-size:3rem;display:block;margin-bottom:0.5rem;opacity:0.4;">receipt_long</span>
                                No orders found in this view
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div style="padding:1.25rem;border-top:1px solid var(--border);">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
