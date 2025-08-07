<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.auth.register.page_title') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary-color: #1e40af;
            --secondary-color: #3b82f6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
        }
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
        .auth-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
        }
        .brand-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }
        .brand-logo img {
            max-width: 305px;
            height: 305px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1.5rem;
        }
        .form-section {
            padding: 3rem;
        }
        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
        .btn-submit {
            background: var(--primary-color);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }
        .btn-submit:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.3);
        }
        .password-strength {
            margin-top: 0.5rem;
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
        }
        .strength-bar {
            height: 100%;
            border-radius: 2px;
            transition: all 0.3s ease;
            width: 0%;
        }
        .strength-weak { background: #ef4444; width: 33%; }
        .strength-medium { background: var(--warning-color); width: 66%; }
        .strength-strong { background: var(--success-color); width: 100%; }
        @media (max-width: 991px) {
            .brand-section {
                border-radius: 20px 20px 0 0;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="container">
            <div class="auth-card">
                <div class="row g-0">
                    <!-- Brand Section -->
                    <div class="col-lg-5 d-none d-lg-flex">
                        <div class="brand-section">
                            <div class="brand-logo">
                                <img src="{{ asset('images/logo1.svg') }}" alt="Logo DeltaScrow" onerror="this.onerror=null;this.src='https://placehold.co/140x140/FFFFFF/1e40af?text=DS';">
                            </div>
                            <h2 class="fw-bold mb-3">{{ __('messages.auth.brand.title') }}</h2>
                            <p class="lead">{{ __('messages.auth.brand.description') }}</p>
                        </div>
                    </div>
                    <!-- Form Section -->
                    <div class="col-lg-7">
                        <div class="form-section">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold mb-2">{{ __('messages.auth.register.title') }}</h3>
                                <p class="text-muted">{{ __('messages.auth.register.subtitle') }}</p>
                            </div>
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('register') }}" method="POST" novalidate>
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-bold">{{ __('messages.auth.register.full_name') }}</label>
                                    <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-bold">{{ __('messages.auth.register.email') }}</label>
                                    <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-bold">{{ __('messages.auth.register.password') }}</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="password-strength mt-2">
                                        <div class="strength-bar" id="strengthBar"></div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-bold">{{ __('messages.auth.register.confirm_password') }}</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-submit btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>{{ __('messages.auth.register.submit') }}
                                </button>
                                <div class="text-center mt-4">
                                    <p class="text-muted">
                                        {{ __('messages.auth.register.have_account') }}
                                        <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: var(--primary-color);">
                                            {{ __('messages.auth.register.login') }}
                                        </a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('password')?.addEventListener('input', function(e) {
            const strengthBar = document.getElementById('strengthBar');
            const password = e.target.value;
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            
            strengthBar.className = 'strength-bar';
            if (strength === 1) strengthBar.classList.add('strength-weak');
            else if (strength === 2) strengthBar.classList.add('strength-medium');
            else if (strength >= 3) strengthBar.classList.add('strength-strong');
        });
    </script>
</body>
</html>