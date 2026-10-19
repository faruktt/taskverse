@extends('layouts.app')
@section('title','Order #' . $order->order_number)
@section('page-title','Order Details')
@section('sidebar-role','Seller Account')
@section('sidebar-nav')
<div class="nav-section-label">Overview</div>
<a href="{{ route('seller.dashboard') }}" class="nav-link"><span class="material-icons-round">dashboard</span> Dashboard</a>
<div class="nav-section-label">Business</div>
<a href="{{ route('seller.gigs') }}" class="nav-link"><span class="material-icons-round">work</span> My Gigs</a>
<a href="{{ route('seller.orders') }}" class="nav-link active"><span class="material-icons-round">receipt_long</span> Orders</a>
<div class="nav-section-label">Account</div>
<a href="{{ route('seller.profile') }}" class="nav-link"><span class="material-icons-round">manage_accounts</span> Profile</a>
@endsection
@section('content')
@php $b = $order->statusBadge(); @endphp
<div style="display:grid;grid-template-columns:1fr 360px;gap:1.5rem;">
    <div>
        <!-- Order info -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="flex items-center gap-3">
                    <span class="material-icons-round">receipt_long</span>
                    Order {{ $order->order_number }}
                    <span class="badge" style="background:{{ $b['color'] }}20;color:{{ $b['color'] }};">{{ $b['label'] }}</span>
                </div>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.5rem;">
                    <div><div class="text-xs text-muted mb-1">Package</div><div class="font-semibold">{{ ucfirst($order->package) }}</div></div>
                    <div><div class="text-xs text-muted mb-1">Price</div><div class="font-bold" style="font-size:1.2rem;">${{ number_format($order->price,2) }}</div></div>
                    <div><div class="text-xs text-muted mb-1">Due Date</div><div class="font-semibold">{{ $order->due_at ? $order->due_at->format('M d, Y') : 'N/A' }}</div></div>
                </div>
                @if($order->requirements)
                <div class="mt-4">
                    <div class="text-xs text-muted mb-2">Buyer Requirements</div>
                    <div style="background:var(--bg-2);border-radius:10px;padding:1rem;font-size:0.88rem;">{{ $order->requirements }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Update Status -->
        @if(!in_array($order->status,['completed','cancelled']))
        <div class="card mb-4">
            <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">update</span> Update Status</div>
            <div class="card-body">
                <form method="POST" action="{{ route('seller.order.status', $order) }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">New Status</label>
                        <select name="status" class="form-control">
                            @if($order->status === 'pending')
                                <option value="in_progress">Accept — Mark In Progress</option>
                                <option value="cancelled">Decline / Cancel</option>
                            @elseif($order->status === 'in_progress')
                                <option value="delivered">Mark Delivered</option>
                                <option value="cancelled">Cancel</option>
                            @elseif($order->status === 'revision')
                                <option value="in_progress">Working on Revision</option>
                                <option value="delivered">Re-deliver</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Delivery Note (optional)</label>
                        <textarea name="delivery_note" class="form-control" rows="3" placeholder="Add a note for the buyer...">{{ $order->delivery_note }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><span class="material-icons-round">send</span> Update</button>
                </form>
            </div>
        </div>
        @endif

        <!-- Chat -->
        @if($order->conversation)
        <div class="card">
            <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">chat</span> Messages with {{ $order->buyer->name }}</div>
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

    <!-- Buyer info -->
    <div>
        <div class="card mb-4">
            <div class="card-header"><span class="material-icons-round" style="vertical-align:middle;font-size:18px;">person</span> Buyer</div>
            <div class="card-body" style="text-align:center;">
                <img src="{{ $order->buyer->avatarUrl() }}" class="avatar avatar-lg" style="margin:0 auto 1rem;">
                <div style="font-weight:700;">{{ $order->buyer->name }}</div>
                <div class="text-muted text-sm">{{ $order->buyer->country ?? 'Unknown' }}</div>
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
  const chat = document.getElementById('chatMessages');
  if(chat) chat.scrollTop = chat.scrollHeight;
</script>
@endsection
