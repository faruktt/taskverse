<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Gig, Order, Category, AdminUser, Withdrawal};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'         => User::count(),
            'total_sellers'       => User::where('role','seller')->count(),
            'total_buyers'        => User::where('role','buyer')->count(),
            'total_gigs'          => Gig::count(),
            'pending_gigs'        => Gig::where('status','pending')->count(),
            'total_orders'        => Order::count(),
            'revenue'             => Order::where('status','completed')->sum('price'),
            'banned_users'        => User::where('is_banned',true)->count(),
            'pending_withdrawals' => Withdrawal::where('status','pending')->count(),
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
                     ->when($request->status, function($q, $status) {
                         if ($status === 'active') {
                             $q->where('is_banned', false);
                         } elseif ($status === 'inactive') {
                             $q->where('is_banned', true);
                         }
                     })
                     ->withCount(['gigs', 'sellerOrders', 'buyerOrders'])
                     ->latest()->paginate(20);

        $counts = [
            'all'      => User::count(),
            'sellers'  => User::where('role', 'seller')->count(),
            'buyers'   => User::where('role', 'buyer')->count(),
            'active'   => User::where('is_banned', false)->count(),
            'inactive' => User::where('is_banned', true)->count(),
        ];

        return view('admin.users.index', compact('users', 'counts'));
    }

    public function banUser(User $user)
    {
        $user->update(['is_banned' => !$user->is_banned]);
        $statusText = $user->is_banned ? 'Inactive' : 'Active';
        return back()->with('success', "User '{$user->name}' is now marked as {$statusText}.");
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

    /* ─ Withdrawals ─ */
    public function withdrawals(Request $request)
    {
        $withdrawals = Withdrawal::with('user')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->method, fn($q, $m) => $q->where('method', $m))
            ->latest()
            ->paginate(20);

        $counts = [
            'all'       => Withdrawal::count(),
            'pending'   => Withdrawal::where('status', 'pending')->count(),
            'completed' => Withdrawal::where('status', 'completed')->count(),
            'rejected'  => Withdrawal::where('status', 'rejected')->count(),
        ];

        return view('admin.withdrawals.index', compact('withdrawals', 'counts'));
    }

    public function approveWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        $data = $request->validate([
            'admin_note' => 'nullable|string|max:500',
        ]);

        $note = $data['admin_note'] ?: 'Approved by admin.';
        $withdrawal->update([
            'status'     => 'completed',
            'admin_note' => $note,
        ]);

        return back()->with('success', "Withdrawal #{$withdrawal->id} has been approved with note.");
    }

    public function rejectWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        $data = $request->validate([
            'admin_note' => 'required|string|max:500',
        ]);

        $withdrawal->update([
            'status'     => 'rejected',
            'admin_note' => $data['admin_note'],
        ]);

        return back()->with('success', "Withdrawal #{$withdrawal->id} has been rejected and funds refunded to seller balance.");
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
