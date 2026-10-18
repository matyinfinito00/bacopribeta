<x-layouts.admin title="Detalle de la Novedad - {{ $novedad->titulo }}" active="novedades">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Detalle de la novedad</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Publicación, noticia o comunicado oficial para los vecinos.</p>
        </div>
        <div>
            <a href="{{ route('admin.novedades.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a novedades
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- TARJETA HERO DE LA NOVEDAD -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            <div class="profile-avatar-large" style="background: linear-gradient(135deg, #0284c7, #0369a1);">
                <i class="fas fa-newspaper"></i>
            </div>

            <div class="profile-hero-details">
                <h1>{{ $novedad->titulo }}</h1>
                <div class="profile-hero-badges">
                    <span class="badge-admin badge-info">
                        <i class="far fa-calendar-alt me-1"></i> Publicado: {{ optional($novedad->publicado_en)->format('d/m/Y H:i') ?? 'Borrador' }}
                    </span>

                    <span class="badge-admin badge-secondary">
                        <i class="fas fa-user me-1"></i> Por {{ optional($novedad->administrador)->nombre ?? 'Administración' }}
                    </span>
                </div>

                <div class="profile-hero-meta">
                    <span>
                        <i class="fas fa-building me-1 text-primary"></i>
                        {{ optional($novedad->comunidad)->nombre ?? 'Comunidad general' }}
                    </span>
                    <span>
                        <i class="far fa-clock me-1 text-muted"></i>
                        Actualizado {{ optional($novedad->updated_at)->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="profile-hero-actions">
            <a href="{{ route('admin.novedades.edit', $novedad) }}" class="btn-modern-primary">
                <i class="fas fa-edit"></i>
                <span>Editar novedad</span>
            </a>

            <form method="POST" action="{{ route('admin.novedades.destroy', $novedad) }}" class="d-inline m-0"
                onsubmit="return confirm('¿Estás seguro de eliminar esta novedad definitivamente?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modern-danger" title="Eliminar novedad">
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
                            <i class="fas fa-align-left"></i>
                        </span>
                        Cuerpo de la Publicación
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-description-box mb-4" style="background: #ffffff; border-color: #e2e8f0; font-size: 0.98rem; line-height: 1.7;">
                        {!! nl2br(e($novedad->contenido)) !!}
                    </div>

                    @if($novedad->imagen)
                        <div>
                            <div class="detail-tile-label mb-2">Imagen de portada / adjunta</div>
                            @php
                                $imgUrl = Storage::disk('public')->url($novedad->imagen);
                            @endphp
                            <div class="detail-gallery-grid" style="grid-template-columns: minmax(220px, 380px);">
                                <div class="detail-gallery-item"
                                     data-bs-toggle="modal"
                                     data-bs-target="#imageModal"
                                     onclick="document.getElementById('modalImage').src='{{ $imgUrl }}'">
                                    <img src="{{ $imgUrl }}" alt="Imagen de la novedad">
                                    <div class="detail-gallery-overlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA (4 COLS) -->
        <div class="col-lg-4">

            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-info-circle text-muted"></i>
                    Información Editorial
                </div>
                <ul class="detail-meta-list">
                    <li class="detail-meta-item">
                        <span class="meta-label">Autor</span>
                        <span class="meta-value">{{ optional($novedad->administrador)->nombre ?? 'Administrador' }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Comunidad</span>
                        <span class="meta-value">{{ optional($novedad->comunidad)->nombre ?? 'Sin asignar' }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Fecha de publicación</span>
                        <span class="meta-value">{{ optional($novedad->publicado_en)->format('d/m/Y H:i') ?? 'Borrador' }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Última edición</span>
                        <span class="meta-value">{{ optional($novedad->updated_at)->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>
            </div>

            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.novedades.edit', $novedad) }}" class="btn-modern-primary w-100 justify-content-center">
                    <i class="fas fa-edit"></i>
                    <span>Editar novedad</span>
                </a>

                <form method="POST" action="{{ route('admin.novedades.destroy', $novedad) }}"
                    onsubmit="return confirm('¿Eliminar definitivamente esta novedad?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modern-danger w-100 justify-content-center">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar novedad</span>
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- Modal para ver imagen ampliada -->
    @if($novedad->imagen)
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border: none; border-radius: var(--admin-radius-lg); overflow: hidden;">
                <div class="modal-header" style="border-bottom: 1px solid var(--admin-border); padding: 1rem 1.5rem;">
                    <h2 class="h5 modal-title fw-bold text-navy-950 mb-0">
                        <i class="fas fa-image me-2 text-primary"></i>{{ $novedad->titulo }}
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center" style="background: #091e36;">
                    <img id="modalImage" src="{{ Storage::disk('public')->url($novedad->imagen) }}" alt="Imagen" class="img-fluid" style="max-height: 75vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
    @endif

</x-layouts.admin>