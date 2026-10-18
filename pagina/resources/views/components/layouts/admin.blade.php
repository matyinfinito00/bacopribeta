<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'BACOPRI - Panel de Administración' }}</title>

    <!-- Tipografía Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS Bootstrap y FontAwesome -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/admin-theme.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('img/Logo-icono.png') }}">
    <!-- Anti-FOUC inmediato: detecta el estado del sidebar en localStorage antes del render -->
    <script>
        (function() {
            try {
                if (localStorage.getItem('sidebarCollapsed') === 'true') {
                    document.documentElement.classList.add('sidebar-is-collapsed');
                }
                document.documentElement.classList.add('preload-no-transition');
            } catch(e) {}
        })();
    </script>
    {{ $styles ?? '' }}
</head>
<body class="admin-panel">
    <script>
        (function() {
            try {
                if (localStorage.getItem('sidebarCollapsed') === 'true') {
                    document.body.classList.add('sidebar-collapsed');
                }
            } catch(e) {}
        })();
    </script>

    <!-- HEADER SUPERIOR -->
    <header class="admin-top-header">
        <div class="admin-header-content">
            <div class="admin-header-left">
                <!-- Botón móvil para abrir sidebar -->
                <button class="admin-mobile-toggle" id="sidebarMobileToggle" type="button" aria-label="Abrir menú">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="admin-header-breadcrumb">
                    @if(auth('superadmin')->check())
                        <span class="badge-superadmin">
                            <i class="fas fa-crown me-1"></i>Super Administrador
                        </span>
                    @else
                        <span class="badge-community">
                            <i class="fas fa-building me-1"></i>{{ auth('admin')->user()->comunidad->nombre ?? 'Comunidad' }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Perfil de Usuario Dropdown -->
            <div class="admin-header-user">
                @php
                    $isSuper = auth('superadmin')->check();
                    $currentUser = $isSuper ? auth('superadmin')->user() : auth('admin')->user();
                    $userInitials = mb_substr($currentUser->nombre ?? 'A', 0, 1) . mb_substr($currentUser->apellido ?? '', 0, 1);
                @endphp
                <div class="dropdown">
                    <a href="#" class="admin-header-user-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="admin-header-user-avatar">
                            {{ strtoupper($userInitials ?: 'A') }}
                        </div>
                        <div class="admin-header-user-info">
                            <span class="admin-header-user-name">{{ $currentUser->nombre_completo ?? 'Usuario' }}</span>
                            <span class="admin-header-user-role">{{ $isSuper ? 'Super Admin' : 'Admin de Comunidad' }}</span>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end admin-header-dropdown shadow">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.cambiar-contrasenia') }}">
                                <i class="fas fa-key text-muted"></i>
                                <span>Cambiar Contraseña</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider my-1">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('admin.logout') }}" class="d-inline m-0 p-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger w-100 text-start border-0 bg-transparent">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Cerrar Sesión</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- SIDEBAR -->
    <x-admin.sidebar :active="$active ?? ''" />

    <!-- CONTENIDO PRINCIPAL -->
    <main class="admin-main-content">
        {{ $slot }}
    </main>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        // Manejador del menú móvil
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.getElementById('sidebarMobileToggle');
            const sidebar = document.querySelector('.sidebar-modern');
            
            if (mobileToggle && sidebar) {
                mobileToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('show');
                });

                // Cerrar al hacer clic fuera en dispositivos móviles
                document.addEventListener('click', function(e) {
                    if (window.innerWidth < 992 && sidebar.classList.contains('show')) {
                        if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                            sidebar.classList.remove('show');
                        }
                    }
                });
            }
        });
    </script>
    {{ $scripts ?? '' }}
</body>
</html>
