@props(['active' => ''])

<aside class="sidebar-modern">
    <script>
        (function() {
            try {
                if (localStorage.getItem('sidebarCollapsed') === 'true') {
                    document.currentScript.parentElement.classList.add('collapsed');
                }
            } catch(e) {}
        })();
    </script>
    <!-- CABECERA DEL SIDEBAR -->
    <div class="sidebar-header">
        <a href="{{ auth('superadmin')->check() ? route('admin.super-admin-dashboard') : route('admin.admin-comunidad-dashboard') }}" class="sidebar-logo">
            <img src="{{ asset('img/Logo-icono.png') }}" alt="BACOPRI" class="sidebar-logo-img">
            <span class="sidebar-logo-text">BACOPRI</span>
        </a>
        <button class="sidebar-toggle-btn" id="sidebarToggle" type="button" title="Colapsar menú">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>

    <!-- NAVEGACIÓN SCROLLABLE -->
    <div class="sidebar-scrollable">
        @if(auth('superadmin')->check())
            {{-- SECCIÓN SUPER ADMIN --}}
            <div class="sidebar-section-title">Super Administración</div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'dashboard' ? 'active' : '' }}"
                       href="{{ route('admin.super-admin-dashboard') }}" title="Dashboard">
                        <span class="sidebar-icon"><i class="fas fa-tachometer-alt"></i></span>
                        <span class="sidebar-link-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'comunidades' ? 'active' : '' }}"
                       href="{{ route('admin.comunidades.index') }}" title="Comunidades">
                        <span class="sidebar-icon"><i class="fas fa-city"></i></span>
                        <span class="sidebar-link-text">Comunidades</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'administradores' ? 'active' : '' }}"
                       href="{{ route('admin.administradores.index') }}" title="Administradores">
                        <span class="sidebar-icon"><i class="fas fa-user-shield"></i></span>
                        <span class="sidebar-link-text">Administradores</span>
                    </a>
                </li>
            </ul>
        @else
            {{-- SECCIÓN PRINCIPAL ADMIN COMUNIDAD --}}
            <div class="sidebar-section-title">Principal</div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'dashboard' ? 'active' : '' }}"
                       href="{{ route('admin.admin-comunidad-dashboard') }}" title="Dashboard">
                        <span class="sidebar-icon"><i class="fas fa-th-large"></i></span>
                        <span class="sidebar-link-text">Dashboard</span>
                    </a>
                </li>
            </ul>

            {{-- SECCIÓN GESTIÓN Y PERSONAS --}}
            <div class="sidebar-section-title">Comunidad & Acceso</div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'residentes' ? 'active' : '' }}"
                       href="{{ route('admin.residentes.index') }}" title="Residentes">
                        <span class="sidebar-icon"><i class="fas fa-users"></i></span>
                        <span class="sidebar-link-text">Residentes</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'invitados' ? 'active' : '' }}" 
                       href="{{ route('admin.invitados.index') }}" title="Invitados">
                        <span class="sidebar-icon"><i class="fas fa-user-friends"></i></span>
                        <span class="sidebar-link-text">Invitados</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'seguridad' ? 'active' : '' }}" 
                       href="{{ route('admin.seguridad.index') }}" title="Personal de Seguridad">
                        <span class="sidebar-icon"><i class="fas fa-shield-alt"></i></span>
                        <span class="sidebar-link-text">Seguridad</span>
                    </a>
                </li>
            </ul>

            {{-- SECCIÓN INSTALACIONES & ESPACIOS --}}
            <div class="sidebar-section-title">Instalaciones & Áreas</div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'instalaciones' ? 'active' : '' }}"
                       href="{{ route('admin.instalaciones.index') }}" title="Instalaciones">
                        <span class="sidebar-icon"><i class="fas fa-swimming-pool"></i></span>
                        <span class="sidebar-link-text">Instalaciones</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'instalaciones-mapa' ? 'active' : '' }}"
                       href="{{ route('admin.mapa.index') }}" title="Mapa Interactivo">
                        <span class="sidebar-icon"><i class="fas fa-map-marked-alt"></i></span>
                        <span class="sidebar-link-text">Mapa</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'reservas' ? 'active' : '' }}" 
                       href="{{ route('admin.reservas.index') }}" title="Reservas">
                        <span class="sidebar-icon"><i class="fas fa-calendar-check"></i></span>
                        <span class="sidebar-link-text">Reservas</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'servicios' ? 'active' : '' }}"
                       href="{{ route('admin.servicios.index') }}" title="Servicios">
                        <span class="sidebar-icon"><i class="fas fa-concierge-bell"></i></span>
                        <span class="sidebar-link-text">Servicios</span>
                    </a>
                </li>
            </ul>

            {{-- SECCIÓN COMUNICACIÓN Y OPERACIONES --}}
            <div class="sidebar-section-title">Operaciones & Avisos</div>
            <ul class="sidebar-nav">
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'incidencias' ? 'active' : '' }}" 
                       href="{{ route('admin.incidencias.index') }}" title="Incidencias">
                        <span class="sidebar-icon"><i class="fas fa-tools"></i></span>
                        <span class="sidebar-link-text">Incidencias</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'alertas-seguridad' ? 'active' : '' }}"
                       href="{{ route('admin.alertas-seguridad.index') }}" title="Alertas de Seguridad">
                        <span class="sidebar-icon"><i class="fas fa-exclamation-triangle"></i></span>
                        <span class="sidebar-link-text">Alertas de Seguridad</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'novedades' ? 'active' : '' }}"
                       href="{{ route('admin.novedades.index') }}" title="Novedades">
                        <span class="sidebar-icon"><i class="fas fa-bullhorn"></i></span>
                        <span class="sidebar-link-text">Novedades</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'eventos' ? 'active' : '' }}"
                       href="{{ route('admin.eventos.index') }}" title="Eventos">
                        <span class="sidebar-icon"><i class="fas fa-calendar-alt"></i></span>
                        <span class="sidebar-link-text">Eventos</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'expensas' ? 'active' : '' }}"
                       href="{{ route('admin.expensas.index') }}" title="Expensas">
                        <span class="sidebar-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                        <span class="sidebar-link-text">Expensas</span>
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a class="sidebar-nav-link {{ $active === 'reglamentos' ? 'active' : '' }}"
                       href="{{ route('admin.reglamentos.index') }}" title="Reglamento">
                        <span class="sidebar-icon"><i class="fas fa-book-reader"></i></span>
                        <span class="sidebar-link-text">Reglamento</span>
                    </a>
                </li>
            </ul>
        @endif
    </div>
</aside>
