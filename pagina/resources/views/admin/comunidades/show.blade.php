<x-layouts.admin title="Detalle de la Comunidad - {{ $comunidad->nombre }}" active="comunidades">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Detalle de la comunidad</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Información general, administradores asignados y métricas del barrio.</p>
        </div>
        <div>
            <a href="{{ route('admin.comunidades.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a comunidades
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- TARJETA HERO DE LA COMUNIDAD -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            @php
                $words = explode(' ', trim($comunidad->nombre));
                $cInitials = mb_substr($words[0] ?? 'C', 0, 1) . (isset($words[1]) ? mb_substr($words[1], 0, 1) : '');
            @endphp
            <div class="profile-avatar-large" style="background: linear-gradient(135deg, #1e3a8a, #0d9488);">
                {{ strtoupper($cInitials ?: 'C') }}
            </div>

            <div class="profile-hero-details">
                <h1>{{ $comunidad->nombre }}</h1>
                <div class="profile-hero-badges">
                    @if($comunidad->estado)
                        <span class="badge-admin badge-success">
                            <i class="fas fa-circle" style="font-size: 6px;"></i> Activa
                        </span>
                    @else
                        <span class="badge-admin badge-danger">
                            <i class="fas fa-circle" style="font-size: 6px;"></i> Inactiva
                        </span>
                    @endif

                    <span class="badge-admin badge-info">
                        <i class="fas fa-users me-1"></i> {{ $comunidad->residentes->count() }} Residentes
                    </span>

                    <span class="badge-admin badge-purple">
                        <i class="fas fa-user-shield me-1"></i> {{ $comunidad->administradores->count() }} Administradores
                    </span>

                    <span class="badge-admin badge-secondary">
                        <i class="fas fa-building me-1"></i> {{ $comunidad->instalaciones->count() }} Instalaciones
                    </span>
                </div>

                <div class="profile-hero-meta">
                    @if($comunidad->direccion)
                        <span>
                            <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                            {{ $comunidad->direccion }}{{ $comunidad->ciudad ? ', ' . $comunidad->ciudad : '' }}{{ $comunidad->provincia ? ' (' . $comunidad->provincia . ')' : '' }}
                        </span>
                    @endif
                    @if($comunidad->telefono)
                        <span>
                            <i class="fas fa-phone-alt me-1 text-success"></i>
                            {{ $comunidad->telefono }}
                        </span>
                    @endif
                    @if($comunidad->correo)
                        <span>
                            <i class="fas fa-envelope me-1 text-muted"></i>
                            {{ $comunidad->correo }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="profile-hero-actions">
            <a href="{{ route('admin.comunidades.edit', $comunidad->id) }}" class="btn-modern-primary">
                <i class="fas fa-edit"></i>
                <span>Editar comunidad</span>
            </a>

            <form method="POST" action="{{ route('admin.comunidades.cambiar-estado', $comunidad->id) }}" class="d-inline m-0">
                @csrf
                <button type="submit" class="btn-modern-secondary" title="{{ $comunidad->estado ? 'Desactivar comunidad' : 'Activar comunidad' }}">
                    <i class="fas {{ $comunidad->estado ? 'fa-pause text-warning' : 'fa-play text-success' }}"></i>
                    <span>{{ $comunidad->estado ? 'Desactivar' : 'Activar' }}</span>
                </button>
            </form>

            @if($comunidad->administradores->count() === 0)
                <form method="POST" action="{{ route('admin.comunidades.destroy', $comunidad->id) }}" class="d-inline m-0"
                    onsubmit="return confirm('¿Eliminar definitivamente esta comunidad? Esta acción no se puede deshacer.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modern-danger" title="Eliminar comunidad">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="row g-4">
        <!-- COLUMNA PRINCIPAL (8 COLS) -->
        <div class="col-lg-8">

            <!-- DATOS DE CONTACTO & UBICACIÓN -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-map-marked-alt"></i>
                        </span>
                        Ubicación & Canales de Contacto
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-tile-grid mb-4">
                        <!-- Dirección -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-map-pin"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Dirección principal</div>
                                <div class="detail-tile-value">{{ $comunidad->direccion ?: 'No especificada' }}</div>
                            </div>
                        </div>

                        <!-- Ciudad & Provincia -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon green">
                                <i class="fas fa-city"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Localidad / Jurisdicción</div>
                                <div class="detail-tile-value">
                                    {{ $comunidad->ciudad ?: 'Ciudad no especificada' }}{{ $comunidad->provincia ? ' - ' . $comunidad->provincia : '' }}
                                </div>
                            </div>
                        </div>

                        <!-- Código Postal -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon purple">
                                <i class="fas fa-mail-bulk"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Código Postal</div>
                                <div class="detail-tile-value">{{ $comunidad->codigo_postal ?: 'Sin código' }}</div>
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon amber">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Teléfono de guardia / adm.</div>
                                <div class="detail-tile-value">{{ $comunidad->telefono ?: 'No especificado' }}</div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="detail-tile" style="grid-column: 1 / -1;">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Correo institucional</div>
                                <div class="detail-tile-value">
                                    <a href="mailto:{{ $comunidad->correo }}" class="text-navy-950 text-decoration-none">
                                        {{ $comunidad->correo ?: 'Sin correo registrado' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DESCRIPCIÓN -->
                    <div>
                        <div class="detail-tile-label mb-2">Acerca de la comunidad</div>
                        <div class="detail-description-box">
                            {!! $comunidad->descripcion ? nl2br(e($comunidad->descripcion)) : '<span class="text-muted fst-italic">No se ha redactado una descripción para esta comunidad.</span>' !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- ADMINISTRADORES ASIGNADOS -->
            <div class="admin-card mb-0">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-purple-subtle text-purple" style="background: #f5f3ff; color: #7c3aed;">
                            <i class="fas fa-user-shield"></i>
                        </span>
                        Administradores Asignados
                        <span class="badge-admin badge-secondary ms-2">{{ $comunidad->administradores->count() }}</span>
                    </h2>
                </div>
                <div class="admin-card-body p-0">
                    @if($comunidad->administradores->isEmpty())
                        <div class="empty-state-modern py-4">
                            <div class="empty-state-icon">
                                <i class="fas fa-users-slash"></i>
                            </div>
                            <div class="empty-state-title">Sin administradores</div>
                            <div class="empty-state-text">Aún no se han asignado administradores específicos a esta comunidad.</div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table-admin">
                                <thead>
                                    <tr>
                                        <th>Administrador</th>
                                        <th>Correo electrónico</th>
                                        <th>Cargo</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($comunidad->administradores as $admin)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="admin-header-user-avatar" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                        {{ strtoupper(mb_substr($admin->nombre ?? 'A', 0, 1)) }}
                                                    </div>
                                                    <div class="table-cell-title">{{ $admin->nombre_completo ?? $admin->nombre }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted" style="font-size: 0.88rem;">{{ $admin->correo }}</span>
                                            </td>
                                            <td>
                                                <span class="badge-admin badge-secondary">{{ $admin->cargo ?: 'Administrador' }}</span>
                                            </td>
                                            <td>
                                                @if($admin->estado)
                                                    <span class="badge-admin badge-success"><i class="fas fa-circle" style="font-size: 6px;"></i> Activo</span>
                                                @else
                                                    <span class="badge-admin badge-danger"><i class="fas fa-circle" style="font-size: 6px;"></i> Inactivo</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA (4 COLS) -->
        <div class="col-lg-4">

            <!-- KPIs DE LA COMUNIDAD -->
            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-chart-pie text-primary"></i>
                    Indicadores del Barrio
                </div>
                <ul class="detail-meta-list">
                    <li class="detail-meta-item">
                        <span class="meta-label"><i class="fas fa-users me-1 text-primary"></i> Residentes registrados</span>
                        <span class="meta-value">{{ $comunidad->residentes->count() }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label"><i class="fas fa-landmark me-1 text-success"></i> Instalaciones activas</span>
                        <span class="meta-value">{{ $comunidad->instalaciones->count() }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label"><i class="fas fa-user-shield me-1 text-warning"></i> Administradores</span>
                        <span class="meta-value">{{ $comunidad->administradores->count() }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Alta en el sistema</span>
                        <span class="meta-value">{{ optional($comunidad->created_at)->format('d/m/Y') }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Última actualización</span>
                        <span class="meta-value">{{ optional($comunidad->updated_at)->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>
            </div>

            <!-- ACCIONES SECUNDARIAS -->
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.comunidades.edit', $comunidad->id) }}" class="btn-modern-primary w-100 justify-content-center">
                    <i class="fas fa-edit"></i>
                    <span>Editar comunidad</span>
                </a>

                <form method="POST" action="{{ route('admin.comunidades.cambiar-estado', $comunidad->id) }}">
                    @csrf
                    <button type="submit" class="btn-modern-secondary w-100 justify-content-center">
                        <i class="fas {{ $comunidad->estado ? 'fa-pause text-warning' : 'fa-play text-success' }}"></i>
                        <span>{{ $comunidad->estado ? 'Desactivar comunidad' : 'Activar comunidad' }}</span>
                    </button>
                </form>

                @if($comunidad->administradores->count() === 0)
                    <form method="POST" action="{{ route('admin.comunidades.destroy', $comunidad->id) }}"
                        onsubmit="return confirm('¿Eliminar definitivamente esta comunidad? Esta acción no se puede deshacer.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-modern-danger w-100 justify-content-center">
                            <i class="fas fa-trash"></i>
                            <span>Eliminar comunidad</span>
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </div>

</x-layouts.admin>
