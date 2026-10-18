/**
 * Lógica de mapas Leaflet para el panel admin (crear/editar mostrar coordenadas, configurar barrio)
 */
(function () {
    'use strict';

    function initMapa() {
        if (typeof L === 'undefined') return;

        const configEl = document.getElementById('mapa-config');
        if (configEl) {
            const comunidadData = configEl.dataset.comunidad;
            const instalacionData = configEl.dataset.instalacion;
            const tipo = configEl.dataset.tipo || '';
            if (comunidadData) {
                try {
                    window.mapaComunidadData = JSON.parse(comunidadData);
                } catch (e) {}
            }
            if (instalacionData) {
                try {
                    const inst = JSON.parse(instalacionData);
                    window.mapaInstalacionData = inst;
                    if (tipo === 'show') window.mapaInstalacionShowData = inst;
                } catch (e) {}
            }
        }

        const mapElement = document.getElementById('map');
        if (!mapElement) return;

        const instalacionSelect = document.getElementById('instalacion-select');
        const latInput = document.getElementById('lat-input');
        const lngInput = document.getElementById('lng-input');
        const mapStatus = document.getElementById('map-status');
        const saveBtn = document.getElementById('save-btn');
        const searchInput = document.getElementById('search-input');
        const searchBtn = document.getElementById('search-btn');
        const selectBtn = document.getElementById('select-btn');
        const clearBtn = document.getElementById('clear-btn');
        const statusConfig = document.getElementById('status');
        const centerLat = document.getElementById('center-lat');
        const centerLng = document.getElementById('center-lng');
        const zoomInput = document.getElementById('zoom-input');

        // Vista: crear coordenadas (tiene instalacion-select)
        if (instalacionSelect && window.mapaComunidadData) {
            const comunidad = window.mapaComunidadData;
            const defaultCenter = { lat: -34.6037, lng: -58.3816 };
            const mapCenter = comunidad.centerLat && comunidad.centerLng
                ? { lat: comunidad.centerLat, lng: comunidad.centerLng }
                : defaultCenter;
            const mapZoom = comunidad.zoom || 15;

            const map = L.map('map').setView([mapCenter.lat, mapCenter.lng], mapZoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap',
            }).addTo(map);

            let activeMarker = null;

            const setActiveMarker = (lat, lng) => {
                if (activeMarker) map.removeLayer(activeMarker);
                activeMarker = L.marker([lat, lng], { draggable: true }).addTo(map);
                activeMarker.on('dragend', (event) => {
                    const pos = event.target.getLatLng();
                    latInput.value = pos.lat.toFixed(6);
                    lngInput.value = pos.lng.toFixed(6);
                });
            };

            const buscarUbicacion = async () => {
                const query = searchInput.value.trim();
                if (!query) return;
                mapStatus.textContent = 'Buscando ubicación...';
                try {
                    const response = await fetch(
                        'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(query)
                    );
                    const results = await response.json();
                    if (!results || results.length === 0) {
                        mapStatus.textContent = 'No se encontró esa ubicación.';
                        return;
                    }
                    const item = results[0];
                    const lat = Number(item.lat);
                    const lng = Number(item.lon);
                    map.setView([lat, lng], 15);
                    mapStatus.textContent = 'Ubicación encontrada. Marca el punto en el mapa.';
                } catch (error) {
                    mapStatus.textContent = 'Error al buscar ubicación.';
                }
            };

            if (searchBtn && searchInput) {
                searchBtn.addEventListener('click', buscarUbicacion);
                searchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') { e.preventDefault(); buscarUbicacion(); }
                });
            }

            map.on('click', (event) => {
                if (!instalacionSelect.value) {
                    if (mapStatus) mapStatus.textContent = 'Selecciona una instalación primero.';
                    return;
                }
                const lat = event.latlng.lat.toFixed(6);
                const lng = event.latlng.lng.toFixed(6);
                if (latInput) latInput.value = lat;
                if (lngInput) lngInput.value = lng;
                if (mapStatus) mapStatus.textContent = 'Coordenadas listas para guardar.';
                setActiveMarker(lat, lng);
                if (saveBtn) saveBtn.disabled = false;
            });

            instalacionSelect.addEventListener('change', () => {
                if (instalacionSelect.value) {
                    if (mapStatus) mapStatus.textContent = 'Marca la instalación con un click en el mapa.';
                    if (saveBtn) saveBtn.disabled = true;
                    if (latInput) latInput.value = '';
                    if (lngInput) lngInput.value = '';
                    if (activeMarker) { map.removeLayer(activeMarker); activeMarker = null; }
                }
            });
            return;
        }

        // Vista: configurar barrio (select-btn + clear-btn)
        if (selectBtn && clearBtn && window.mapaComunidadData) {
            const comunidadConfig = window.mapaComunidadData;
            const defaultCenter = { lat: -34.6037, lng: -58.3816 };
            const mapCenter = comunidadConfig.centerLat && comunidadConfig.centerLng
                ? { lat: comunidadConfig.centerLat, lng: comunidadConfig.centerLng }
                : defaultCenter;
            const mapZoom = comunidadConfig.zoom || 15;

            const mapConfig = L.map('map').setView([mapCenter.lat, mapCenter.lng], mapZoom);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap',
            }).addTo(mapConfig);

            let elegirCentro = false;
            let centroMarker = null;

            const updateInputs = (lat, lng) => {
                if (centerLat) centerLat.value = Number(lat).toFixed(6);
                if (centerLng) centerLng.value = Number(lng).toFixed(6);
                if (zoomInput) zoomInput.value = mapConfig.getZoom();
            };

            const setCentroMarker = (lat, lng) => {
                if (centroMarker) mapConfig.removeLayer(centroMarker);
                centroMarker = L.marker([lat, lng]).addTo(mapConfig);
                centroMarker.bindPopup('Centro del barrio').openPopup();
            };

            selectBtn.addEventListener('click', () => {
                elegirCentro = true;
                if (statusConfig) statusConfig.textContent = 'Haz click en el mapa para marcar el centro.';
            });

            clearBtn.addEventListener('click', () => {
                elegirCentro = false;
                if (centerLat) centerLat.value = '';
                if (centerLng) centerLng.value = '';
                if (zoomInput) zoomInput.value = '';
                if (centroMarker) { mapConfig.removeLayer(centroMarker); centroMarker = null; }
                if (statusConfig) statusConfig.textContent = 'Marca el centro del barrio para habilitar la configuración.';
            });

            const buscarUbicacionConfig = async () => {
                const query = (searchInput || document.getElementById('search-input'))?.value?.trim();
                if (!query) return;
                if (statusConfig) statusConfig.textContent = 'Buscando ubicación...';
                try {
                    const response = await fetch(
                        'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(query)
                    );
                    const results = await response.json();
                    if (!results || results.length === 0) {
                        if (statusConfig) statusConfig.textContent = 'No se encontró esa ubicación.';
                        return;
                    }
                    const item = results[0];
                    const lat = Number(item.lat);
                    const lng = Number(item.lon);
                    mapConfig.setView([lat, lng], 15);
                    setCentroMarker(lat, lng);
                    updateInputs(lat, lng);
                    if (statusConfig) statusConfig.textContent = 'Ubicación encontrada. Puedes guardar o ajustar.';
                } catch (error) {
                    if (statusConfig) statusConfig.textContent = 'Error al buscar ubicación.';
                }
            };

            const searchInputConfig = document.getElementById('search-input');
            const searchBtnConfig = document.getElementById('search-btn');
            if (searchBtnConfig && searchInputConfig) {
                searchBtnConfig.addEventListener('click', buscarUbicacionConfig);
                searchInputConfig.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') { e.preventDefault(); buscarUbicacionConfig(); }
                });
            }

            mapConfig.on('click', (event) => {
                if (elegirCentro) {
                    elegirCentro = false;
                    setCentroMarker(event.latlng.lat, event.latlng.lng);
                    updateInputs(event.latlng.lat, event.latlng.lng);
                    if (statusConfig) statusConfig.textContent = 'Ubicación lista para guardar.';
                }
            });

            mapConfig.on('zoomend', () => {
                if (centerLat && centerLat.value && centerLng && centerLng.value) {
                    if (zoomInput) zoomInput.value = mapConfig.getZoom();
                }
            });

            if (comunidadConfig.centerLat && comunidadConfig.centerLng) {
                setCentroMarker(comunidadConfig.centerLat, comunidadConfig.centerLng);
                updateInputs(comunidadConfig.centerLat, comunidadConfig.centerLng);
            }
            return;
        }

        // Vista: editar coordenadas (mapaInstalacionData, sin instalacion-select)
        if (!instalacionSelect && window.mapaInstalacionData) {
            const instalacion = window.mapaInstalacionData;
            const mapCenter = { lat: instalacion.lat, lng: instalacion.lng };

            const mapEdit = L.map('map').setView([mapCenter.lat, mapCenter.lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap',
            }).addTo(mapEdit);

            let activeMarkerEdit = L.marker([instalacion.lat, instalacion.lng], { draggable: true }).addTo(mapEdit);
            activeMarkerEdit.bindPopup(instalacion.nombre).openPopup();

            activeMarkerEdit.on('dragend', (event) => {
                const pos = event.target.getLatLng();
                if (latInput) latInput.value = pos.lat.toFixed(6);
                if (lngInput) lngInput.value = pos.lng.toFixed(6);
            });

            const buscarUbicacionEdit = async () => {
                const query = (document.getElementById('search-input'))?.value?.trim();
                if (!query) return;
                if (mapStatus) mapStatus.textContent = 'Buscando ubicación...';
                try {
                    const response = await fetch(
                        'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(query)
                    );
                    const results = await response.json();
                    if (!results || results.length === 0) {
                        if (mapStatus) mapStatus.textContent = 'No se encontró esa ubicación.';
                        return;
                    }
                    const item = results[0];
                    const lat = Number(item.lat);
                    const lng = Number(item.lon);
                    mapEdit.setView([lat, lng], 15);
                    if (mapStatus) mapStatus.textContent = 'Ubicación encontrada. Marca el punto en el mapa.';
                } catch (error) {
                    if (mapStatus) mapStatus.textContent = 'Error al buscar ubicación.';
                }
            };

            const searchInputEdit = document.getElementById('search-input');
            const searchBtnEdit = document.getElementById('search-btn');
            if (searchBtnEdit && searchInputEdit) {
                searchBtnEdit.addEventListener('click', buscarUbicacionEdit);
                searchInputEdit.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') { e.preventDefault(); buscarUbicacionEdit(); }
                });
            }

            mapEdit.on('click', (event) => {
                const lat = event.latlng.lat.toFixed(6);
                const lng = event.latlng.lng.toFixed(6);
                if (latInput) latInput.value = lat;
                if (lngInput) lngInput.value = lng;
                if (mapStatus) mapStatus.textContent = 'Coordenadas actualizadas. Puedes arrastrar el marcador o guardar.';
                mapEdit.removeLayer(activeMarkerEdit);
                activeMarkerEdit = L.marker([lat, lng], { draggable: true }).addTo(mapEdit);
                activeMarkerEdit.bindPopup(instalacion.nombre).openPopup();
                activeMarkerEdit.on('dragend', (ev) => {
                    const pos = ev.target.getLatLng();
                    if (latInput) latInput.value = pos.lat.toFixed(6);
                    if (lngInput) lngInput.value = pos.lng.toFixed(6);
                });
            });
            return;
        }

        // Vista: mostrar coordenadas (solo lectura)
        if (window.mapaInstalacionShowData) {
            const instalacionShow = window.mapaInstalacionShowData;
            const mapShow = L.map('map').setView([instalacionShow.lat, instalacionShow.lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap',
            }).addTo(mapShow);
            L.marker([instalacionShow.lat, instalacionShow.lng])
                .addTo(mapShow)
                .bindPopup(instalacionShow.nombre)
                .openPopup();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMapa);
    } else {
        initMapa();
    }
})();
