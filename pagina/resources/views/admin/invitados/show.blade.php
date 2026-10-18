<x-layouts.admin title="Detalle del Invitado - {{ $invitado->nombre_completo }}" active="invitados">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Detalle del invitado</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Información de la visita, vehículo y control de acceso en portería.</p>
        </div>
        <div>
            <a href="{{ route('admin.invitados.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a invitados
            </a>
        </div>
    </div>

    <!-- TARJETA HERO DEL INVITADO -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            @php
                $invInitials = mb_substr($invitado->nombre ?? 'I', 0, 1) . mb_substr($invitado->apellido ?? '', 0, 1);
                $iColor = match($invitado->estado) {
                    'activo' => 'badge-success',
                    'cancelado' => 'badge-danger',
                    default => 'badge-warning'
                };
            @endphp
            <div class="profile-avatar-large" style="background: linear-gradient(135deg, #0284c7, #0f766e);">
                {{ strtoupper($invInitials ?: 'I') }}
            </div>

            <div class="profile-hero-details">
                <h1>{{ $invitado->nombre_completo }}</h1>
                <div class="profile-hero-badges">
                    <span class="badge-admin {{ $iColor }}">
                        <i class="fas fa-circle" style="font-size: 6px;"></i> {{ ucfirst($invitado->estado) }}
                    </span>

                    @if($invitado->ingresa_con_auto)
                        <span class="badge-admin badge-info">
                            <i class="fas fa-car me-1"></i> Vehículo: {{ $invitado->patente ?: 'Registrado' }}
                        </span>
                    @else
                        <span class="badge-admin badge-secondary">
                            <i class="fas fa-walking me-1"></i> Ingreso peatonal
                        </span>
                    @endif

                    @if($invitado->visita_en)
                        <span class="badge-admin badge-secondary">
                            <i class="far fa-calendar-alt me-1"></i> Visita: {{ $invitado->visita_en->format('d/m/Y H:i') }}
                        </span>
                    @endif
                </div>

                <div class="profile-hero-meta">
                    @if($invitado->residente)
                        <span>
                            <i class="fas fa-user-home me-1 text-primary"></i>
                            Anfitrión: {{ $invitado->residente->nombre }} {{ $invitado->residente->apellido ?? '' }} (Unidad {{ $invitado->residente->numero_unidad ?? '-' }})
                        </span>
                    @endif
                    @if($invitado->numero_documento)
                        <span>
                            <i class="fas fa-id-card me-1 text-muted"></i>
                            Doc: {{ $invitado->numero_documento }}
                        </span>
                    @endif
                    <span>
                        <i class="far fa-clock me-1 text-muted"></i>
                        Creado el {{ $invitado->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="profile-hero-actions">
            <a href="{{ route('admin.invitados.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i>
                <span>Volver al listado</span>
            </a>
        </div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="row g-4">
        <!-- COLUMNA PRINCIPAL (8 COLS) -->
        <div class="col-lg-8">

            <!-- DATOS DEL INVITADO -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-user"></i>
                        </span>
                        Datos de Identificación del Visitante
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-tile-grid mb-4">
                        <div class="detail-tile">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Documento de identidad</div>
                                <div class="detail-tile-value">{{ $invitado->numero_documento ?: 'Sin documento' }}</div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon green">
                                <i class="far fa-calendar-check"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Fecha estimada de visita</div>
                                <div class="detail-tile-value">
                                    {{ $invitado->visita_en ? $invitado->visita_en->format('d/m/Y H:i') . ' hs' : 'No especificada' }}
                                </div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon purple">
                                <i class="fas fa-car"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Modalidad de acceso</div>
                                <div class="detail-tile-value">
                                    {{ $invitado->ingresa_con_auto ? 'En vehículo' : 'Peatonal' }}
                                </div>
                            </div>
                        </div>

                        @if($invitado->ingresa_con_auto)
                        <div class="detail-tile">
                            <div class="detail-tile-icon amber">
                                <i class="fas fa-digital-tachograph"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Patente / Dominio</div>
                                <div class="detail-tile-value text-uppercase">{{ $invitado->patente ?: 'No informada' }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($invitado->comentario)
                    <div>
                        <div class="detail-tile-label mb-2">Comentarios o indicaciones adicionales</div>
                        <div class="detail-description-box">
                            {!! nl2br(e($invitado->comentario)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- RESIDENTE ANFITRIÓN -->
            <div class="admin-card mb-0">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-info-subtle text-info">
                            <i class="fas fa-home"></i>
                        </span>
                        Residente Anfitrión
                    </h2>
                </div>
                <div class="admin-card-body">
                    @if($invitado->residente)
                        <div class="detail-tile-grid">
                            <div class="detail-tile">
                                <div class="detail-tile-icon blue">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="detail-tile-content">
                                    <div class="detail-tile-label">Nombre del residente</div>
                                    <div class="detail-tile-value">
                                        <a href="{{ route('admin.residentes.show', $invitado->residente) }}" class="text-primary text-decoration-none">
                                            {{ $invitado->residente->nombre }} {{ $invitado->residente->apellido ?? '' }}
                                            <i class="fas fa-external-link-alt ms-1" style="font-size: 0.75rem;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="detail-tile">
                                <div class="detail-tile-icon green">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <div class="detail-tile-content">
                                    <div class="detail-tile-label">Unidad / Inmueble destino</div>
                                    <div class="detail-tile-value">{{ $invitado->residente->numero_unidad ?: 'Sin unidad' }}</div>
                                </div>
                            </div>

                            <div class="detail-tile">
                                <div class="detail-tile-icon purple">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="detail-tile-content">
                                    <div class="detail-tile-label">Correo</div>
                                    <div class="detail-tile-value">
                                        <a href="mailto:{{ $invitado->residente->correo }}" class="text-navy-950 text-decoration-none">
                                            {{ $invitado->residente->correo }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            @if($invitado->residente->telefono)
                            <div class="detail-tile">
                                <div class="detail-tile-icon amber">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="detail-tile-content">
                                    <div class="detail-tile-label">Teléfono</div>
                                    <div class="detail-tile-value">
                                        <a href="tel:{{ $invitado->residente->telefono }}" class="text-navy-950 text-decoration-none">
                                            {{ $invitado->residente->telefono }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted mb-0">Residente anfitrión no disponible o dado de baja.</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA (4 COLS) -->
        <div class="col-lg-4">

            <!-- CONTROL DE TIEMPOS -->
            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-stopwatch text-primary"></i>
                    Control de Acceso
                </div>
                <ul class="detail-meta-list">
                    <li class="detail-meta-item">
                        <span class="meta-label"><i class="fas fa-sign-in-alt text-success me-1"></i> Ingreso</span>
                        <span class="meta-value">
                            @if($invitado->ingreso_en)
                                <span class="text-success">{{ $invitado->ingreso_en->format('d/m/Y H:i') }}</span>
                            @else
                                <span class="text-muted">Pendiente</span>
                            @endif
                        </span>
                    </li>
                    @if($invitado->confirmadoPor)
                        <li class="detail-meta-item">
                            <span class="meta-label" style="font-size: 0.78rem;">Autorizado por</span>
                            <span class="meta-value" style="font-size: 0.8rem;">{{ $invitado->confirmadoPor->nombre }}</span>
                        </li>
                    @endif

                    <li class="detail-meta-item">
                        <span class="meta-label"><i class="fas fa-sign-out-alt text-warning me-1"></i> Salida</span>
                        <span class="meta-value">
                            @if($invitado->salida_en)
                                <span class="text-warning">{{ $invitado->salida_en->format('d/m/Y H:i') }}</span>
                            @elseif($invitado->ingreso_en)
                                <span class="badge-admin badge-success" style="font-size: 0.75rem;">En barrio</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </span>
                    </li>
                    @if($invitado->salidaRegistradaPor)
                        <li class="detail-meta-item">
                            <span class="meta-label" style="font-size: 0.78rem;">Salida registrada por</span>
                            <span class="meta-value" style="font-size: 0.8rem;">{{ $invitado->salidaRegistradaPor->nombre }}</span>
                        </li>
                    @endif

                    @if($invitado->ingreso_en && $invitado->salida_en)
                        @php
                            $diff = $invitado->ingreso_en->diff($invitado->salida_en);
                            $horas = $diff->h + ($diff->days * 24);
                            $minutos = $diff->i;
                        @endphp
                        <li class="detail-meta-item">
                            <span class="meta-label"><i class="fas fa-clock text-primary me-1"></i> Permanencia</span>
                            <span class="meta-value fw-bold text-navy-950">{{ $horas }}h {{ $minutos }}m</span>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- ESTADO ACTUAL -->
            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-info-circle text-muted"></i>
                    Estado de la Visita
                </div>
                @if($invitado->estado === 'pendiente')
                    <div class="alert alert-warning mb-0" style="font-size: 0.85rem;">
                        <i class="fas fa-clock me-1"></i>
                        <strong>Pendiente:</strong> Aún no ha registrado ingreso por guardia.
                    </div>
                @elseif($invitado->estado === 'activo' && !$invitado->salida_en)
                    <div class="alert alert-success mb-0" style="font-size: 0.85rem;">
                        <i class="fas fa-check-circle me-1"></i>
                        <strong>Activo:</strong> El invitado se encuentra actualmente dentro del barrio.
                    </div>
                @elseif($invitado->estado === 'activo' && $invitado->salida_en)
                    <div class="alert alert-info mb-0" style="font-size: 0.85rem;">
                        <i class="fas fa-check-double me-1"></i>
                        <strong>Completado:</strong> Ingreso y egreso completados con éxito.
                    </div>
                @elseif($invitado->estado === 'cancelado')
                    <div class="alert alert-danger mb-0" style="font-size: 0.85rem;">
                        <i class="fas fa-times-circle me-1"></i>
                        <strong>Cancelado:</strong> La visita fue cancelada.
                        @if($invitado->canceladoPor)
                            <div class="mt-1" style="font-size: 0.78rem;">Por: {{ $invitado->canceladoPor->nombre }}</div>
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-layouts.admin>
