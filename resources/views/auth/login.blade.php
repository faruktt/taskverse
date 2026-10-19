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
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 440px;
            padding: 2.5rem 2.25rem;
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

        @media (max-width: 576px) {
            .auth-nav {
                padding: 1rem 1.25rem;
            }
            .auth-container {
                padding: 1rem;
                align-items: flex-start;
            }
            .fiverr-auth-card {
                padding: 1.75rem 1.25rem;
                border-radius: 8px;
            }
            .fiverr-auth-card h1 {
                font-size: 1.35rem;
            }
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

            <!-- Email / Password Form -->
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="emailInput">Email Address</label>
                    <input type="email" id="emailInput" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.4rem;">
                        <label class="form-label" for="passwordInput" style="margin-bottom:0;">Password</label>
                    </div>
                    <input type="password" id="passwordInput" name="password" class="form-control" placeholder="Enter your password" required>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                    <label style="display:flex;align-items:center;gap:8px;font-size:0.85rem;color:var(--muted);cursor:pointer;">
                        <input type="checkbox" name="remember" style="accent-color:var(--primary);width:15px;height:15px;">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-primary w-full" style="padding:0.8rem;font-size:1rem;border-radius:6px;font-weight:700;">
                    Sign In
                </button>
            </form>
        </div>
    </main>

    <footer style="padding:1.5rem;text-align:center;font-size:0.82rem;color:var(--muted-light);border-top:1px solid var(--border);">
        &copy; {{ date('Y') }} TaskVerse International Ltd. All rights reserved. &bull; Developed by <a href="https://faruk.stsoft.top/" target="_blank" rel="noopener noreferrer" style="color:var(--primary);font-weight:700;text-decoration:none;">FARUK</a>
    </footer>
</body>
</html>
