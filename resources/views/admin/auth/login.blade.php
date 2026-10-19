<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Portal — TaskVerse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .admin-auth-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 2.25rem;
        }
    </style>
</head>
<body>
    <div class="admin-auth-card">
        <div style="text-align:center;margin-bottom:1.5rem;">
            <div style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;border-radius:8px;background:#fef2f2;color:#dc2626;margin-bottom:0.75rem;">
                <span class="material-icons-round" style="font-size:28px;">shield</span>
            </div>
            <h1 style="font-size:1.5rem;font-weight:800;color:var(--dark);letter-spacing:-0.02em;">TaskVerse Admin</h1>
            <p style="font-size:0.85rem;color:var(--muted);margin-top:2px;">Restricted administrative management portal</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <span class="material-icons-round">error_outline</span>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}" id="adminForm">
            @csrf
            <div class="form-group">
                <label class="form-label">Administrator Email</label>
                <input type="email" id="adminEmail" name="email" class="form-control" placeholder="admin@taskverse.com" value="{{ old('email', 'admin@taskverse.com') }}" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" id="adminPass" name="password" class="form-control" placeholder="••••••••" value="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-full" style="padding:0.8rem;background:#0f172a;border-color:#0f172a;margin-top:0.5rem;">
                <span class="material-icons-round" style="font-size:18px;">lock_open</span> Access Admin Panel
            </button>
        </form>

        <div style="margin-top:1.5rem;padding-top:1rem;border-top:1px solid var(--border);text-align:center;">
            <a href="{{ route('home') }}" style="color:var(--muted);font-size:0.85rem;display:inline-flex;align-items:center;gap:4px;">
                <span class="material-icons-round" style="font-size:16px;">arrow_back</span> Return to Marketplace
            </a>
        </div>
    </div>
</body>
</html>
