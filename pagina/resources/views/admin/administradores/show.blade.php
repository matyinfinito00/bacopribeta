<x-layouts.admin title="Detalle del Administrador - {{ $administrador->nombre_completo }}" active="administradores">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Detalle del administrador</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Credenciales, datos de contacto y comunidad asignada.</p>
        </div>
        <div>
            <a href="{{ route('admin.administradores.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a administradores
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- TARJETA HERO DEL ADMINISTRADOR -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            @php
                $initials = mb_substr($administrador->nombre ?? 'A', 0, 1) . mb_substr($administrador->apellido ?? '', 0, 1);
            @endphp
            <div class="profile-avatar-large" style="background: linear-gradient(135deg, #4338ca, #1e1b4b);">
                {{ strtoupper($initials ?: 'A') }}
            </div>

            <div class="profile-hero-details">
                <h1>{{ $administrador->nombre_completo }}</h1>
                <div class="profile-hero-badges">
                    @if($administrador->estado)
                        <span class="badge-admin badge-success">
                            <i class="fas fa-circle" style="font-size: 6px;"></i> Activo
                        </span>
                    @else
                        <span class="badge-admin badge-danger">
                            <i class="fas fa-circle" style="font-size: 6px;"></i> Inactivo
                        </span>
                    @endif

                    <span class="badge-admin badge-purple">
                        <i class="fas fa-id-badge me-1"></i> {{ $administrador->cargo ?: 'Administrador de Comunidad' }}
                    </span>

                    <span class="badge-admin badge-secondary">
                        <i class="fas fa-building me-1"></i> {{ $administrador->comunidad->nombre ?? 'Sin asignar' }}
                    </span>
                </div>

                <div class="profile-hero-meta">
                    <span>
                        <i class="fas fa-envelope me-1 text-primary"></i>
                        {{ $administrador->correo }}
                    </span>
                    @if($administrador->telefono)
                        <span>
                            <i class="fas fa-phone-alt me-1 text-success"></i>
                            {{ $administrador->telefono }}
                        </span>
                    @endif
                    <span>
                        <i class="far fa-calendar-alt me-1 text-muted"></i>
                        Registrado el {{ optional($administrador->created_at)->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="profile-hero-actions">
            <a href="{{ route('admin.administradores.edit', $administrador) }}" class="btn-modern-primary">
                <i class="fas fa-edit"></i>
                <span>Editar administrador</span>
            </a>

            <form method="POST" action="{{ route('admin.administradores.cambiar-estado', $administrador) }}" class="d-inline m-0">
                @csrf
                <button type="submit" class="btn-modern-secondary" title="{{ $administrador->estado ? 'Desactivar cuenta' : 'Activar cuenta' }}">
                    <i class="fas {{ $administrador->estado ? 'fa-pause text-warning' : 'fa-play text-success' }}"></i>
                    <span>{{ $administrador->estado ? 'Desactivar' : 'Activar' }}</span>
                </button>
            </form>

            <form method="POST" action="{{ route('admin.administradores.destroy', $administrador) }}" class="d-inline m-0"
                onsubmit="return confirm('¿Estás seguro de eliminar este administrador? Esta acción es irreversible.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modern-danger" title="Eliminar administrador">
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

            <div class="admin-card mb-0">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-user-circle"></i>
                        </span>
                        Datos Personales & Cargo Asignado
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-tile-grid">
                        <!-- Correo -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Correo electrónico</div>
                                <div class="detail-tile-value">
                                    <a href="mailto:{{ $administrador->correo }}" class="text-navy-950 text-decoration-none">
                                        {{ $administrador->correo }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon green">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Teléfono de contacto</div>
                                <div class="detail-tile-value">
                                    @if($administrador->telefono)
                                        <a href="tel:{{ $administrador->telefono }}" class="text-navy-950 text-decoration-none">
                                            {{ $administrador->telefono }}
                                        </a>
                                    @else
                                        <span class="text-muted fw-normal">No especificado</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Documento -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon purple">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Documento nacional</div>
                                <div class="detail-tile-value">
                                    {{ $administrador->tipo_documento ? $administrador->tipo_documento . ' ' . $administrador->numero_documento : 'Sin registrar' }}
                                </div>
                            </div>
                        </div>

                        <!-- Cargo -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon amber">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Función / Rol</div>
                                <div class="detail-tile-value">{{ $administrador->cargo ?: 'Administrador de Comunidad' }}</div>
                            </div>
                        </div>

                        <!-- Comunidad -->
                        <div class="detail-tile" style="grid-column: 1 / -1;">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Comunidad / Barrio a cargo</div>
                                <div class="detail-tile-value">
                                    @if($administrador->comunidad)
                                        <a href="{{ route('admin.comunidades.show', $administrador->comunidad->id) }}" class="text-primary text-decoration-none">
                                            {{ $administrador->comunidad->nombre }}
                                            <i class="fas fa-arrow-right ms-1" style="font-size: 0.8rem;"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">Sin comunidad asignada</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA (4 COLS) -->
        <div class="col-lg-4">

            <!-- METADATOS Y ACCESO -->
            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-shield-alt text-primary"></i>
                    Seguridad y Registro
                </div>
                <ul class="detail-meta-list">
                    <li class="detail-meta-item">
                        <span class="meta-label">Estado de la cuenta</span>
                        <span class="meta-value">
                            @if($administrador->estado)
                                <span class="badge-admin badge-success"><i class="fas fa-circle" style="font-size: 6px;"></i> Activa</span>
                            @else
                                <span class="badge-admin badge-danger"><i class="fas fa-circle" style="font-size: 6px;"></i> Inactiva</span>
                            @endif
                        </span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Fecha de creación</span>
                        <span class="meta-value">{{ optional($administrador->created_at)->format('d/m/Y H:i') }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Última actualización</span>
                        <span class="meta-value">{{ optional($administrador->updated_at)->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>
            </div>

            <!-- ACCIONES SECUNDARIAS -->
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.administradores.edit', $administrador) }}" class="btn-modern-primary w-100 justify-content-center">
                    <i class="fas fa-edit"></i>
                    <span>Editar administrador</span>
                </a>

                <form method="POST" action="{{ route('admin.administradores.cambiar-estado', $administrador) }}">
                    @csrf
                    <button type="submit" class="btn-modern-secondary w-100 justify-content-center">
                        <i class="fas {{ $administrador->estado ? 'fa-pause text-warning' : 'fa-play text-success' }}"></i>
                        <span>{{ $administrador->estado ? 'Desactivar cuenta' : 'Activar cuenta' }}</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.administradores.destroy', $administrador) }}"
                    onsubmit="return confirm('¿Eliminar definitivamente este administrador?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modern-danger w-100 justify-content-center">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar administrador</span>
                    </button>
                </form>
            </div>

        </div>
    </div>

</x-layouts.admin>
