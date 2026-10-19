@extends('layouts.app')
@section('title','Browse Gigs')
@section('page-title','Browse Gigs')
@section('sidebar-role','Buyer Account')
@section('sidebar-nav')
<div class="nav-section-label">Overview</div>
<a href="{{ route('buyer.dashboard') }}" class="nav-link"><span class="material-icons-round">dashboard</span> Dashboard</a>
<div class="nav-section-label">Marketplace</div>
<a href="{{ route('buyer.browse') }}" class="nav-link active"><span class="material-icons-round">search</span> Browse Gigs</a>
<a href="{{ route('buyer.orders') }}" class="nav-link"><span class="material-icons-round">receipt_long</span> My Orders</a>
<div class="nav-section-label">Account</div>
<a href="{{ route('buyer.profile') }}" class="nav-link"><span class="material-icons-round">manage_accounts</span> Profile</a>
@endsection
@section('content')
<div class="page-header"><h1>Browse Gigs</h1><p>Discover talented freelancers</p></div>

<!-- Search & Filter -->
<form method="GET" action="{{ route('buyer.browse') }}" style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:1.25rem;margin-bottom:1.5rem;">
    <div style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;">
        <div style="flex:1;min-width:200px;">
            <label class="form-label">Search</label>
            <input type="text" name="q" class="form-control" placeholder="Search gigs..." value="{{ request('q') }}">
        </div>
        <div style="min-width:160px;">
            <label class="form-label">Category</label>
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width:100px;">
            <label class="form-label">Min Price</label>
            <input type="number" name="min_price" class="form-control" placeholder="$0" value="{{ request('min_price') }}" min="0">
        </div>
        <div style="min-width:100px;">
            <label class="form-label">Max Price</label>
            <input type="number" name="max_price" class="form-control" placeholder="$999" value="{{ request('max_price') }}" min="0">
        </div>
        <div>
            <button type="submit" class="btn btn-primary"><span class="material-icons-round">search</span> Filter</button>
        </div>
    </div>
</form>

@if($gigs->isEmpty())
    <div style="text-align:center;padding:4rem;color:var(--text-muted);">
        <span class="material-icons-round" style="font-size:56px;color:var(--text-faint);">search_off</span>
        <p style="margin-top:1rem;">No gigs found. Try different filters.</p>
    </div>
@else
<div class="gig-grid">
    @foreach($gigs as $gig)
    <a href="{{ route('buyer.gig.show', $gig) }}" class="gig-card">
        <div class="gig-card-thumb">
            @if($gig->thumbnail)
                <img src="{{ asset('storage/'.$gig->thumbnail) }}" alt="{{ $gig->title }}">
            @else
                <span class="material-icons-round placeholder-icon">image</span>
            @endif
        </div>
        <div class="gig-card-body">
            <div class="gig-card-seller">
                <img src="{{ $gig->seller->avatarUrl() }}" class="avatar avatar-sm" alt="{{ $gig->seller->name }}">
                <span>{{ $gig->seller->name }}</span>
            </div>
            <div class="gig-card-title">{{ Str::limit($gig->title, 60) }}</div>
            <div class="gig-card-rating">
                <span class="material-icons-round" style="font-size:14px;">star</span>
                {{ $gig->avg_rating > 0 ? number_format($gig->avg_rating,1) : 'New' }}
                <span>({{ $gig->total_reviews }})</span>
            </div>
            <div class="gig-card-footer">
                <div class="gig-card-price"><small>From </small>${{ number_format($gig->price_basic,0) }}</div>
                <span class="badge badge-muted">{{ $gig->category->name ?? '' }}</span>
            </div>
        </div>
    </a>
    @endforeach
</div>
<div class="pagination-wrap">{{ $gigs->appends(request()->query())->links('pagination::simple-default') }}</div>
@endif
@endsection
