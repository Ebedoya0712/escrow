<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.auth.login.page_title') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #1e40af;
            --secondary-color: #3b82f6;
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
                    <!-- Form Section -->
                    <div class="col-lg-7">
                        <div class="form-section">
                            <div class="text-center mb-4">
                                <h3 class="fw-bold mb-2">{{ __('messages.auth.login.welcome') }}</h3>
                                <p class="text-muted">{{ __('messages.auth.login.subtitle') }}</p>
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

                            <form action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-bold">{{ __('messages.auth.login.email') }}</label>
                                    <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-bold">{{ __('messages.auth.login.password') }}</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                        <label class="form-check-label" for="remember">{{ __('messages.auth.login.remember') }}</label>
                                    </div>
                                    <a href="#" class="text-decoration-none small" style="color: var(--primary-color);">
                                        {{ __('messages.auth.login.forgot_password') }}
                                    </a>
                                </div>
                                <button type="submit" class="btn btn-primary btn-submit btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>{{ __('messages.auth.login.submit') }}
                                </button>
                                <div class="text-center mt-4">
                                    <p class="text-muted">
                                        {{ __('messages.auth.login.no_account') }}
                                        <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: var(--primary-color);">
                                            {{ __('messages.auth.login.register') }}
                                        </a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
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
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('status'))
                Swal.fire({
                    title: '{{ __("messages.auth.alerts.register_success") }}',
                    text: "{{ session('status') }}",
                    icon: 'success',
                    confirmButtonText: '{{ __("messages.auth.alerts.great") }}',
                    confirmButtonColor: '#3b82f6'
                });
            @endif
        });
    </script>
</body>
</html>