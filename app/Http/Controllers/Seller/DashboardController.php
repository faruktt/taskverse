<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\{Gig, Order, Review, Category, GigImage};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $seller = Auth::user();
        $stats  = [
            'total_gigs'    => $seller->gigs()->count(),
            'active_gigs'   => $seller->gigs()->where('status','active')->count(),
            'total_orders'  => $seller->sellerOrders()->count(),
            'pending'       => $seller->sellerOrders()->where('status','pending')->count(),
            'in_progress'   => $seller->sellerOrders()->where('status','in_progress')->count(),
            'completed'     => $seller->sellerOrders()->where('status','completed')->count(),
            'earnings'      => $seller->sellerOrders()->where('status','completed')->sum('price'),
        ];
        $recentOrders = $seller->sellerOrders()->with(['buyer','gig'])->latest()->take(6)->get();
        return view('seller.dashboard', compact('stats','recentOrders'));
    }

    /* ─── Gigs ─── */
    public function gigs()
    {
        $gigs = Auth::user()->gigs()->with('category')->latest()->get();
        $gigCount = $gigs->count();
        $canCreateGig = $gigCount < 4;
        return view('seller.gigs.index', compact('gigs', 'gigCount', 'canCreateGig'));
    }

    public function createGig()
    {
        $seller = Auth::user();
        if ($seller->gigs()->count() >= 4) {
            return redirect()->route('seller.gigs')->with('error', 'You have reached the maximum limit of 4 gigs allowed for your seller account.');
        }

        $categories = Category::where('is_active',true)->get();
        return view('seller.gigs.create', compact('categories'));
    }

    public function storeGig(Request $request)
    {
        $seller = Auth::user();
        if ($seller->gigs()->count() >= 4) {
            return redirect()->route('seller.gigs')->with('error', 'You cannot create more than 4 gigs. Please edit or delete an existing gig.');
        }

        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'required|string',
            'price_basic'    => 'required|numeric|min:1',
            'price_standard' => 'nullable|numeric|min:1',
            'price_premium'  => 'nullable|numeric|min:1',
            'basic_desc'     => 'nullable|string|max:200',
            'standard_desc'  => 'nullable|string|max:200',
            'premium_desc'   => 'nullable|string|max:200',
            'delivery_days'  => 'required|integer|min:1',
            'revisions'      => 'required|integer|min:0',
            'tags'           => 'nullable|string',
            'thumbnail'      => 'nullable|image|max:2048',
            'images'         => 'nullable|array',
            'images.*'       => 'image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('gigs/thumbnails','public');
        }

        $gig = $seller->gigs()->create(array_merge($data, ['status' => 'pending']));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $img) {
                $path = $img->store('gigs/images','public');
                GigImage::create(['gig_id' => $gig->id, 'path' => $path, 'sort_order' => $i]);
            }
        }

        return redirect()->route('seller.gigs')->with('success', 'Gig submitted for review! (' . $seller->gigs()->count() . '/4 gigs used)');
    }

    public function editGig(Gig $gig)
    {
        abort_if($gig->seller_id !== Auth::id(), 403);
        $categories = Category::where('is_active',true)->get();
        return view('seller.gigs.edit', compact('gig','categories'));
    }

    public function updateGig(Request $request, Gig $gig)
    {
        abort_if($gig->seller_id !== Auth::id(), 403);
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'required|string',
            'price_basic'    => 'required|numeric|min:1',
            'price_standard' => 'nullable|numeric|min:1',
            'price_premium'  => 'nullable|numeric|min:1',
            'basic_desc'     => 'nullable|string|max:200',
            'standard_desc'  => 'nullable|string|max:200',
            'premium_desc'   => 'nullable|string|max:200',
            'delivery_days'  => 'required|integer|min:1',
            'revisions'      => 'required|integer|min:0',
            'tags'           => 'nullable|string',
            'thumbnail'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($gig->thumbnail) Storage::disk('public')->delete($gig->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('gigs/thumbnails','public');
        }

        $gig->update($data);
        return redirect()->route('seller.gigs')->with('success', 'Gig updated!');
    }

    public function deleteGig(Gig $gig)
    {
        abort_if($gig->seller_id !== Auth::id(), 403);
        $gig->delete();
        return back()->with('success', 'Gig deleted.');
    }

    /* ─── Orders ─── */
    public function orders()
    {
        $orders = Auth::user()->sellerOrders()->with(['buyer','gig'])->latest()->paginate(15);
        return view('seller.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        abort_if($order->seller_id !== Auth::id(), 403);
        $order->load(['buyer','gig','conversation.messages.sender']);
        return view('seller.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        abort_if($order->seller_id !== Auth::id(), 403);
        $request->validate(['status' => 'required|in:in_progress,delivered,cancelled']);
        $order->update([
            'status'        => $request->status,
            'delivery_note' => $request->delivery_note,
        ]);
        return back()->with('success', 'Order status updated.');
    }

    public function profile()
    {
        return view('seller.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'bio'     => 'nullable|string|max:500',
            'country' => 'nullable|string|max:60',
            'phone'   => 'nullable|string|max:20',
            'avatar'  => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars','public');
        }

        $user->update($data);
        return back()->with('success', 'Profile updated!');
    }
}
