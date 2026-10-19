@extends('layouts.admin')
@section('title', 'Manage Users')
@section('page-title', 'User Management')

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>Users Directory</h1>
        <p>Monitor, moderate, ban or manage all registered buyers and sellers</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <!-- Role & Status Tabs to separate Buyers and Sellers -->
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;margin-bottom:1.25rem;border-bottom:1px solid #e2e8f0;padding-bottom:1rem;">
            <a href="{{ route('admin.users') }}" class="btn {{ !request('role') && !request('status') ? 'btn-primary' : 'btn-secondary' }} btn-sm" style="font-weight:600;">
                All Users ({{ $counts['all'] ?? $users->total() }})
            </a>
            <a href="{{ route('admin.users', array_merge(request()->except(['page']), ['role' => 'seller'])) }}" class="btn {{ request('role') === 'seller' ? 'btn-primary' : 'btn-secondary' }} btn-sm" style="font-weight:600;display:inline-flex;align-items:center;gap:0.35rem;{{ request('role') === 'seller' ? 'background:#4f46e5;border-color:#4f46e5;' : '' }}">
                <span class="material-icons-round" style="font-size:16px;">storefront</span>
                Sellers / Freelancers ({{ $counts['sellers'] ?? 0 }})
            </a>
            <a href="{{ route('admin.users', array_merge(request()->except(['page']), ['role' => 'buyer'])) }}" class="btn {{ request('role') === 'buyer' ? 'btn-primary' : 'btn-secondary' }} btn-sm" style="font-weight:600;display:inline-flex;align-items:center;gap:0.35rem;{{ request('role') === 'buyer' ? 'background:#0284c7;border-color:#0284c7;' : '' }}">
                <span class="material-icons-round" style="font-size:16px;">shopping_cart</span>
                Buyers / Clients ({{ $counts['buyers'] ?? 0 }})
            </a>

            <div style="margin-left:auto;display:flex;gap:0.5rem;align-items:center;">
                <a href="{{ route('admin.users', array_merge(request()->except(['page', 'status']), ['status' => 'active'])) }}" class="badge {{ request('status') === 'active' ? 'badge-success' : '' }}" style="text-decoration:none;padding:0.4rem 0.65rem;border:1px solid #bbf7d0;background:{{ request('status') === 'active' ? '#16a34a' : '#f0fdf4' }};color:{{ request('status') === 'active' ? '#fff' : '#166534' }};font-size:0.75rem;">
                    Active ({{ $counts['active'] ?? 0 }})
                </a>
                <a href="{{ route('admin.users', array_merge(request()->except(['page', 'status']), ['status' => 'inactive'])) }}" class="badge {{ request('status') === 'inactive' ? 'badge-error' : '' }}" style="text-decoration:none;padding:0.4rem 0.65rem;border:1px solid #fecaca;background:{{ request('status') === 'inactive' ? '#dc2626' : '#fef2f2' }};color:{{ request('status') === 'inactive' ? '#fff' : '#991b1b' }};font-size:0.75rem;">
                    Inactive ({{ $counts['inactive'] ?? 0 }})
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.users') }}" style="display:flex;gap:1rem;flex-wrap:wrap;">
            @if(request('role'))
                <input type="hidden" name="role" value="{{ request('role') }}">
            @endif
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div style="flex:1;min-width:240px;">
                <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary">
                <span class="material-icons-round">search</span> Search
            </button>
            @if(request('search') || request('role') || request('status'))
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Clear Filters</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role & Activity</th>
                        <th>Country</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    @php
                                        $avatarSrc = $user->avatar
                                            ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar))
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=' . ($user->role === 'seller' ? '4f46e5' : '0284c7') . '&color=fff';
                                    @endphp
                                    <img src="{{ $avatarSrc }}" 
                                         alt="{{ $user->name }}" 
                                         style="width:40px;height:40px;min-width:40px;max-width:40px;min-height:40px;max-height:40px;border-radius:50%;object-fit:cover;border:1.5px solid #e2e8f0;display:block;flex-shrink:0;">
                                    <div>
                                        <div style="font-weight:700;color:var(--text-dark);font-size:0.92rem;">{{ $user->name }}</div>
                                        <div style="font-size:0.78rem;color:var(--text-muted);">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->role === 'seller')
                                    <span class="badge" style="background:#e0e7ff;color:#4338ca;font-weight:700;display:inline-flex;align-items:center;gap:4px;padding:0.25rem 0.6rem;">
                                        <span class="material-icons-round" style="font-size:14px;">storefront</span> Seller
                                    </span>
                                    <div style="font-size:0.75rem;color:var(--text-muted);margin-top:3px;">
                                        {{ $user->gigs_count ?? $user->gigs()->count() }} Gigs &bull; {{ $user->seller_orders_count ?? $user->sellerOrders()->count() }} Orders
                                    </div>
                                @else
                                    <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:700;display:inline-flex;align-items:center;gap:4px;padding:0.25rem 0.6rem;">
                                        <span class="material-icons-round" style="font-size:14px;">shopping_cart</span> Buyer
                                    </span>
                                    <div style="font-size:0.75rem;color:var(--text-muted);margin-top:3px;">
                                        {{ $user->buyer_orders_count ?? $user->buyerOrders()->count() }} Orders Placed
                                    </div>
                                @endif
                            </td>
                            <td style="color:var(--text-muted);font-size:0.85rem;">{{ $user->country ?? '—' }}</td>
                            <td style="color:var(--text-muted);font-size:0.85rem;">{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($user->is_banned)
                                    <span class="badge badge-error">
                                        <span class="material-icons-round" style="font-size:13px;">cancel</span> Inactive
                                    </span>
                                @else
                                    <span class="badge badge-success">
                                        <span class="material-icons-round" style="font-size:13px;">check_circle</span> Active
                                    </span>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <div style="display:inline-flex;gap:6px;">
                                    <form method="POST" action="{{ route('admin.user.ban', $user) }}" onsubmit="return confirm('{{ $user->is_banned ? 'Activate' : 'Deactivate' }} user {{ $user->name }}?');">
                                        @csrf
                                        @if($user->is_banned)
                                            <button type="submit" class="btn btn-sm btn-success" title="Activate User">
                                                <span class="material-icons-round" style="font-size:16px;">check_circle</span>
                                                Activate
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-secondary" style="color:var(--danger)!important;border-color:#fca5a5;" title="Make Inactive">
                                                <span class="material-icons-round" style="font-size:16px;">block</span>
                                                Make Inactive
                                            </button>
                                        @endif
                                    </form>
                                    <form method="POST" action="{{ route('admin.user.delete', $user) }}" onsubmit="return confirm('Permanently delete {{ $user->name }}? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete User">
                                            <span class="material-icons-round" style="font-size:16px;">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding:3.5rem;color:var(--text-muted);">
                                <span class="material-icons-round" style="font-size:3rem;display:block;margin-bottom:0.5rem;opacity:0.4;">group_off</span>
                                No users found for this filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div style="padding:1.25rem;border-top:1px solid var(--border);">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
