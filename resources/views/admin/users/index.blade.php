@extends('layouts.admin')
@section('title', 'Manage Users')
@section('page-title', 'User Management')

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>Users Directory</h1>
        <p>Monitor, moderate, ban or manage all registered buyers and sellers</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users') }}" style="display:flex;gap:1rem;flex-wrap:wrap;">
            <div style="flex:1;min-width:240px;">
                <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
            </div>
            <div style="width:160px;">
                <select name="role" class="form-control">
                    <option value="">All Roles</option>
                    <option value="seller" {{ request('role') == 'seller' ? 'selected' : '' }}>Sellers</option>
                    <option value="buyer" {{ request('role') == 'buyer' ? 'selected' : '' }}>Buyers</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <span class="material-icons-round">search</span> Filter
            </button>
            @if(request('search') || request('role'))
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Reset</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Country</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="avatar avatar-sm">
                                    @else
                                        <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.85rem;color:#fff;">
                                            {{ strtoupper(substr($user->name,0,1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight:600;">{{ $user->name }}</div>
                                        <div style="font-size:0.8rem;color:var(--text-muted);">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $user->role === 'seller' ? 'badge-primary' : 'badge-info' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td style="color:var(--text-muted);font-size:0.85rem;">{{ $user->country ?? '—' }}</td>
                            <td style="color:var(--text-muted);font-size:0.85rem;">{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                @if($user->is_banned)
                                    <span class="badge badge-error">Suspended</span>
                                @else
                                    <span class="badge badge-success">Active</span>
                                @endif
                            </td>
                            <td style="text-align:right;">
                                <div style="display:inline-flex;gap:6px;">
                                    <form method="POST" action="{{ route('admin.user.ban', $user) }}" onsubmit="return confirm('Change ban status for {{ $user->name }}?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $user->is_banned ? 'btn-success' : 'btn-secondary' }}" title="{{ $user->is_banned ? 'Unban' : 'Ban' }}">
                                            <span class="material-icons-round" style="font-size:16px;">{{ $user->is_banned ? 'lock_open' : 'block' }}</span>
                                            {{ $user->is_banned ? 'Unban' : 'Ban' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.user.delete', $user) }}" onsubmit="return confirm('Permanently delete {{ $user->name }}? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete User">
                                            <span class="material-icons-round" style="font-size:16px;">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding:3rem;color:var(--text-muted);">
                                <span class="material-icons-round" style="font-size:3rem;display:block;margin-bottom:0.5rem;opacity:0.4;">group_off</span>
                                No users found matching query
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div style="padding:1.25rem;border-top:1px solid var(--border);">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
