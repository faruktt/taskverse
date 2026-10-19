<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in to TaskVerse</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .auth-nav {
            padding: 1.5rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .auth-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .fiverr-auth-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 440px;
            padding: 2.75rem 2.25rem 2.25rem;
        }
        .fiverr-auth-card h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }
        .fiverr-auth-card p.subtitle {
            font-size: 0.9rem;
            color: var(--muted);
            margin-bottom: 1.75rem;
        }
        .social-auth-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 0.65rem 1rem;
            border: 1px solid var(--border);
            border-radius: 4px;
            background: #ffffff;
            color: var(--dark);
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.15s ease;
            margin-bottom: 0.75rem;
        }
        .social-auth-btn:hover {
            border-color: var(--dark);
            background: #fafafa;
        }
        .auth-divider {
            display: flex;
            align-items: center;
            margin: 1.25rem 0;
            color: var(--muted-light);
            font-size: 0.8rem;
            font-weight: 600;
        }
        .auth-divider::before, .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        .auth-divider span {
            padding: 0 1rem;
        }
        .quick-fill-btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            border-radius: 4px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            cursor: pointer;
            font-weight: 600;
            color: var(--dark);
            transition: all 0.15s;
        }
        .quick-fill-btn:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }
    </style>
</head>
<body>

    <header class="auth-nav">
        <a href="{{ route('home') }}" class="logo-brand">
            TaskVerse<span class="brand-dot">.</span>
        </a>
        <div style="font-size:0.9rem;color:var(--muted);">
            Don't have an account? <a href="{{ route('register') }}" style="color:var(--primary);font-weight:700;">Join</a>
        </div>
    </header>

    <main class="auth-container">
        <div class="fiverr-auth-card">
            <h1>Sign in to your account</h1>
            <p class="subtitle">Welcome back! Access your orders and workspace</p>

            @if ($errors->any())
                <div class="alert alert-error">
                    <span class="material-icons-round">error_outline</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Social Connectors (Fiverr Style) -->
            <button type="button" class="social-auth-btn" onclick="quickFill('buyer@taskverse.com')">
                <span class="material-icons-round" style="color:#ea4335;">mail</span> Continue with Google
            </button>
            <button type="button" class="social-auth-btn" onclick="quickFill('seller@taskverse.com')">
                <span class="material-icons-round" style="color:#000;">apple</span> Continue with Apple
            </button>

            <div class="auth-divider">
                <span>OR</span>
            </div>

            <!-- Email / Password Form -->
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email or Username</label>
                    <input type="email" id="emailInput" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.4rem;">
                        <label class="form-label" style="margin-bottom:0;">Password</label>
                        <a href="#" style="font-size:0.8rem;color:var(--muted);text-decoration:none;">Forgot password?</a>
                    </div>
                    <input type="password" id="passwordInput" name="password" class="form-control" placeholder="Enter your password" required>
                </div>

                <div style="display:flex;align-items:center;margin-bottom:1.5rem;">
                    <label style="display:flex;align-items:center;gap:8px;font-size:0.85rem;color:var(--muted);cursor:pointer;">
                        <input type="checkbox" name="remember" style="accent-color:var(--primary);width:15px;height:15px;">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-full" style="padding:0.8rem;font-size:1rem;border-radius:4px;">
                    Continue
                </button>
            </form>

            <!-- Quick One-click Demo Credentials Fill -->
            <div style="margin-top:1.5rem;padding-top:1rem;border-top:1px solid var(--border);text-align:center;">
                <div style="font-size:0.75rem;color:var(--muted);margin-bottom:0.5rem;font-weight:600;">QUICK 1-CLICK DEMO LOGIN:</div>
                <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;">
                    <button type="button" class="quick-fill-btn" onclick="quickFill('seller@taskverse.com')">
                        <span class="material-icons-round" style="font-size:13px;">storefront</span> Seller (Alex)
                    </button>
                    <button type="button" class="quick-fill-btn" onclick="quickFill('buyer@taskverse.com')">
                        <span class="material-icons-round" style="font-size:13px;">shopping_bag</span> Buyer (Sarah)
                    </button>
                    <a href="{{ route('admin.login') }}" class="quick-fill-btn" style="text-decoration:none;display:inline-flex;align-items:center;gap:3px;">
                        <span class="material-icons-round" style="font-size:13px;">shield</span> Admin Portal
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer style="padding:1.5rem;text-align:center;font-size:0.82rem;color:var(--muted-light);border-top:1px solid var(--border);">
        &copy; {{ date('Y') }} TaskVerse International Ltd. All rights reserved.
    </footer>

    <script>
        function quickFill(email) {
            document.getElementById('emailInput').value = email;
            document.getElementById('passwordInput').value = 'password';
            document.getElementById('loginForm').submit();
        }
    </script>
</body>
</html>
