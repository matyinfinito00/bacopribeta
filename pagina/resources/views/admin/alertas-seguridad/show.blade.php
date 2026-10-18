<x-layouts.admin title="Detalle de Alerta de Seguridad - {{ $alerta->titulo }}" active="alertas-seguridad">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Detalle de la alerta</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Comunicado oficial emitido a los vecinos y personal de seguridad.</p>
        </div>
        <div>
            <a href="{{ route('admin.alertas-seguridad.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a alertas
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- TARJETA HERO DE LA ALERTA -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            @php
                $aBg = match($alerta->prioridad) {
                    'urgente' => 'linear-gradient(135deg, #dc2626, #b91c1c)',
                    'alta'    => 'linear-gradient(135deg, #ea580c, #c2410c)',
                    default   => 'linear-gradient(135deg, #d97706, #b45309)'
                };
            @endphp
            <div class="profile-avatar-large" style="background: {{ $aBg }};">
                <i class="fas fa-shield-alt"></i>
            </div>

            <div class="profile-hero-details">
                <h1>{{ $alerta->titulo }}</h1>
                <div class="profile-hero-badges">
                    @php
                        $pBadge = match($alerta->prioridad) {
                            'urgente' => 'badge-danger',
                            'alta'    => 'badge-warning',
                            default   => 'badge-secondary'
                        };
                    @endphp
                    <span class="badge-admin {{ $pBadge }}">
                        <i class="fas fa-exclamation-circle me-1"></i> Prioridad {{ ucfirst($alerta->prioridad) }}
                    </span>

                    <span class="badge-admin badge-secondary">
                        <i class="far fa-calendar-alt me-1"></i> Publicado el {{ optional($alerta->publicado_en)->format('d/m/Y H:i') ?? 'Pendiente' }}
                    </span>
                </div>

                <div class="profile-hero-meta">
                    <span>
                        <i class="fas fa-user-shield me-1 text-primary"></i>
                        Emitida por {{ optional($alerta->administrador)->nombre ?? 'Administrador' }}
                    </span>
                    <span>
                        <i class="far fa-clock me-1 text-muted"></i>
                        Actualizado {{ optional($alerta->updated_at)->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="profile-hero-actions">
            <a href="{{ route('admin.alertas-seguridad.edit', $alerta) }}" class="btn-modern-primary">
                <i class="fas fa-edit"></i>
                <span>Editar alerta</span>
            </a>

            <form method="POST" action="{{ route('admin.alertas-seguridad.destroy', $alerta) }}" class="d-inline m-0"
                onsubmit="return confirm('¿Estás seguro de eliminar esta alerta definitivamente?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modern-danger" title="Eliminar alerta">
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
                        <span class="admin-card-title-icon bg-danger-subtle text-danger">
                            <i class="fas fa-bullhorn"></i>
                        </span>
                        Mensaje Oficial & Contenido de la Alerta
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-description-box" style="font-size: 1rem; line-height: 1.7; background: #ffffff; border-color: #e2e8f0;">
                        {!! nl2br(e($alerta->contenido)) !!}
                    </div>
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA (4 COLS) -->
        <div class="col-lg-4">

            <div class="detail-sidebar-block">
                <div class="detail-sidebar-title">
                    <i class="fas fa-info-circle text-muted"></i>
                    Detalles de Emisión
                </div>
                <ul class="detail-meta-list">
                    <li class="detail-meta-item">
                        <span class="meta-label">Nivel de prioridad</span>
                        <span class="meta-value">
                            <span class="badge-admin {{ $pBadge }}">{{ ucfirst($alerta->prioridad) }}</span>
                        </span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Emitido por</span>
                        <span class="meta-value">{{ optional($alerta->administrador)->nombre ?? 'Administrador' }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Fecha de emisión</span>
                        <span class="meta-value">{{ optional($alerta->publicado_en)->format('d/m/Y H:i') ?? 'Sin fecha' }}</span>
                    </li>
                    <li class="detail-meta-item">
                        <span class="meta-label">Última edición</span>
                        <span class="meta-value">{{ optional($alerta->updated_at)->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>
            </div>

            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.alertas-seguridad.edit', $alerta) }}" class="btn-modern-primary w-100 justify-content-center">
                    <i class="fas fa-edit"></i>
                    <span>Editar alerta</span>
                </a>

                <form method="POST" action="{{ route('admin.alertas-seguridad.destroy', $alerta) }}"
                    onsubmit="return confirm('¿Eliminar definitivamente esta alerta?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modern-danger w-100 justify-content-center">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar alerta</span>
                    </button>
                </form>
            </div>

        </div>
    </div>

</x-layouts.admin>
