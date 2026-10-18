<x-layouts.admin title="Coordenadas en Mapa - {{ $instalacion->nombre }}" active="instalaciones-mapa">
    <x-slot name="styles">
        <link rel="stylesheet" href="{{ asset('css/admin-mapa.css') }}">
    </x-slot>
    <x-slot name="scripts">
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="{{ asset('js/admin-mapa.js') }}"></script>
    </x-slot>

    <div id="mapa-config" data-instalacion='@json($mapaInstalacionConfig)' data-tipo="show" style="display:none" aria-hidden="true"></div>

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Geolocalización en mapa</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Ubicación geográfica y coordenadas exactas de la instalación.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.mapa.edit', $instalacion) }}" class="btn-modern-primary">
                <i class="fas fa-edit"></i>
                <span>Editar posición</span>
            </a>
            <a href="{{ route('admin.mapa.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i>
                <span>Volver al mapa</span>
            </a>
        </div>
    </div>

    <!-- TARJETA HERO -->
    <div class="profile-hero-card">
        <div class="profile-hero-main">
            <div class="profile-avatar-large" style="background: linear-gradient(135deg, #059669, #0284c7);">
                <i class="fas fa-map-marked-alt"></i>
            </div>

            <div class="profile-hero-details">
                <h1>{{ $instalacion->nombre }}</h1>
                <div class="profile-hero-badges">
                    <span class="badge-admin badge-info">
                        <i class="fas fa-tag me-1"></i> {{ $instalacion->tipo_label }}
                    </span>

                    <span class="badge-admin badge-success">
                        <i class="fas fa-map-pin me-1"></i> Lat: {{ number_format($instalacion->lat, 4) }}, Lng: {{ number_format($instalacion->lng, 4) }}
                    </span>
                </div>

                <div class="profile-hero-meta">
                    <span>
                        <i class="fas fa-location-arrow me-1 text-primary"></i>
                        {{ $instalacion->ubicacion ?: 'Ubicación en barrio' }}
                    </span>
                    @if($instalacion->capacidad)
                        <span>
                            <i class="fas fa-users me-1 text-muted"></i>
                            Capacidad: {{ $instalacion->capacidad }} personas
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="profile-hero-actions">
            <a href="{{ route('admin.mapa.edit', $instalacion) }}" class="btn-modern-primary">
                <i class="fas fa-map-marker-alt"></i>
                <span>Reubicar</span>
            </a>

            <form method="POST" action="{{ route('admin.mapa.destroy', $instalacion) }}" class="d-inline m-0"
                  onsubmit="return confirm('¿Eliminar coordenadas de {{ $instalacion->nombre }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modern-danger" title="Eliminar coordenadas">
                    <i class="fas fa-trash"></i>
                    <span>Quitar del mapa</span>
                </button>
            </form>
        </div>
    </div>

    <!-- CONTENIDO MAPA Y DETALLES -->
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-compass"></i>
                        </span>
                        Coordenadas GPS
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="detail-tile mb-3">
                        <div class="detail-tile-icon green">
                            <i class="fas fa-crosshairs"></i>
                        </div>
                        <div class="detail-tile-content">
                            <div class="detail-tile-label">Latitud</div>
                            <div class="detail-tile-value" style="font-family: monospace;">{{ number_format($instalacion->lat, 6) }}</div>
                        </div>
                    </div>

                    <div class="detail-tile mb-3">
                        <div class="detail-tile-icon blue">
                            <i class="fas fa-crosshairs"></i>
                        </div>
                        <div class="detail-tile-content">
                            <div class="detail-tile-label">Longitud</div>
                            <div class="detail-tile-value" style="font-family: monospace;">{{ number_format($instalacion->lng, 6) }}</div>
                        </div>
                    </div>

                    @if($instalacion->descripcion)
                    <div class="mt-3">
                        <div class="detail-tile-label mb-2">Descripción</div>
                        <div class="detail-description-box" style="font-size: 0.88rem;">
                            {{ $instalacion->descripcion }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.instalaciones.show', $instalacion) }}" class="btn-modern-secondary w-100 justify-content-center">
                    <i class="fas fa-info-circle"></i>
                    <span>Ver ficha de instalación</span>
                </a>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="admin-card mb-0" style="overflow: hidden;">
                <div class="admin-card-body p-0">
                    <div id="map" class="map-container" style="height: 520px; width: 100%; border-radius: var(--admin-radius-lg);"></div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
