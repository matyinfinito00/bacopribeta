<x-layouts.admin title="Crear Servicio" active="servicios">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Crear nuevo servicio de utilidad</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Publicá proveedores, emergencias, farmacias de turno o mantenimiento para los vecinos.</p>
        </div>
        <div>
            <a href="{{ route('admin.servicios.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a la guía
            </a>
        </div>
    </div>

    <!-- Mensajes de error -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <div><strong>Se encontraron algunos errores:</strong> Revisá los campos indicados abajo.</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    <div class="row g-4">
        <!-- FORMULARIO PRINCIPAL -->
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-concierge-bell"></i>
                        </span>
                        Información del servicio
                    </h2>
                </div>
                <div class="admin-card-body">
                    <!-- Banner de campos obligatorios -->
                    <div class="form-legend-banner">
                        <i class="fas fa-info-circle"></i>
                        <span>Los campos marcados con <span class="required-asterisk">*</span> son obligatorios.</span>
                    </div>

                    <form method="POST" action="{{ route('admin.servicios.store') }}" id="serviceForm">
                        @csrf

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                Nombre del servicio o comercio <span class="required-asterisk">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre"
                                name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Farmacia Central o Urgencias Médicas" required autofocus>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Categoría + contacto -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="categoria" class="form-label">
                                    Categoría <span class="required-asterisk">*</span>
                                </label>
                                <select class="form-select @error('categoria') is-invalid @enderror" id="categoria"
                                    name="categoria" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categorias as $value => $label)
                                    <option value="{{ $value }}" {{ old('categoria') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('categoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nombre_contacto" class="form-label">
                                    Nombre del referente o encargado <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control @error('nombre_contacto') is-invalid @enderror"
                                    id="nombre_contacto" name="nombre_contacto" value="{{ old('nombre_contacto') }}"
                                    placeholder="Ej: Dr. Roberto Ruiz / Atención al Cliente" required>
                                @error('nombre_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Teléfono + correo -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    Teléfono de contacto o guardia <span class="required-asterisk">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        <i class="fas fa-phone-alt"></i>
                                    </span>
                                    <input type="tel" class="form-control border-start-0 @error('telefono') is-invalid @enderror"
                                        id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="Ej: 0800-333-2211" required>
                                </div>
                                @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="correo" class="form-label">
                                    Correo electrónico <span class="required-asterisk">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        <i class="far fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control border-start-0 @error('correo') is-invalid @enderror"
                                        id="correo" name="correo" value="{{ old('correo') }}" placeholder="contacto@servicio.com" required>
                                </div>
                                @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="mb-3">
                            <label for="direccion" class="form-label">
                                Dirección o ubicación física <span class="required-asterisk">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 @error('direccion') is-invalid @enderror"
                                    id="direccion" name="direccion" value="{{ old('direccion') }}" placeholder="Ej: Av. Principal 1420 o Portería Central" required>
                            </div>
                            @error('direccion')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="descripcion" class="form-label mb-0">Descripción o notas para los vecinos</label>
                                <span class="badge-field-optional">Opcional</span>
                            </div>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion"
                                name="descripcion" rows="3" placeholder="Detalles de cobertura, pedidos a domicilio, requisitos o promociones para el barrio...">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Horario de atención -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-navy-950 d-block mb-1">
                                Horarios de atención <span class="required-asterisk">*</span>
                            </label>
                            <small class="text-muted d-block mb-3">Definí el horario general habitual y marcá los días en que aplica. Podés sumar excepciones abajo.</small>
                            
                            <!-- Horario General -->
                            <div class="border rounded-3 p-3 bg-light mb-3">
                                <h6 class="fw-bold text-navy-950 mb-3">
                                    <i class="far fa-clock text-primary me-2"></i>Horario General
                                </h6>
                                <div class="row g-3 mb-3">
                                    <div class="col-sm-6">
                                        <label class="form-label small text-muted">Apertura (Desde)</label>
                                        <input type="time" class="form-control" id="horario_general_desde" name="horario_general_desde" value="{{ old('horario_general_desde') }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label small text-muted">Cierre (Hasta)</label>
                                        <input type="time" class="form-control" id="horario_general_hasta" name="horario_general_hasta" value="{{ old('horario_general_hasta') }}">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="form-label small text-muted d-block mb-2">Días que aplica este horario:</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($dias as $diaValue => $diaLabel)
                                        <div class="form-check form-check-inline m-0">
                                            <input class="form-check-input horario-general-dia" type="checkbox" 
                                                id="general_dia_{{ $diaValue }}" 
                                                name="horario_general_dias[]" 
                                                value="{{ $diaValue }}"
                                                {{ old('horario_general_dias') && in_array($diaValue, old('horario_general_dias')) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="general_dia_{{ $diaValue }}">
                                                {{ $diaLabel }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Horarios Adicionales -->
                            <div class="border rounded-3 p-3 bg-white">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-bold text-navy-950 mb-0">
                                        <i class="fas fa-calendar-plus text-info me-2"></i>Horarios adicionales o de fin de semana
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="agregar-horario-adicional">
                                        <i class="fas fa-plus me-1"></i>Agregar horario
                                    </button>
                                </div>
                                <div id="horarios-adicionales-container">
                                    <!-- Dinámico vía script.js -->
                                </div>
                            </div>
                            
                            @error('horario')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.servicios.index') }}" class="btn-modern-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-modern-primary px-4">
                                <i class="fas fa-save me-2"></i>Crear servicio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: VISTA PREVIA & GUÍA -->
        <div class="col-lg-4">
            <!-- TARJETA SIMULADOR DE TARJETA DE SERVICIO -->
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-info-subtle text-info">
                            <i class="fas fa-mobile-alt"></i>
                        </span>
                        Ficha en la app móvil
                    </h2>
                </div>
                <div class="admin-card-body p-4">
                    <div class="border rounded-3 p-3 bg-light text-start shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge-admin badge-info" id="previewCategory">
                                <i class="fas fa-tag me-1"></i> Servicio
                            </span>
                            <span class="badge-admin badge-success">
                                <i class="fas fa-circle" style="font-size: 6px;"></i> Disponible
                            </span>
                        </div>

                        <h3 class="h6 fw-bold text-navy-950 mb-1" id="previewTitle">Nombre del Servicio</h3>
                        <p class="text-muted small mb-3" id="previewDescription">Descripción breve visible por los residentes desde su teléfono móvil.</p>

                        <div class="small pt-2 border-top">
                            <div class="d-flex align-items-center gap-2 mb-1 text-muted">
                                <i class="fas fa-phone-alt text-primary" style="width: 16px;"></i>
                                <span id="previewPhone" class="fw-semibold text-navy-950">Teléfono no indicado</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-1 text-muted">
                                <i class="fas fa-map-marker-alt text-danger" style="width: 16px;"></i>
                                <span id="previewAddress" class="text-truncate">Dirección no indicada</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 text-muted">
                                <i class="far fa-user text-secondary" style="width: 16px;"></i>
                                <span id="previewContact" class="text-truncate">Referente de contacto</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RECOMENDACIONES -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-warning-subtle text-warning">
                            <i class="fas fa-lightbulb"></i>
                        </span>
                        Recomendaciones
                    </h2>
                </div>
                <div class="admin-card-body p-3">
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="fas fa-check text-success mt-1"></i>
                            <span>Si es una <strong>urgencia o guardia 24hs</strong>, aclaralo en la descripción y cargá el número directo.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2 mb-2">
                            <i class="fas fa-check text-success mt-1"></i>
                            <span>Para comercios externos (ej. delivery), indicá si tienen convenio o descuento para el barrio.</span>
                        </li>
                        <li class="d-flex align-items-start gap-2">
                            <i class="fas fa-check text-success mt-1"></i>
                            <span>Mantené los horarios actualizados para evitar llamadas a proveedores fuera de turno.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const nombre = document.getElementById('nombre');
            const categoria = document.getElementById('categoria');
            const telefono = document.getElementById('telefono');
            const direccion = document.getElementById('direccion');
            const contacto = document.getElementById('nombre_contacto');
            const descripcion = document.getElementById('descripcion');

            const previewTitle = document.getElementById('previewTitle');
            const previewCategory = document.getElementById('previewCategory');
            const previewPhone = document.getElementById('previewPhone');
            const previewAddress = document.getElementById('previewAddress');
            const previewContact = document.getElementById('previewContact');
            const previewDescription = document.getElementById('previewDescription');

            function updatePreview() {
                previewTitle.textContent = nombre.value.trim() || 'Nombre del Servicio';
                
                const catText = categoria.options[categoria.selectedIndex]?.text;
                previewCategory.innerHTML = `<i class="fas fa-tag me-1"></i> ${catText && categoria.value ? catText : 'Servicio'}`;

                previewPhone.textContent = telefono.value.trim() || 'Teléfono no indicado';
                previewAddress.textContent = direccion.value.trim() || 'Dirección no indicada';
                previewContact.textContent = contacto.value.trim() || 'Referente de contacto';
                previewDescription.textContent = descripcion.value.trim() || 'Descripción breve visible por los residentes desde su teléfono móvil.';
            }

            [nombre, categoria, telefono, direccion, contacto, descripcion].forEach(el => {
                if (el) el.addEventListener('input', updatePreview);
            });
            updatePreview();
        });
    </script>
</x-layouts.admin>