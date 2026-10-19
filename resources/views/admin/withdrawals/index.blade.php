@extends('layouts.admin')
@section('title', 'Withdrawal Requests')
@section('page-title', 'Withdrawal Requests')

@section('content')
<div class="page-header flex justify-between items-center">
    <div>
        <h1>Freelancer Withdrawals</h1>
        <p>Review, verify, and approve payout requests from freelancers via bKash, Nagad, Rocket, Bank, or PayPal</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
            <a href="{{ route('admin.withdrawals') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                All ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.withdrawals', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'btn-primary' : 'btn-secondary' }} btn-sm" style="{{ request('status') == 'pending' ? 'background:#eab308;border-color:#eab308;color:#000;' : '' }}">
                Pending ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.withdrawals', ['status' => 'completed']) }}" class="btn {{ request('status') == 'completed' ? 'btn-primary' : 'btn-secondary' }} btn-sm" style="{{ request('status') == 'completed' ? 'background:#16a34a;border-color:#16a34a;' : '' }}">
                Approved ({{ $counts['completed'] }})
            </a>
            <a href="{{ route('admin.withdrawals', ['status' => 'rejected']) }}" class="btn {{ request('status') == 'rejected' ? 'btn-primary' : 'btn-secondary' }} btn-sm" style="{{ request('status') == 'rejected' ? 'background:#dc2626;border-color:#dc2626;' : '' }}">
                Rejected ({{ $counts['rejected'] }})
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
                        <th>Req #</th>
                        <th>Freelancer (Seller)</th>
                        <th>Amount (BDT / USD)</th>
                        <th>Method</th>
                        <th>Account Details</th>
                        <th>Seller Note</th>
                        <th>Status</th>
                        <th>Admin Note</th>
                        <th style="text-align:right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $withdrawal)
                        <tr>
                            <td>
                                <span style="font-weight:700;font-family:monospace;color:var(--primary-light);">#WTH-{{ $withdrawal->id }}</span>
                                <div style="font-size:0.72rem;color:var(--text-muted);">{{ $withdrawal->created_at->format('M d, Y h:i A') }}</div>
                            </td>
                            <td>
                                <div style="font-weight:600;font-size:0.9rem;">{{ $withdrawal->user->name ?? 'Freelancer' }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">{{ $withdrawal->user->email ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <div style="font-weight:800;color:#16a34a;font-size:1.05rem;">
                                    ৳{{ number_format($withdrawal->amount_bdt, 0) }}
                                </div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">${{ number_format($withdrawal->amount, 2) }} USD</div>
                            </td>
                            <td>
                                <span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:700;font-size:0.78rem;padding:0.25rem 0.6rem;">
                                    {{ $withdrawal->method }}
                                </span>
                            </td>
                            <td>
                                <div style="font-family:monospace;font-weight:700;background:#f8fafc;padding:4px 8px;border-radius:4px;border:1px solid #e2e8f0;display:inline-block;font-size:0.85rem;">
                                    {{ $withdrawal->account_details }}
                                </div>
                            </td>
                            <td style="font-size:0.8rem;color:var(--text-muted);max-width:180px;">
                                {{ $withdrawal->note ?: '—' }}
                            </td>
                            <td>
                                @if($withdrawal->status === 'completed')
                                    <span class="badge badge-success" style="font-size:0.75rem;padding:0.25rem 0.55rem;">
                                        <span class="material-icons-round" style="font-size:13px;vertical-align:middle;">check_circle</span> Approved
                                    </span>
                                @elseif($withdrawal->status === 'pending')
                                    <span class="badge badge-warning" style="font-size:0.75rem;padding:0.25rem 0.55rem;background:#fef08a;color:#854d0e;">
                                        <span class="material-icons-round" style="font-size:13px;vertical-align:middle;">hourglass_top</span> Pending Review
                                    </span>
                                @else
                                    <span class="badge badge-error" style="font-size:0.75rem;padding:0.25rem 0.55rem;">
                                        <span class="material-icons-round" style="font-size:13px;vertical-align:middle;">cancel</span> Rejected
                                    </span>
                                @endif
                            </td>
                            <td style="font-size:0.8rem;max-width:200px;">
                                @if($withdrawal->admin_note)
                                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:4px 8px;border-radius:4px;color:#166534;">
                                        {{ $withdrawal->admin_note }}
                                    </div>
                                @else
                                    <span style="color:var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td style="text-align:right;white-space:nowrap;">
                                @if($withdrawal->status === 'pending')
                                    <div style="display:inline-flex;gap:0.4rem;">
                                        <!-- Approve Button -->
                                        <button type="button" class="btn btn-success btn-sm btn-approve"
                                            data-id="{{ $withdrawal->id }}"
                                            data-name="{{ $withdrawal->user->name ?? 'Freelancer' }}"
                                            data-amount="৳{{ number_format($withdrawal->amount_bdt, 0) }} (${{ number_format($withdrawal->amount, 2) }})"
                                            data-method="{{ $withdrawal->method }}"
                                            data-account="{{ $withdrawal->account_details }}"
                                            style="background:#16a34a;border-color:#16a34a;font-weight:600;padding:0.3rem 0.65rem;">
                                            <span class="material-icons-round" style="font-size:15px;">check</span> Approve
                                        </button>

                                        <!-- Reject Button -->
                                        <button type="button" class="btn btn-danger btn-sm btn-reject"
                                            data-id="{{ $withdrawal->id }}"
                                            data-name="{{ $withdrawal->user->name ?? 'Freelancer' }}"
                                            data-amount="৳{{ number_format($withdrawal->amount_bdt, 0) }}"
                                            style="background:#dc2626;border-color:#dc2626;font-weight:600;padding:0.3rem 0.65rem;">
                                            <span class="material-icons-round" style="font-size:15px;">close</span> Reject
                                        </button>
                                    </div>
                                @else
                                    <span style="font-size:0.75rem;color:var(--text-muted);font-weight:600;">Processed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center" style="padding:3.5rem;color:var(--text-muted);">
                                <span class="material-icons-round" style="font-size:42px;color:#cbd5e1;display:block;margin-bottom:0.5rem;">account_balance_wallet</span>
                                No withdrawal requests found for this filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($withdrawals->hasPages())
        <div style="padding:1rem;border-top:1px solid var(--border);">
            {{ $withdrawals->links() }}
        </div>
    @endif
</div>

<!-- Modal: Approve Withdrawal -->
<div id="approveModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:#fff;border-radius:10px;max-width:480px;width:100%;padding:1.5rem;box-shadow:0 20px 25px -5px rgba(0,0,0,0.2);position:relative;">
        <h3 style="font-size:1.15rem;font-weight:800;color:#166534;margin-bottom:0.75rem;display:flex;align-items:center;gap:0.4rem;">
            <span class="material-icons-round">task_alt</span> Approve Withdrawal Request
        </h3>
        <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:1rem;" id="approveModalDesc">
            Confirm payout to seller.
        </p>

        <form id="approveForm" method="POST" action="">
            @csrf
            <div style="margin-bottom:1.25rem;">
                <label class="form-label" style="font-weight:700;font-size:0.85rem;">
                    Admin Payment Note / Transaction ID (TrxID)
                </label>
                <input type="text" name="admin_note" id="approveAdminNote" class="form-control" placeholder="e.g. Paid via bKash personal TrxID: 9B7F129A" value="Paid and confirmed. TrxID: ">
                <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">
                    This note will be shown to the seller on their dashboard.
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeApproveModal()">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" style="background:#16a34a;border-color:#16a34a;font-weight:700;">
                    Confirm Approval
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Reject Withdrawal -->
<div id="rejectModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:#fff;border-radius:10px;max-width:480px;width:100%;padding:1.5rem;box-shadow:0 20px 25px -5px rgba(0,0,0,0.2);position:relative;">
        <h3 style="font-size:1.15rem;font-weight:800;color:#dc2626;margin-bottom:0.75rem;display:flex;align-items:center;gap:0.4rem;">
            <span class="material-icons-round">cancel</span> Reject Withdrawal Request
        </h3>
        <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:1rem;" id="rejectModalDesc">
            Reject this request. The funds will be refunded back to the seller's available balance.
        </p>

        <form id="rejectForm" method="POST" action="">
            @csrf
            <div style="margin-bottom:1.25rem;">
                <label class="form-label" style="font-weight:700;font-size:0.85rem;">
                    Rejection Reason / Note <span style="color:#dc2626;">*</span>
                </label>
                <textarea name="admin_note" class="form-control" rows="3" placeholder="e.g. Account number invalid or payment gateway temporarily unavailable." required></textarea>
                <div style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">
                    Required. The seller will see this explanation on their dashboard.
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" class="btn btn-danger btn-sm" style="background:#dc2626;border-color:#dc2626;font-weight:700;">
                    Reject & Refund
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openApproveModal(id, name, amount, method, account) {
    var modal = document.getElementById('approveModal');
    var form = document.getElementById('approveForm');
    var desc = document.getElementById('approveModalDesc');
    var noteInput = document.getElementById('approveAdminNote');

    if (!modal || !form) return;
    form.action = '/admin/withdrawal/' + id + '/approve';
    desc.innerHTML = 'Approving payout of <strong>' + (amount || '') + '</strong> to <strong>' + (name || 'Freelancer') + '</strong> via <strong>' + (method || '') + '</strong> (' + (account || '') + ').';
    noteInput.value = 'Approved and sent via ' + (method || 'Gateway') + '. TrxID: ' + Math.random().toString(36).substring(2, 10).toUpperCase();

    modal.style.display = 'flex';
}

function closeApproveModal() {
    var modal = document.getElementById('approveModal');
    if (modal) modal.style.display = 'none';
}

function openRejectModal(id, name, amount) {
    var modal = document.getElementById('rejectModal');
    var form = document.getElementById('rejectForm');
    var desc = document.getElementById('rejectModalDesc');

    if (!modal || !form) return;
    form.action = '/admin/withdrawal/' + id + '/reject';
    desc.innerHTML = 'Rejecting withdrawal of <strong>' + (amount || '') + '</strong> for <strong>' + (name || 'Freelancer') + '</strong>. The amount will be refunded to seller balance immediately.';

    modal.style.display = 'flex';
}

function closeRejectModal() {
    var modal = document.getElementById('rejectModal');
    if (modal) modal.style.display = 'none';
}

// Attach event listeners for robust clicking
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-approve').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            var name = this.getAttribute('data-name');
            var amount = this.getAttribute('data-amount');
            var method = this.getAttribute('data-method');
            var account = this.getAttribute('data-account');
            openApproveModal(id, name, amount, method, account);
        });
    });

    document.querySelectorAll('.btn-reject').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var id = this.getAttribute('data-id');
            var name = this.getAttribute('data-name');
            var amount = this.getAttribute('data-amount');
            openRejectModal(id, name, amount);
        });
    });

    // Close on backdrop click
    var approveModal = document.getElementById('approveModal');
    if (approveModal) {
        approveModal.addEventListener('click', function(e) {
            if (e.target === approveModal) closeApproveModal();
        });
    }

    var rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('click', function(e) {
            if (e.target === rejectModal) closeRejectModal();
        });
    }
});
</script>
@endsection
