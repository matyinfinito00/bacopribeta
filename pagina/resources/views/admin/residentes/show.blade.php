<x-layouts.admin title="Detalle del Residente - {{ $residente->nombre_completo }}" active="residentes">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Detalle del residente</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Ficha completa del habitante, su vivienda y su actividad en el barrio.</p>
        </div>
        <div>
            <a href="{{ route('admin.residentes.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a residentes
            </a>
        </div>
    </div>

    <!-- TARJETA HERO DE PERFIL -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            @php
                $initials = mb_substr($residente->nombre ?? 'R', 0, 1) . mb_substr($residente->apellido ?? '', 0, 1);
            @endphp
            @if(!empty($residente->foto_perfil))
                <img src="{{ Storage::disk('public')->url($residente->foto_perfil) }}" alt="{{ $residente->nombre_completo }}" class="profile-avatar-large" style="object-fit: cover;">
            @else
                <div class="profile-avatar-large">
                    {{ strtoupper($initials ?: 'R') }}
                </div>
            @endif
            <div class="profile-hero-details">
                <h1>{{ $residente->nombre_completo }}</h1>
                <div class="profile-hero-badges">
                    @if($residente->estado)
                        <span class="badge-admin badge-success">
                            <i class="fas fa-circle" style="font-size: 6px;"></i> Activo
                        </span>
                    @else
                        <span class="badge-admin badge-danger">
                            <i class="fas fa-circle" style="font-size: 6px;"></i> Inactivo
                        </span>
                    @endif

                    @if($residente->es_propietario)
                        <span class="badge-admin badge-info">
                            <i class="fas fa-home me-1"></i> Propietario
                        </span>
                    @else
                        <span class="badge-admin badge-secondary">
                            <i class="fas fa-user me-1"></i> Inquilino
                        </span>
                    @endif

                    <span class="badge-admin badge-secondary">
                        <i class="fas fa-door-open me-1"></i> Unidad: {{ $residente->numero_unidad }}
                    </span>
                </div>
                <div class="profile-hero-meta">
                    <span>
                        <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                        {{ $residente->direccion }}{{ $residente->altura ? ' ' . $residente->altura : '' }}{{ $residente->numero_lote ? ' (Lote ' . $residente->numero_lote . ')' : '' }}
                    </span>
                    <span>
                        <i class="far fa-calendar-alt me-1 text-muted"></i>
                        Registrado el {{ $residente->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="profile-hero-actions">
            <a href="{{ route('admin.residentes.edit', $residente) }}" class="btn-modern-primary">
                <i class="fas fa-edit"></i>
                <span>Editar residente</span>
            </a>

            <form method="POST" action="{{ route('admin.residentes.cambiar-estado', $residente) }}" class="d-inline m-0">
                @csrf
                <button type="submit" class="btn-modern-secondary" title="{{ $residente->estado ? 'Desactivar acceso' : 'Activar acceso' }}">
                    <i class="fas {{ $residente->estado ? 'fa-user-slash text-warning' : 'fa-user-check text-success' }}"></i>
                    <span>{{ $residente->estado ? 'Desactivar' : 'Activar' }}</span>
                </button>
            </form>

            <form method="POST" action="{{ route('admin.residentes.destroy', $residente) }}" class="d-inline m-0"
                onsubmit="return confirm('¿Estás seguro de eliminar este residente? Esta acción no se puede deshacer.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modern-danger" title="Eliminar residente">
                    <i class="fas fa-trash"></i>
                    <span>Eliminar</span>
                </button>
            </form>
        </div>
    </div>

    <!-- FILA DE INFORMACIÓN EN TILES MODERNOS -->
    <div class="row g-4 mb-4">
        <!-- Tarjeta 1: Datos Personales & Contacto -->
        <div class="col-lg-6">
            <div class="admin-card h-100 mb-0">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-user-circle"></i>
                        </span>
                        Datos Personales & Contacto
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
                                    <a href="mailto:{{ $residente->correo }}" class="text-decoration-none text-navy-950">
                                        {{ $residente->correo }}
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
                                <div class="detail-tile-label">Teléfono</div>
                                <div class="detail-tile-value">
                                    <a href="tel:{{ $residente->telefono }}" class="text-decoration-none text-navy-950">
                                        {{ $residente->telefono }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Documento -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon purple">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Documento de identidad</div>
                                <div class="detail-tile-value">
                                    {{ $residente->tipo_documento }} {{ $residente->numero_documento }}
                                </div>
                            </div>
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon amber">
                                <i class="fas fa-birthday-cake"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Fecha de nacimiento</div>
                                <div class="detail-tile-value">
                                    {{ optional($residente->fecha_nacimiento)->format('d/m/Y') }}
                                    @if($residente->fecha_nacimiento)
                                        <span class="text-muted fw-normal" style="font-size: 0.8rem;">
                                            ({{ \Carbon\Carbon::parse($residente->fecha_nacimiento)->age }} años)
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta 2: Vivienda & Estado -->
        <div class="col-lg-6">
            <div class="admin-card h-100 mb-0">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-success-subtle text-success">
                            <i class="fas fa-home"></i>
                        </span>
                        Ubicación & Vivienda
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-tile-grid">
                        <!-- Número de Unidad -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-door-closed"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Unidad / Inmueble</div>
                                <div class="detail-tile-value">{{ $residente->numero_unidad }}</div>
                            </div>
                        </div>

                        <!-- Calle y Altura -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon green">
                                <i class="fas fa-road"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Calle y Altura</div>
                                <div class="detail-tile-value">
                                    {{ $residente->direccion }} {{ $residente->altura ? $residente->altura : '' }}
                                </div>
                            </div>
                        </div>

                        <!-- Lote -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon purple">
                                <i class="fas fa-map"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Número de Lote</div>
                                <div class="detail-tile-value">
                                    {{ $residente->numero_lote ?: 'Sin asignar' }}
                                </div>
                            </div>
                        </div>

                        <!-- Tenencia / Condición -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon amber">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Condición de tenencia</div>
                                <div class="detail-tile-value">
                                    {{ $residente->es_propietario ? 'Propietario titular' : 'Inquilino / Ocupante' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE ACTIVIDAD: RESERVAS E INCIDENCIAS -->
    <div class="row g-4">
        <!-- Reservas Recientes -->
        <div class="col-lg-6">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-calendar-check"></i>
                        </span>
                        Reservas recientes
                        @if($reservas->count() > 0)
                            <span class="badge-admin badge-info ms-2">{{ $reservas->count() }}</span>
                        @endif
                    </h2>
                    @if($reservas->count() > 0)
                        <a href="{{ route('admin.reservas.index', ['residente_id' => $residente->id]) }}" class="admin-card-action-link">
                            <span>Ver todas</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    @endif
                </div>
                <div class="admin-card-body p-0">
                    @if($reservas->count() > 0)
                        <div class="table-responsive">
                            <table class="table-admin">
                                <thead>
                                    <tr>
                                        <th>Instalación</th>
                                        <th>Fecha</th>
                                        <th>Horario</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservas as $reserva)
                                        <tr>
                                            <td>
                                                <div class="table-cell-title">{{ $reserva->instalacion->nombre ?? 'Instalación' }}</div>
                                                <div class="table-cell-sub">{{ $reserva->instalacion->tipo_label ?? '' }}</div>
                                            </td>
                                            <td>
                                                <span class="badge-admin badge-secondary">
                                                    {{ optional($reserva->fecha)->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted" style="font-size: 0.85rem;">
                                                    <i class="far fa-clock me-1"></i>{{ $reserva->hora_inicio }} ({{ $reserva->duracion_minutos }} min)
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $rColor = match($reserva->estado) {
                                                        'activa', 'confirmada' => 'badge-success',
                                                        'cancelada'            => 'badge-danger',
                                                        default                => 'badge-secondary'
                                                    };
                                                @endphp
                                                <span class="badge-admin {{ $rColor }}">
                                                    {{ ucfirst($reserva->estado) }}
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
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <div class="empty-state-title">Sin reservas registradas</div>
                            <div class="empty-state-text">Este residente aún no ha realizado reservas de instalaciones.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Incidencias Reportadas -->
        <div class="col-lg-6">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-danger-subtle text-danger">
                            <i class="fas fa-tools"></i>
                        </span>
                        Incidencias reportadas
                        @if($incidencias->count() > 0)
                            <span class="badge-admin badge-danger ms-2">{{ $incidencias->count() }}</span>
                        @endif
                    </h2>
                    @if($incidencias->count() > 0)
                        <a href="{{ route('admin.incidencias.index', ['residente_id' => $residente->id]) }}" class="admin-card-action-link">
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
                                        <th>Incidencia</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($incidencias as $incidencia)
                                        <tr>
                                            <td>
                                                <div class="table-cell-title">{{ Str::limit($incidencia->titulo, 30) }}</div>
                                                <div class="table-cell-sub">{{ $incidencia->tipo_label ?? $incidencia->tipo }}</div>
                                            </td>
                                            <td>
                                                <span class="text-muted" style="font-size: 0.82rem;">
                                                    {{ $incidencia->created_at->format('d/m/Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $iColor = match($incidencia->estado) {
                                                        'resuelta'   => 'badge-success',
                                                        'en_proceso' => 'badge-warning',
                                                        default      => 'badge-danger'
                                                    };
                                                @endphp
                                                <span class="badge-admin {{ $iColor }}">
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
                            <div class="empty-state-title">Sin incidencias</div>
                            <div class="empty-state-text">El residente no cuenta con reportes ni reclamos activos.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>