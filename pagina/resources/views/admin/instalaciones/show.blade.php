<x-layouts.admin title="Detalle de la Instalación - {{ $instalacion->nombre }}" active="instalaciones">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Detalle de la instalación</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Información, disponibilidad, reglas y amenidades del espacio.</p>
        </div>
        <div>
            <a href="{{ route('admin.instalaciones.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a instalaciones
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- TARJETA HERO DE LA INSTALACIÓN -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            @if(!empty($instalacion->imagenes) && count($instalacion->imagenes) > 0)
                <div class="profile-avatar-large" style="border-radius: var(--admin-radius-md); overflow: hidden; background: #f8fafc;">
                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($instalacion->imagenes[0]) }}" 
                         alt="{{ $instalacion->nombre }}" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            @else
                @php
                    $iconClass = match($instalacion->tipo) {
                        'gastronomia' => 'fa-utensils',
                        'cancha', 'deporte' => 'fa-futbol',
                        'salon', 'sum' => 'fa-champagne-glasses',
                        'pileta', 'piscina' => 'fa-water',
                        'gimnasio' => 'fa-dumbbell',
                        default => 'fa-landmark'
                    };
                @endphp
                <div class="profile-avatar-large" style="border-radius: var(--admin-radius-md);">
                    <i class="fas {{ $iconClass }}"></i>
                </div>
            @endif

            <div class="profile-hero-details">
                <h1>{{ $instalacion->nombre }}</h1>
                <div class="profile-hero-badges">
                    @if($instalacion->estado)
                        <span class="badge-admin badge-success">
                            <i class="fas fa-circle" style="font-size: 6px;"></i> Activa
                        </span>
                    @else
                        <span class="badge-admin badge-danger">
                            <i class="fas fa-circle" style="font-size: 6px;"></i> Inactiva
                        </span>
                    @endif

                    <span class="badge-admin badge-info">
                        <i class="fas fa-layer-group me-1"></i> {{ $instalacion->tipo_label }}
                    </span>

                    @if($instalacion->tipo === 'gastronomia')
                        <span class="badge-admin badge-purple">
                            <i class="fas fa-utensils me-1"></i> Gastronomía
                        </span>
                    @elseif($instalacion->es_reservable)
                        <span class="badge-admin badge-success">
                            <i class="fas fa-calendar-check me-1"></i> Reservable
                        </span>
                    @else
                        <span class="badge-admin badge-secondary">
                            <i class="fas fa-lock me-1"></i> No reservable
                        </span>
                    @endif

                    @if($instalacion->capacidad)
                        <span class="badge-admin badge-secondary">
                            <i class="fas fa-users me-1"></i> Capacidad: {{ $instalacion->capacidad }} personas
                        </span>
                    @endif
                </div>

                <div class="profile-hero-meta">
                    @if($instalacion->ubicacion)
                        <span>
                            <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                            {{ $instalacion->ubicacion }}
                        </span>
                    @endif
                    @if($instalacion->telefono)
                        <span>
                            <i class="fas fa-phone-alt me-1 text-success"></i>
                            {{ $instalacion->telefono }}
                        </span>
                    @endif
                    <span>
                        <i class="far fa-calendar-alt me-1 text-muted"></i>
                        Creada el {{ optional($instalacion->created_at)->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="profile-hero-actions">
            @if($instalacion->tipo === 'gastronomia' && !empty($instalacion->link_reserva))
                <a href="{{ $instalacion->link_reserva }}" target="_blank" rel="noopener noreferrer" class="btn-modern-secondary" title="Abrir enlace externo de reservas o menú">
                    <i class="fas fa-external-link-alt text-primary"></i>
                    <span>Ver menú / reserva</span>
                </a>
            @endif

            <a href="{{ route('admin.instalaciones.edit', $instalacion) }}" class="btn-modern-primary">
                <i class="fas fa-edit"></i>
                <span>Editar instalación</span>
            </a>

            <form method="POST" action="{{ route('admin.instalaciones.destroy', $instalacion) }}" class="d-inline m-0"
                onsubmit="return confirm('¿Estás seguro de eliminar definitivamente esta instalación? Esta acción no se puede revertir.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modern-danger" title="Eliminar instalación">
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

            <!-- INFORMACIÓN & ESPECIFICACIONES -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        Especificaciones & Datos del Espacio
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-tile-grid mb-4">
                        <!-- Tipo -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Categoría</div>
                                <div class="detail-tile-value">{{ $instalacion->tipo_label }}</div>
                            </div>
                        </div>

                        <!-- Capacidad -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon green">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Aforo / Capacidad</div>
                                <div class="detail-tile-value">
                                    {{ $instalacion->capacidad ? $instalacion->capacidad . ' personas' : 'Sin límite especificado' }}
                                </div>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon purple">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Ubicación física</div>
                                <div class="detail-tile-value">
                                    {{ $instalacion->ubicacion ?: 'No especificada' }}
                                </div>
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="detail-tile">
                            <div class="detail-tile-icon amber">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Teléfono de contacto</div>
                                <div class="detail-tile-value">
                                    {{ $instalacion->telefono ?: 'No especificado' }}
                                </div>
                            </div>
                        </div>

                        @if($instalacion->tipo === 'gastronomia' && !empty($instalacion->link_reserva))
                        <!-- Link reserva externa -->
                        <div class="detail-tile" style="grid-column: 1 / -1;">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Enlace externo de reservas / menú</div>
                                <div class="detail-tile-value">
                                    <a href="{{ $instalacion->link_reserva }}" target="_blank" rel="noopener noreferrer" class="text-primary text-decoration-none">
                                        {{ $instalacion->link_reserva }}
                                        <i class="fas fa-external-link-alt ms-1" style="font-size: 0.8rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- DESCRIPCIÓN -->
                    <div>
                        <div class="detail-tile-label mb-2">Descripción del espacio</div>
                        <div class="detail-description-box">
                            {!! $instalacion->descripcion ? nl2br(e($instalacion->descripcion)) : '<span class="text-muted fst-italic">Sin descripción cargada para este espacio.</span>' !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- GALERÍA DE IMÁGENES -->
            @if($instalacion->imagenes && count($instalacion->imagenes) > 0)
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-camera"></i>
                        </span>
                        Galería de Fotos
                        <span class="badge-admin badge-secondary ms-2">{{ count($instalacion->imagenes) }}</span>
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-gallery-grid">
                        @foreach($instalacion->imagenes as $imagen)
                            @php
                                $imgUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($imagen);
                            @endphp
                            <div class="detail-gallery-item"
                                 data-bs-toggle="modal"
                                 data-bs-target="#imageModal"
                                 data-image-url="{{ $imgUrl }}"
                                 onclick="document.getElementById('modalImage').src='{{ $imgUrl }}'">
                                <img src="{{ $imgUrl }}" alt="Foto de {{ $instalacion->nombre }}">
                                <div class="detail-gallery-overlay">
                                    <i class="fas fa-search-plus"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- REGLAS & NORMAS -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-warning-subtle text-warning">
                            <i class="fas fa-clipboard-check"></i>
                        </span>
                        Reglas y Normas de Convivencia
                    </h2>
                </div>
                <div class="admin-card-body">
                    @if(empty($instalacion->reglas_array) || count($instalacion->reglas_array) === 0)
                        <div class="empty-state-modern py-4">
                            <div class="empty-state-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="empty-state-title">Sin reglas específicas</div>
                            <div class="empty-state-text">No se han registrado normas puntuales para esta instalación. Rige el reglamento general del barrio.</div>
                        </div>
                    @else
                        <ul class="detail-checklist">
                            @foreach($instalacion->reglas_array as $regla)
                                <li class="detail-checklist-item">
                                    <span class="detail-checklist-icon">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span>{{ $regla }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- AMENIDADES -->
            <div class="admin-card mb-0">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-success-subtle text-success">
                            <i class="fas fa-concierge-bell"></i>
                        </span>
                        Amenidades Disponibles
                    </h2>
                </div>
                <div class="admin-card-body">
                    @if(empty($instalacion->amenidades_array) || count($instalacion->amenidades_array) === 0)
                        <div class="empty-state-modern py-4">
                            <div class="empty-state-icon">
                                <i class="fas fa-couch"></i>
                            </div>
                            <div class="empty-state-title">Sin amenidades detalladas</div>
                            <div class="empty-state-text">No se han listado servicios o comodidades específicas.</div>
                        </div>
                    @else
                        <ul class="detail-pill-list">
                            @foreach($instalacion->amenidades_array as $amenidad)
                                <li class="detail-pill">
                                    <i class="fas fa-check-circle text-success"></i>
                                    {{ $amenidad }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA (4 COLS) -->
        <div class="col-lg-4">

            <!-- HORARIOS DE DISPONIBILIDAD -->
            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-clock text-primary"></i>
                    Horarios de Atención
                </div>
                @if(empty($instalacion->horario_array) || count($instalacion->horario_array) === 0)
                    <p class="text-muted mb-0" style="font-size: 0.88rem;">Horario libre o a coordinar con la administración.</p>
                @else
                    <ul class="detail-meta-list">
                        @foreach($instalacion->horario_array as $item)
                            <li class="detail-meta-item">
                                <span class="meta-label"><i class="far fa-calendar-check me-1 text-primary"></i> Disponibilidad</span>
                                <span class="meta-value">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- ESTADO Y AUDITORÍA -->
            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-info-circle text-muted"></i>
                    Auditoría del Registro
                </div>
                <ul class="detail-meta-list">
                    <li class="detail-meta-item">
                        <span class="meta-label">Estado</span>
                        <span class="meta-value">
                            @if($instalacion->estado)
                                <span class="badge-admin badge-success"><i class="fas fa-circle" style="font-size: 6px;"></i> Activa</span>
                            @else
                                <span class="badge-admin badge-danger"><i class="fas fa-circle" style="font-size: 6px;"></i> Inactiva</span>
                            @endif
                        </span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Modalidad</span>
                        <span class="meta-value">
                            @if($instalacion->tipo === 'gastronomia')
                                Link / Reserva externa
                            @elseif($instalacion->es_reservable)
                                Sistema interno
                            @else
                                Uso por orden de llegada
                            @endif
                        </span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Fecha de alta</span>
                        <span class="meta-value">{{ optional($instalacion->created_at)->format('d/m/Y H:i') }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Última actualización</span>
                        <span class="meta-value">{{ optional($instalacion->updated_at)->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>
            </div>

            <!-- ACCIONES SECUNDARIAS -->
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.instalaciones.edit', $instalacion) }}" class="btn-modern-primary w-100 justify-content-center">
                    <i class="fas fa-edit"></i>
                    <span>Editar información</span>
                </a>

                <form method="POST" action="{{ route('admin.instalaciones.destroy', $instalacion) }}"
                    onsubmit="return confirm('¿Eliminar definitivamente esta instalación? Esta acción no se puede deshacer.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modern-danger w-100 justify-content-center">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar instalación</span>
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Modal para ver imagen ampliada -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border: none; border-radius: var(--admin-radius-lg); overflow: hidden;">
                <div class="modal-header" style="border-bottom: 1px solid var(--admin-border); padding: 1rem 1.5rem;">
                    <h2 class="h5 modal-title fw-bold text-navy-950 mb-0">
                        <i class="fas fa-image me-2 text-primary"></i>{{ $instalacion->nombre }}
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center" style="background: #091e36;">
                    <img id="modalImage" src="" alt="Imagen" class="img-fluid" style="max-height: 75vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>