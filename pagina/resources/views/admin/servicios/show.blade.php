<x-layouts.admin title="Detalle del Servicio - {{ $servicio->nombre }}" active="servicios">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Detalle del servicio</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Prestadores externos, teléfonos útiles y servicios de guardia o emergencia.</p>
        </div>
        <div>
            <a href="{{ route('admin.servicios.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a servicios
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- TARJETA HERO DEL SERVICIO -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            @php
                $sIcon = $servicio->es_emergencia ? 'fa-ambulance' : 'fa-tools';
                $sBg = $servicio->es_emergencia
                    ? 'linear-gradient(135deg, #dc2626, #b91c1c)'
                    : 'linear-gradient(135deg, #0284c7, #0369a1)';
            @endphp
            <div class="profile-avatar-large" style="background: {{ $sBg }};">
                <i class="fas {{ $sIcon }}"></i>
            </div>

            <div class="profile-hero-details">
                <h1>{{ $servicio->nombre }}</h1>
                <div class="profile-hero-badges">
                    @if($servicio->estado)
                        <span class="badge-admin badge-success">
                            <i class="fas fa-circle" style="font-size: 6px;"></i> Activo
                        </span>
                    @else
                        <span class="badge-admin badge-danger">
                            <i class="fas fa-circle" style="font-size: 6px;"></i> Inactivo
                        </span>
                    @endif

                    <span class="badge-admin badge-info">
                        <i class="fas fa-layer-group me-1"></i> {{ $servicio->categoria_label }}
                    </span>

                    @if($servicio->es_emergencia)
                        <span class="badge-admin badge-danger">
                            <i class="fas fa-exclamation-triangle me-1"></i> Guardia / Urgencia 24hs
                        </span>
                    @endif
                </div>

                <div class="profile-hero-meta">
                    @if($servicio->telefono)
                        <span>
                            <i class="fas fa-phone-alt me-1 text-success"></i>
                            {{ $servicio->telefono }}
                        </span>
                    @endif
                    @if($servicio->nombre_contacto)
                        <span>
                            <i class="fas fa-user me-1 text-primary"></i>
                            Contacto: {{ $servicio->nombre_contacto }}
                        </span>
                    @endif
                    @if($servicio->direccion)
                        <span>
                            <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                            {{ $servicio->direccion }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="profile-hero-actions">
            <a href="{{ route('admin.servicios.edit', $servicio) }}" class="btn-modern-primary">
                <i class="fas fa-edit"></i>
                <span>Editar servicio</span>
            </a>

            <form method="POST" action="{{ route('admin.servicios.destroy', $servicio) }}" class="d-inline m-0"
                onsubmit="return confirm('¿Estás seguro de eliminar este servicio definitivamente?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modern-danger" title="Eliminar servicio">
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

            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-align-left"></i>
                        </span>
                        Descripción del Servicio
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-description-box" style="font-size: 0.95rem; line-height: 1.7; background: #ffffff; border-color: #e2e8f0;">
                        {!! $servicio->descripcion ? nl2br(e($servicio->descripcion)) : '<span class="text-muted fst-italic">No se cargó una descripción detallada para este servicio.</span>' !!}
                    </div>
                </div>
            </div>

            <!-- HORARIOS DE ATENCIÓN -->
            <div class="admin-card mb-0">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-success-subtle text-success">
                            <i class="far fa-clock"></i>
                        </span>
                        Horarios de Cobertura
                    </h2>
                </div>
                <div class="admin-card-body">
                    @if(empty($servicio->horario_array) || count($servicio->horario_array) === 0)
                        <p class="text-muted mb-0">Disponibilidad según demanda o guardia permanente.</p>
                    @else
                        <ul class="detail-checklist">
                            @foreach($servicio->horario_array as $item)
                                <li class="detail-checklist-item">
                                    <span class="detail-checklist-icon">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA (4 COLS) -->
        <div class="col-lg-4">

            <!-- CONTACTO DEL PRESTADOR -->
            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-address-book text-primary"></i>
                    Contacto del Prestador
                </div>
                <ul class="detail-meta-list">
                    <li class="detail-meta-item">
                        <span class="meta-label">Responsable</span>
                        <span class="meta-value">{{ $servicio->nombre_contacto ?: 'No especificado' }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Teléfono directo</span>
                        <span class="meta-value">
                            @if($servicio->telefono)
                                <a href="tel:{{ $servicio->telefono }}" class="text-navy-950 text-decoration-none">
                                    {{ $servicio->telefono }}
                                </a>
                            @else
                                <span class="text-muted">Sin teléfono</span>
                            @endif
                        </span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Correo</span>
                        <span class="meta-value">
                            @if($servicio->correo)
                                <a href="mailto:{{ $servicio->correo }}" class="text-navy-950 text-decoration-none">
                                    {{ $servicio->correo }}
                                </a>
                            @else
                                <span class="text-muted">Sin correo</span>
                            @endif
                        </span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Dirección / Base</span>
                        <span class="meta-value">{{ $servicio->direccion ?: 'No especificada' }}</span>
                    </li>
                </ul>
            </div>

            <!-- ESTADO Y AUDITORÍA -->
            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-info-circle text-muted"></i>
                    Disponibilidad y Auditoría
                </div>
                <ul class="detail-meta-list">
                    <li class="detail-meta-item">
                        <span class="meta-label">Visibilidad vecinos</span>
                        <span class="meta-value">
                            @if($servicio->estado)
                                <span class="badge-admin badge-success"><i class="fas fa-circle" style="font-size: 6px;"></i> Visible</span>
                            @else
                                <span class="badge-admin badge-danger"><i class="fas fa-circle" style="font-size: 6px;"></i> Oculto</span>
                            @endif
                        </span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Clasificación</span>
                        <span class="meta-value">{{ $servicio->es_emergencia ? 'Emergencia' : 'Servicio general' }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Última actualización</span>
                        <span class="meta-value">{{ optional($servicio->updated_at)->format('d/m/Y H:i') ?? 'Sin registro' }}</span>
                    </li>
                </ul>
            </div>

            <!-- ACCIONES -->
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.servicios.edit', $servicio) }}" class="btn-modern-primary w-100 justify-content-center">
                    <i class="fas fa-edit"></i>
                    <span>Editar servicio</span>
                </a>

                <form method="POST" action="{{ route('admin.servicios.destroy', $servicio) }}"
                    onsubmit="return confirm('¿Eliminar definitivamente este servicio?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modern-danger w-100 justify-content-center">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar servicio</span>
                    </button>
                </form>
            </div>

        </div>
    </div>

</x-layouts.admin>