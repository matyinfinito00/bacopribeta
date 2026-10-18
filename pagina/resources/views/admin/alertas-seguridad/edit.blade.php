<x-layouts.admin title="Editar Alerta de Seguridad" active="alertas-seguridad">

    <!-- CABECERA DE NAVEGACIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Editar alerta de seguridad</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Modifica el contenido o nivel de urgencia del aviso emitido.</p>
        </div>
        <div>
            <a href="{{ route('admin.alertas-seguridad.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a alertas
            </a>
        </div>
    </div>

    <!-- VALIDACIONES -->
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

    <form method="POST" action="{{ route('admin.alertas-seguridad.update', $alerta) }}" id="alertaForm">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- COLUMNA PRINCIPAL (EDITOR) -->
            <div class="col-lg-8">
                <div class="admin-card mb-0">
                    <div class="admin-card-header">
                        <h2 class="admin-card-title">
                            <span class="admin-card-title-icon bg-danger-subtle text-danger">
                                <i class="fas fa-edit"></i>
                            </span>
                            Edición del Comunicado
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
                                   value="{{ old('titulo', $alerta->titulo) }}"
                                   class="form-control modern-input @error('titulo') is-invalid @enderror"
                                   placeholder="Ej: ALERTA: Movimiento sospechoso en el acceso principal"
                                   required>
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
                                @foreach($prioridades as $value => $label)
                                    <option value="{{ $value }}" {{ old('prioridad', $alerta->prioridad) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('prioridad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CONTENIDO -->
                        <div class="mb-2">
                            <label for="contenido" class="form-label fw-semibold" style="color: var(--admin-navy-950);">
                                Contenido de la alerta <span class="required-asterisk">*</span>
                            </label>
                            <textarea id="contenidoInput"
                                      name="contenido"
                                      rows="7"
                                      class="form-control modern-input @error('contenido') is-invalid @enderror"
                                      style="line-height: 1.6;"
                                      required>{{ old('contenido', $alerta->contenido) }}</textarea>
                            @error('contenido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA -->
            <div class="col-lg-4">

                <!-- TARJETA DE AUDITORÍA Y GUARDADO -->
                <div class="detail-sidebar-block mb-4">
                    <div class="detail-sidebar-title">
                        <i class="fas fa-save text-primary"></i>
                        Guardar Cambios
                    </div>

                    <ul class="detail-meta-list mb-4">
                        <li class="detail-meta-item">
                            <span class="meta-label">Emitida el</span>
                            <span class="meta-value">{{ optional($alerta->publicado_en)->format('d/m/Y H:i') ?? 'Sin fecha' }}</span>
                        </li>
                        <li class="detail-meta-item">
                            <span class="meta-label">Última edición</span>
                            <span class="meta-value">{{ optional($alerta->updated_at)->format('d/m/Y H:i') }}</span>
                        </li>
                    </ul>

                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn-modern-primary w-100 justify-content-center py-2 fs-6">
                            <i class="fas fa-save"></i>
                            <span>Guardar cambios</span>
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

                    @php
                        $topColor = match($alerta->prioridad) {
                            'urgente' => '#dc2626',
                            'alta' => '#ea580c',
                            default => '#0284c7'
                        };
                        $badgeClass = match($alerta->prioridad) {
                            'urgente' => 'badge-danger',
                            'alta' => 'badge-warning',
                            default => 'badge-info'
                        };
                    @endphp

                    <div class="feed-preview-card" style="border-top: 4px solid {{ $topColor }};" id="liveAlertCard">
                        <div class="feed-preview-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge-admin {{ $badgeClass }}" id="liveAlertBadge">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Prioridad {{ ucfirst($alerta->prioridad) }}
                                </span>
                                <span class="text-muted" style="font-size: 0.75rem;">Actualizada</span>
                            </div>
                            <div class="feed-preview-title" id="liveAlertTitle" style="font-size: 1.05rem;">
                                {{ $alerta->titulo }}
                            </div>
                            <div class="feed-preview-text" id="liveAlertText">
                                {{ $alerta->contenido }}
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
                liveAlertText.textContent = val.length > 0 ? val : 'Aquí se mostrará el detalle de la advertencia...';
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