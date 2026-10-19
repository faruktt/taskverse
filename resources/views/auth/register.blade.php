<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Join TaskVerse — Create an Account</title>
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
            max-width: 460px;
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
            margin-bottom: 1.5rem;
        }
        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 1.5rem;
        }
        .role-option {
            border: 2px solid var(--border);
            border-radius: 6px;
            padding: 0.9rem 0.75rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.15s ease;
            background: #ffffff;
        }
        .role-option:hover {
            border-color: var(--primary);
        }
        .role-option.active {
            border-color: var(--primary);
            background: var(--primary-light);
        }
        .role-option input {
            display: none;
        }
        .role-option .role-icon {
            font-size: 24px;
            color: var(--dark);
            display: block;
            margin-bottom: 4px;
        }
        .role-option.active .role-icon {
            color: var(--primary-dark);
        }
        .role-option .role-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--dark);
        }
        .role-option .role-desc {
            font-size: 0.72rem;
            color: var(--muted);
            margin-top: 2px;
        }
    </style>
</head>
<body>

    <header class="auth-nav">
        <a href="{{ route('home') }}" class="logo-brand">
            TaskVerse<span class="brand-dot">.</span>
        </a>
        <div style="font-size:0.9rem;color:var(--muted);">
            Already have an account? <a href="{{ route('login') }}" style="color:var(--primary);font-weight:700;">Sign In</a>
        </div>
    </header>

    <main class="auth-container">
        <div class="fiverr-auth-card">
            <h1>Join TaskVerse</h1>
            <p class="subtitle">Find talent or start offering your freelance services</p>

            @if ($errors->any())
                <div class="alert alert-error">
                    <span class="material-icons-round">error_outline</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Role Selection -->
                <label class="form-label">I want to join as a:</label>
                <div class="role-selector">
                    <label class="role-option {{ old('role', request('role', 'buyer')) === 'buyer' ? 'active' : '' }}" onclick="selectRole('buyer', this)">
                        <input type="radio" name="role" value="buyer" {{ old('role', request('role', 'buyer')) === 'buyer' ? 'checked' : '' }}>
                        <span class="material-icons-round role-icon">shopping_bag</span>
                        <div class="role-title">Buyer / Client</div>
                        <div class="role-desc">Order services for projects</div>
                    </label>

                    <label class="role-option {{ old('role', request('role')) === 'seller' ? 'active' : '' }}" onclick="selectRole('seller', this)">
                        <input type="radio" name="role" value="seller" {{ old('role', request('role')) === 'seller' ? 'checked' : '' }}>
                        <span class="material-icons-round role-icon">storefront</span>
                        <div class="role-title">Freelancer / Seller</div>
                        <div class="role-desc">Publish gigs & earn money</div>
                    </label>
                </div>

                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Create a password (min 8 chars)" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat your password" required>
                </div>

                <div style="font-size:0.78rem;color:var(--muted);line-height:1.5;margin-bottom:1.5rem;">
                    By joining, you agree to the TaskVerse <a href="#" style="color:var(--dark);text-decoration:underline;">Terms of Service</a> and to occasionally receive marketing emails from us.
                </div>

                <button type="submit" class="btn btn-primary w-full" style="padding:0.8rem;font-size:1rem;border-radius:4px;">
                    Join TaskVerse
                </button>
            </form>
        </div>
    </main>

    <footer style="padding:1.5rem;text-align:center;font-size:0.82rem;color:var(--muted-light);border-top:1px solid var(--border);">
        &copy; {{ date('Y') }} TaskVerse International Ltd. All rights reserved.
    </footer>

    <script>
        function selectRole(role, element) {
            document.querySelectorAll('.role-option').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            element.querySelector('input').checked = true;
        }
    </script>
</body>
</html>
