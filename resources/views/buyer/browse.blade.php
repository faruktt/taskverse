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
<div class="page-header">
    <h1>Browse Gigs</h1>
    <p>Discover talented freelancers & professional services</p>
</div>

<!-- Category Pills (Fiverr Mobile Style Carousel) -->
<div class="categories-bar mb-3" style="border-radius:8px;padding:6px 0;background:#ffffff;border:1px solid var(--border);">
    <div style="display:flex;gap:0.5rem;overflow-x:auto;padding:0.25rem 0.75rem;scrollbar-width:none;-webkit-overflow-scrolling:touch;">
        <a href="{{ route('buyer.browse', request()->except(['category', 'page'])) }}" class="badge {{ !request('category') ? 'badge-primary' : 'badge-muted' }}" style="padding:0.45rem 0.85rem;text-decoration:none;white-space:nowrap;font-size:0.82rem;border-radius:999px;">
            All Categories
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('buyer.browse', array_merge(request()->except(['page']), ['category' => $cat->id])) }}" class="badge {{ request('category') == $cat->id ? 'badge-primary' : 'badge-muted' }}" style="padding:0.45rem 0.85rem;text-decoration:none;white-space:nowrap;font-size:0.82rem;border-radius:999px;display:inline-flex;align-items:center;gap:4px;">
                <span class="material-icons-round" style="font-size:15px;color:{{ $cat->color }};">{{ $cat->icon }}</span>
                {{ $cat->name }}
            </a>
        @endforeach
    </div>
</div>

<!-- Search & Filter -->
<form method="GET" action="{{ route('buyer.browse') }}" style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-lg);padding:1rem 1.25rem;margin-bottom:1.5rem;">
    @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:0.75rem;align-items:end;">
        <div style="grid-column: 1 / -1;">
            <label class="form-label" style="font-size:0.82rem;font-weight:700;">Search Service</label>
            <input type="text" name="q" class="form-control" placeholder="What service are you looking for?" value="{{ request('q') }}">
        </div>
        <div>
            <label class="form-label" style="font-size:0.82rem;font-weight:700;">Min Price ($)</label>
            <input type="number" name="min_price" class="form-control" placeholder="$0" value="{{ request('min_price') }}" min="0">
        </div>
        <div>
            <label class="form-label" style="font-size:0.82rem;font-weight:700;">Max Price ($)</label>
            <input type="number" name="max_price" class="form-control" placeholder="$999" value="{{ request('max_price') }}" min="0">
        </div>
        <div style="display:flex;gap:0.5rem;">
            <button type="submit" class="btn btn-primary w-full" style="justify-content:center;height:42px;">
                <span class="material-icons-round" style="font-size:18px;">search</span> Filter
            </button>
            @if(request('q') || request('min_price') || request('max_price'))
                <a href="{{ route('buyer.browse', request()->only(['category'])) }}" class="btn btn-secondary btn-sm" style="height:42px;display:flex;align-items:center;">Clear</a>
            @endif
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
