<x-layouts.admin title="Editar Evento" active="eventos">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Editar evento</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Modifica los detalles, fecha, horario o lugar de esta actividad comunitaria.</p>
        </div>
        <div>
            <a href="{{ route('admin.eventos.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a eventos
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-start gap-2">
                <i class="fas fa-exclamation-circle fs-5 mt-1 text-danger"></i>
                <div>
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.eventos.update', $evento) }}" id="eventoForm">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- COLUMNA PRINCIPAL (FORMULARIO) -->
            <div class="col-lg-8">
                <div class="admin-card mb-0">
                    <div class="admin-card-header">
                        <h2 class="admin-card-title">
                            <span class="admin-card-title-icon bg-primary-subtle text-primary">
                                <i class="fas fa-edit"></i>
                            </span>
                            Edición de la Actividad
                        </h2>
                    </div>
                    <div class="admin-card-body">

                        <!-- BANNER OBLIGATORIEDAD -->
                        <div class="form-legend-banner">
                            <i class="fas fa-info-circle"></i>
                            <span>Los campos marcados con <span class="required-asterisk">*</span> son estrictamente obligatorios.</span>
                        </div>

                        <!-- TÍTULO -->
                        <div class="mb-4">
                            <label for="titulo" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Título del evento <span class="required-asterisk">*</span>
                            </label>
                            <input type="text"
                                   class="form-control modern-input @error('titulo') is-invalid @enderror"
                                   id="tituloInput"
                                   name="titulo"
                                   value="{{ old('titulo', $evento->titulo) }}"
                                   placeholder="Ej: Cine al aire libre"
                                   required>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- FECHA Y HORAS -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="fecha" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                    Fecha <span class="required-asterisk">*</span>
                                </label>
                                <input type="date"
                                       class="form-control modern-input @error('fecha') is-invalid @enderror"
                                       id="fechaInput"
                                       name="fecha"
                                       value="{{ old('fecha', optional($evento->fecha)->format('Y-m-d')) }}"
                                       required>
                                @error('fecha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="hora_inicio" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                    Hora de inicio <span class="badge-field-optional">Opcional</span>
                                </label>
                                <input type="time"
                                       class="form-control modern-input @error('hora_inicio') is-invalid @enderror"
                                       id="horaInicioInput"
                                       name="hora_inicio"
                                       value="{{ old('hora_inicio', $evento->hora_inicio ? substr($evento->hora_inicio, 0, 5) : '') }}">
                                @error('hora_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="hora_fin" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                    Hora de fin <span class="badge-field-optional">Opcional</span>
                                </label>
                                <input type="time"
                                       class="form-control modern-input @error('hora_fin') is-invalid @enderror"
                                       id="horaFinInput"
                                       name="hora_fin"
                                       value="{{ old('hora_fin', $evento->hora_fin ? substr($evento->hora_fin, 0, 5) : '') }}">
                                @error('hora_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- LUGAR Y CATEGORÍA -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="lugar" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                    Lugar o sector <span class="badge-field-optional">Opcional</span>
                                </label>
                                <input type="text"
                                       class="form-control modern-input @error('lugar') is-invalid @enderror"
                                       id="lugarInput"
                                       name="lugar"
                                       value="{{ old('lugar', $evento->lugar) }}"
                                       placeholder="Ej: SUM principal">
                                @error('lugar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="categoria" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                    Categoría <span class="badge-field-optional">Opcional</span>
                                </label>
                                <input type="text"
                                       class="form-control modern-input @error('categoria') is-invalid @enderror"
                                       id="categoriaInput"
                                       name="categoria"
                                       value="{{ old('categoria', $evento->categoria) }}"
                                       placeholder="Ej: Deportes, Social">
                                @error('categoria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- DESCRIPCIÓN -->
                        <div class="mb-2">
                            <label for="descripcion" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Descripción para los vecinos <span class="badge-field-optional">Opcional</span>
                            </label>
                            <textarea class="form-control modern-input @error('descripcion') is-invalid @enderror"
                                      id="descripcionInput"
                                      name="descripcion"
                                      rows="5">{{ old('descripcion', $evento->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA (GUARDAR & SIMULADOR) -->
            <div class="col-lg-4">

                <!-- TARJETA DE AUDITORÍA Y ACCIONES -->
                <div class="detail-sidebar-block mb-4">
                    <div class="detail-sidebar-title">
                        <i class="fas fa-save text-primary"></i>
                        Guardar Cambios
                    </div>

                    <ul class="detail-meta-list mb-4">
                        <li class="detail-meta-item">
                            <span class="meta-label">Última edición</span>
                            <span class="meta-value">{{ optional($evento->updated_at)->format('d/m/Y H:i') }}</span>
                        </li>
                    </ul>

                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn-modern-primary w-100 justify-content-center py-2 fs-6">
                            <i class="fas fa-save"></i>
                            <span>Guardar cambios</span>
                        </button>
                        <a href="{{ route('admin.eventos.index') }}" class="btn-modern-secondary w-100 justify-content-center">
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- SIMULADOR DE AGENDA -->
                <div class="detail-sidebar-block mb-0">
                    <div class="detail-sidebar-title">
                        <i class="fas fa-mobile-alt text-primary"></i>
                        Vista Previa en Agenda
                    </div>

                    <div class="feed-preview-card" style="border-top: 4px solid #7c3aed;">
                        <div class="feed-preview-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge-admin badge-purple" id="liveEventCategory">
                                    {{ $evento->categoria ?: 'General' }}
                                </span>
                                <span class="text-muted" style="font-size: 0.78rem;" id="liveEventDate">
                                    {{ optional($evento->fecha)->format('d/m/Y') ?: 'Fecha a definir' }}
                                </span>
                            </div>
                            <div class="feed-preview-title" id="liveEventTitle">
                                {{ $evento->titulo }}
                            </div>
                            <div class="text-muted small mb-2" id="liveEventLocation">
                                <i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $evento->lugar ?: 'Lugar a confirmar' }}
                            </div>
                            <div class="feed-preview-text" id="liveEventDesc">
                                {{ $evento->descripcion ?: 'Descripción del evento para los vecinos...' }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tituloInput = document.getElementById('tituloInput');
            const fechaInput = document.getElementById('fechaInput');
            const lugarInput = document.getElementById('lugarInput');
            const categoriaInput = document.getElementById('categoriaInput');
            const descripcionInput = document.getElementById('descripcionInput');

            const liveEventTitle = document.getElementById('liveEventTitle');
            const liveEventDate = document.getElementById('liveEventDate');
            const liveEventLocation = document.getElementById('liveEventLocation');
            const liveEventCategory = document.getElementById('liveEventCategory');
            const liveEventDesc = document.getElementById('liveEventDesc');

            tituloInput.addEventListener('input', function () {
                const val = this.value.trim();
                liveEventTitle.textContent = val.length > 0 ? val : 'Título del evento...';
            });

            fechaInput.addEventListener('change', function () {
                liveEventDate.textContent = this.value || 'Fecha a definir';
            });

            lugarInput.addEventListener('input', function () {
                const val = this.value.trim();
                liveEventLocation.innerHTML = '<i class="fas fa-map-marker-alt me-1 text-danger"></i> ' + (val.length > 0 ? val : 'Lugar a confirmar');
            });

            categoriaInput.addEventListener('input', function () {
                const val = this.value.trim();
                liveEventCategory.textContent = val.length > 0 ? val : 'General';
            });

            descripcionInput.addEventListener('input', function () {
                const val = this.value.trim();
                liveEventDesc.textContent = val.length > 0 ? val : 'Descripción del evento...';
            });
        });
    </script>

</x-layouts.admin>
