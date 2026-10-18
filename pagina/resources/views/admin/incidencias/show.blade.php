<x-layouts.admin title="Detalle de Incidencia - {{ $incidencia->titulo }}" active="incidencias">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Detalle de la incidencia</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Seguimiento, diagnóstico y actualización de reclamos o eventos reportados.</p>
        </div>
        <div>
            <a href="{{ route('admin.incidencias.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a incidencias
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- TARJETA HERO DE LA INCIDENCIA -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            @php
                $heroIcon = match($incidencia->estado) {
                    'resuelta' => 'fa-check-circle',
                    'en_proceso' => 'fa-spinner fa-spin',
                    default => 'fa-exclamation-triangle'
                };
                $heroBg = match($incidencia->estado) {
                    'resuelta' => 'linear-gradient(135deg, #059669, #10b981)',
                    'en_proceso' => 'linear-gradient(135deg, #2563eb, #3b82f6)',
                    default => 'linear-gradient(135deg, #d97706, #f59e0b)'
                };
            @endphp
            <div class="profile-avatar-large" style="background: {{ $heroBg }};">
                <i class="fas {{ $heroIcon }}"></i>
            </div>

            <div class="profile-hero-details">
                <h1>{{ $incidencia->titulo }}</h1>
                <div class="profile-hero-badges">
                    @php
                        $badgeStyle = match($incidencia->estado) {
                            'resuelta' => 'badge-success',
                            'en_proceso' => 'badge-info',
                            default => 'badge-warning'
                        };
                    @endphp
                    <span class="badge-admin {{ $badgeStyle }}">
                        <i class="fas fa-circle" style="font-size: 6px;"></i> {{ $incidencia->estado_label }}
                    </span>

                    <span class="badge-admin badge-secondary">
                        <i class="fas fa-tag me-1"></i> {{ $incidencia->tipo_label }}
                    </span>

                    <span class="badge-admin badge-secondary">
                        <i class="far fa-calendar-alt me-1"></i> Reportada el {{ $incidencia->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>

                <div class="profile-hero-meta">
                    @if($incidencia->ubicacion)
                        <span>
                            <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                            {{ $incidencia->ubicacion }}
                        </span>
                    @endif
                    @if($incidencia->residente)
                        <span>
                            <i class="fas fa-user me-1 text-primary"></i>
                            Reportado por: {{ $incidencia->residente->nombre }} {{ $incidencia->residente->apellido ?? '' }} (Unidad {{ $incidencia->residente->numero_unidad ?? '-' }})
                        </span>
                    @elseif($incidencia->administrador)
                        <span>
                            <i class="fas fa-user-shield me-1 text-purple"></i>
                            Reportado por personal: {{ $incidencia->administrador->nombre }}
                        </span>
                    @else
                        <span>
                            <i class="fas fa-shield-alt me-1 text-muted"></i>
                            Reportado por personal de guardia
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="profile-hero-actions">
            <a href="#cambiarEstadoCard" class="btn-modern-primary">
                <i class="fas fa-tasks"></i>
                <span>Gestionar estado</span>
            </a>
        </div>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="row g-4">
        <!-- COLUMNA PRINCIPAL (8 COLS) -->
        <div class="col-lg-8">

            <!-- DESCRIPCIÓN & DETALLES -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-file-alt"></i>
                        </span>
                        Descripción & Ubicación del Hecho
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-tile-grid mb-4">
                        <div class="detail-tile">
                            <div class="detail-tile-icon blue">
                                <i class="fas fa-filter"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Categoría de Incidencia</div>
                                <div class="detail-tile-value">{{ $incidencia->tipo_label }}</div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon amber">
                                <i class="far fa-clock"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Momento del Registro</div>
                                <div class="detail-tile-value">{{ $incidencia->created_at->format('d/m/Y H:i') }} hs</div>
                            </div>
                        </div>

                        @if($incidencia->ubicacion)
                        <div class="detail-tile" style="grid-column: 1 / -1;">
                            <div class="detail-tile-icon purple">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Lugar / Sector afectado</div>
                                <div class="detail-tile-value">{{ $incidencia->ubicacion }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div>
                        <div class="detail-tile-label mb-2">Relato detallado</div>
                        <div class="detail-description-box mb-4">
                            {!! nl2br(e($incidencia->descripcion)) !!}
                        </div>
                    </div>

                    <!-- FOTO ADJUNTA -->
                    @if($incidencia->imagen)
                        <div>
                            <div class="detail-tile-label mb-2">Evidencia fotográfica adjunta</div>
                            @php
                                $imgUrl = Storage::disk('public')->url($incidencia->imagen);
                            @endphp
                            <div class="detail-gallery-grid" style="grid-template-columns: minmax(200px, 320px);">
                                <div class="detail-gallery-item"
                                     data-bs-toggle="modal"
                                     data-bs-target="#imageModal"
                                     onclick="document.getElementById('modalImage').src='{{ $imgUrl }}'">
                                    <img src="{{ $imgUrl }}" alt="Foto de la incidencia">
                                    <div class="detail-gallery-overlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- INFORMANTE -->
            <div class="admin-card mb-0">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-info-subtle text-info">
                            <i class="fas fa-user-check"></i>
                        </span>
                        Datos del Informante
                    </h2>
                </div>
                <div class="admin-card-body">
                    @if($incidencia->residente)
                        <div class="detail-tile-grid">
                            <div class="detail-tile">
                                <div class="detail-tile-icon blue">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="detail-tile-content">
                                    <div class="detail-tile-label">Residente</div>
                                    <div class="detail-tile-value">
                                        <a href="{{ route('admin.residentes.show', $incidencia->residente) }}" class="text-primary text-decoration-none">
                                            {{ $incidencia->residente->nombre }} {{ $incidencia->residente->apellido ?? '' }}
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
                                    <div class="detail-tile-label">Unidad / Inmueble</div>
                                    <div class="detail-tile-value">
                                        {{ $incidencia->residente->numero_unidad ?: 'Sin unidad' }}
                                    </div>
                                </div>
                            </div>

                            <div class="detail-tile">
                                <div class="detail-tile-icon purple">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="detail-tile-content">
                                    <div class="detail-tile-label">Correo electrónico</div>
                                    <div class="detail-tile-value">
                                        <a href="mailto:{{ $incidencia->residente->correo }}" class="text-navy-950 text-decoration-none">
                                            {{ $incidencia->residente->correo }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            @if($incidencia->residente->telefono)
                            <div class="detail-tile">
                                <div class="detail-tile-icon amber">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="detail-tile-content">
                                    <div class="detail-tile-label">Teléfono</div>
                                    <div class="detail-tile-value">
                                        <a href="tel:{{ $incidencia->residente->telefono }}" class="text-navy-950 text-decoration-none">
                                            {{ $incidencia->residente->telefono }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    @elseif($incidencia->administrador)
                        <div class="detail-tile-grid">
                            <div class="detail-tile">
                                <div class="detail-tile-icon blue">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div class="detail-tile-content">
                                    <div class="detail-tile-label">Personal / Guardia</div>
                                    <div class="detail-tile-value">{{ $incidencia->administrador->nombre }}</div>
                                </div>
                            </div>

                            <div class="detail-tile">
                                <div class="detail-tile-icon green">
                                    <i class="fas fa-id-badge"></i>
                                </div>
                                <div class="detail-tile-content">
                                    <div class="detail-tile-label">Cargo</div>
                                    <div class="detail-tile-value">{{ $incidencia->administrador->cargo ?: 'Personal' }}</div>
                                </div>
                            </div>

                            <div class="detail-tile">
                                <div class="detail-tile-icon purple">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="detail-tile-content">
                                    <div class="detail-tile-label">Correo</div>
                                    <div class="detail-tile-value">{{ $incidencia->administrador->correo }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-shield-alt me-2"></i>
                            Incidencia registrada por el departamento de seguridad del barrio.
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA (4 COLS) -->
        <div class="col-lg-4">

            <!-- FORMULARIO CAMBIO DE ESTADO -->
            <div class="detail-sidebar-block" id="cambiarEstadoCard">
                <div class="detail-sidebar-title">
                    <i class="fas fa-tasks text-primary"></i>
                    Actualizar Estado
                </div>
                <form method="POST" action="{{ route('admin.incidencias.cambiar-estado', $incidencia) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.85rem; color: var(--admin-navy-950);">
                            Estado de la Incidencia
                        </label>
                        <select name="estado" class="form-select modern-input" required>
                            @foreach(\App\Models\Incidencia::getEstadosDisponibles() as $key => $label)
                                <option value="{{ $key }}" {{ $incidencia->estado === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-modern-primary w-100 justify-content-center mb-2">
                        <i class="fas fa-check-circle"></i>
                        <span>Guardar nuevo estado</span>
                    </button>
                </form>
            </div>

            <!-- AUDITORÍA -->
            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-history text-muted"></i>
                    Trazabilidad
                </div>
                <ul class="detail-meta-list">
                    <li class="detail-meta-item">
                        <span class="meta-label">Estado actual</span>
                        <span class="meta-value">
                            <span class="badge-admin {{ $badgeStyle }}">{{ $incidencia->estado_label }}</span>
                        </span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Fecha de apertura</span>
                        <span class="meta-value">{{ $incidencia->created_at->format('d/m/Y H:i') }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Última modificación</span>
                        <span class="meta-value">{{ $incidencia->updated_at->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <!-- Modal para ver imagen ampliada -->
    @if($incidencia->imagen)
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border: none; border-radius: var(--admin-radius-lg); overflow: hidden;">
                <div class="modal-header" style="border-bottom: 1px solid var(--admin-border); padding: 1rem 1.5rem;">
                    <h2 class="h5 modal-title fw-bold text-navy-950 mb-0">
                        <i class="fas fa-image me-2 text-primary"></i>Evidencia adjunta
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center" style="background: #091e36;">
                    <img id="modalImage" src="{{ Storage::disk('public')->url($incidencia->imagen) }}" alt="Evidencia" class="img-fluid" style="max-height: 75vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
    @endif

</x-layouts.admin>
