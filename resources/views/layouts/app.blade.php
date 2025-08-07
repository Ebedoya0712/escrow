<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DeltaScrow')</title>

    <!--
        Cargamos Font Awesome desde la CDN para asegurar que los iconos siempre funcionen.
    -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Vite se encarga de inyectar nuestros estilos (Bootstrap) y scripts. -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        window.translations = @json(__('messages.alerts'));
    </script>

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #212529;
            --sidebar-link-color: rgba(255, 255, 255, 0.7);
            --sidebar-link-hover-color: #fff;
            --sidebar-link-active-color: #fff;
            --sidebar-link-active-bg: #0d6efd;
        }

        body {
            background-color: #f4f6f9;
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: #fff;
            transition: all 0.3s;
            position: fixed;
            height: 100vh;
            z-index: 999;
        }

        #sidebar.collapsed {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        .sidebar-header {
            padding: 1.25rem;
            background: rgba(0,0,0,0.1);
            text-align: center;
        }

        .sidebar-header img {
            max-width: 60px;
            border-radius: 50%;
        }
        
        .sidebar-header h5 {
            margin-top: 10px;
            margin-bottom: 0;
        }

        #sidebar .nav-link {
            padding: 0.75rem 1.25rem;
            color: var(--sidebar-link-color);
            transition: all 0.2s;
            font-weight: 500;
        }

        #sidebar .nav-link:hover {
            color: var(--sidebar-link-hover-color);
            background: rgba(255,255,255,0.1);
        }
        
        #sidebar .nav-link.active {
            color: var(--sidebar-link-active-color);
            background: var(--sidebar-link-active-bg);
        }

        #sidebar .nav-link i {
            width: 25px;
            text-align: center;
            margin-right: 0.5rem;
        }
        
        .sidebar-footer {
            border-top: 1px solid #495057;
        }

        #content {
            width: 100%;
            padding-left: var(--sidebar-width);
            transition: all 0.3s;
        }
        
        #content.sidebar-collapsed {
            padding-left: 0;
        }
        
        .navbar {
            box-shadow: 0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.collapsed {
                margin-left: 0;
            }
            #content, #content.sidebar-collapsed {
                padding-left: 0;
            }
        }

    </style>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <aside id="sidebar" class="d-flex flex-column">
        <div>
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.jpg') }}" alt="DeltaScrow Logo" onerror="this.onerror=null;this.src='https://placehold.co/80x80/1e40af/FFFFFF?text=DS&font=sans';">
                <h5 class="fw-bold mt-2">DeltaScrow</h5>
            </div>

            <ul class="nav flex-column">
                @if(Auth::user()->role === 'admin')
                    {{-- MENÚ PARA ADMINISTRADORES --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> {{ __('messages.menu.admin_dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.transactions.index') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">
                            <i class="fas fa-tasks"></i> {{ __('messages.menu.admin_manage_transactions') }}
                        </a>
                    </li>
                @else
                    {{-- MENÚ PARA USUARIOS NORMALES --}}
                     <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="fas fa-home"></i> {{ __('messages.menu.my_dashboard') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('transacciones.index') ? 'active' : '' }}" href="{{ route('transacciones.index') }}"><i class="fas fa-exchange-alt"></i> {{ __('messages.menu.my_transactions') }}</a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('transacciones.create') ? 'active' : '' }}" href="{{ route('transacciones.create') }}"><i class="fas fa-plus-circle"></i> {{ __('messages.menu.new_transaction') }}</a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Menú de Usuario en el Footer del Sidebar -->
        <div class="sidebar-footer mt-auto">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.payment.index') ? 'active' : '' }}" href="{{ route('profile.payment.index') }}">
                        <i class="fas fa-credit-card"></i> {{ __('messages.menu.payment_methods') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                        <i class="fas fa-user-edit"></i> {{ __('messages.menu.my_profile') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="confirmLogout(event, 'sidebar')">
                        <i class="fas fa-sign-out-alt"></i> {{ __('messages.menu.logout') }}
                    </a>
                    <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-light">
                    <i class="fas fa-bars"></i>
                </button>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle fa-lg me-1"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user-cog me-2"></i>{{ __('messages.menu.my_profile') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="confirmLogout(event, 'nav')">
                                    <i class="fas fa-sign-out-alt me-2"></i>{{ __('messages.menu.logout') }}
                                </a>
                                <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                 <h1 class="h2">@yield('page-title', 'Bienvenido')</h1>
                 <div>
                    @yield('page-actions')
                 </div>
            </div>
            @yield('content')
        </main>
    </div>
</div>

{{-- Formulario oculto global para la cancelación --}}
<form id="cancel-form" action="" method="POST" class="d-none">
    @csrf
    <input type="hidden" name="reason" id="cancellation-reason">
</form>

{{-- Scripts --}}
<script>
    // Función para confirmar el cierre de sesión con SweetAlert (Traducida)
    function confirmLogout(event, formType) {
        event.preventDefault();
        Swal.fire({
            title: "{{ __('messages.alerts.confirm_logout_title') }}",
            text: "{{ __('messages.alerts.confirm_logout_text') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: "{{ __('messages.alerts.confirm_logout_button') }}",
            cancelButtonText: "{{ __('messages.alerts.cancel_button') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form-' + formType).submit();
            }
        });
    }

    // Función para confirmar la cancelación con SweetAlert (Traducida)
    function confirmCancel(actionUrl) {
        Swal.fire({
            title: "{{ __('messages.alerts.confirm_cancel_title') }}",
            text: "{{ __('messages.alerts.confirm_cancel_text') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: "{{ __('messages.alerts.confirm_cancel_button') }}",
            cancelButtonText: "{{ __('messages.alerts.cancel_button') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "{{ __('messages.alerts.cancellation_reason_title') }}",
                    input: 'textarea',
                    inputPlaceholder: "{{ __('messages.alerts.cancellation_reason_placeholder') }}",
                    showCancelButton: true,
                    confirmButtonText: "{{ __('messages.alerts.cancellation_reason_confirm') }}",
                    cancelButtonText: "{{ __('messages.alerts.cancel_button') }}",
                    inputValidator: (value) => {
                        if (!value) {
                            return "{{ __('messages.alerts.cancellation_reason_error') }}"
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        const form = document.getElementById('cancel-form');
                        const reasonInput = document.getElementById('cancellation-reason');
                        reasonInput.value = result.value;
                        form.action = actionUrl;
                        form.submit();
                    }
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // SweetAlert para mensajes de éxito
        @if (session('sweet_success'))
            Swal.fire({
                title: "{{ session('sweet_success')['title'] }}",
                text: "{{ session('sweet_success')['text'] }}",
                icon: 'success',
                confirmButtonText: "{{ __('messages.alerts.ok_button') }}",
                confirmButtonColor: '#0d6efd'
            });
        @endif

        // Toggle para el menú lateral
        const sidebarCollapse = document.getElementById('sidebarCollapse');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');

        if (sidebarCollapse) {
            sidebarCollapse.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('sidebar-collapsed');
            });
        }
    });
</script>

@stack('scripts')

</body>
</html>