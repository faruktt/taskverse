@extends('layouts.app')
@section('title', 'Seller Dashboard')
@section('sidebar-role', 'Seller Workspace')
@section('page-title', 'Seller Dashboard')

@section('sidebar-nav')
<div class="nav-section-label">Overview</div>
<a href="{{ route('seller.dashboard') }}" class="nav-link active"><span class="material-icons-round">dashboard</span> Dashboard</a>
<div class="nav-section-label">Business</div>
<a href="{{ route('seller.gigs') }}" class="nav-link"><span class="material-icons-round">work</span> My Gigs</a>
<a href="{{ route('seller.orders') }}" class="nav-link"><span class="material-icons-round">receipt_long</span> Orders</a>
<div class="nav-section-label">Account</div>
<a href="{{ route('seller.profile') }}" class="nav-link"><span class="material-icons-round">manage_accounts</span> Profile</a>
@endsection

@section('content')
<div class="dashboard-split-grid">
    <!-- Left Column: Seller Profile & Metrics -->
    <div>
        <div class="card mb-4">
            <div class="card-body" style="text-align:center;padding:2rem 1.5rem 1.5rem;">
                <div style="position:relative;display:inline-block;margin-bottom:0.75rem;">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="avatar avatar-lg" style="width:84px;height:84px;border-radius:50%;margin:0 auto;object-fit:cover;">
                    @else
                        <div style="width:84px;height:84px;border-radius:50%;background:linear-gradient(135deg,var(--dark),#475569);color:#fff;font-size:2rem;font-weight:700;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <h3 style="font-size:1.15rem;font-weight:800;color:var(--dark);margin-bottom:2px;">{{ auth()->user()->name }}</h3>
                <div style="font-size:0.8rem;color:var(--muted);margin-bottom:0.75rem;">{{ auth()->user()->email }}</div>
                <span class="badge badge-primary" style="margin-bottom:1.5rem;">
                    <span class="material-icons-round" style="font-size:14px;">verified</span> Level 2 Seller
                </span>

                <!-- Performance Metrics (Fiverr Standard) -->
                <div style="border-top:1px solid var(--border-light);padding-top:1.25rem;text-align:left;display:flex;flex-direction:column;gap:0.75rem;font-size:0.85rem;">
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--muted);">Response rate</span>
                        <strong style="color:var(--primary-dark);">100%</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--muted);">Delivered on time</span>
                        <strong style="color:var(--primary-dark);">100%</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--muted);">Order completion</span>
                        <strong style="color:var(--primary-dark);">100%</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;border-top:1px dashed var(--border-light);padding-top:0.75rem;">
                        <span style="color:var(--muted);">Total Earned</span>
                        <strong style="color:var(--dark);font-size:0.95rem;">৳{{ number_format($stats['earnings_bdt'], 0) }} <span style="font-size:0.75rem;color:var(--muted);font-weight:normal;">(${{ number_format($stats['earnings'], 2) }})</span></strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--muted);">Available to Withdraw</span>
                        <strong style="color:var(--primary-dark);font-size:0.95rem;">৳{{ number_format($stats['available_bdt'], 0) }} <span style="font-size:0.75rem;color:var(--muted);font-weight:normal;">(${{ number_format($stats['available_balance'], 2) }})</span></strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span style="color:var(--muted);">Total Withdrawn</span>
                        <strong style="color:var(--muted);font-size:0.95rem;">৳{{ number_format($stats['withdrawn_bdt'], 0) }}</strong>
                    </div>
                </div>

                <!-- 4 Gigs Policy Badge -->
                <div style="margin-top:1.25rem;padding:0.75rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;text-align:left;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;font-size:0.82rem;font-weight:700;">
                        <span>Gigs Limit:</span>
                        <span style="color:{{ $stats['total_gigs'] >= 4 ? '#b45309' : 'var(--primary-dark)' }};">
                            {{ $stats['total_gigs'] }} / 4 Used
                        </span>
                    </div>
                    <div style="width:100%;height:6px;background:#e2e8f0;border-radius:999px;overflow:hidden;">
                        <div style="width:{{ ($stats['total_gigs'] / 4) * 100 }}%;height:100%;background:{{ $stats['total_gigs'] >= 4 ? '#f59e0b' : 'var(--primary)' }};"></div>
                    </div>
                    <div style="font-size:0.72rem;color:var(--muted);margin-top:4px;">
                        Max limit: 4 published gigs for freelancers.
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" style="background:#fafafa;font-size:0.9rem;">
                <span class="material-icons-round" style="font-size:18px;">lightbulb</span> Seller Tips
            </div>
            <div class="card-body" style="font-size:0.85rem;color:var(--muted);line-height:1.6;">
                Quickly responding to incoming buyer inquiries increases your chances of securing higher-ticket orders by 40%.
            </div>
        </div>
    </div>

    <!-- Right Column: Stats & Active Orders -->
    <div>
        <!-- Stats Row -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background:var(--primary-light);color:var(--primary-dark);"><span class="material-icons-round">payments</span></div>
                <div class="stat-value" style="font-size:1.35rem;">৳{{ number_format($stats['earnings_bdt'], 0) }}</div>
                <div class="stat-label">Total Earned (${{ number_format($stats['earnings'], 2) }})</div>
            </div>
            <div class="stat-card" style="border:1.5px solid #bbf7d0;">
                <div class="stat-icon" style="background:#dcfce7;color:#15803d;"><span class="material-icons-round">account_balance_wallet</span></div>
                <div class="stat-value" style="font-size:1.35rem;color:#15803d;">৳{{ number_format($stats['available_bdt'], 0) }}</div>
                <div class="stat-label">Available Balance (${{ number_format($stats['available_balance'], 2) }})</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#e0f2fe;color:#0369a1;"><span class="material-icons-round">timelapse</span></div>
                <div class="stat-value" style="font-size:1.35rem;">{{ $stats['in_progress'] + $stats['pending'] }}</div>
                <div class="stat-label">Active Orders</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:#fef3c7;color:#b45309;"><span class="material-icons-round">check_circle</span></div>
                <div class="stat-value" style="font-size:1.35rem;">{{ $stats['completed'] }}</div>
                <div class="stat-label">Completed Deliveries</div>
            </div>
        </div>

        <!-- Freelancer Wallet & Withdrawal Section -->
        <div class="card mb-4" style="border:1px solid #bbf7d0;background:#ffffff;box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);">
            <div class="card-header flex justify-between items-center" style="background:#f0fdf4;border-bottom:1px solid #dcfce7;padding:1rem 1.25rem;">
                <div style="display:flex;align-items:center;gap:0.6rem;">
                    <span class="material-icons-round" style="color:#16a34a;font-size:24px;">account_balance_wallet</span>
                    <div>
                        <div style="font-weight:700;color:#166534;font-size:1.05rem;">Earnings & Withdrawal</div>
                        <div style="font-size:0.78rem;color:#15803d;">Earned money from completed orders can be withdrawn directly</div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm" id="toggleWithdrawBtn" style="background:#16a34a;border-color:#16a34a;font-weight:600;display:inline-flex;align-items:center;gap:0.35rem;">
                    <span class="material-icons-round" style="font-size:17px;">payments</span>
                    <span>Withdraw Funds</span>
                </button>
            </div>

            <div class="card-body" style="padding:1.25rem;">
                <!-- Wallet Balance Quick Bar -->
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.25rem;padding:1rem;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;">
                    <div>
                        <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;font-weight:700;">Available For Payout</div>
                        <div style="font-size:1.4rem;font-weight:800;color:#16a34a;">
                            ৳{{ number_format($stats['available_bdt'], 0) }}
                            <span style="font-size:0.82rem;font-weight:600;color:var(--muted);">(${{ number_format($stats['available_balance'], 2) }})</span>
                        </div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;font-weight:700;">Total Lifetime Earned</div>
                        <div style="font-size:1.25rem;font-weight:700;color:var(--dark);">
                            ৳{{ number_format($stats['earnings_bdt'], 0) }}
                            <span style="font-size:0.82rem;font-weight:500;color:var(--muted);">(${{ number_format($stats['earnings'], 2) }})</span>
                        </div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;font-weight:700;">Total Withdrawn</div>
                        <div style="font-size:1.25rem;font-weight:700;color:var(--muted);">
                            ৳{{ number_format($stats['withdrawn_bdt'], 0) }}
                            <span style="font-size:0.82rem;font-weight:500;color:var(--muted);">(${{ number_format($stats['withdrawn'], 2) }})</span>
                        </div>
                    </div>
                </div>

                <!-- Withdrawal Form (Collapsible/Interactive) -->
                <div id="withdrawFormContainer" style="{{ $errors->any() ? 'display:block;' : 'display:none;' }}background:#f0fdf4;border:1.5px solid #86efac;border-radius:8px;padding:1.25rem;margin-bottom:1.5rem;">
                    <div style="font-weight:700;color:#166534;margin-bottom:1rem;display:flex;align-items:center;gap:0.4rem;font-size:0.95rem;">
                        <span class="material-icons-round" style="font-size:20px;">send</span>
                        New Withdrawal Request
                    </div>

                    @if($stats['available_balance'] <= 0)
                        <div style="padding:1rem;background:#ffffff;border-radius:6px;border:1px dashed #cbd5e1;color:var(--muted);font-size:0.88rem;text-align:center;">
                            <span class="material-icons-round" style="color:#94a3b8;font-size:28px;display:block;margin-bottom:0.25rem;">hourglass_empty</span>
                            Your current withdrawable balance is <strong>$0.00 (৳0)</strong>.<br>
                            Deliver orders and when buyers complete them, your earnings will appear here for instant withdrawal.
                        </div>
                    @else
                        <form action="{{ route('seller.withdraw') }}" method="POST">
                            @csrf
                            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-bottom:1rem;">
                                <div>
                                    <label class="form-label" style="font-weight:700;font-size:0.85rem;color:#166534;">
                                        Amount to Withdraw ($ USD) <span style="color:#dc2626;">*</span>
                                    </label>
                                    <div style="position:relative;">
                                        <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);font-weight:700;color:#64748b;">$</span>
                                        <input type="number" step="0.01" min="1" max="{{ $stats['available_balance'] }}" name="amount" id="withdrawAmountInput" class="form-control" style="padding-left:24px;" value="{{ old('amount', min(10, $stats['available_balance'])) }}" required>
                                    </div>
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px;">
                                        <span style="font-size:0.75rem;color:#15803d;font-weight:600;" id="bdtEstimatedText">
                                            = ৳<span id="bdtCalcValue">{{ number_format(min(10, $stats['available_balance']) * 120, 0) }}</span> BDT
                                        </span>
                                        <button type="button" id="withdrawAllBtn" style="border:none;background:none;color:#16a34a;font-size:0.75rem;font-weight:700;cursor:pointer;text-decoration:underline;">
                                            Withdraw All (${{ number_format($stats['available_balance'], 2) }})
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label" style="font-weight:700;font-size:0.85rem;color:#166534;">
                                        Payout Method <span style="color:#dc2626;">*</span>
                                    </label>
                                    <select name="method" class="form-control" required style="font-weight:600;">
                                        <option value="bKash" {{ old('method') == 'bKash' ? 'selected' : '' }}>bKash Personal</option>
                                        <option value="Nagad" {{ old('method') == 'Nagad' ? 'selected' : '' }}>Nagad Personal</option>
                                        <option value="Rocket" {{ old('method') == 'Rocket' ? 'selected' : '' }}>Rocket Personal</option>
                                        <option value="Bank Transfer" {{ old('method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="PayPal" {{ old('method') == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label" style="font-weight:700;font-size:0.85rem;color:#166534;">
                                        Account / Mobile Number <span style="color:#dc2626;">*</span>
                                    </label>
                                    <input type="text" name="account_details" class="form-control" placeholder="e.g. 017XXXXXXXX or Bank details" value="{{ old('account_details') }}" required>
                                </div>
                            </div>

                            <div style="margin-bottom:1rem;">
                                <label class="form-label" style="font-weight:600;font-size:0.82rem;color:var(--muted);">
                                    Note / Reference (Optional)
                                </label>
                                <input type="text" name="note" class="form-control" placeholder="Optional reference note" value="{{ old('note') }}">
                            </div>

                            <div style="display:flex;justify-content:flex-end;gap:0.75rem;align-items:center;">
                                <button type="button" class="btn btn-secondary btn-sm" id="cancelWithdrawBtn">Cancel</button>
                                <button type="submit" class="btn btn-primary" style="background:#16a34a;border-color:#16a34a;font-weight:700;">
                                    <span class="material-icons-round" style="font-size:18px;">check_circle</span>
                                    Confirm Withdrawal
                                </button>
                            </div>
                        </form>
                    @endif
                </div>

                <!-- Recent Withdrawals List -->
                <div>
                    <div style="font-size:0.88rem;font-weight:700;color:var(--dark);margin-bottom:0.6rem;display:flex;align-items:center;gap:0.35rem;">
                        <span class="material-icons-round" style="font-size:18px;color:var(--muted);">history</span>
                        Recent Withdrawals
                    </div>

                    <div class="table-container" style="border:1px solid #e2e8f0;border-radius:6px;">
                        <table class="table" style="font-size:0.84rem;">
                            <thead>
                                <tr style="background:#f8fafc;">
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th>Account Details</th>
                                    <th>Amount (BDT / USD)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawals as $withdrawal)
                                    <tr>
                                        <td style="color:var(--muted);white-space:nowrap;">
                                            {{ $withdrawal->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td>
                                            <span style="font-weight:700;color:var(--dark);display:inline-flex;align-items:center;gap:0.25rem;">
                                                <span class="material-icons-round" style="font-size:15px;color:#16a34a;">account_balance</span>
                                                {{ $withdrawal->method }}
                                            </span>
                                        </td>
                                        <td style="font-family:monospace;font-size:0.85rem;">
                                            {{ $withdrawal->account_details }}
                                        </td>
                                        <td>
                                            <strong style="color:#16a34a;">৳{{ number_format($withdrawal->amount_bdt, 0) }}</strong>
                                            <span style="color:var(--muted);font-size:0.75rem;">(${{ number_format($withdrawal->amount, 2) }})</span>
                                        </td>
                                        <td>
                                            @if($withdrawal->status === 'completed')
                                                <span class="badge badge-success" style="font-size:0.72rem;padding:0.25rem 0.5rem;display:inline-flex;align-items:center;gap:0.2rem;">
                                                    <span class="material-icons-round" style="font-size:13px;">check_circle</span> Approved
                                                </span>
                                            @elseif($withdrawal->status === 'pending')
                                                <span class="badge badge-warning" style="font-size:0.72rem;padding:0.25rem 0.5rem;display:inline-flex;align-items:center;gap:0.2rem;">
                                                    <span class="material-icons-round" style="font-size:13px;">hourglass_top</span> Pending Approval
                                                </span>
                                            @else
                                                <span class="badge badge-error" style="font-size:0.72rem;padding:0.25rem 0.5rem;display:inline-flex;align-items:center;gap:0.2rem;">
                                                    <span class="material-icons-round" style="font-size:13px;">cancel</span> Rejected
                                                </span>
                                            @endif

                                            @if($withdrawal->admin_note)
                                                <div style="font-size:0.74rem;color:#0369a1;background:#f0f9ff;padding:3px 6px;border-radius:4px;margin-top:4px;border:1px solid #bae6fd;white-space:normal;max-width:220px;">
                                                    <strong>Admin Note:</strong> {{ $withdrawal->admin_note }}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center" style="padding:1.5rem;color:var(--muted);font-size:0.82rem;">
                                            No withdrawal history yet. Once you withdraw your balance, it will be listed here.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="card">
            <div class="card-header flex justify-between items-center" style="background:#fafafa;">
                <span><span class="material-icons-round" style="color:var(--primary);">receipt_long</span> Manage Orders</span>
                <a href="{{ route('seller.orders') }}" class="btn btn-secondary btn-sm" style="font-weight:600;">View All Orders</a>
            </div>

            <div class="card-body p-0">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Buyer</th>
                                <th>Gig Title</th>
                                <th>Due Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th style="text-align:right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <div style="font-weight:700;color:var(--dark);">{{ $order->buyer->name ?? 'Buyer' }}</div>
                                        <div style="font-size:0.75rem;color:var(--muted);">#{{ $order->order_number }}</div>
                                    </td>
                                    <td>
                                        <div style="font-weight:600;font-size:0.88rem;max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            {{ $order->gig->title ?? 'N/A' }}
                                        </div>
                                        <div style="font-size:0.75rem;color:var(--muted);">{{ ucfirst($order->package) }} package</div>
                                    </td>
                                    <td style="font-size:0.85rem;color:var(--muted);white-space:nowrap;">
                                        {{ $order->due_at ? $order->due_at->format('M d, Y') : '—' }}
                                    </td>
                                    <td>
                                        <span style="font-weight:800;color:var(--dark);">${{ number_format($order->price, 2) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($order->status) {
                                                'completed'   => 'badge-success',
                                                'in_progress' => 'badge-info',
                                                'delivered'   => 'badge-primary',
                                                'revision'    => 'badge-warning',
                                                'cancelled'   => 'badge-error',
                                                default       => 'badge-warning'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_',' ',$order->status)) }}</span>
                                    </td>
                                    <td style="text-align:right;">
                                        <a href="{{ route('seller.order.show', $order) }}" class="btn btn-secondary btn-sm" style="font-weight:600;">
                                            Open Order
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center" style="padding:3.5rem;color:var(--muted);">
                                        <span class="material-icons-round" style="font-size:40px;color:#cbd5e1;display:block;margin-bottom:0.5rem;">receipt</span>
                                        No active orders currently
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleWithdrawBtn');
    const cancelBtn = document.getElementById('cancelWithdrawBtn');
    const container = document.getElementById('withdrawFormContainer');
    const amountInput = document.getElementById('withdrawAmountInput');
    const bdtCalc = document.getElementById('bdtCalcValue');
    const withdrawAllBtn = document.getElementById('withdrawAllBtn');
    const maxBalance = {{ (float) $stats['available_balance'] }};

    if (toggleBtn && container) {
        toggleBtn.addEventListener('click', function() {
            if (container.style.display === 'none' || container.style.display === '') {
                container.style.display = 'block';
                if (amountInput) amountInput.focus();
            } else {
                container.style.display = 'none';
            }
        });
    }

    if (cancelBtn && container) {
        cancelBtn.addEventListener('click', function() {
            container.style.display = 'none';
        });
    }

    function updateBdt() {
        if (!amountInput || !bdtCalc) return;
        const val = parseFloat(amountInput.value) || 0;
        const bdt = Math.round(val * 120);
        bdtCalc.textContent = bdt.toLocaleString();
    }

    if (amountInput) {
        amountInput.addEventListener('input', updateBdt);
        amountInput.addEventListener('change', updateBdt);
    }

    if (withdrawAllBtn && amountInput) {
        withdrawAllBtn.addEventListener('click', function() {
            amountInput.value = maxBalance.toFixed(2);
            updateBdt();
        });
    }
});
</script>
@endpush
@endsection
