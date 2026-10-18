<x-layouts.admin title="Detalle de Reserva - {{ $reserva->instalacion->nombre }}" active="reservas">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Detalle de la reserva</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Información del turno reservado, residente y espacio asignado.</p>
        </div>
        <div>
            <a href="{{ route('admin.reservas.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a reservas
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- TARJETA HERO DE LA RESERVA -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            @php
                $rColor = match($reserva->estado) {
                    'activa', 'confirmada' => 'badge-success',
                    'cancelada'            => 'badge-danger',
                    default                => 'badge-secondary'
                };
                $avatarBg = match($reserva->estado) {
                    'activa', 'confirmada' => 'linear-gradient(135deg, #059669, #10b981)',
                    'cancelada'            => 'linear-gradient(135deg, #dc2626, #ef4444)',
                    default                => 'linear-gradient(135deg, #475569, #64748b)'
                };
            @endphp
            <div class="profile-avatar-large" style="background: {{ $avatarBg }};">
                <i class="fas fa-calendar-check"></i>
            </div>

            <div class="profile-hero-details">
                <h1>{{ $reserva->instalacion->nombre }}</h1>
                <div class="profile-hero-badges">
                    <span class="badge-admin {{ $rColor }}">
                        <i class="fas fa-circle" style="font-size: 6px;"></i> {{ ucfirst($reserva->estado) }}
                    </span>

                    <span class="badge-admin badge-info">
                        <i class="far fa-calendar me-1"></i> {{ $reserva->fecha->format('d/m/Y') }} ({{ ucfirst($reserva->fecha->locale('es')->dayName) }})
                    </span>

                    <span class="badge-admin badge-secondary">
                        <i class="far fa-clock me-1"></i> {{ $reserva->hora_inicio }} ({{ $reserva->duracion_minutos }} min)
                    </span>
                </div>

                <div class="profile-hero-meta">
                    <span>
                        <i class="fas fa-user me-1 text-primary"></i>
                        {{ $reserva->residente->nombre }} {{ $reserva->residente->apellido ?? '' }} (Unidad {{ $reserva->residente->numero_unidad ?? '-' }})
                    </span>
                    <span>
                        <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                        {{ $reserva->instalacion->ubicacion ?: 'Instalación del barrio' }}
                    </span>
                    <span>
                        <i class="far fa-clock me-1 text-muted"></i>
                        Reservado el {{ $reserva->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="profile-hero-actions">
            @if($reserva->estado === 'activa')
                <form method="POST" action="{{ route('admin.reservas.cancelar', $reserva) }}" class="d-inline m-0"
                    onsubmit="return confirm('¿Estás seguro de cancelar esta reserva?');">
                    @csrf
                    <button type="submit" class="btn-modern-danger" title="Cancelar esta reserva">
                        <i class="fas fa-ban"></i>
                        <span>Cancelar reserva</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="row g-4">
        <!-- COLUMNA PRINCIPAL (8 COLS) -->
        <div class="col-lg-8">

            <!-- DETALLES DEL TURNO -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-clock"></i>
                        </span>
                        Detalles del Turno
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-tile-grid">
                        <div class="detail-tile">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Fecha reservada</div>
                                <div class="detail-tile-value">
                                    {{ $reserva->fecha->format('d/m/Y') }}
                                    <div class="text-muted fw-normal" style="font-size: 0.8rem;">
                                        {{ ucfirst($reserva->fecha->locale('es')->dayName) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon green">
                                <i class="fas fa-hourglass-start"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Hora de inicio</div>
                                <div class="detail-tile-value">{{ $reserva->hora_inicio }} hs</div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon purple">
                                <i class="fas fa-stopwatch"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Duración autorizada</div>
                                <div class="detail-tile-value">{{ $reserva->duracion_minutos }} minutos</div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon amber">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Estado de la reserva</div>
                                <div class="detail-tile-value">
                                    <span class="badge-admin {{ $rColor }}">{{ ucfirst($reserva->estado) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RESIDENTE TITULAR -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-info-subtle text-info">
                            <i class="fas fa-user"></i>
                        </span>
                        Residente Titular
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-tile-grid">
                        <div class="detail-tile">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Nombre del residente</div>
                                <div class="detail-tile-value">
                                    <a href="{{ route('admin.residentes.show', $reserva->residente) }}" class="text-primary text-decoration-none">
                                        {{ $reserva->residente->nombre }} {{ $reserva->residente->apellido ?? '' }}
                                        <i class="fas fa-external-link-alt ms-1" style="font-size: 0.75rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon green">
                                <i class="fas fa-door-closed"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Unidad funcional</div>
                                <div class="detail-tile-value">{{ $reserva->residente->numero_unidad ?: 'Sin unidad' }}</div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon purple">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Correo electrónico</div>
                                <div class="detail-tile-value">
                                    <a href="mailto:{{ $reserva->residente->correo }}" class="text-navy-950 text-decoration-none">
                                        {{ $reserva->residente->correo }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        @if($reserva->residente->telefono)
                        <div class="detail-tile">
                            <div class="detail-tile-icon amber">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Teléfono de contacto</div>
                                <div class="detail-tile-value">
                                    <a href="tel:{{ $reserva->residente->telefono }}" class="text-navy-950 text-decoration-none">
                                        {{ $reserva->residente->telefono }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- INSTALACIÓN -->
            <div class="admin-card mb-0">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-success-subtle text-success">
                            <i class="fas fa-landmark"></i>
                        </span>
                        Instalación Reservada
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-tile-grid">
                        <div class="detail-tile">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Espacio común</div>
                                <div class="detail-tile-value">
                                    <a href="{{ route('admin.instalaciones.show', $reserva->instalacion) }}" class="text-primary text-decoration-none">
                                        {{ $reserva->instalacion->nombre }}
                                        <i class="fas fa-external-link-alt ms-1" style="font-size: 0.75rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon green">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Tipo / Categoría</div>
                                <div class="detail-tile-value">{{ $reserva->instalacion->tipo_label }}</div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon purple">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Ubicación</div>
                                <div class="detail-tile-value">{{ $reserva->instalacion->ubicacion ?: 'No especificada' }}</div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon amber">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Capacidad máxima</div>
                                <div class="detail-tile-value">{{ $reserva->instalacion->capacidad ? $reserva->instalacion->capacidad . ' personas' : 'Sin aforo' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA (4 COLS) -->
        <div class="col-lg-4">

            <!-- AUDITORÍA -->
            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-history text-muted"></i>
                    Registro de la Reserva
                </div>
                <ul class="detail-meta-list">
                    <li class="detail-meta-item">
                        <span class="meta-label">Estado actual</span>
                        <span class="meta-value">
                            <span class="badge-admin {{ $rColor }}">{{ ucfirst($reserva->estado) }}</span>
                        </span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Creada el</span>
                        <span class="meta-value">{{ $reserva->created_at->format('d/m/Y H:i') }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">ID de reserva</span>
                        <span class="meta-value">#{{ $reserva->id }}</span>
                    </li>
                </ul>
            </div>

            <!-- ACCIONES SECUNDARIAS -->
            <div class="d-flex flex-column gap-2">
                @if($reserva->estado === 'activa')
                    <form method="POST" action="{{ route('admin.reservas.cancelar', $reserva) }}"
                        onsubmit="return confirm('¿Estás seguro de cancelar esta reserva?');">
                        @csrf
                        <button type="submit" class="btn-modern-danger w-100 justify-content-center">
                            <i class="fas fa-ban"></i>
                            <span>Cancelar reserva</span>
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.reservas.index') }}" class="btn-modern-secondary w-100 justify-content-center">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver a reservas</span>
                </a>
            </div>

        </div>
    </div>

</x-layouts.admin>
