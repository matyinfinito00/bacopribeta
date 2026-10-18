<x-layouts.admin title="Crear Alerta de Seguridad" active="alertas-seguridad">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Nueva alerta de seguridad</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Emite comunicados urgentes e instructivos de seguridad para los vecinos y guardias.</p>
        </div>
        <div>
            <a href="{{ route('admin.alertas-seguridad.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a alertas
            </a>
        </div>
    </div>

    <!-- VALIDACIÓN DE ERRORES -->
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

    <form method="POST" action="{{ route('admin.alertas-seguridad.store') }}" id="alertaForm">
        @csrf

        <div class="row g-4">
            <!-- COLUMNA PRINCIPAL (EDITOR) -->
            <div class="col-lg-8">
                <div class="admin-card mb-0">
                    <div class="admin-card-header">
                        <h2 class="admin-card-title">
                            <span class="admin-card-title-icon bg-danger-subtle text-danger">
                                <i class="fas fa-bullhorn"></i>
                            </span>
                            Datos de la Alerta Oficial
                        </h2>
                    </div>
                    <div class="admin-card-body">

                        <div class="form-legend-banner">
                            <i class="fas fa-info-circle"></i>
                            <span>Los campos marcados con <span class="required-asterisk">*</span> son estrictamente obligatorios.</span>
                        </div>

                        <!-- TÍTULO -->
                        <div class="mb-4">
                            <label for="titulo" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Título de la alerta <span class="required-asterisk">*</span>
                            </label>
                            <input type="text"
                                   id="tituloInput"
                                   name="titulo"
                                   value="{{ old('titulo') }}"
                                   class="form-control modern-input @error('titulo') is-invalid @enderror"
                                   placeholder="Ej: AVISO: Mantenimiento de cámaras de seguridad perimetral"
                                   autocomplete="off"
                                   required>
                            <div class="form-text text-muted" style="font-size: 0.8rem;">
                                Usa un encabezado claro y descriptivo para que se identifique de inmediato.
                            </div>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NIVEL DE PRIORIDAD -->
                        <div class="mb-4">
                            <label for="prioridad" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Nivel de prioridad <span class="required-asterisk">*</span>
                            </label>
                            <select id="prioridadSelect"
                                    name="prioridad"
                                    class="form-select modern-input @error('prioridad') is-invalid @enderror"
                                    required>
                                <option value="">Seleccionar nivel de prioridad</option>
                                @foreach($prioridades as $value => $label)
                                    <option value="{{ $value }}" {{ old('prioridad') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted" style="font-size: 0.8rem;">
                                El nivel determina el color y la urgencia visual en la aplicación de los residentes.
                            </div>
                            @error('prioridad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CONTENIDO -->
                        <div class="mb-2">
                            <label for="contenido" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Detalle / Contenido del comunicado <span class="required-asterisk">*</span>
                            </label>
                            <textarea id="contenidoInput"
                                      name="contenido"
                                      rows="7"
                                      class="form-control modern-input @error('contenido') is-invalid @enderror"
                                      placeholder="Describe los hechos, las zonas afectadas, precauciones y teléfonos de guardia a los que acudir..."
                                      style="line-height: 1.6;"
                                      required>{{ old('contenido') }}</textarea>
                            @error('contenido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA -->
            <div class="col-lg-4">

                <!-- TARJETA DE EMISIÓN -->
                <div class="detail-sidebar-block mb-4">
                    <div class="detail-sidebar-title">
                        <i class="fas fa-shield-alt text-danger"></i>
                        Emisión Inmediata
                    </div>

                    <ul class="detail-meta-list mb-4">
                        <li class="detail-meta-item">
                            <span class="meta-label">Destinatarios</span>
                            <span class="meta-value">
                                <span class="badge-admin badge-info">Comunidad entera</span>
                            </span>
                        </li>
                        <li class="detail-meta-item">
                            <span class="meta-label">Difusión</span>
                            <span class="meta-value text-danger fw-bold">
                                <i class="fas fa-bell me-1"></i> Alta prioridad
                            </span>
                        </li>
                    </ul>

                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn-modern-primary w-100 justify-content-center py-2 fs-6">
                            <i class="fas fa-bullhorn"></i>
                            <span>Publicar alerta</span>
                        </button>
                        <a href="{{ route('admin.alertas-seguridad.index') }}" class="btn-modern-secondary w-100 justify-content-center">
                            Cancelar
                        </a>
                    </div>
                </div>

                <!-- VISTA PREVIA SIMULADA -->
                <div class="detail-sidebar-block mb-0">
                    <div class="detail-sidebar-title">
                        <i class="fas fa-mobile-alt text-primary"></i>
                        Vista Previa para Vecinos
                    </div>

                    <div class="feed-preview-card" style="border-top: 4px solid #dc2626;" id="liveAlertCard">
                        <div class="feed-preview-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge-admin badge-danger" id="liveAlertBadge">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Alerta
                                </span>
                                <span class="text-muted" style="font-size: 0.75rem;">Ahora</span>
                            </div>
                            <div class="feed-preview-title" id="liveAlertTitle" style="font-size: 1.05rem;">
                                Título de la alerta...
                            </div>
                            <div class="feed-preview-text" id="liveAlertText">
                                Aquí se mostrará el detalle de la advertencia para los residentes...
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
            const contenidoInput = document.getElementById('contenidoInput');
            const prioridadSelect = document.getElementById('prioridadSelect');

            const liveAlertTitle = document.getElementById('liveAlertTitle');
            const liveAlertText = document.getElementById('liveAlertText');
            const liveAlertBadge = document.getElementById('liveAlertBadge');
            const liveAlertCard = document.getElementById('liveAlertCard');

            tituloInput.addEventListener('input', function () {
                const val = this.value.trim();
                liveAlertTitle.textContent = val.length > 0 ? val : 'Título de la alerta...';
            });

            contenidoInput.addEventListener('input', function () {
                const val = this.value.trim();
                liveAlertText.textContent = val.length > 0 ? val : 'Aquí se mostrará el detalle de la advertencia para los residentes...';
            });

            prioridadSelect.addEventListener('change', function () {
                const val = this.value;
                if (val === 'urgente') {
                    liveAlertBadge.className = 'badge-admin badge-danger';
                    liveAlertBadge.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i> Urgente';
                    liveAlertCard.style.borderTopColor = '#dc2626';
                } else if (val === 'alta') {
                    liveAlertBadge.className = 'badge-admin badge-warning';
                    liveAlertBadge.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Alta';
                    liveAlertCard.style.borderTopColor = '#ea580c';
                } else {
                    liveAlertBadge.className = 'badge-admin badge-info';
                    liveAlertBadge.innerHTML = '<i class="fas fa-info-circle me-1"></i> Media / Informativa';
                    liveAlertCard.style.borderTopColor = '#0284c7';
                }
            });
        });
    </script>

</x-layouts.admin>
