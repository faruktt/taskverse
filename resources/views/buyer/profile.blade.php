@extends('layouts.app')
@section('title','Buyer Profile')
@section('page-title','Buyer Profile')
@section('sidebar-role','Buyer Account')
@section('sidebar-nav')
<div class="nav-section-label">Explore</div>
<a href="{{ route('buyer.browse') }}" class="nav-link"><span class="material-icons-round">explore</span> Browse Services</a>
<div class="nav-section-label">Activity</div>
<a href="{{ route('buyer.dashboard') }}" class="nav-link"><span class="material-icons-round">dashboard</span> Dashboard</a>
<a href="{{ route('buyer.orders') }}" class="nav-link"><span class="material-icons-round">shopping_bag</span> My Orders</a>
<div class="nav-section-label">Account</div>
<a href="{{ route('buyer.profile') }}" class="nav-link active"><span class="material-icons-round">manage_accounts</span> Profile</a>
@endsection
@section('content')
<div class="page-header">
    <h1>Buyer Profile</h1>
    <p>Manage your account settings and preferences</p>
</div>

<div style="display:grid;grid-template-columns:300px 1fr;gap:1.5rem;align-items:start;">
    <div class="card">
        <div class="card-body" style="text-align:center;padding:2rem 1.5rem;">
            <div style="position:relative;display:inline-block;margin-bottom:1rem;">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--accent);">
                @else
                    <div style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--primary));display:flex;align-items:center;justify-content:center;font-size:2.2rem;font-weight:700;margin:0 auto;color:#fff;">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                @endif
            </div>
            <h3 style="font-size:1.2rem;font-weight:700;margin-bottom:0.25rem;">{{ $user->name }}</h3>
            <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:1rem;">{{ $user->email }}</p>
            <div style="display:flex;gap:0.5rem;justify-content:center;margin-bottom:1.5rem;">
                <span class="badge badge-info"><span class="material-icons-round">shopping_cart</span> Verified Buyer</span>
            </div>
            <div style="border-top:1px solid var(--border);padding-top:1rem;display:flex;justify-content:space-between;text-align:left;font-size:0.85rem;color:var(--text-muted);">
                <span>Member since</span>
                <span style="color:var(--text-primary);font-weight:600;">{{ $user->created_at->format('M Y') }}</span>
            </div>
            <div style="padding-top:0.5rem;display:flex;justify-content:space-between;text-align:left;font-size:0.85rem;color:var(--text-muted);">
                <span>Country</span>
                <span style="color:var(--text-primary);font-weight:600;">{{ $user->country ?? 'Not set' }}</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">edit</span> Edit Account Information</div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-error"><span class="material-icons-round">error</span>{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('buyer.profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" class="form-control" placeholder="e.g. United Kingdom" value="{{ old('country', $user->country) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" placeholder="+1 234 567 890" value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">About You / Company</label>
                    <textarea name="bio" class="form-control" rows="3" placeholder="Tell sellers about your projects or organization...">{{ old('bio', $user->bio) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Avatar</label>
                    <input type="file" name="avatar" class="form-control" accept="image/*">
                </div>

                <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem;">
                        <span class="material-icons-round">save</span> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
