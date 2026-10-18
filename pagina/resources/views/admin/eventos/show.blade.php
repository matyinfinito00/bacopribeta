<x-layouts.admin title="Detalle del Evento - {{ $evento->titulo }}" active="eventos">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Detalle del evento</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Información, fecha, convocatoria y descripción de la actividad.</p>
        </div>
        <div>
            <a href="{{ route('admin.eventos.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a eventos
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- TARJETA HERO DEL EVENTO -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            <div class="profile-avatar-large" style="background: linear-gradient(135deg, #7c3aed, #4f46e5);">
                <i class="fas fa-calendar-star"></i>
            </div>

            <div class="profile-hero-details">
                <h1>{{ $evento->titulo }}</h1>
                <div class="profile-hero-badges">
                    <span class="badge-admin badge-info">
                        <i class="far fa-calendar-alt me-1"></i> {{ optional($evento->fecha)->format('d/m/Y') }}
                    </span>

                    @if($evento->categoria)
                        <span class="badge-admin badge-purple">
                            <i class="fas fa-tag me-1"></i> {{ $evento->categoria }}
                        </span>
                    @endif

                    @if($evento->hora_inicio)
                        <span class="badge-admin badge-secondary">
                            <i class="far fa-clock me-1"></i> {{ substr($evento->hora_inicio, 0, 5) }} {{ $evento->hora_fin ? 'a ' . substr($evento->hora_fin, 0, 5) : '' }} hs
                        </span>
                    @endif
                </div>

                <div class="profile-hero-meta">
                    @if($evento->lugar)
                        <span>
                            <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                            {{ $evento->lugar }}
                        </span>
                    @endif
                    <span>
                        <i class="fas fa-user me-1 text-primary"></i>
                        Creado por {{ optional($evento->administrador)->nombre ?? 'Administración' }}
                    </span>
                    <span>
                        <i class="fas fa-building me-1 text-muted"></i>
                        {{ optional($evento->comunidad)->nombre ?? 'Comunidad' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="profile-hero-actions">
            <a href="{{ route('admin.eventos.edit', $evento) }}" class="btn-modern-primary">
                <i class="fas fa-edit"></i>
                <span>Editar evento</span>
            </a>

            <form method="POST" action="{{ route('admin.eventos.destroy', $evento) }}" class="d-inline m-0"
                onsubmit="return confirm('¿Estás seguro de eliminar este evento definitivamente?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modern-danger" title="Eliminar evento">
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
                            <i class="fas fa-info-circle"></i>
                        </span>
                        Horarios & Locación
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-tile-grid mb-4">
                        <div class="detail-tile">
                            <div class="detail-tile-icon blue">
                                <i class="far fa-calendar-check"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Fecha del evento</div>
                                <div class="detail-tile-value">
                                    {{ optional($evento->fecha)->format('d/m/Y') }}
                                    <div class="text-muted fw-normal" style="font-size: 0.8rem;">
                                        {{ ucfirst(optional($evento->fecha)->locale('es')->dayName ?? '') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon green">
                                <i class="far fa-clock"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Franja horaria</div>
                                <div class="detail-tile-value">
                                    {{ $evento->hora_inicio ? substr($evento->hora_inicio, 0, 5) : 'Sin definir' }}
                                    {{ $evento->hora_fin ? ' a ' . substr($evento->hora_fin, 0, 5) : '' }} hs
                                </div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon purple">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Lugar / Sector</div>
                                <div class="detail-tile-value">{{ $evento->lugar ?? 'Sin definir' }}</div>
                            </div>
                        </div>

                        <div class="detail-tile">
                            <div class="detail-tile-icon amber">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="detail-tile-content">
                                <div class="detail-tile-label">Categoría</div>
                                <div class="detail-tile-value">{{ $evento->categoria ?? 'General' }}</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="detail-tile-label mb-2">Descripción y detalles para residentes</div>
                        <div class="detail-description-box">
                            {!! $evento->descripcion ? nl2br(e($evento->descripcion)) : '<span class="text-muted fst-italic">Sin descripción detallada para este evento.</span>' !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA (4 COLS) -->
        <div class="col-lg-4">

            <!-- METADATOS -->
            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-info-circle text-muted"></i>
                    Auditoría
                </div>
                <ul class="detail-meta-list">
                    <li class="detail-meta-item">
                        <span class="meta-label">Organizador / Creador</span>
                        <span class="meta-value">{{ optional($evento->administrador)->nombre ?? 'Administrador' }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Comunidad</span>
                        <span class="meta-value">{{ optional($evento->comunidad)->nombre ?? 'No asignada' }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Última actualización</span>
                        <span class="meta-value">{{ optional($evento->updated_at)->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>
            </div>

            <!-- ACCIONES -->
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.eventos.edit', $evento) }}" class="btn-modern-primary w-100 justify-content-center">
                    <i class="fas fa-edit"></i>
                    <span>Editar evento</span>
                </a>

                <form method="POST" action="{{ route('admin.eventos.destroy', $evento) }}"
                    onsubmit="return confirm('¿Eliminar definitivamente este evento?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modern-danger w-100 justify-content-center">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar evento</span>
                    </button>
                </form>
            </div>

        </div>
    </div>

</x-layouts.admin>
