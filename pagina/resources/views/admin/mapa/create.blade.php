<x-layouts.admin title="Agregar Coordenadas" active="instalaciones-mapa">
    <x-slot name="styles">
        <link rel="stylesheet" href="{{ asset('css/admin-mapa.css') }}">
    </x-slot>
    <x-slot name="scripts">
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="{{ asset('js/admin-mapa.js') }}"></script>
    </x-slot>

    <div id="mapa-config" data-comunidad='@json($mapaComunidadConfig)' data-tipo="create" style="display:none" aria-hidden="true"></div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Agregar coordenadas a instalación</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Geolocaliza canchas, áreas comunes o quinchos sobre el mapa satelital del barrio.</p>
        </div>
        <div>
            <a href="{{ route('admin.mapa.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver al mapa
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <div><strong>Se encontraron algunos errores:</strong> Revisá los campos indicados abajo.</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        <!-- FORMULARIO DE COORDENADAS -->
        <div class="col-lg-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-map-pin"></i>
                        </span>
                        Seleccionar instalación
                    </h2>
                </div>
                <div class="admin-card-body">
                    <!-- Banner de campos obligatorios -->
                    <div class="form-legend-banner">
                        <i class="fas fa-info-circle"></i>
                        <span>Los campos con <span class="required-asterisk">*</span> son requeridos para geolocalizar.</span>
                    </div>

                    <form method="POST" action="{{ route('admin.mapa.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="instalacion-select" class="form-label">
                                Instalación <span class="required-asterisk">*</span>
                            </label>
                            <select id="instalacion-select" name="instalacion_id" class="form-select" required>
                                <option value="">Selecciona una instalación</option>
                                @foreach ($instalaciones as $inst)
                                    <option value="{{ $inst->id }}" 
                                            @if(request('instalacion') == $inst->id) selected @endif>
                                        {{ $inst->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Buscar zona en el mapa</label>
                            <div class="input-group">
                                <input id="search-input" type="text" class="form-control" placeholder="Ej: Calle Principal o Club House">
                                <button id="search-btn" type="button" class="btn btn-outline-secondary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <small class="text-muted" style="font-size: 0.78rem;">Centra el mapa en una calle o sector del barrio.</small>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label for="lat-input" class="form-label">
                                    Latitud <span class="required-asterisk">*</span>
                                </label>
                                <input id="lat-input" name="lat" type="text" class="form-control bg-light" readonly required placeholder="-34.xxx">
                            </div>
                            <div class="col-6">
                                <label for="lng-input" class="form-label">
                                    Longitud <span class="required-asterisk">*</span>
                                </label>
                                <input id="lng-input" name="lng" type="text" class="form-control bg-light" readonly required placeholder="-58.xxx">
                            </div>
                        </div>

                        <div id="map-status" class="alert alert-info py-2 px-3 small mb-3">
                            <i class="fas fa-info-circle me-1"></i> Selecciona una instalación y luego hacé click en el mapa para ubicarla.
                        </div>

                        <div class="d-grid gap-2">
                            <button id="save-btn" type="submit" class="btn btn-modern-primary" disabled>
                                <i class="fas fa-save me-2"></i>Guardar coordenadas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- VISUALIZADOR DE MAPA -->
        <div class="col-lg-8">
            <div class="admin-card overflow-hidden shadow-sm" style="border: 1px solid #e2e8f0; border-radius: 16px;">
                <div class="admin-card-header bg-white py-3 px-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-navy-950">
                            <i class="fas fa-satellite text-primary me-2"></i>Mapa interactivo del barrio
                        </span>
                        <span class="badge-admin badge-info">
                            <i class="fas fa-mouse-pointer me-1"></i> Click para fijar punto
                        </span>
                    </div>
                </div>
                <div class="admin-card-body p-0">
                    <div id="map" class="map-container" style="height: 520px; border-radius: 0 0 16px 16px;"></div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
