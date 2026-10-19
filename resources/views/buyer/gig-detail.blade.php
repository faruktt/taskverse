@extends('layouts.app')
@section('title', $gig->title)
@section('page-title', 'Gig Details')
@section('sidebar-role', 'Buyer Workspace')

@section('sidebar-nav')
<div class="nav-section-label">Explore</div>
<a href="{{ route('buyer.browse') }}" class="nav-link"><span class="material-icons-round">explore</span> Browse Gigs</a>
<div class="nav-section-label">Activity</div>
<a href="{{ route('buyer.dashboard') }}" class="nav-link"><span class="material-icons-round">dashboard</span> Dashboard</a>
<a href="{{ route('buyer.orders') }}" class="nav-link"><span class="material-icons-round">shopping_bag</span> My Orders</a>
<div class="nav-section-label">Account</div>
<a href="{{ route('buyer.profile') }}" class="nav-link"><span class="material-icons-round">manage_accounts</span> Profile</a>
@endsection

@section('content')
<!-- Breadcrumbs (Fiverr Style) -->
<div style="font-size:0.85rem;color:var(--muted);margin-bottom:1rem;display:flex;align-items:center;gap:6px;">
    <a href="{{ route('home') }}" style="color:var(--muted);">Home</a>
    <span>/</span>
    <a href="{{ route('buyer.browse', ['category' => $gig->category_id]) }}" style="color:var(--muted);">{{ $gig->category->name ?? 'Services' }}</a>
    <span>/</span>
    <span style="color:var(--dark);font-weight:600;">Details</span>
</div>

<!-- Gig Title -->
<h1 style="font-size:1.85rem;font-weight:800;color:var(--dark);line-height:1.3;margin-bottom:1rem;letter-spacing:-0.02em;">
    {{ $gig->title }}
</h1>

