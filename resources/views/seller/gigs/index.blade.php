@extends('layouts.app')
@section('title','My Gigs')
@section('page-title','Gigs Management')
@section('sidebar-role','Seller Workspace')

@section('sidebar-nav')
<div class="nav-section-label">Overview</div>
<a href="{{ route('seller.dashboard') }}" class="nav-link"><span class="material-icons-round">dashboard</span> Dashboard</a>
<div class="nav-section-label">Business</div>
<a href="{{ route('seller.gigs') }}" class="nav-link active"><span class="material-icons-round">work</span> My Gigs</a>
<a href="{{ route('seller.orders') }}" class="nav-link"><span class="material-icons-round">receipt_long</span> Orders</a>
<div class="nav-section-label">Account</div>
<a href="{{ route('seller.profile') }}" class="nav-link"><span class="material-icons-round">manage_accounts</span> Profile</a>
@endsection

@section('content')
@php
    $gigCount = $gigCount ?? $gigs->count();
    $canCreateGig = $canCreateGig ?? ($gigCount < 4);
@endphp
<div class="page-header flex justify-between items-center" style="margin-bottom:1.5rem;">
    <div>
        <h1>My Gigs</h1>
        <p>Manage your services, track performance, and edit your pricing</p>
    </div>

    <div style="display:flex;align-items:center;gap:1rem;">
        <!-- Gig Limit Counter (Fiverr Policy: 4 Gigs Max) -->
        <div style="padding:0.45rem 0.9rem;border-radius:4px;font-size:0.85rem;font-weight:700;display:flex;align-items:center;gap:6px;background:{{ $gigCount >= 4 ? '#fef3c7' : '#e8fbf3' }};color:{{ $gigCount >= 4 ? '#92400e' : '#108a50' }};border:1px solid {{ $gigCount >= 4 ? '#fde68a' : '#bbf3db' }};">
            <span class="material-icons-round" style="font-size:16px;">{{ $gigCount >= 4 ? 'lock' : 'check_circle' }}</span>
            Gig Limit: {{ $gigCount }} / 4 Used
        </div>

        @if($canCreateGig)
            <a href="{{ route('seller.gigs.create') }}" class="btn btn-primary">
                <span class="material-icons-round">add</span> Create a New Gig
            </a>
        @else
            <button class="btn btn-secondary" style="opacity:0.6;cursor:not-allowed;" title="You have reached the maximum allowed limit of 4 gigs" disabled>
                <span class="material-icons-round">block</span> Max Limit Reached (4/4)
            </button>
        @endif
    </div>
</div>

@if($gigCount >= 4)
    <div class="alert alert-warning" style="background:#fffbeb;border:1px solid #fef3c7;color:#92400e;margin-bottom:1.5rem;">
        <span class="material-icons-round" style="color:#d97706;">info</span>
        <strong>Freelancer Policy:</strong> You have reached your account limit of <strong>4 published gigs</strong>. To offer a different service, you can edit an existing gig or delete one you no longer offer.
    </div>
@endif

<div class="card">
    <div class="card-header flex justify-between items-center" style="background:#fafafa;">
        <div style="display:flex;align-items:center;gap:1.5rem;font-size:0.9rem;font-weight:700;">
            <span style="color:var(--dark);border-bottom:2px solid var(--primary);padding-bottom:4px;">
                Active &amp; Pending Gigs ({{ $gigs->count() }})
            </span>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:340px;">Gig</th>
                        <th>Category</th>
                        <th>Impressions / Views</th>
                        <th>Orders</th>
                        <th>Base Price</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gigs as $gig)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <div style="width:58px;height:42px;border-radius:4px;overflow:hidden;background:#f1f5f9;flex-shrink:0;">
                                        @if($gig->thumbnail)
                                            <img src="{{ asset('storage/' . $gig->thumbnail) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#94a3b8;">
                                                <span class="material-icons-round" style="font-size:20px;">image</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div style="min-width:0;">
                                        <a href="{{ route('buyer.gig.show', $gig) }}" target="_blank" style="font-weight:600;font-size:0.9rem;color:var(--dark);line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;" title="{{ $gig->title }}">
                                            {{ $gig->title }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background:#f1f5f9;color:#475569;font-weight:600;">
                                    {{ $gig->category->name ?? 'General' }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight:700;color:var(--dark);">{{ $gig->views }}</div>
                                <div style="font-size:0.75rem;color:var(--muted);">Unique views</div>
                            </td>
                            <td>
                                <div style="font-weight:700;color:var(--dark);">{{ $gig->total_orders }}</div>
                                <div style="font-size:0.75rem;color:var(--muted);">Orders completed</div>
                            </td>
                            <td>
                                <div style="font-weight:800;color:var(--dark);">${{ number_format($gig->price_basic, 0) }}</div>
                                <div style="font-size:0.75rem;color:var(--muted);">{{ $gig->delivery_days }} days delivery</div>
                            </td>
                            <td>
                                @if($gig->status === 'active')
                                    <span class="badge badge-success"><span class="material-icons-round" style="font-size:12px;">check</span> Active</span>
                                @elseif($gig->status === 'pending')
                                    <span class="badge badge-warning"><span class="material-icons-round" style="font-size:12px;">hourglass_empty</span> Pending</span>
                                @elseif($gig->status === 'rejected')
                                    <span class="badge badge-error"><span class="material-icons-round" style="font-size:12px;">close</span> Rejected</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($gig->status) }}</span>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <div style="display:inline-flex;gap:6px;align-items:center;">
                                    <a href="{{ route('seller.gig.edit', $gig) }}" class="btn btn-secondary btn-sm" title="Edit Gig">
                                        <span class="material-icons-round" style="font-size:15px;">edit</span> Edit
                                    </a>
                                    <form method="POST" action="{{ route('seller.gig.delete', $gig) }}" onsubmit="return confirm('Delete this gig?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--danger)!important;" title="Delete">
                                            <span class="material-icons-round" style="font-size:16px;">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center" style="padding:4rem 2rem;color:var(--muted);">
                                <span class="material-icons-round" style="font-size:48px;color:#cbd5e1;display:block;margin-bottom:0.75rem;">work_outline</span>
                                <p style="font-size:1.05rem;font-weight:700;color:var(--dark);margin-bottom:0.25rem;">You haven't created any gigs yet</p>
                                <p style="font-size:0.88rem;margin-bottom:1.5rem;">Start selling your talent to global buyers. You can create up to 4 gigs.</p>
                                <a href="{{ route('seller.gigs.create') }}" class="btn btn-primary">
                                    <span class="material-icons-round">add</span> Create Your First Gig
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
