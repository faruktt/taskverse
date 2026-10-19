@extends('layouts.app')
@section('title','Order #' . $order->order_number)
@section('page-title','Order Details')
@section('sidebar-role','Buyer Account')
@section('sidebar-nav')
<div class="nav-section-label">Overview</div>
<a href="{{ route('buyer.dashboard') }}" class="nav-link"><span class="material-icons-round">dashboard</span> Dashboard</a>
<div class="nav-section-label">Marketplace</div>
<a href="{{ route('buyer.browse') }}" class="nav-link"><span class="material-icons-round">search</span> Browse Gigs</a>
<a href="{{ route('buyer.orders') }}" class="nav-link active"><span class="material-icons-round">receipt_long</span> My Orders</a>
<div class="nav-section-label">Account</div>
<a href="{{ route('buyer.profile') }}" class="nav-link"><span class="material-icons-round">manage_accounts</span> Profile</a>
@endsection
@section('content')
@php $b = $order->statusBadge(); @endphp
<div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;">
    <div>
        <div class="card mb-4">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <span class="material-icons-round">receipt_long</span>
                    {{ $order->order_number }}
                    <span class="badge" style="background:{{ $b['color'] }}20;color:{{ $b['color'] }};">{{ $b['label'] }}</span>
                </div>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.5rem;">
                    <div><div class="text-xs text-muted mb-1">Package</div><div class="font-semibold">{{ ucfirst($order->package) }}</div></div>
                    <div><div class="text-xs text-muted mb-1">Price</div><div class="font-bold" style="font-size:1.2rem;">${{ number_format($order->price,2) }}</div></div>
                    <div><div class="text-xs text-muted mb-1">Due Date</div><div class="font-semibold">{{ $order->due_at ? $order->due_at->format('M d, Y') : 'N/A' }}</div></div>
                </div>
                @if($order->delivery_note)
                <div class="mt-4">
                    <div class="text-xs text-muted mb-2">Seller Delivery Note</div>
                    <div style="background:rgba(108,99,255,0.08);border:1px solid rgba(108,99,255,0.2);border-radius:10px;padding:1rem;font-size:0.88rem;">{{ $order->delivery_note }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if($order->status === 'delivered')
        <div class="card mb-4">
            <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">task_alt</span> Review Delivery</div>
            <div class="card-body">
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('buyer.order.complete', $order) }}">
                        @csrf
                        <button type="submit" class="btn btn-success"><span class="material-icons-round">check_circle</span> Accept & Complete</button>
                    </form>
                    <form method="POST" action="{{ route('buyer.order.revision', $order) }}">
                        @csrf
                        <button type="submit" class="btn btn-warning"><span class="material-icons-round">refresh</span> Request Revision</button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Review -->
        @if($order->status === 'completed' && !$order->review)
        <div class="card mb-4">
            <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">star</span> Leave a Review</div>
            <div class="card-body">
                <form method="POST" action="{{ route('buyer.order.review', $order) }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Rating</label>
                        <div style="display:flex;gap:0.5rem;">
                            @for($i=1;$i<=5;$i++)
                            <label style="cursor:pointer;font-size:1.5rem;color:var(--border);" id="star-{{ $i }}" onclick="setRating({{ $i }})">
                                <span class="material-icons-round">star</span>
                                <input type="radio" name="rating" value="{{ $i }}" style="display:none;">
                            </label>
                            @endfor
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Comment</label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="Share your experience..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><span class="material-icons-round">send</span> Submit Review</button>
                </form>
            </div>
        </div>
        @endif
        @if($order->review)
        <div class="card mb-4">
            <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">star</span> Your Review</div>
            <div class="card-body">
                <div class="flex gap-1 mb-2">
                    @for($i=1;$i<=5;$i++)<span class="material-icons-round" style="font-size:18px;color:{{ $i<=$order->review->rating ? 'var(--warning)':'var(--border)' }};">star</span>@endfor
                </div>
                <p style="font-size:0.9rem;color:var(--text-muted);">{{ $order->review->comment }}</p>
            </div>
        </div>
        @endif

        <!-- Chat -->
        @if($order->conversation)
        <div class="card">
            <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">chat</span> Messages with {{ $order->seller->name }}</div>
            <div class="chat-container">
                <div class="chat-messages" id="chatMessages">
                    @forelse($order->conversation->messages as $msg)
                    <div class="msg-bubble {{ $msg->sender_id === auth()->id() ? 'mine' : 'theirs' }}">
                        <div class="msg-content">{{ $msg->body }}</div>
                        <div class="msg-time">{{ $msg->created_at->diffForHumans() }}</div>
                    </div>
                    @empty
                    <div style="text-align:center;color:var(--text-faint);font-size:0.85rem;padding:1rem;">No messages yet</div>
                    @endforelse
                </div>
                <form class="chat-input" method="POST" action="{{ route('message.send', $order->conversation) }}">
                    @csrf
                    <input type="text" name="body" class="form-control" placeholder="Type a message..." required>
                    <button type="submit" class="btn btn-primary"><span class="material-icons-round">send</span></button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <div>
        <div class="card mb-4">
            <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">person</span> Seller</div>
            <div class="card-body" style="text-align:center;">
                <img src="{{ $order->seller->avatarUrl() }}" class="avatar avatar-lg" style="margin:0 auto 1rem;">
                <div style="font-weight:700;">{{ $order->seller->name }}</div>
                <div class="text-muted text-sm">{{ $order->seller->country ?? '' }}</div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">work</span> Gig</div>
            <div class="card-body">
                @if($order->gig->thumbnail)
                    <img src="{{ asset('storage/'.$order->gig->thumbnail) }}" style="width:100%;height:100px;object-fit:cover;border-radius:8px;margin-bottom:0.75rem;">
                @endif
                <div style="font-weight:600;font-size:0.9rem;">{{ $order->gig->title }}</div>
            </div>
        </div>
    </div>
</div>

<script>
function setRating(r) {
    for(let i=1;i<=5;i++) {
        const el = document.getElementById('star-'+i);
        if(el) el.style.color = i<=r ? 'var(--warning)' : 'var(--border)';
    }
    document.querySelector('input[name="rating"][value="'+r+'"]').checked = true;
}
const chat = document.getElementById('chatMessages');
if(chat) chat.scrollTop = chat.scrollHeight;
</script>
@endsection