<!-- Seller Top Bar -->
<div style="display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap;padding-bottom:1.5rem;margin-bottom:1.75rem;border-bottom:1px solid var(--border-light);font-size:0.9rem;">
    <div style="display:flex;align-items:center;gap:10px;">
        @if($gig->seller && $gig->seller->avatar)
            <img src="{{ asset('storage/' . $gig->seller->avatar) }}" alt="" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
        @else
            <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--dark),#475569);color:#fff;font-weight:700;display:flex;align-items:center;justify-content:center;">
                {{ strtoupper(substr($gig->seller->name ?? 'S', 0, 1)) }}
            </div>
        @endif
        <div>
            <div style="font-weight:700;color:var(--dark);">{{ $gig->seller->name ?? 'Verified Seller' }}</div>
            <div style="font-size:0.75rem;color:var(--muted);">Level 2 Seller &bull; Highly responsive</div>
        </div>
    </div>

    <div style="height:24px;width:1px;background:var(--border);"></div>

    <div style="display:flex;align-items:center;gap:4px;">
        <span class="material-icons-round" style="color:var(--star);font-size:18px;">star</span>
        <strong style="color:var(--dark);">{{ number_format($gig->avg_rating, 1) }}</strong>
        <span style="color:var(--muted);">({{ $gig->total_reviews }} reviews)</span>
    </div>

    <div style="height:24px;width:1px;background:var(--border);"></div>

    <div style="color:var(--muted);">
        <strong style="color:var(--dark);">{{ $gig->total_orders }}</strong> orders completed
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 400px;gap:2.5rem;align-items:start;">
    <!-- Left Column: Media & Information -->
    <div>
        <!-- Main Gig Image / Showcase -->
        <div style="border-radius:8px;overflow:hidden;border:1px solid var(--border);margin-bottom:2rem;background:#f8fafc;aspect-ratio:16/10;">
            @if($gig->thumbnail)
                <img src="{{ asset('storage/' . $gig->thumbnail) }}" alt="{{ $gig->title }}" style="width:100%;height:100%;object-fit:cover;">
            @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f1f5f9,#e2e8f0);color:#64748b;">
                    <span class="material-icons-round" style="font-size:56px;opacity:0.5;">photo_camera</span>
                </div>
            @endif
        </div>

        <!-- About This Gig -->
        <div class="card mb-4">
            <div class="card-header" style="background:#fafafa;font-size:1.1rem;font-weight:800;">
                About this gig
            </div>
            <div class="card-body" style="font-size:0.95rem;line-height:1.7;color:#334155;white-space:pre-line;">
{{ $gig->description }}
            </div>
        </div>

        <!-- About The Seller -->
        <div class="card mb-4">
            <div class="card-header" style="background:#fafafa;font-size:1.1rem;font-weight:800;">
                About the seller
            </div>
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:1.25rem;margin-bottom:1.25rem;">
                    @if($gig->seller && $gig->seller->avatar)
                        <img src="{{ asset('storage/' . $gig->seller->avatar) }}" alt="" style="width:64px;height:64px;border-radius:50%;object-fit:cover;">
                    @else
                        <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,var(--dark),#475569);color:#fff;font-size:1.5rem;font-weight:700;display:flex;align-items:center;justify-content:center;">
                            {{ strtoupper(substr($gig->seller->name ?? 'S', 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h4 style="font-size:1.1rem;font-weight:800;color:var(--dark);">{{ $gig->seller->name ?? 'Seller' }}</h4>
                        <div style="font-size:0.85rem;color:var(--muted);margin-bottom:4px;">{{ $gig->seller->bio ? Str::limit($gig->seller->bio, 80) : 'Professional creator on TaskVerse' }}</div>
                        <span class="badge badge-primary"><span class="material-icons-round" style="font-size:14px;">verified</span> Verified Freelancer</span>
                    </div>
                </div>

                <div style="border:1px solid var(--border);border-radius:6px;padding:1rem;background:#f8fafc;display:grid;grid-template-columns:1fr 1fr;gap:1rem;font-size:0.85rem;">
                    <div>
                        <span style="color:var(--muted);">From:</span>
                        <div style="font-weight:700;color:var(--dark);margin-top:2px;">{{ $gig->seller->country ?? 'Global' }}</div>
                    </div>
                    <div>
                        <span style="color:var(--muted);">Member since:</span>
                        <div style="font-weight:700;color:var(--dark);margin-top:2px;">{{ $gig->seller ? $gig->seller->created_at->format('M Y') : '2026' }}</div>
                    </div>
                    <div>
                        <span style="color:var(--muted);">Avg. response time:</span>
                        <div style="font-weight:700;color:var(--dark);margin-top:2px;">1 hour</div>
                    </div>
                    <div>
                        <span style="color:var(--muted);">Last delivery:</span>
                        <div style="font-weight:700;color:var(--dark);margin-top:2px;">About 1 day ago</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Reviews -->
        <div class="card">
            <div class="card-header flex justify-between items-center" style="background:#fafafa;">
                <span style="font-size:1.1rem;font-weight:800;">Reviews ({{ $gig->reviews->count() }})</span>
                <div style="display:flex;align-items:center;gap:4px;font-size:0.95rem;">
                    <span class="material-icons-round" style="color:var(--star);">star</span>
                    <strong>{{ number_format($gig->avg_rating, 1) }}</strong>
                </div>
            </div>
            <div class="card-body">
                @forelse($gig->reviews as $rev)
                    <div style="padding-bottom:1.25rem;margin-bottom:1.25rem;border-bottom:1px solid var(--border-light);">
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:0.5rem;">
                            <div style="width:32px;height:32px;border-radius:50%;background:#e2e8f0;font-weight:700;font-size:0.8rem;display:flex;align-items:center;justify-content:center;">
                                {{ strtoupper(substr($rev->buyer->name ?? 'B', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:0.9rem;color:var(--dark);">{{ $rev->buyer->name ?? 'Client' }}</div>
                                <div style="display:flex;align-items:center;gap:2px;">
                                    @for($i=1; $i<=5; $i++)
                                        <span class="material-icons-round" style="font-size:14px;color:{{ $i <= $rev->rating ? 'var(--star)' : '#cbd5e1' }};">star</span>
                                    @endfor
                                    <span style="font-size:0.75rem;color:var(--muted);margin-left:4px;">{{ $rev->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <p style="font-size:0.9rem;color:#475569;line-height:1.5;">{{ $rev->comment }}</p>
                    </div>
                @empty
                    <div style="text-align:center;padding:2rem;color:var(--muted);">
                        No client reviews posted yet. Be the first to order!
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Sticky Column: Fiverr Iconic 3-Tab Pricing Package Box -->
    <div style="position:sticky;top:90px;">
        <div class="card" style="border:1px solid var(--border);box-shadow:var(--shadow-md);">
            <!-- Package Tabs -->
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;border-bottom:1px solid var(--border);text-align:center;background:#fafafa;">
                <button type="button" class="pkg-tab active" id="tabBasic" onclick="switchPackage('basic', {{ $gig->price_basic }}, '{{ addslashes($gig->basic_desc ?? 'Basic Deliverables') }}')">
                    Basic
                </button>
                <button type="button" class="pkg-tab" id="tabStandard" onclick="switchPackage('standard', {{ $gig->price_standard ?? $gig->price_basic * 2 }}, '{{ addslashes($gig->standard_desc ?? 'Standard Package with full deliverables') }}')">
                    Standard
                </button>
                <button type="button" class="pkg-tab" id="tabPremium" onclick="switchPackage('premium', {{ $gig->price_premium ?? $gig->price_basic * 4 }}, '{{ addslashes($gig->premium_desc ?? 'Premium comprehensive end-to-end delivery') }}')">
                    Premium
                </button>
            </div>

            <div class="card-body" style="padding:1.75rem 1.5rem;">
                <!-- Price Display -->
                <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:1rem;">
                    <span id="pkgLabel" style="font-weight:700;font-size:1.05rem;color:var(--dark);">Basic Package</span>
                    <span id="pkgPrice" style="font-size:1.75rem;font-weight:800;color:var(--dark);">${{ number_format($gig->price_basic, 0) }}</span>
                </div>

                <p id="pkgDesc" style="font-size:0.9rem;color:var(--muted);line-height:1.5;min-height:50px;margin-bottom:1.25rem;">
                    {{ $gig->basic_desc ?? 'Core project delivery with source files and documentation.' }}
                </p>

                <!-- Delivery & Revisions Row -->
                <div style="display:flex;align-items:center;gap:1.5rem;font-size:0.85rem;font-weight:700;color:var(--dark);padding-bottom:1.25rem;border-bottom:1px solid var(--border-light);margin-bottom:1.25rem;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span class="material-icons-round" style="font-size:18px;color:var(--muted);">schedule</span>
                        <span>{{ $gig->delivery_days }} Days Delivery</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span class="material-icons-round" style="font-size:18px;color:var(--muted);">refresh</span>
                        <span>{{ $gig->revisions }} Revisions</span>
                    </div>
                </div>

                <!-- Features Included List (Fiverr Checkmarks) -->
                <div style="display:flex;flex-direction:column;gap:0.65rem;font-size:0.85rem;color:var(--dark);margin-bottom:1.75rem;">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span class="material-icons-round" style="color:var(--primary);font-size:18px;">check</span>
                        <span>Commercial Use License</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span class="material-icons-round" style="color:var(--primary);font-size:18px;">check</span>
                        <span>Full Source Code &amp; Assets Handover</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span class="material-icons-round" style="color:var(--primary);font-size:18px;">check</span>
                        <span>Dedicated Order Chat Collaboration</span>
                    </div>
                </div>

                <!-- Order Placement Form -->
                <form method="POST" action="{{ route('buyer.gig.order', $gig) }}">
                    @csrf
                    <input type="hidden" name="package" id="selectedPackageInput" value="basic">

                    <div class="form-group">
                        <label class="form-label" style="font-size:0.82rem;">Project Requirements (Optional)</label>
                        <textarea name="requirements" class="form-control" rows="2" placeholder="Tell the seller what you need..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-full" style="padding:0.9rem;font-size:1.05rem;border-radius:4px;box-shadow:var(--shadow-sm);margin-top:0.5rem;">
                        Continue (<span id="btnPrice">${{ number_format($gig->price_basic, 0) }}</span>)
                    </button>
                </form>

                <div style="text-align:center;margin-top:1rem;font-size:0.8rem;color:var(--muted);">
                    You won't be charged until you review order details.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .pkg-tab {
        background: transparent;
        border: none;
        padding: 0.9rem 0.5rem;
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--muted);
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.15s ease;
    }
    .pkg-tab:hover {
        color: var(--dark);
    }
    .pkg-tab.active {
        color: var(--primary);
        background: #ffffff;
        border-bottom-color: var(--primary);
    }
</style>

<script>
    function switchPackage(pkg, price, desc) {
        document.querySelectorAll('.pkg-tab').forEach(el => el.classList.remove('active'));
        if(pkg === 'basic') document.getElementById('tabBasic').classList.add('active');
        if(pkg === 'standard') document.getElementById('tabStandard').classList.add('active');
        if(pkg === 'premium') document.getElementById('tabPremium').classList.add('active');

        document.getElementById('selectedPackageInput').value = pkg;
        document.getElementById('pkgLabel').innerText = pkg.charAt(0).toUpperCase() + pkg.slice(1) + ' Package';
        document.getElementById('pkgPrice').innerText = '$' + price;
        document.getElementById('btnPrice').innerText = '$' + price;
        document.getElementById('pkgDesc').innerText = desc;
    }
</script>
@endsection
