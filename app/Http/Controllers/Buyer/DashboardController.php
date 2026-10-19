<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\{Gig, Order, Category, Conversation, Review};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $buyer = Auth::user();
        $stats = [
            'total_orders'   => $buyer->buyerOrders()->count(),
            'pending'        => $buyer->buyerOrders()->where('status','pending')->count(),
            'in_progress'    => $buyer->buyerOrders()->where('status','in_progress')->count(),
            'completed'      => $buyer->buyerOrders()->where('status','completed')->count(),
            'total_spent'    => $buyer->buyerOrders()->where('status','completed')->sum('price'),
        ];
        $recentOrders = $buyer->buyerOrders()->with(['seller','gig'])->latest()->take(6)->get();
        return view('buyer.dashboard', compact('stats','recentOrders'));
    }

    /* ─── Browse / Gigs ─── */
    public function browse(Request $request)
    {
        $categories = Category::where('is_active',true)->withCount('gigs')->get();
        $gigs = Gig::where('status','active')
                   ->with(['seller','category'])
                   ->when($request->q, fn($q,$s)=>$q->where('title','like',"%$s%")->orWhere('description','like',"%$s%"))
                   ->when($request->category, fn($q,$c)=>$q->where('category_id',$c))
                   ->when($request->min_price, fn($q,$p)=>$q->where('price_basic','>=',$p))
                   ->when($request->max_price, fn($q,$p)=>$q->where('price_basic','<=',$p))
                   ->latest()->paginate(12);
        return view('buyer.browse', compact('gigs','categories'));
    }

    public function showGig(Gig $gig)
    {
        abort_if($gig->status !== 'active', 404);
        $gig->increment('views');
        $gig->load(['seller','category','images','reviews.buyer']);
        return view('buyer.gig-detail', compact('gig'));
    }

    /* ─── Orders ─── */
    public function orders()
    {
        $orders = Auth::user()->buyerOrders()->with(['seller','gig'])->latest()->paginate(15);
        return view('buyer.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        abort_if($order->buyer_id !== Auth::id(), 403);
        $order->load(['seller','gig','conversation.messages.sender','review']);
        return view('buyer.orders.show', compact('order'));
    }

    public function placeOrder(Request $request, Gig $gig)
    {
        $request->validate([
            'package'      => 'required|in:basic,standard,premium',
            'requirements' => 'nullable|string',
        ]);

        $price = match($request->package) {
            'standard' => $gig->price_standard ?? $gig->price_basic,
            'premium'  => $gig->price_premium  ?? $gig->price_basic,
            default    => $gig->price_basic,
        };

        $order = Order::create([
            'order_number'  => Order::generateNumber(),
            'gig_id'        => $gig->id,
            'buyer_id'      => Auth::id(),
            'seller_id'     => $gig->seller_id,
            'package'       => $request->package,
            'price'         => $price,
            'delivery_days' => $gig->delivery_days,
            'status'        => 'pending',
            'payment_status'=> 'paid',
            'requirements'  => $request->requirements,
            'due_at'        => now()->addDays($gig->delivery_days),
        ]);

        // Create conversation
        Conversation::create([
            'order_id'  => $order->id,
            'buyer_id'  => Auth::id(),
            'seller_id' => $gig->seller_id,
        ]);

        $gig->increment('total_orders');

        return redirect()->route('buyer.order.show', $order)->with('success', 'Order placed successfully!');
    }

    public function markComplete(Order $order)
    {
        abort_if($order->buyer_id !== Auth::id(), 403);
        $order->update(['status' => 'completed']);
        return back()->with('success', 'Order marked as complete.');
    }

    public function requestRevision(Order $order)
    {
        abort_if($order->buyer_id !== Auth::id(), 403);
        $order->update(['status' => 'revision']);
        return back()->with('success', 'Revision requested.');
    }

    /* ─── Reviews ─── */
    public function storeReview(Request $request, Order $order)
    {
        abort_if($order->buyer_id !== Auth::id() || !in_array($order->status, ['delivered', 'completed']), 403);
        $data = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        Review::updateOrCreate(
            ['order_id' => $order->id],
            [
                'gig_id'   => $order->gig_id,
                'buyer_id' => Auth::id(),
                'rating'   => $data['rating'],
                'comment'  => $data['comment'] ?? null,
            ]
        );

        // Mark order as completed if it was delivered
        if ($order->status !== 'completed') {
            $order->update(['status' => 'completed']);
        }

        // Update gig average rating and total reviews
        $gig = $order->gig;
        $avg = $gig->reviews()->avg('rating');
        $gig->update([
            'avg_rating'    => round($avg, 2),
            'total_reviews' => $gig->reviews()->count(),
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted and published on this Gig.');
    }

    public function profile()
    {
        return view('buyer.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name'   => 'required|string|max:100',
            'bio'    => 'nullable|string|max:500',
            'country'=> 'nullable|string|max:60',
            'phone'  => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:1024',
        ]);
        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars','public');
        }
        $user->update($data);
        return back()->with('success', 'Profile updated!');
    }
}
