<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Gig, Order, Category, AdminUser};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'    => User::count(),
            'total_sellers'  => User::where('role','seller')->count(),
            'total_buyers'   => User::where('role','buyer')->count(),
            'total_gigs'     => Gig::count(),
            'pending_gigs'   => Gig::where('status','pending')->count(),
            'total_orders'   => Order::count(),
            'revenue'        => Order::where('status','completed')->sum('price'),
            'banned_users'   => User::where('is_banned',true)->count(),
        ];
        $recentOrders = Order::with(['buyer','seller','gig'])->latest()->take(8)->get();
        $recentUsers  = User::latest()->take(8)->get();
        return view('admin.dashboard', compact('stats','recentOrders','recentUsers'));
    }

    /* ─ Users ─ */
    public function users(Request $request)
    {
        $users = User::when($request->search, fn($q,$s)=>$q->where('name','like',"%$s%")->orWhere('email','like',"%$s%"))
                     ->when($request->role, fn($q,$r)=>$q->where('role',$r))
                     ->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function banUser(User $user)
    {
        $user->update(['is_banned' => !$user->is_banned]);
        return back()->with('success', $user->is_banned ? 'User banned.' : 'User unbanned.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    /* ─ Gigs ─ */
    public function gigs(Request $request)
    {
        $gigs = Gig::with(['seller','category'])
                   ->when($request->status, fn($q,$s)=>$q->where('status',$s))
                   ->latest()->paginate(20);
        return view('admin.gigs.index', compact('gigs'));
    }

    public function approveGig(Gig $gig)
    {
        $gig->update(['status' => 'active']);
        return back()->with('success', 'Gig approved.');
    }

    public function rejectGig(Gig $gig)
    {
        $gig->update(['status' => 'rejected']);
        return back()->with('success', 'Gig rejected.');
    }

    public function deleteGig(Gig $gig)
    {
        $gig->delete();
        return back()->with('success', 'Gig deleted.');
    }

    /* ─ Orders ─ */
    public function orders(Request $request)
    {
        $orders = Order::with(['buyer','seller','gig'])
                       ->when($request->status, fn($q,$s)=>$q->where('status',$s))
                       ->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    /* ─ Categories ─ */
    public function categories()
    {
        $categories = Category::withCount('gigs')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'icon'  => 'required|string|max:60',
            'color' => 'required|string|max:20',
        ]);
        $data['slug'] = \Str::slug($data['name']);
        Category::create($data);
        return back()->with('success', 'Category created.');
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }
}
