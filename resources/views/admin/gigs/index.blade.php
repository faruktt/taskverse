@extends('layouts.admin')
@section('title', 'Manage Gigs')
@section('page-title', 'Gig Moderation')

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>All Marketplace Services</h1>
        <p>Review, approve, reject or remove gigs posted by sellers</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
            <a href="{{ route('admin.gigs') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-secondary' }} btn-sm">All Gigs</a>
            <a href="{{ route('admin.gigs', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                <span class="material-icons-round" style="font-size:16px;">pending_actions</span> Pending Review
            </a>
            <a href="{{ route('admin.gigs', ['status' => 'active']) }}" class="btn {{ request('status') == 'active' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                <span class="material-icons-round" style="font-size:16px;">check_circle</span> Active
            </a>
            <a href="{{ route('admin.gigs', ['status' => 'rejected']) }}" class="btn {{ request('status') == 'rejected' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                <span class="material-icons-round" style="font-size:16px;">cancel</span> Rejected
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Gig Service</th>
                        <th>Seller</th>
                        <th>Category</th>
                        <th>Pricing</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gigs as $gig)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    @if($gig->thumbnail)
                                        <img src="{{ asset('storage/' . $gig->thumbnail) }}" alt="" style="width:48px;height:48px;border-radius:var(--radius-sm);object-fit:cover;">
                                    @else
                                        <div style="width:48px;height:48px;border-radius:var(--radius-sm);background:rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                                            <span class="material-icons-round">work</span>
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight:600;max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $gig->title }}</div>
                                        <div style="font-size:0.75rem;color:var(--text-muted);">Orders: {{ $gig->total_orders }} &bull; Rating: {{ $gig->avg_rating }} ★</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight:500;">{{ $gig->seller->name ?? 'Seller' }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $gig->seller->email ?? '' }}</div>
                            </td>
                            <td>
                                <span class="badge" style="background:rgba(255,255,255,0.08);color:var(--text-primary);">
                                    {{ $gig->category->name ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td>
                                <div style="font-weight:700;color:var(--accent);">${{ number_format($gig->price_basic, 2) }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">Delivery: {{ $gig->delivery_days }}d</div>
                            </td>
                            <td>
                                @if($gig->status === 'active')
                                    <span class="badge badge-success"><span class="material-icons-round" style="font-size:12px;">check</span> Active</span>
                                @elseif($gig->status === 'pending')
                                    <span class="badge badge-warning"><span class="material-icons-round" style="font-size:12px;">hourglass_top</span> Pending</span>
                                @elseif($gig->status === 'rejected')
                                    <span class="badge badge-error"><span class="material-icons-round" style="font-size:12px;">close</span> Rejected</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($gig->status) }}</span>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <div style="display:inline-flex;gap:6px;">
                                    @if($gig->status !== 'active')
                                        <form method="POST" action="{{ route('admin.gig.approve', $gig) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="Approve Gig">
                                                <span class="material-icons-round" style="font-size:16px;">check</span> Approve
                                            </button>
                                        </form>
                                    @endif
                                    @if($gig->status !== 'rejected')
                                        <form method="POST" action="{{ route('admin.gig.reject', $gig) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary btn-sm" title="Reject Gig">
                                                <span class="material-icons-round" style="font-size:16px;">close</span> Reject
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.gig.delete', $gig) }}" onsubmit="return confirm('Permanently delete this gig?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Gig">
                                            <span class="material-icons-round" style="font-size:16px;">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding:3rem;color:var(--text-muted);">
                                <span class="material-icons-round" style="font-size:3rem;display:block;margin-bottom:0.5rem;opacity:0.4;">work_off</span>
                                No gigs found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($gigs->hasPages())
            <div style="padding:1.25rem;border-top:1px solid var(--border);">
                {{ $gigs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
