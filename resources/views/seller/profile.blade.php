@extends('layouts.app')
@section('title','Seller Profile')
@section('page-title','Seller Profile')
@section('sidebar-role','Seller Account')
@section('sidebar-nav')
<div class="nav-section-label">Overview</div>
<a href="{{ route('seller.dashboard') }}" class="nav-link"><span class="material-icons-round">dashboard</span> Dashboard</a>
<div class="nav-section-label">Business</div>
<a href="{{ route('seller.gigs') }}" class="nav-link"><span class="material-icons-round">work</span> My Gigs</a>
<a href="{{ route('seller.orders') }}" class="nav-link"><span class="material-icons-round">receipt_long</span> Orders</a>
<div class="nav-section-label">Account</div>
<a href="{{ route('seller.profile') }}" class="nav-link active"><span class="material-icons-round">manage_accounts</span> Profile</a>
@endsection
@section('content')
<div class="page-header">
    <h1>Seller Profile</h1>
    <p>Manage your public seller presence and personal information</p>
</div>

<div style="display:grid;grid-template-columns:300px 1fr;gap:1.5rem;align-items:start;">
    <div class="card">
        <div class="card-body" style="text-align:center;padding:2rem 1.5rem;">
            <div style="position:relative;display:inline-block;margin-bottom:1rem;">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);">
                @else
                    <div style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;font-size:2.2rem;font-weight:700;margin:0 auto;color:#fff;">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                @endif
                <span class="badge badge-success" style="position:absolute;bottom:0;right:0;border-radius:50%;padding:4px;"><span class="material-icons-round" style="font-size:14px;">check</span></span>
            </div>
            <h3 style="font-size:1.2rem;font-weight:700;margin-bottom:0.25rem;">{{ $user->name }}</h3>
            <p style="color:var(--text-muted);font-size:0.85rem;margin-bottom:1rem;">{{ $user->email }}</p>
            <div style="display:flex;gap:0.5rem;justify-content:center;margin-bottom:1.5rem;">
                <span class="badge badge-primary"><span class="material-icons-round">verified</span> Verified Seller</span>
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
        <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">edit</span> Edit Profile Details</div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-error"><span class="material-icons-round">error</span>{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('seller.profile.update') }}" enctype="multipart/form-data">
                @csrf
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Country</label>
                        <input type="text" name="country" class="form-control" placeholder="e.g. Bangladesh, United States" value="{{ old('country', $user->country) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" placeholder="+1 234 567 890" value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Professional Bio / Description</label>
                    <textarea name="bio" class="form-control" rows="4" placeholder="Tell buyers about your skills, expertise and background...">{{ old('bio', $user->bio) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Profile Photo / Avatar</label>
                    <input type="file" name="avatar" class="form-control" accept="image/*">
                    <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.35rem;">JPG, PNG or WEBP up to 1MB</p>
                </div>

                <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary" style="padding:0.75rem 2rem;">
                        <span class="material-icons-round">save</span> Save Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
