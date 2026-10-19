@extends('layouts.app')
@section('title','Edit Gig')
@section('page-title','Edit Gig')
@section('sidebar-role','Seller Account')
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
<div class="page-header flex justify-between items-center">
    <div>
        <h1>Edit Gig</h1>
        <p>Update your service offering and pricing</p>
    </div>
    <a href="{{ route('seller.gigs') }}" class="btn btn-secondary btn-sm">
        <span class="material-icons-round">arrow_back</span> Back to Gigs
    </a>
</div>

@if($errors->any())
    <div class="alert alert-error"><span class="material-icons-round">error</span>{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('seller.gig.update', $gig) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;">
        <div>
            <div class="card mb-4">
                <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">info</span> Basic Info</div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Gig Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $gig->title) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select category...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $gig->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="6" required>{{ old('description', $gig->description) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tags (comma separated)</label>
                        <input type="text" name="tags" class="form-control" value="{{ old('tags', $gig->tags) }}">
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">inventory_2</span> Packages</div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                        <div>
                            <p style="font-weight:700;font-size:0.85rem;margin-bottom:0.75rem;color:var(--primary-light);">Basic</p>
                            <div class="form-group">
                                <label class="form-label">Price ($)</label>
                                <input type="number" name="price_basic" class="form-control" value="{{ old('price_basic', $gig->price_basic) }}" min="1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <input type="text" name="basic_desc" class="form-control" value="{{ old('basic_desc', $gig->basic_desc) }}">
                            </div>
                        </div>
                        <div>
                            <p style="font-weight:700;font-size:0.85rem;margin-bottom:0.75rem;color:var(--warning);">Standard</p>
                            <div class="form-group">
                                <label class="form-label">Price ($)</label>
                                <input type="number" name="price_standard" class="form-control" value="{{ old('price_standard', $gig->price_standard) }}" min="1">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <input type="text" name="standard_desc" class="form-control" value="{{ old('standard_desc', $gig->standard_desc) }}">
                            </div>
                        </div>
                        <div>
                            <p style="font-weight:700;font-size:0.85rem;margin-bottom:0.75rem;color:#fd79a8;">Premium</p>
                            <div class="form-group">
                                <label class="form-label">Price ($)</label>
                                <input type="number" name="price_premium" class="form-control" value="{{ old('price_premium', $gig->price_premium) }}" min="1">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <input type="text" name="premium_desc" class="form-control" value="{{ old('premium_desc', $gig->premium_desc) }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card mb-4">
                <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">image</span> Thumbnail</div>
                <div class="card-body">
                    @if($gig->thumbnail)
                        <div style="margin-bottom:1rem;border-radius:var(--radius-sm);overflow:hidden;border:1px solid var(--border);">
                            <img src="{{ asset('storage/' . $gig->thumbnail) }}" alt="Preview" style="width:100%;height:140px;object-fit:cover;display:block;">
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label">Change Thumbnail</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">settings</span> Details</div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Delivery Days</label>
                        <input type="number" name="delivery_days" class="form-control" value="{{ old('delivery_days', $gig->delivery_days) }}" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Revisions</label>
                        <input type="number" name="revisions" class="form-control" value="{{ old('revisions', $gig->revisions) }}" min="0" required>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-full" style="justify-content:center;padding:0.85rem;">
                <span class="material-icons-round">save</span> Save Changes
            </button>
        </div>
    </div>
</form>
@endsection
