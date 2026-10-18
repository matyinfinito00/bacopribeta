<x-layouts.admin title="Dashboard - {{ $comunidad->nombre }}" active="dashboard">

    <!-- HERO BANNER DE COMUNIDAD -->
    <div class="dashboard-hero">
        <div class="dashboard-hero-content">
            <div>
                <h1 class="dashboard-hero-title">¡Hola, {{ $admin->nombre ?? 'Administrador' }}!</h1>
                <div class="dashboard-hero-subtitle">
                    <span class="hero-pill">
                        <i class="fas fa-building"></i> {{ $comunidad->nombre }}
                    </span>
                    <span class="hero-pill">
                        <i class="fas fa-calendar-day"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    </span>
                    <span class="hero-pill">
                        <i class="fas fa-map-marker-alt"></i> {{ $comunidad->ciudad ?? 'Argentina' }}
                    </span>
                </div>
            </div>
            <div class="dashboard-hero-actions">
                <a href="{{ route('admin.novedades.create') }}" class="btn-modern-primary">
                    <i class="fas fa-bullhorn"></i>
                    <span>Nueva Novedad</span>
                </a>
                <a href="{{ route('admin.alertas-seguridad.create') }}" class="btn-modern-ghost-light">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Crear Alerta</span>
                </a>
                <a href="{{ route('admin.residentes.create') }}" class="btn-modern-ghost-light">
                    <i class="fas fa-user-plus"></i>
                    <span>Registrar Residente</span>
                </a>
            </div>
        </div>
    </div>

    <!-- TARJETAS DE MÉTRICAS (KPIs) -->
    <div class="row g-4 mb-4">
        <!-- KPI 1: Residentes -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-kpi-card">
                <div>
                    <div class="stat-kpi-top">
                        <div class="stat-kpi-icon blue">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="stat-kpi-badge success">
                            <i class="fas fa-check"></i> {{ $estadisticas['residentes_activos'] }} activos
                        </span>
                    </div>
                    <div class="stat-kpi-value">{{ $estadisticas['total_residentes'] }}</div>
                    <div class="stat-kpi-label">Residentes Registrados</div>
                </div>
                <div class="stat-kpi-footer">
                    <span>Población del barrio</span>
                    <a href="{{ route('admin.residentes.index') }}" class="text-decoration-none text-primary fw-semibold">Ver directorio</a>
                </div>
            </div>
        </div>

        <!-- KPI 2: Instalaciones -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-kpi-card">
                <div>
                    <div class="stat-kpi-top">
                        <div class="stat-kpi-icon gold">
                            <i class="fas fa-swimming-pool"></i>
                        </div>
                        <span class="stat-kpi-badge success">
                            <i class="fas fa-door-open"></i> {{ $estadisticas['instalaciones_activas'] }} operativas
                        </span>
                    </div>
                    <div class="stat-kpi-value">{{ $estadisticas['total_instalaciones'] }}</div>
                    <div class="stat-kpi-label">Instalaciones & Áreas</div>
                </div>
                <div class="stat-kpi-footer">
                    <span>Espacios reservables</span>
                    <a href="{{ route('admin.instalaciones.index') }}" class="text-decoration-none text-primary fw-semibold">Ver áreas</a>
                </div>
            </div>
        </div>

        <!-- KPI 3: Incidencias Pendientes -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-kpi-card">
                <div>
                    <div class="stat-kpi-top">
                        <div class="stat-kpi-icon rose">
                            <i class="fas fa-tools"></i>
                        </div>
                        @if($estadisticas['incidencias_pendientes'] > 0)
                            <span class="stat-kpi-badge danger">
                                <i class="fas fa-clock"></i> Por resolver
                            </span>
                        @else
                            <span class="stat-kpi-badge success">
                                <i class="fas fa-check-circle"></i> Al día
                            </span>
                        @endif
                    </div>
                    <div class="stat-kpi-value">{{ $estadisticas['incidencias_pendientes'] }}</div>
                    <div class="stat-kpi-label">Incidencias Abiertas</div>
                </div>
                <div class="stat-kpi-footer">
                    <span>Total históricas: {{ $estadisticas['total_incidencias'] }}</span>
                    <a href="{{ route('admin.incidencias.index') }}" class="text-decoration-none text-danger fw-semibold">Revisar</a>
                </div>
            </div>
        </div>

        <!-- KPI 4: Alertas de Seguridad -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-kpi-card">
                <div>
                    <div class="stat-kpi-top">
                        <div class="stat-kpi-icon amber">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span class="stat-kpi-badge warning">
                            <i class="fas fa-bell"></i> Avisos
                        </span>
                    </div>
                    <div class="stat-kpi-value">{{ $alertasRecientes->count() }}</div>
                    <div class="stat-kpi-label">Alertas Activas</div>
                </div>
                <div class="stat-kpi-footer">
                    <span>Comunicaciones críticas</span>
                    <a href="{{ route('admin.alertas-seguridad.index') }}" class="text-decoration-none text-warning fw-semibold">Ver alertas</a>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE WIDGETS Y ACTIVIDAD RECIENTE -->
    <div class="row g-4">
        <!-- Widget 1: Novedades Recientes -->
        <div class="col-lg-6">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-bullhorn"></i>
                        </span>
                        Novedades recientes
                    </h2>
                    <a href="{{ route('admin.novedades.index') }}" class="admin-card-action-link">
                        <span>Ver todas</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="admin-card-body p-0">
                    @if($novedadesRecientes->count() > 0)
                        <div class="table-responsive">
                            <table class="table-admin">
                                <thead>
                                    <tr>
                                        <th>Título del comunicado</th>
                                        <th>Fecha</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($novedadesRecientes as $novedad)
                                        <tr>
                                            <td>
                                                <div class="table-cell-title">{{ Str::limit($novedad->titulo, 38) }}</div>
                                                <div class="table-cell-sub">
                                                    {{ Str::limit(strip_tags($novedad->contenido), 45) }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge-admin badge-secondary">
                                                    <i class="far fa-calendar-alt me-1"></i>{{ $novedad->created_at->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.novedades.show', $novedad) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    Ver
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
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="empty-state-title">Sin novedades publicadas</div>
                            <div class="empty-state-text">Publicá comunicados para mantener informada a la comunidad.</div>
                            <a href="{{ route('admin.novedades.create') }}" class="btn-modern-primary btn-sm">
                                <i class="fas fa-plus"></i> Publicar novedad
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Widget 2: Alertas de Seguridad -->
        <div class="col-lg-6">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-warning-subtle text-warning">
                            <i class="fas fa-shield-alt"></i>
                        </span>
                        Alertas de seguridad
                    </h2>
                    <a href="{{ route('admin.alertas-seguridad.index') }}" class="admin-card-action-link">
                        <span>Ver todas</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="admin-card-body p-0">
                    @if($alertasRecientes->count() > 0)
                        <div class="table-responsive">
                            <table class="table-admin">
                                <thead>
                                    <tr>
                                        <th>Alerta</th>
                                        <th>Prioridad</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($alertasRecientes as $alerta)
                                        <tr>
                                            <td>
                                                <div class="table-cell-title text-danger">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ Str::limit($alerta->titulo, 35) }}
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $pColor = match(strtolower($alerta->prioridad ?? '')) {
                                                        'urgente' => 'badge-danger',
                                                        'alta'    => 'badge-warning',
                                                        'media'   => 'badge-info',
                                                        default   => 'badge-secondary'
                                                    };
                                                @endphp
                                                <span class="badge-admin {{ $pColor }}">
                                                    {{ ucfirst($alerta->prioridad ?? 'Normal') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted" style="font-size: 0.8rem;">
                                                    {{ $alerta->created_at->format('d/m/Y H:i') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state-modern">
                            <div class="empty-state-icon text-success" style="background: #ecfdf5;">
                                <i class="fas fa-shield-check"></i>
                            </div>
                            <div class="empty-state-title">Todo tranquilo</div>
                            <div class="empty-state-text">No hay alertas de seguridad activas en este momento.</div>
                            <a href="{{ route('admin.alertas-seguridad.create') }}" class="btn-modern-secondary btn-sm">
                                <i class="fas fa-plus"></i> Crear alerta
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SEGUNDA FILA: EVENTOS & INCIDENCIAS -->
    <div class="row g-4 mt-1">
        <!-- Widget 3: Próximos Eventos -->
        <div class="col-lg-6">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-info-subtle text-info">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        Próximos eventos
                    </h2>
                    <a href="{{ route('admin.eventos.index') }}" class="admin-card-action-link">
                        <span>Ver calendario</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="admin-card-body p-0">
                    @if($eventosRecientes->count() > 0)
                        <div class="table-responsive">
                            <table class="table-admin">
                                <thead>
                                    <tr>
                                        <th>Evento</th>
                                        <th>Fecha</th>
                                        <th>Horario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($eventosRecientes as $evento)
                                        <tr>
                                            <td>
                                                <div class="table-cell-title">{{ Str::limit($evento->titulo, 32) }}</div>
                                                <div class="table-cell-sub">{{ $evento->lugar ?? 'Comunidad' }}</div>
                                            </td>
                                            <td>
                                                <span class="badge-admin badge-info">
                                                    <i class="far fa-calendar-day me-1"></i>{{ optional($evento->fecha)->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold" style="font-size: 0.85rem;">
                                                    <i class="far fa-clock me-1"></i>{{ $evento->hora_inicio ? substr($evento->hora_inicio, 0, 5) : '--:--' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state-modern">
                            <div class="empty-state-icon">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div class="empty-state-title">No hay eventos programados</div>
                            <div class="empty-state-text">Creá actividades y eventos para fomentar la integración comunitaria.</div>
                            <a href="{{ route('admin.eventos.create') }}" class="btn-modern-primary btn-sm">
                                <i class="fas fa-plus"></i> Crear evento
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Widget 4: Incidencias Recientes -->
        <div class="col-lg-6">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-danger-subtle text-danger">
                            <i class="fas fa-tools"></i>
                        </span>
                        Incidencias reportadas
                    </h2>
                    <a href="{{ route('admin.incidencias.index') }}" class="admin-card-action-link">
                        <span>Ver todas</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="admin-card-body p-0">
                    @if(isset($incidenciasRecientes) && $incidenciasRecientes->count() > 0)
                        <div class="table-responsive">
                            <table class="table-admin">
                                <thead>
                                    <tr>
                                        <th>Incidencia</th>
                                        <th>Reportada por</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($incidenciasRecientes as $incidencia)
                                        <tr>
                                            <td>
                                                <div class="table-cell-title">{{ Str::limit($incidencia->titulo, 28) }}</div>
                                                <div class="table-cell-sub">{{ ucfirst($incidencia->tipo_label ?? $incidencia->tipo) }}</div>
                                            </td>
                                            <td>
                                                <span class="text-muted" style="font-size: 0.82rem;">
                                                    {{ $incidencia->residente->nombre_completo ?? 'Residente' }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $stColor = match($incidencia->estado) {
                                                        'resuelta'   => 'badge-success',
                                                        'en_proceso' => 'badge-warning',
                                                        default      => 'badge-danger'
                                                    };
                                                @endphp
                                                <span class="badge-admin {{ $stColor }}">
                                                    {{ ucfirst(str_replace('_', ' ', $incidencia->estado)) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.incidencias.show', $incidencia) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state-modern">
                            <div class="empty-state-icon text-success" style="background: #ecfdf5;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="empty-state-title">Sin incidencias pendientes</div>
                            <div class="empty-state-text">No hay averías ni incidencias reportadas en la comunidad.</div>
                            <a href="{{ route('admin.incidencias.index') }}" class="btn-modern-secondary btn-sm">
                                <i class="fas fa-list"></i> Historial de incidencias
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>
