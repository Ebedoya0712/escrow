<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeltaScrow - Transacciones Seguras con Depósito en Garantía</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e40af;
            --secondary-color: #3b82f6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .feature-card {
            border: none;
            border-radius: 15px;
            padding: 2rem;
            height: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .process-step {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 200px;
        }

        .process-number {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.5rem;
            margin: 0 auto 1.5rem;
        }

        .process-line {
            position: absolute;
            top: 30px;
            left: calc(50% + 30px);
            width: calc(100% - 60px);
            height: 2px;
            background: var(--secondary-color);
            z-index: -1;
        }

        .process-step:last-child .process-line {
            display: none;
        }

        .stats-section {
            background: #f8fafc;
            padding: 80px 0;
        }

        .stat-item {
            text-align: center;
            margin-bottom: 2rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: var(--primary-color);
            display: block;
        }

        .faq-item {
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .faq-header {
            background: #f9fafb;
            padding: 1.5rem;
            cursor: pointer;
            border: none;
            width: 100%;
            text-align: left;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .faq-header:hover {
            background: #f3f4f6;
        }

        .faq-content {
            padding: 1.5rem;
            display: none;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.3);
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }

        .security-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin: 0.5rem;
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }
            
            .process-line {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#" style="display: flex; align-items: center; gap: 0;">
            <img src="{{ asset('images/deltalogo.svg') }}" alt="DeltaScrow Logo" style="height: 65px;"><span style="color: #1b80c0; margin-left: -16px;">DeltaScrow</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#como-funciona">{{ __('messages.landing.navbar.how_it_works') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#beneficios">{{ __('messages.landing.navbar.benefits') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#seguridad">{{ __('messages.landing.navbar.security') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#faq">{{ __('messages.landing.navbar.faq') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="#contacto">{{ __('messages.landing.navbar.contact') }}</a></li>
                <li class="nav-item"><a class="btn btn-primary ms-2" href="{{ route('register') }}">{{ __('messages.landing.navbar.get_started') }}</a></li>
            </ul>
        </div>
    </div>
</nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="display-4 fw-bold mb-4">{!! __('messages.landing.hero.title') !!}</h1>
                            <p class="lead mb-4">{{ __('messages.landing.hero.description') }}</p>
                        <div class="d-flex flex-wrap mb-4">
                            <span class="security-badge"><i class="fas fa-check me-2"></i>{{ __('messages.landing.hero.badges.secure') }}</span>
                    <span class="security-badge"><i class="fas fa-user-shield me-2"></i>{{ __('messages.landing.hero.badges.verification') }}</span>
                    <span class="security-badge"><i class="fas fa-comments me-2"></i>{{ __('messages.landing.hero.badges.chat') }}</span>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="{{ route('register') }}" class="btn btn-warning btn-lg">
                                <i class="fas fa-user-plus me-2"></i>{{ __('messages.landing.hero.register') }}
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>{{ __('messages.landing.hero.login') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-handshake" style="font-size: 15rem; opacity: 0.8;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number" data-count="6500">0</span>
                        <p class="text-muted">{{ __('messages.landing.stats.transactions') }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number" data-count="99">0</span>
                        <p class="text-muted">{{ __('messages.landing.stats.satisfaction') }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number" data-count="24">0</span>
                        <p class="text-muted">{{ __('messages.landing.stats.support') }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <span class="stat-number" data-count="0">0</span>
                        <p class="text-muted">{{ __('messages.landing.stats.fraud') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="como-funciona" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">{{ __('messages.landing.how_it_works.title') }}</h2>
                <p class="lead text-muted">{{ __('messages.landing.how_it_works.subtitle') }}</p>
            </div>
<div class="row justify-content-center">
    <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
        <div class="process-step">
            <div class="process-number">1</div>
            <div class="process-line"></div>
            <h5>{{ __('messages.landing.how_it_works.steps.create') }}</h5>
            <p class="text-muted">{{ __('messages.landing.how_it_works.steps.create_desc') }}</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
        <div class="process-step">
            <div class="process-number">2</div>
            <div class="process-line"></div>
            <h5>{{ __('messages.landing.how_it_works.steps.accept') }}</h5>
            <p class="text-muted">{{ __('messages.landing.how_it_works.steps.accept_desc') }}</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
        <div class="process-step">
            <div class="process-number">3</div>
            <div class="process-line"></div>
            <h5>{{ __('messages.landing.how_it_works.steps.payment') }}</h5>
            <p class="text-muted">{{ __('messages.landing.how_it_works.steps.payment_desc') }}</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
        <div class="process-step">
            <div class="process-number">4</div>
            <div class="process-line"></div>
            <h5>{{ __('messages.landing.how_it_works.steps.deliver') }}</h5>
            <p class="text-muted">{{ __('messages.landing.how_it_works.steps.deliver_desc') }}</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
        <div class="process-step">
            <div class="process-number">5</div>
            <h5>{{ __('messages.landing.how_it_works.steps.release') }}</h5>
            <p class="text-muted">{{ __('messages.landing.how_it_works.steps.release_desc') }}</p>
        </div>
    </div>
    </div>
</div>
    </section>

    <!-- Benefits Section -->
<section id="beneficios" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">{{ __('messages.landing.benefits.title') }}</h2>
            <p class="lead text-muted">{{ __('messages.landing.benefits.subtitle') }}</p>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card feature-card">
                    <div class="feature-icon" style="background: var(--success-color);">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="card-title">{{ __('messages.landing.benefits.items.protection') }}</h5>
                    <p class="card-text">{{ __('messages.landing.benefits.items.protection_desc') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card feature-card">
                    <div class="feature-icon" style="background: var(--primary-color);">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <h5 class="card-title">{{ __('messages.landing.benefits.items.verification') }}</h5>
                    <p class="card-text">{{ __('messages.landing.benefits.items.verification_desc') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card feature-card">
                    <div class="feature-icon" style="background: var(--warning-color);">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h5 class="card-title">{{ __('messages.landing.benefits.items.chat') }}</h5>
                    <p class="card-text">{{ __('messages.landing.benefits.items.chat_desc') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card feature-card">
                    <div class="feature-icon" style="background: var(--danger-color);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h5 class="card-title">{{ __('messages.landing.benefits.items.speed') }}</h5>
                    <p class="card-text">{{ __('messages.landing.benefits.items.speed_desc') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card feature-card">
                    <div class="feature-icon" style="background: var(--secondary-color);">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h5 class="card-title">{{ __('messages.landing.benefits.items.global') }}</h5>
                    <p class="card-text">{{ __('messages.landing.benefits.items.global_desc') }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card feature-card">
                    <div class="feature-icon" style="background: var(--success-color);">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5 class="card-title">{{ __('messages.landing.benefits.items.support') }}</h5>
                    <p class="card-text">{{ __('messages.landing.benefits.items.support_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Security Section -->
<section id="seguridad" class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold mb-4">{{ __('messages.landing.security.title') }}</h2>
                <p class="lead mb-4">
                    {{ __('messages.landing.security.description') }}
                </p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <div class="feature-icon me-3" style="width: 50px; height: 50px; background: var(--success-color); font-size: 1.2rem;">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold">{{ __('messages.landing.security.features.ssl') }}</h6>
                                <p class="text-muted small">{{ __('messages.landing.security.features.ssl_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <div class="feature-icon me-3" style="width: 50px; height: 50px; background: var(--primary-color); font-size: 1.2rem;">
                                <i class="fas fa-database"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold">{{ __('messages.landing.security.features.database') }}</h6>
                                <p class="text-muted small">{{ __('messages.landing.security.features.database_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <div class="feature-icon me-3" style="width: 50px; height: 50px; background: var(--warning-color); font-size: 1.2rem;">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold">{{ __('messages.landing.security.features.monitoring') }}</h6>
                                <p class="text-muted small">{{ __('messages.landing.security.features.monitoring_desc') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex mb-3">
                            <div class="feature-icon me-3" style="width: 50px; height: 50px; background: var(--danger-color); font-size: 1.2rem;">
                                <i class="fas fa-ban"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold">{{ __('messages.landing.security.features.antifraud') }}</h6>
                                <p class="text-muted small">{{ __('messages.landing.security.features.antifraud_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="fas fa-lock" style="font-size: 12rem; color: var(--success-color); opacity: 0.8;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- FAQ Section -->
<section id="faq" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">{{ __('messages.landing.faq.title') }}</h2>
            <p class="lead text-muted">{{ __('messages.landing.faq.subtitle') }}</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="faq-item">
                    <button class="faq-header" onclick="toggleFaq(this)">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('messages.landing.faq.questions.how_escrow_works') }}
                    </button>
                    <div class="faq-content">
                        <p>{{ __('messages.landing.faq.questions.how_escrow_works_answer') }}</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button class="faq-header" onclick="toggleFaq(this)">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('messages.landing.faq.questions.fees') }}
                    </button>
                    <div class="faq-content">
                        <p>{{ __('messages.landing.faq.questions.fees_answer') }}</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button class="faq-header" onclick="toggleFaq(this)">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('messages.landing.faq.questions.disputes') }}
                    </button>
                    <div class="faq-content">
                        <p>{{ __('messages.landing.faq.questions.disputes_answer') }}</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button class="faq-header" onclick="toggleFaq(this)">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('messages.landing.faq.questions.timing') }}
                    </button>
                    <div class="faq-content">
                        <p>{{ __('messages.landing.faq.questions.timing_answer') }}</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button class="faq-header" onclick="toggleFaq(this)">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('messages.landing.faq.questions.safety') }}
                    </button>
                    <div class="faq-content">
                        <p class="text-muted">{{ __('messages.landing.faq.questions.safety_answer') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Contact Section -->
<section id="contacto" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">{{ __('messages.landing.contact.title') }}</h2>
            <p class="lead text-muted">{{ __('messages.landing.contact.subtitle') }}</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="feature-icon mx-auto" style="background: var(--primary-color); font-size: 1.5rem;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h5>{{ __('messages.landing.contact.email') }}</h5>
                        <p class="text-muted">{{ __('messages.landing.contact.email_value') }}</p>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <div class="feature-icon mx-auto" style="background: var(--success-color); font-size: 1.5rem;">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h5>{{ __('messages.landing.contact.phone') }}</h5>
                        <p class="text-muted">{{ __('messages.landing.contact.phone_value') }}</p>
                    </div>
                    <div class="col-md-4 text-center mb-4">
                        <div class="feature-icon mx-auto" style="background: var(--warning-color); font-size: 1.5rem;">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h5>{{ __('messages.landing.contact.hours') }}</h5>
                        <p class="text-muted">{{ __('messages.landing.contact.hours_value') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section id="registro" class="py-5" style="background: var(--primary-color); color: white;">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">{{ __('messages.landing.cta.title') }}</h2>
        <p class="lead mb-4">{{ __('messages.landing.cta.description') }}</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('register') }}" class="btn btn-warning btn-lg">
                <i class="fas fa-user-plus me-2"></i>{{ __('messages.landing.cta.register') }}
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>{{ __('messages.landing.cta.login') }}
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 text-center text-lg-start">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="{{ __('messages.landing.title') }}" 
                     style="max-height: 40px;"
                     class="mb-3"
                     onerror="this.onerror=null;this.src='https://placehold.co/150x40/1e40af/FFFFFF?text=DeltaScrow';">
                <p class="text-white mb-0">{{ __('messages.landing.footer.description') }}</p>
            </div>
            
            <div class="col-lg-6 mb-4">
                <h6 class="text-white text-center text-lg-end mb-3">DeltaScrow</h6>
                <div class="d-flex flex-wrap justify-content-center justify-content-lg-end gap-3">
                    <a href="#como-funciona" class="text-white text-decoration-none">{{ __('messages.landing.navbar.how_it_works') }}</a>
                    <a href="#beneficios" class="text-white text-decoration-none">{{ __('messages.landing.navbar.benefits') }}</a>
                    <a href="#seguridad" class="text-white text-decoration-none">{{ __('messages.landing.navbar.security') }}</a>
                    <a href="#faq" class="text-white text-decoration-none">{{ __('messages.landing.navbar.faq') }}</a>
                    <a href="#contacto" class="text-white text-decoration-none">{{ __('messages.landing.navbar.contact') }}</a>
                </div>
            </div>
        </div>
        <hr class="my-4 bg-white">
        <div class="row">
            <div class="col-12 text-center">
                <p class="text-white mb-0 small">
                    &copy; {{ date('Y') }} {{ __('messages.landing.footer.copyright') }}
                </p>
            </div>
        </div>
    </div>
</footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // FAQ Toggle Function
        function toggleFaq(element) {
            const content = element.nextElementSibling;
            const icon = element.querySelector('i');
            
            if (content.style.display === 'block') {
                content.style.display = 'none';
                icon.className = 'fas fa-plus me-2';
            } else {
                // Close all other FAQ items
                document.querySelectorAll('.faq-content').forEach(item => {
                    item.style.display = 'none';
                });
                document.querySelectorAll('.faq-header i').forEach(item => {
                    item.className = 'fas fa-plus me-2';
                });
                
                // Open clicked FAQ item
                content.style.display = 'block';
                icon.className = 'fas fa-minus me-2';
            }
        }

        // Counter Animation
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            
            counters.forEach(counter => {
                const target = parseInt(counter.dataset.count);
                const increment = target / 100;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = target + (target === 99 ? '%' : target === 24 ? '/7' : '');
                        clearInterval(timer);
                    } else {
                        counter.textContent = Math.floor(current);
                    }
                }, 20);
            });
        }

        // Smooth Scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('stats-section')) {
                        animateCounters();
                    }
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe sections for animation
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation classes
            const sections = document.querySelectorAll('section');
            sections.forEach(section => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(30px)';
                section.style.transition = 'all 0.6s ease';
                observer.observe(section);
            });

            // Show first section immediately
            if (sections.length > 0) {
                sections[0].style.opacity = '1';
                sections[0].style.transform = 'translateY(0)';
            }
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('shadow');
                navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                navbar.style.backdropFilter = 'blur(10px)';
            } else {
                navbar.classList.remove('shadow');
                navbar.style.backgroundColor = '#ffffff';
                navbar.style.backdropFilter = 'none';
            }
        });

        // Add hover effects to cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Process steps animation
        function animateProcessSteps() {
            const steps = document.querySelectorAll('.process-step');
            steps.forEach((step, index) => {
                setTimeout(() => {
                    step.style.opacity = '1';
                    step.style.transform = 'translateY(0)';
                }, index * 200);
            });
        }

        // Initialize process steps animation when section is visible
        const processObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && entry.target.id === 'como-funciona') {
                    animateProcessSteps();
                }
            });
        }, { threshold: 0.3 });

        document.addEventListener('DOMContentLoaded', function() {
            const processSection = document.getElementById('como-funciona');
            if (processSection) {
                processObserver.observe(processSection);
                
                // Initially hide process steps
                document.querySelectorAll('.process-step').forEach(step => {
                    step.style.opacity = '0';
                    step.style.transform = 'translateY(30px)';
                    step.style.transition = 'all 0.5s ease';
                });
            }
        });

        // Loading animation for buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.getAttribute('href') === '#registro' || this.getAttribute('href') === '#') {
                    e.preventDefault();
                    
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Cargando...';
                    this.disabled = true;
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.disabled = false;
                        
                        // Show success message
                        const toast = document.createElement('div');
                        toast.className = 'position-fixed top-0 end-0 p-3';
                        toast.style.zIndex = '9999';
                        toast.innerHTML = `
                            <div class="toast show" role="alert">
                                <div class="toast-header">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <strong class="me-auto">DeltaScrow</strong>
                                    <button type="button" class="btn-close" onclick="this.parentElement.parentElement.parentElement.remove()"></button>
                                </div>
                                <div class="toast-body">
                                    ¡Gracias por tu interés! Te contactaremos pronto.
                                </div>
                            </div>
                        `;
                        document.body.appendChild(toast);
                        
                        setTimeout(() => {
                            if (toast.parentElement) {
                                toast.remove();
                            }
                        }, 5000);
                    }, 2000);
                }
            });
        });
    </script>
</body>
</html>