<x-layouts.admin title="Detalle del Reglamento - {{ $reglamento->titulo }}" active="reglamentos">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Detalle del reglamento</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Estatutos, normativas de convivencia y régimen interno del barrio.</p>
        </div>
        <div>
            <a href="{{ route('admin.reglamentos.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a reglamentos
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- TARJETA HERO DEL REGLAMENTO -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            <div class="profile-avatar-large" style="background: linear-gradient(135deg, #4338ca, #312e81);">
                <i class="fas fa-gavel"></i>
            </div>

            <div class="profile-hero-details">
                <h1>{{ $reglamento->titulo }}</h1>
                <div class="profile-hero-badges">
                    <span class="badge-admin badge-purple">
                        <i class="fas fa-book me-1"></i> Normativa Oficial
                    </span>

                    <span class="badge-admin badge-secondary">
                        <i class="far fa-calendar-alt me-1"></i> Publicado el {{ $reglamento->created_at->format('d/m/Y') }}
                    </span>
                </div>

                <div class="profile-hero-meta">
                    <span>
                        <i class="fas fa-user-edit me-1 text-primary"></i>
                        Redactado por {{ optional($reglamento->administrador)->nombre ?? 'Administración' }}
                    </span>
                    <span>
                        <i class="fas fa-building me-1 text-muted"></i>
                        {{ optional($reglamento->comunidad)->nombre ?? 'Comunidad' }}
                    </span>
                    <span>
                        <i class="far fa-clock me-1 text-muted"></i>
                        Actualizado {{ optional($reglamento->updated_at)->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="profile-hero-actions">
            <a href="{{ route('admin.reglamentos.edit', $reglamento) }}" class="btn-modern-primary">
                <i class="fas fa-edit"></i>
                <span>Editar reglamento</span>
            </a>

            <form method="POST" action="{{ route('admin.reglamentos.destroy', $reglamento) }}" class="d-inline m-0"
                onsubmit="return confirm('¿Estás seguro de eliminar este reglamento definitivamente?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modern-danger" title="Eliminar reglamento">
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
                            <i class="fas fa-file-contract"></i>
                        </span>
                        Articulado & Disposiciones del Reglamento
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-description-box" style="font-size: 0.95rem; line-height: 1.8; background: #ffffff; border-color: #e2e8f0;">
                        {!! nl2br(e($reglamento->contenido)) !!}
                    </div>
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA (4 COLS) -->
        <div class="col-lg-4">

            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-info-circle text-muted"></i>
                    Datos del Documento
                </div>
                <ul class="detail-meta-list">
                    <li class="detail-meta-item">
                        <span class="meta-label">Autor</span>
                        <span class="meta-value">{{ optional($reglamento->administrador)->nombre ?? 'Administrador' }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Comunidad</span>
                        <span class="meta-value">{{ optional($reglamento->comunidad)->nombre ?? 'No asignada' }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Fecha de alta</span>
                        <span class="meta-value">{{ $reglamento->created_at->format('d/m/Y H:i') }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Última revisión</span>
                        <span class="meta-value">{{ $reglamento->updated_at->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>
            </div>

            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.reglamentos.edit', $reglamento) }}" class="btn-modern-primary w-100 justify-content-center">
                    <i class="fas fa-edit"></i>
                    <span>Editar documento</span>
                </a>

                <form method="POST" action="{{ route('admin.reglamentos.destroy', $reglamento) }}"
                    onsubmit="return confirm('¿Eliminar definitivamente este reglamento?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modern-danger w-100 justify-content-center">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar reglamento</span>
                    </button>
                </form>
            </div>

        </div>
    </div>

</x-layouts.admin>
