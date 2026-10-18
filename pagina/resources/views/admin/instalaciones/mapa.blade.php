<x-layouts.admin title="Mapa de instalaciones" active="instalaciones-mapa">

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h2 class="h2">Mapa de instalaciones</h2>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('admin.instalaciones.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Paso 1: ubicacion del barrio</h5>
                    <ol class="small mb-0">
                        <li>Hace click en el mapa para marcar el centro del barrio.</li>
                        <li>Guarda la ubicacion.</li>
                    </ol>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.instalaciones.mapa.barrio') }}">
                        @csrf
                        <input id="barrio-center-lat" name="mapa_center_lat" type="hidden" required>
                        <input id="barrio-center-lng" name="mapa_center_lng" type="hidden" required>
                        <input id="barrio-zoom" name="mapa_zoom" type="hidden" required>

                        <div class="mb-3">
                            <label class="form-label">Buscar barrio</label>
                            <div class="input-group">
                                <input id="barrio-search" type="text" class="form-control" placeholder="Ej: Los Cardales, Buenos Aires">
                                <button id="barrio-search-btn" type="button" class="btn btn-outline-secondary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div class="form-text">Usa el buscador para centrar el mapa.</div>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button id="barrio-select" type="button" class="btn btn-outline-primary">Elegir ubicacion</button>
                            <button id="barrio-clear" type="button" class="btn btn-outline-danger">Limpiar ubicacion</button>
                        </div>

                        <div id="barrio-status" class="small text-muted mb-3">
                            Marca el centro del barrio para habilitar instalaciones.
                        </div>

                        <button id="barrio-save" type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Guardar ubicacion
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Paso 2: instalaciones</h5>
                    <form method="POST" action="{{ route('admin.instalaciones.mapa.guardar') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Instalacion</label>
                            <select id="instalacion-select" name="instalacion_id" class="form-select" required>
                                <option value="">Selecciona una instalacion</option>
                                @foreach ($instalaciones as $instalacion)
                                    <option value="{{ $instalacion->id }}"
                                        data-lat="{{ $instalacion->lat }}"
                                        data-lng="{{ $instalacion->lng }}">
                                        {{ $instalacion->nombre }} @if($instalacion->lat && $instalacion->lng) (ubicada) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Lat</label>
                                <input id="lat-input" name="lat" type="text" class="form-control" readonly required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Lng</label>
                                <input id="lng-input" name="lng" type="text" class="form-control" readonly required>
                            </div>
                        </div>

                        <div id="map-status" class="small text-muted mb-3">
                            Selecciona una instalacion y luego marca el punto en el mapa.
                        </div>

                        <button id="instalacion-save" type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Guardar coordenadas
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">Instalaciones ubicadas</h6>
                    @php
                        $instalacionesUbicadas = $instalaciones->filter(fn($inst) => $inst->lat && $inst->lng);
                    @endphp
                    @if ($instalacionesUbicadas->isEmpty())
                        <p class="text-muted small mb-0">Todavia no hay instalaciones ubicadas.</p>
                    @else
                        <div class="list-group">
                            @foreach ($instalacionesUbicadas as $instalacion)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $instalacion->nombre }}</strong>
                                        <div class="small text-muted">
                                            {{ number_format($instalacion->lat, 6) }}, {{ number_format($instalacion->lng, 6) }}
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary rounded js-editar-instalacion"
                                            data-id="{{ $instalacion->id }}"
                                            data-lat="{{ $instalacion->lat }}"
                                            data-lng="{{ $instalacion->lng }}"
                                        >
                                            Editar
                                        </button>
                                        <form
                                            method="POST"
                                            action="{{ route('admin.instalaciones.mapa.limpiar', $instalacion) }}"
                                            onsubmit="return confirm('¿Eliminar coordenadas de {{ $instalacion->nombre }}?')"
                                        >
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div id="map" style="height: 520px; border-radius: 12px;"></div>
                </div>
            </div>
        </div>
    </div>

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    />
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>
    <script>
        const instalaciones = @json($instalacionesMapa);
        const comunidad = {
            centerLat: {{ $comunidad->mapa_center_lat ?? 'null' }},
            centerLng: {{ $comunidad->mapa_center_lng ?? 'null' }},
            zoom: {{ $comunidad->mapa_zoom ?? 'null' }},
        };

        const defaultCenter = { lat: -34.6037, lng: -58.3816 };
        const first = instalaciones.find((i) => i.lat !== null && i.lng !== null);
        const fallbackCenter = first ? { lat: first.lat, lng: first.lng } : defaultCenter;
        const mapCenter = comunidad.centerLat && comunidad.centerLng
            ? { lat: comunidad.centerLat, lng: comunidad.centerLng }
            : fallbackCenter;
        const mapZoom = comunidad.zoom || 15;

        const map = L.map('map').setView([mapCenter.lat, mapCenter.lng], mapZoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap',
        }).addTo(map);

        const barrioSelect = document.getElementById('barrio-select');
        const barrioClear = document.getElementById('barrio-clear');
        const barrioStatus = document.getElementById('barrio-status');
        const barrioCenterLat = document.getElementById('barrio-center-lat');
        const barrioCenterLng = document.getElementById('barrio-center-lng');
        const barrioZoom = document.getElementById('barrio-zoom');
        const barrioSearch = document.getElementById('barrio-search');
        const barrioSearchBtn = document.getElementById('barrio-search-btn');
        const instalacionSave = document.getElementById('instalacion-save');
        const editButtons = document.querySelectorAll('.js-editar-instalacion');

        const select = document.getElementById('instalacion-select');
        const latInput = document.getElementById('lat-input');
        const lngInput = document.getElementById('lng-input');
        const statusEl = document.getElementById('map-status');

        let elegirCentro = false;
        let centroMarker = null;
        let activeMarker = null;

        const setInstalacionesEnabled = (enabled) => {
            select.disabled = !enabled;
            instalacionSave.disabled = !enabled;
            if (!enabled) {
                select.value = '';
                latInput.value = '';
                lngInput.value = '';
                statusEl.textContent = 'Primero guarda el barrio.';
            }
        };

        const updateBarrioInputs = (lat, lng) => {
            barrioCenterLat.value = Number(lat).toFixed(6);
            barrioCenterLng.value = Number(lng).toFixed(6);
            barrioZoom.value = map.getZoom();
        };

        const setCentroMarker = (lat, lng) => {
            if (centroMarker) {
                map.removeLayer(centroMarker);
            }
            centroMarker = L.marker([lat, lng]).addTo(map);
        };

        barrioSelect.addEventListener('click', () => {
            elegirCentro = true;
            barrioStatus.textContent = 'Hace click en el mapa para marcar el centro.';
        });

        barrioClear.addEventListener('click', () => {
            elegirCentro = false;
            barrioCenterLat.value = '';
            barrioCenterLng.value = '';
            barrioZoom.value = '';
            if (centroMarker) {
                map.removeLayer(centroMarker);
                centroMarker = null;
            }
            barrioStatus.textContent = 'Marca el centro del barrio para habilitar instalaciones.';
        });

        const buscarUbicacion = async () => {
            const query = barrioSearch.value.trim();
            if (!query) return;
            barrioStatus.textContent = 'Buscando ubicacion...';

            try {
                const response = await fetch(
                    'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(query)
                );
                const results = await response.json();
                if (!results || results.length === 0) {
                    barrioStatus.textContent = 'No se encontro esa ubicacion.';
                    return;
                }

                const item = results[0];
                const lat = Number(item.lat);
                const lng = Number(item.lon);
                map.setView([lat, lng], 15);
                setCentroMarker(lat, lng);
                updateBarrioInputs(lat, lng);
                barrioStatus.textContent = 'Ubicacion encontrada. Puedes guardar o ajustar.';
            } catch (error) {
                barrioStatus.textContent = 'Error al buscar ubicacion.';
            }
        };

        barrioSearchBtn.addEventListener('click', buscarUbicacion);
        barrioSearch.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                buscarUbicacion();
            }
        });

        const setActiveMarker = (lat, lng) => {
            if (activeMarker) {
                map.removeLayer(activeMarker);
            }
            activeMarker = L.marker([lat, lng], { draggable: true }).addTo(map);
            activeMarker.on('dragend', (event) => {
                const pos = event.target.getLatLng();
                latInput.value = pos.lat.toFixed(6);
                lngInput.value = pos.lng.toFixed(6);
            });
        };

        map.on('click', (event) => {
            if (elegirCentro) {
                elegirCentro = false;
                setCentroMarker(event.latlng.lat, event.latlng.lng);
                updateBarrioInputs(event.latlng.lat, event.latlng.lng);
                barrioStatus.textContent = 'Ubicacion lista para guardar.';
                return;
            }

            if (!select.value) {
                statusEl.textContent = 'Selecciona una instalacion primero.';
                return;
            }

            const lat = event.latlng.lat.toFixed(6);
            const lng = event.latlng.lng.toFixed(6);
            latInput.value = lat;
            lngInput.value = lng;
            statusEl.textContent = 'Coordenadas listas para guardar.';
            setActiveMarker(lat, lng);
        });

        select.addEventListener('change', () => {
            const option = select.options[select.selectedIndex];
            const lat = option.getAttribute('data-lat');
            const lng = option.getAttribute('data-lng');

            if (lat && lng) {
                latInput.value = Number(lat).toFixed(6);
                lngInput.value = Number(lng).toFixed(6);
                setActiveMarker(lat, lng);
                map.setView([lat, lng], 16);
                statusEl.textContent = 'Esta instalacion ya tiene coordenadas. Puedes ajustarlas.';
            } else {
                latInput.value = '';
                lngInput.value = '';
                if (activeMarker) {
                    map.removeLayer(activeMarker);
                    activeMarker = null;
                }
                statusEl.textContent = 'Marca la instalacion con un click en el mapa.';
            }
        });

        editButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const lat = button.getAttribute('data-lat');
                const lng = button.getAttribute('data-lng');

                select.value = id;
                latInput.value = Number(lat).toFixed(6);
                lngInput.value = Number(lng).toFixed(6);
                setActiveMarker(lat, lng);
                map.setView([lat, lng], 16);
                statusEl.textContent = 'Edita la ubicacion con un click en el mapa.';
            });
        });

        instalaciones.forEach((inst) => {
            if (inst.lat === null || inst.lng === null) return;
            L.circleMarker([inst.lat, inst.lng], {
                radius: 5,
                color: '#0D3B66',
                fillColor: '#0D3B66',
                fillOpacity: 0.6,
            }).addTo(map).bindPopup(inst.nombre);
        });

        if (comunidad.centerLat && comunidad.centerLng) {
            setCentroMarker(comunidad.centerLat, comunidad.centerLng);
            updateBarrioInputs(comunidad.centerLat, comunidad.centerLng);
            barrioStatus.textContent = 'Barrio guardado. Puedes cambiar la ubicacion si lo necesitas.';
            setInstalacionesEnabled(true);
        } else {
            setInstalacionesEnabled(false);
        }
    </script>
</x-layouts.admin>
