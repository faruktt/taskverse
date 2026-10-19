@extends('layouts.app')
@section('title','Create a New Gig')
@section('page-title','Create Gig')
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
<div class="page-header flex justify-between items-center" style="margin-bottom:1.5rem;">
    <div>
        <h1>Create a New Gig</h1>
        <p>Offer your professional freelance services to buyers worldwide</p>
    </div>
    <div style="font-size:0.85rem;color:var(--muted);background:#f1f5f9;padding:0.4rem 0.8rem;border-radius:4px;font-weight:600;">
        <span class="material-icons-round" style="font-size:16px;color:var(--primary);">info</span> Freelancer Policy: Max 4 Gigs
    </div>
</div>

@if($errors->any())
    <div class="alert alert-error"><span class="material-icons-round">error</span>{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('seller.gigs.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="gig-form-layout">
        <div>
            <!-- Section 1: Gig Overview -->
            <div class="card mb-4">
                <div class="card-header" style="background:#fafafa;">
                    <span class="material-icons-round" style="color:var(--primary);">info</span> Gig Overview
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Gig Title</label>
                        <input type="text" name="title" class="form-control" placeholder="I will do something I'm really good at..." value="{{ old('title') }}" required>
                        <p style="font-size:0.75rem;color:var(--muted);margin-top:0.35rem;">As your Gig storefront, your title is the most important place to include keywords that buyers would likely use to search for a service like yours.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select a category...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Search Tags (comma separated)</label>
                        <input type="text" name="tags" class="form-control" placeholder="e.g. web development, laravel, responsive" value="{{ old('tags') }}">
                        <p style="font-size:0.75rem;color:var(--muted);margin-top:0.35rem;">Enter up to 5 keywords that describe your Gig.</p>
                    </div>
                </div>
            </div>

            <!-- Section 2: Pricing & Packages -->
            <div class="card mb-4">
                <div class="card-header" style="background:#fafafa;">
                    <span class="material-icons-round" style="color:var(--primary);">inventory_2</span> Pricing &amp; Packages (Fiverr Standard)
                </div>
                <div class="card-body">
                    <div class="packages-grid">
                        <!-- Basic -->
                        <div style="border:1px solid var(--border);border-radius:6px;padding:1rem;background:#fafafa;">
                            <div style="font-weight:700;font-size:0.9rem;color:var(--dark);margin-bottom:0.75rem;border-bottom:2px solid var(--primary);padding-bottom:4px;">
                                Basic Package
                            </div>
                            <div class="form-group">
                                <label class="form-label">Price ($ USD)</label>
                                <input type="number" name="price_basic" class="form-control" placeholder="25" value="{{ old('price_basic') }}" min="1" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea name="basic_desc" class="form-control" rows="3" placeholder="Single core deliverable included in basic">{{ old('basic_desc') }}</textarea>
                            </div>
                        </div>

                        <!-- Standard -->
                        <div style="border:1px solid var(--border);border-radius:6px;padding:1rem;background:#fafafa;">
                            <div style="font-weight:700;font-size:0.9rem;color:var(--dark);margin-bottom:0.75rem;border-bottom:2px solid var(--warning);padding-bottom:4px;">
                                Standard Package
                            </div>
                            <div class="form-group">
                                <label class="form-label">Price ($ USD)</label>
                                <input type="number" name="price_standard" class="form-control" placeholder="60" value="{{ old('price_standard') }}" min="1">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea name="standard_desc" class="form-control" rows="3" placeholder="Mid-tier deliverables & extras">{{ old('standard_desc') }}</textarea>
                            </div>
                        </div>

                        <!-- Premium -->
                        <div style="border:1px solid var(--border);border-radius:6px;padding:1rem;background:#fafafa;">
                            <div style="font-weight:700;font-size:0.9rem;color:var(--dark);margin-bottom:0.75rem;border-bottom:2px solid #fd79a8;padding-bottom:4px;">
                                Premium Package
                            </div>
                            <div class="form-group">
                                <label class="form-label">Price ($ USD)</label>
                                <input type="number" name="price_premium" class="form-control" placeholder="120" value="{{ old('price_premium') }}" min="1">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea name="premium_desc" class="form-control" rows="3" placeholder="Full comprehensive solution">{{ old('premium_desc') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Description -->
            <div class="card mb-4">
                <div class="card-header" style="background:#fafafa;">
                    <span class="material-icons-round" style="color:var(--primary);">description</span> Description &amp; FAQ
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Briefly Describe Your Gig</label>
                        <textarea name="description" class="form-control" rows="8" placeholder="Introduce yourself, your workflow, and what the buyer gets..." required>{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <!-- Section 4: Media Gallery -->
            <div class="card mb-4">
                <div class="card-header" style="background:#fafafa;">
                    <span class="material-icons-round" style="color:var(--primary);">image</span> Gig Gallery
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Primary Thumbnail</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                        <p style="font-size:0.75rem;color:var(--muted);margin-top:0.35rem;">Recommended size: 1280 x 769 px. Max 2MB.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Additional Showcase Images</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    </div>
                </div>
            </div>

            <!-- Delivery Details -->
            <div class="card mb-4">
                <div class="card-header" style="background:#fafafa;">
                    <span class="material-icons-round" style="color:var(--primary);">schedule</span> Delivery Timeline
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Delivery Days</label>
                        <input type="number" name="delivery_days" class="form-control" value="{{ old('delivery_days', 3) }}" min="1" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Revisions Included</label>
                        <input type="number" name="revisions" class="form-control" value="{{ old('revisions', 2) }}" min="0" required>
                    </div>
                </div>
            </div>

            <!-- Publish Button -->
            <button type="submit" class="btn btn-primary w-full" style="padding:0.85rem;font-size:1rem;border-radius:4px;box-shadow:var(--shadow-sm);">
                <span class="material-icons-round">publish</span> Save &amp; Publish Gig
            </button>
        </div>
    </div>
</form>
@endsection
