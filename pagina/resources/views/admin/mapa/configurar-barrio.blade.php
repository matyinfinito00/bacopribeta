<x-layouts.admin title="Configurar Mapa del Barrio" active="instalaciones-mapa">
    <x-slot name="styles">
        <link rel="stylesheet" href="{{ asset('css/admin-mapa.css') }}">
    </x-slot>
    <x-slot name="scripts">
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="{{ asset('js/admin-mapa.js') }}"></script>
    </x-slot>

    <div id="mapa-config" data-comunidad='@json($mapaComunidadConfig)' data-tipo="configurar" style="display:none" aria-hidden="true"></div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Configurar mapa y centro del barrio</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Establece las coordenadas predeterminadas y el nivel de zoom para la app móvil de los residentes.</p>
        </div>
        <div>
            <a href="{{ route('admin.mapa.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver al mapa
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="fas fa-check-circle"></i>
        <div>{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <div><strong>Se encontraron algunos errores:</strong> Revisá los campos indicados abajo.</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        <!-- FORMULARIO DE CONFIGURACIÓN -->
        <div class="col-lg-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-sliders-h"></i>
                        </span>
                        Ajustes del mapa
                    </h2>
                </div>
                <div class="admin-card-body">
                    <p class="text-muted small mb-3">
                        El centro y nivel de zoom elegidos serán la vista por defecto que se cargará cuando los residentes abran el mapa en sus teléfonos.
                    </p>
                    
                    <form method="POST" action="{{ route('admin.mapa.guardar-configuracion-barrio') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Buscar zona o ciudad</label>
                            <div class="input-group">
                                <input id="search-input" type="text" class="form-control" placeholder="Ej: Los Cardales, Pilar, etc.">
                                <button id="search-btn" type="button" class="btn btn-outline-secondary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <small class="text-muted" style="font-size: 0.78rem;">Usa el buscador para aproximarte rápidamente al barrio.</small>
                        </div>

                        <div class="p-3 bg-light rounded-3 mb-3 border">
                            <h6 class="fw-bold text-navy-950 small mb-2">
                                <i class="fas fa-info-circle text-primary me-1"></i>Pasos a seguir:
                            </h6>
                            <ol class="small mb-0 text-muted ps-3">
                                <li class="mb-1">Hacé click en "Elegir ubicación".</li>
                                <li class="mb-1">Click en el centro geográfico del barrio.</li>
                                <li class="mb-1">Ajustá el zoom con +/- hasta abarcar los límites.</li>
                                <li>Hacé click en "Guardar configuración".</li>
                            </ol>
                        </div>

                        <div class="d-flex gap-2 mb-3">
                            <button id="select-btn" type="button" class="btn btn-sm btn-outline-primary flex-fill">
                                <i class="fas fa-crosshairs me-1"></i>Elegir ubicación
                            </button>
                            <button id="clear-btn" type="button" class="btn btn-sm btn-outline-secondary flex-fill">
                                <i class="fas fa-times me-1"></i>Limpiar
                            </button>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label for="mapa_center_lat" class="form-label">
                                    Latitud centro <span class="required-asterisk">*</span>
                                </label>
                                <input id="center-lat" name="mapa_center_lat" type="text" class="form-control bg-light" 
                                       value="{{ $comunidad->mapa_center_lat }}" readonly required>
                            </div>
                            <div class="col-6">
                                <label for="mapa_center_lng" class="form-label">
                                    Longitud centro <span class="required-asterisk">*</span>
                                </label>
                                <input id="center-lng" name="mapa_center_lng" type="text" class="form-control bg-light" 
                                       value="{{ $comunidad->mapa_center_lng }}" readonly required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="mapa_zoom" class="form-label">
                                Nivel de zoom <span class="required-asterisk">*</span>
                            </label>
                            <input id="zoom-input" name="mapa_zoom" type="text" class="form-control bg-light" 
                                   value="{{ $comunidad->mapa_zoom ?? 15 }}" readonly required>
                        </div>

                        <div id="status" class="alert alert-info py-2 px-3 small mb-3">
                            @if($comunidad->mapa_center_lat && $comunidad->mapa_center_lng)
                                <i class="fas fa-check-circle text-success me-1"></i> Configuración activa guardada. Podés ajustarla en cualquier momento.
                            @else
                                <i class="fas fa-info-circle me-1"></i> Marcá el centro del barrio para habilitar la configuración inicial.
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-modern-primary">
                                <i class="fas fa-save me-2"></i>Guardar configuración
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
                            <i class="fas fa-map text-primary me-2"></i>Calibración del mapa
                        </span>
                        <span class="badge-admin badge-info">
                            <i class="fas fa-globe me-1"></i> Vista previa en tiempo real
                        </span>
                    </div>
                </div>
                <div class="admin-card-body p-0">
                    <div id="map" class="map-container" style="height: 540px; border-radius: 0 0 16px 16px;"></div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
