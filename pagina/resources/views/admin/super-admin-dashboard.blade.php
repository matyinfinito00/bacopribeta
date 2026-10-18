<x-layouts.admin title="Dashboard - Super Administrador" active="dashboard">

    <!-- HERO BANNER EJECUTIVO -->
    <div class="dashboard-hero">
        <div class="dashboard-hero-content">
            <div>
                <h1 class="dashboard-hero-title">Panel de Super Administrador</h1>
                <div class="dashboard-hero-subtitle">
                    <span>Gestión global de la plataforma BACOPRI</span>
                    <span class="hero-pill">
                        <i class="fas fa-check-circle text-success"></i> Sistema Operativo
                    </span>
                    <span class="hero-pill">
                        <i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::now()->isoFormat('D [de] MMMM, YYYY') }}
                    </span>
                </div>
            </div>
            <div class="dashboard-hero-actions">
                <a href="{{ route('admin.comunidades.create') }}" class="btn-modern-primary">
                    <i class="fas fa-plus"></i>
                    <span>Nueva Comunidad</span>
                </a>
                <a href="{{ route('admin.administradores.create') }}" class="btn-modern-ghost-light">
                    <i class="fas fa-user-plus"></i>
                    <span>Nuevo Administrador</span>
                </a>
            </div>
        </div>
    </div>

    <!-- TARJETAS DE MÉTRICAS GLOBALES (KPIs) -->
    <div class="row g-4 mb-4">
        <!-- KPI 1: Total Comunidades -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-kpi-card">
                <div>
                    <div class="stat-kpi-top">
                        <div class="stat-kpi-icon blue">
                            <i class="fas fa-city"></i>
                        </div>
                        <span class="stat-kpi-badge success">
                            <i class="fas fa-check"></i> {{ $estadisticas['comunidades_activas'] }} activas
                        </span>
                    </div>
                    <div class="stat-kpi-value">{{ $estadisticas['total_comunidades'] }}</div>
                    <div class="stat-kpi-label">Comunidades Totales</div>
                </div>
                <div class="stat-kpi-footer">
                    <span>Barrios y condominios</span>
                    <span class="text-muted">{{ $estadisticas['comunidades_inactivas'] }} inactivas</span>
                </div>
            </div>
        </div>

        <!-- KPI 2: Administradores -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-kpi-card">
                <div>
                    <div class="stat-kpi-top">
                        <div class="stat-kpi-icon emerald">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <span class="stat-kpi-badge info">
                            <i class="fas fa-users"></i> {{ $estadisticas['administradores_activos'] }} activos
                        </span>
                    </div>
                    <div class="stat-kpi-value">{{ $estadisticas['total_administradores'] }}</div>
                    <div class="stat-kpi-label">Administradores</div>
                </div>
                <div class="stat-kpi-footer">
                    <span>Cuentas de gestión</span>
                    <a href="{{ route('admin.administradores.index') }}" class="text-decoration-none text-primary fw-semibold">Ver todos</a>
                </div>
            </div>
        </div>

        <!-- KPI 3: Residentes Globales -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-kpi-card">
                <div>
                    <div class="stat-kpi-top">
                        <div class="stat-kpi-icon indigo">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="stat-kpi-badge info">
                            <i class="fas fa-globe"></i> Global
                        </span>
                    </div>
                    <div class="stat-kpi-value">{{ $estadisticas['total_residentes'] }}</div>
                    <div class="stat-kpi-label">Residentes en Plataforma</div>
                </div>
                <div class="stat-kpi-footer">
                    <span>Usuarios registrados</span>
                    <span class="text-muted">En todas las comunidades</span>
                </div>
            </div>
        </div>

        <!-- KPI 4: Instalaciones Registradas -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-kpi-card">
                <div>
                    <div class="stat-kpi-top">
                        <div class="stat-kpi-icon gold">
                            <i class="fas fa-swimming-pool"></i>
                        </div>
                        <span class="stat-kpi-badge success">
                            <i class="fas fa-layer-group"></i> Espacios
                        </span>
                    </div>
                    <div class="stat-kpi-value">{{ $estadisticas['total_instalaciones'] }}</div>
                    <div class="stat-kpi-label">Instalaciones Totales</div>
                </div>
                <div class="stat-kpi-footer">
                    <span>Canchas, quinchos y áreas</span>
                    <span class="text-muted">Activos en el sistema</span>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLAS DE GESTIÓN RECIENTE -->
    <div class="row g-4">
        <!-- Comunidades Recientes -->
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-city"></i>
                        </span>
                        Comunidades recientes
                    </h2>
                    <a href="{{ route('admin.comunidades.index') }}" class="admin-card-action-link">
                        <span>Ver todas</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="admin-card-body p-0">
                    @if($comunidadesRecientes->count() > 0)
                        <div class="table-responsive">
                            <table class="table-admin">
                                <thead>
                                    <tr>
                                        <th>Comunidad</th>
                                        <th>Ubicación</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($comunidadesRecientes as $comunidad)
                                        <tr>
                                            <td>
                                                <div class="table-user-cell">
                                                    <div class="table-avatar" style="background: linear-gradient(135deg, #1d4ed8, #0f172a);">
                                                        {{ strtoupper(mb_substr($comunidad->nombre, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="table-cell-title">{{ $comunidad->nombre }}</div>
                                                        <div class="table-cell-sub">{{ $comunidad->correo ?? 'Sin correo' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $comunidad->ciudad ?? 'N/D' }}</span>
                                            </td>
                                            <td>
                                                @if($comunidad->estado)
                                                    <span class="badge-admin badge-success">
                                                        <i class="fas fa-circle" style="font-size: 6px;"></i> Activa
                                                    </span>
                                                @else
                                                    <span class="badge-admin badge-danger">
                                                        <i class="fas fa-circle" style="font-size: 6px;"></i> Inactiva
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.comunidades.edit', $comunidad) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Editar comunidad">
                                                    <i class="fas fa-edit me-1"></i>Editar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state-modern">
                            <div class="empty-state-icon">
                                <i class="fas fa-city"></i>
                            </div>
                            <div class="empty-state-title">No hay comunidades registradas</div>
                            <div class="empty-state-text">Comenzá dando de alta la primera comunidad en el sistema.</div>
                            <a href="{{ route('admin.comunidades.create') }}" class="btn-modern-primary btn-sm">
                                <i class="fas fa-plus"></i> Crear comunidad
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Administradores Recientes -->
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-success-subtle text-success">
                            <i class="fas fa-user-shield"></i>
                        </span>
                        Administradores recientes
                    </h2>
                    <a href="{{ route('admin.administradores.index') }}" class="admin-card-action-link">
                        <span>Ver todos</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="admin-card-body p-0">
                    @if($administradoresRecientes->count() > 0)
                        <div class="table-responsive">
                            <table class="table-admin">
                                <thead>
                                    <tr>
                                        <th>Administrador</th>
                                        <th>Comunidad</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($administradoresRecientes as $administrador)
                                        @php
                                            $adminInitials = mb_substr($administrador->nombre ?? 'A', 0, 1) . mb_substr($administrador->apellido ?? '', 0, 1);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="table-user-cell">
                                                    <div class="table-avatar">
                                                        {{ strtoupper($adminInitials ?: 'A') }}
                                                    </div>
                                                    <div>
                                                        <div class="table-cell-title">{{ $administrador->nombre_completo }}</div>
                                                        <div class="table-cell-sub">{{ $administrador->correo }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($administrador->comunidad)
                                                    <span class="badge-admin badge-info">
                                                        <i class="fas fa-building me-1"></i>{{ Str::limit($administrador->comunidad->nombre, 18) }}
                                                    </span>
                                                @else
                                                    <span class="badge-admin badge-secondary">Sin asignar</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($administrador->estado)
                                                    <span class="badge-admin badge-success">
                                                        <i class="fas fa-check me-1"></i>Activo
                                                    </span>
                                                @else
                                                    <span class="badge-admin badge-danger">
                                                        <i class="fas fa-times me-1"></i>Inactivo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.administradores.edit', $administrador) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="Editar administrador">
                                                    <i class="fas fa-edit me-1"></i>Editar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state-modern">
                            <div class="empty-state-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="empty-state-title">No hay administradores registrados</div>
                            <div class="empty-state-text">Registrá un nuevo administrador asignado a una comunidad.</div>
                            <a href="{{ route('admin.administradores.create') }}" class="btn-modern-primary btn-sm">
                                <i class="fas fa-plus"></i> Crear administrador
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>
