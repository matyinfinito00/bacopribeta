<x-layouts.admin title="Detalle de Usuario de Seguridad - {{ $seguridad->nombre_completo }}" active="seguridad">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Detalle del personal de seguridad</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Información operativa, contacto e historial de reportes en portería o patrullaje.</p>
        </div>
        <div>
            <a href="{{ route('admin.seguridad.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a seguridad
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- TARJETA HERO DEL USUARIO DE SEGURIDAD -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            @php
                $initials = mb_substr($seguridad->nombre ?? 'S', 0, 1) . mb_substr($seguridad->apellido ?? '', 0, 1);
            @endphp
            <div class="profile-avatar-large" style="background: linear-gradient(135deg, #1e293b, #0f172a);">
                <i class="fas fa-user-shield"></i>
            </div>

            <div class="profile-hero-details">
                <h1>{{ $seguridad->nombre_completo }}</h1>
                <div class="profile-hero-badges">
                    @if($seguridad->estado)
                        <span class="badge-admin badge-success">
                            <i class="fas fa-circle" style="font-size: 6px;"></i> Activo
                        </span>
                    @else
                        <span class="badge-admin badge-danger">
                            <i class="fas fa-circle" style="font-size: 6px;"></i> Inactivo
                        </span>
                    @endif

                    <span class="badge-admin badge-secondary">
                        <i class="fas fa-id-badge me-1"></i> {{ $seguridad->cargo ?: 'Personal de Seguridad' }}
                    </span>

                    <span class="badge-admin badge-info">
                        <i class="fas fa-building me-1"></i> {{ $seguridad->comunidad->nombre ?? 'Comunidad' }}
                    </span>
                </div>

                <div class="profile-hero-meta">
                    <span>
                        <i class="fas fa-envelope me-1 text-primary"></i>
                        {{ $seguridad->correo }}
                    </span>
                    @if($seguridad->telefono)
                        <span>
                            <i class="fas fa-phone-alt me-1 text-success"></i>
                            {{ $seguridad->telefono }}
                        </span>
                    @endif
                    <span>
                        <i class="far fa-calendar-alt me-1 text-muted"></i>
                        Registrado el {{ optional($seguridad->created_at)->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="profile-hero-actions">
            <a href="{{ route('admin.seguridad.edit', $seguridad) }}" class="btn-modern-primary">
                <i class="fas fa-edit"></i>
                <span>Editar personal</span>
            </a>

            <form method="POST" action="{{ route('admin.seguridad.cambiar-estado', $seguridad) }}" class="d-inline m-0">
                @csrf
                <button type="submit" class="btn-modern-secondary" title="{{ $seguridad->estado ? 'Desactivar acceso' : 'Activar acceso' }}">
                    <i class="fas {{ $seguridad->estado ? 'fa-pause text-warning' : 'fa-play text-success' }}"></i>
                    <span>{{ $seguridad->estado ? 'Desactivar' : 'Activar' }}</span>
                </button>
            </form>

            <form method="POST" action="{{ route('admin.seguridad.destroy', $seguridad) }}" class="d-inline m-0"
                onsubmit="return confirm('¿Estás seguro de eliminar este usuario de seguridad definitivamente?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modern-danger" title="Eliminar usuario">
                    <i class="fas fa-trash"></i>
                    <span>Eliminar</span>
                </button>
            </form>
        </div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="row g-4">
        <!-- COLUMNA PRINCIPAL (8 COLS) -->
        <div class="col-lg-8">

            <!-- DATOS OPERATIVOS -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-id-card"></i>
                        </span>
                        Credenciales & Asignación
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-tile-grid">
                        <div class="detail-tile">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Correo de acceso</div>
                                <div class="detail-tile-value">
                                    <a href="mailto:{{ $seguridad->correo }}" class="text-navy-950 text-decoration-none">
                                        {{ $seguridad->correo }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon green">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Teléfono corporativo / guardia</div>
                                <div class="detail-tile-value">
                                    @if($seguridad->telefono)
                                        <a href="tel:{{ $seguridad->telefono }}" class="text-navy-950 text-decoration-none">
                                            {{ $seguridad->telefono }}
                                        </a>
                                    @else
                                        <span class="text-muted fw-normal">No especificado</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon purple">
                                <i class="fas fa-id-badge"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Documento</div>
                                <div class="detail-tile-value">
                                    {{ $seguridad->tipo_documento ? $seguridad->tipo_documento . ' ' . $seguridad->numero_documento : 'Sin registrar' }}
                                </div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon amber">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Cargo / Puesto</div>
                                <div class="detail-tile-value">{{ $seguridad->cargo ?: 'Personal de Seguridad' }}</div>
                            </div>
                        </div>

                        <div class="detail-tile" style="grid-column: 1 / -1;">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Comunidad asignada</div>
                                <div class="detail-tile-value">{{ $seguridad->comunidad->nombre ?? 'Sin comunidad asignada' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INCIDENCIAS REPORTADAS -->
            <div class="admin-card mb-0">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-danger-subtle text-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </span>
                        Incidencias Reportadas por el Usuario
                        @if($incidencias->count() > 0)
                            <span class="badge-admin badge-secondary ms-2">{{ $incidencias->count() }}</span>
                        @endif
                    </h2>
                    @if($incidencias->count() > 0)
                        <a href="{{ route('admin.incidencias.index', ['administrador_id' => $seguridad->id]) }}" class="admin-card-action-link">
                            <span>Ver todas</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    @endif
                </div>
                <div class="admin-card-body p-0">
                    @if($incidencias->count() > 0)
                        <div class="table-responsive">
                            <table class="table-admin">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Título</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($incidencias as $incidencia)
                                        <tr>
                                            <td>
                                                <span class="badge-admin badge-secondary">
                                                    {{ Str::limit($incidencia->tipo_label, 20) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="table-cell-title">{{ Str::limit($incidencia->titulo, 35) }}</div>
                                            </td>
                                            <td>
                                                <span class="text-muted" style="font-size: 0.85rem;">
                                                    {{ $incidencia->created_at->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $iColor = match($incidencia->estado) {
                                                        'resuelta' => 'badge-success',
                                                        'en_proceso' => 'badge-info',
                                                        default => 'badge-warning'
                                                    };
                                                @endphp
                                                <span class="badge-admin {{ $iColor }}">
                                                    {{ $incidencia->estado_label }}
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
                        <div class="empty-state-modern py-4">
                            <div class="empty-state-icon text-success" style="background: #ecfdf5;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="empty-state-title">Sin incidencias reportadas</div>
                            <div class="empty-state-text">Este miembro del equipo aún no ha originado reportes de seguridad.</div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA (4 COLS) -->
        <div class="col-lg-4">

            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-shield-alt text-primary"></i>
                    Información de la Cuenta
                </div>
                <ul class="detail-meta-list">
                    <li class="detail-meta-item">
                        <span class="meta-label">Estado</span>
                        <span class="meta-value">
                            @if($seguridad->estado)
                                <span class="badge-admin badge-success"><i class="fas fa-circle" style="font-size: 6px;"></i> Activo</span>
                            @else
                                <span class="badge-admin badge-danger"><i class="fas fa-circle" style="font-size: 6px;"></i> Inactivo</span>
                            @endif
                        </span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Fecha de alta</span>
                        <span class="meta-value">{{ optional($seguridad->created_at)->format('d/m/Y H:i') }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Última actualización</span>
                        <span class="meta-value">{{ optional($seguridad->updated_at)->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>
            </div>

            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.seguridad.edit', $seguridad) }}" class="btn-modern-primary w-100 justify-content-center">
                    <i class="fas fa-edit"></i>
                    <span>Editar personal</span>
                </a>

                <form method="POST" action="{{ route('admin.seguridad.cambiar-estado', $seguridad) }}">
                    @csrf
                    <button type="submit" class="btn-modern-secondary w-100 justify-content-center">
                        <i class="fas {{ $seguridad->estado ? 'fa-pause text-warning' : 'fa-play text-success' }}"></i>
                        <span>{{ $seguridad->estado ? 'Desactivar acceso' : 'Activar acceso' }}</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.seguridad.destroy', $seguridad) }}"
                    onsubmit="return confirm('¿Eliminar definitivamente este usuario de seguridad?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modern-danger w-100 justify-content-center">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar usuario</span>
                    </button>
                </form>
            </div>

        </div>
    </div>

</x-layouts.admin>
