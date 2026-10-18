<x-layouts.admin title="Crear Instalación" active="instalaciones">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Crear nueva instalación</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Registrá una nueva área recreativa, deportiva o común para la comunidad.</p>
        </div>
        <div>
            <a href="{{ route('admin.instalaciones.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <div><strong>Se encontraron algunos errores:</strong> Revisá los campos indicados abajo.</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-swimming-pool"></i>
                        </span>
                        Información de la instalación
                    </h2>
                </div>
                <div class="admin-card-body">

                    <!-- Banner de campos obligatorios -->
                    <div class="form-legend-banner">
                        <i class="fas fa-info-circle"></i>
                        <span>Los campos marcados con <span class="required-asterisk">*</span> son obligatorios.</span>
                    </div>

                    <form method="POST" action="{{ route('admin.instalaciones.store') }}" id="facilityForm" enctype="multipart/form-data">
                        @csrf

                        <!-- NOMBRE -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                Nombre de la Instalación <span class="required-asterisk">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre"
                                name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Cancha de Tenis Nº 1" required>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DESCRIPCIÓN -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">
                                Descripción <span class="badge-field-optional">Opcional</span>
                            </label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion"
                                name="descripcion" rows="3"
                                placeholder="Detalles sobre el espacio, iluminación, superficie, etc.">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- IMÁGENES -->
                        <div class="mb-3">
                            <label for="imagenes" class="form-label">
                                Fotografías del espacio <span class="badge-field-optional">Opcional</span>
                            </label>
                            <input type="file" class="form-control @error('imagenes.*') is-invalid @enderror" 
                                id="imagenes" name="imagenes[]" multiple accept="image/*">
                            <small class="form-text text-muted">Podés subir una o varias fotos (máx. 5MB cada una). Formatos: JPG, PNG, WEBP.</small>
                            @error('imagenes.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="imagenes-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
                        </div>

                        <!-- TIPO DE INSTALACIÓN Y CAPACIDAD -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo" class="form-label">
                                    Tipo de Instalación <span class="required-asterisk">*</span>
                                </label>
                                <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                                    <option value="">Seleccionar tipo...</option>
                                    @foreach($tipos as $value => $label)
                                    <option value="{{ $value }}" {{ old('tipo') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="capacidad" class="form-label">
                                    Capacidad máxima <span class="badge-field-optional">Opcional</span>
                                </label>
                                <input type="number" class="form-control @error('capacidad') is-invalid @enderror"
                                    id="capacidad" name="capacidad" value="{{ old('capacidad') }}"
                                    placeholder="Ej: 4 (cancha) o 50 (quincho)">
                                @error('capacidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- UBICACIÓN + TELÉFONO DE CONTACTO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ubicacion" class="form-label">
                                    Ubicación dentro del predio <span class="badge-field-optional">Opcional</span>
                                </label>
                                <input type="text" class="form-control @error('ubicacion') is-invalid @enderror"
                                    id="ubicacion" name="ubicacion" value="{{ old('ubicacion') }}"
                                    placeholder="Ej: Sector Club House o Manzana 4">
                                @error('ubicacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    Teléfono de contacto / Intendencia <span class="required-asterisk">*</span>
                                </label>
                                <input type="tel" class="form-control @error('telefono') is-invalid @enderror"
                                    id="telefono" name="telefono" value="{{ old('telefono') }}"
                                    placeholder="+54 11 1234-5678" required>
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- REGLAS Y AMENIDADES -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reglas" class="form-label">
                                    Reglamento de uso <span class="badge-field-optional">Opcional</span>
                                </label>
                                <textarea class="form-control @error('reglas') is-invalid @enderror" id="reglas"
                                    name="reglas" rows="3" placeholder="Una regla por línea (ej: Uso de calzado adecuado)">{{ old('reglas') }}</textarea>
                                @error('reglas')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="amenidades" class="form-label">
                                    Amenidades disponibles <span class="badge-field-optional">Opcional</span>
                                </label>
                                <textarea class="form-control @error('amenidades') is-invalid @enderror" id="amenidades"
                                    name="amenidades" rows="3" placeholder="Wifi&#10;Parrilla&#10;Vestuarios">{{ old('amenidades') }}</textarea>
                                @error('amenidades')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- HORARIO DE DISPONIBILIDAD -->
                        <div class="mb-4">
                            <label class="form-label">
                                Horarios de disponibilidad <span class="required-asterisk">*</span>
                            </label>
                            <small class="d-block text-muted mb-2">Definí un horario general y seleccioná los días que aplica. Si algún día tiene horario diferente, agregalo como adicional.</small>
                            
                            <!-- Horario General -->
                            <div class="admin-card border mb-3 bg-light">
                                <div class="admin-card-body p-3">
                                    <h3 class="h6 fw-bold text-navy-950 mb-3">Horario habitual</h3>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label small">Hora de apertura</label>
                                            <input type="time" class="form-control" id="horario_general_desde" name="horario_general_desde" value="{{ old('horario_general_desde') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small">Hora de cierre</label>
                                            <input type="time" class="form-control" id="horario_general_hasta" name="horario_general_hasta" value="{{ old('horario_general_hasta') }}">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label small d-block">Días habilitados:</label>
                                        <div class="row g-2">
                                            @foreach($dias as $diaValue => $diaLabel)
                                            <div class="col-md-3 col-6">
                                                <div class="form-check p-2 bg-white rounded border">
                                                    <input class="form-check-input ms-0 me-2 horario-general-dia" type="checkbox" 
                                                        id="general_dia_{{ $diaValue }}" 
                                                        name="horario_general_dias[]" 
                                                        value="{{ $diaValue }}"
                                                        {{ old('horario_general_dias') && in_array($diaValue, old('horario_general_dias')) ? 'checked' : '' }}>
                                                    <label class="form-check-label small fw-semibold" for="general_dia_{{ $diaValue }}">
                                                        {{ $diaLabel }}
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Horarios Adicionales -->
                            <div class="admin-card border">
                                <div class="admin-card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h3 class="h6 fw-bold text-navy-950 mb-0">Horarios especiales por día <span class="badge-field-optional">Opcional</span></h3>
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="agregar-horario-adicional">
                                            <i class="fas fa-plus me-1"></i>Agregar Horario
                                        </button>
                                    </div>
                                    
                                    <div id="horarios-adicionales-container">
                                        <!-- Los horarios adicionales se agregarán dinámicamente -->
                                    </div>
                                </div>
                            </div>
                            
                            @error('horario')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- TIPO DE RESERVA -->
                        <div class="mb-4 form-check p-3 bg-light rounded border">
                            <input type="hidden" name="es_reservable" value="0">
                            <input type="checkbox" class="form-check-input ms-0 me-2" id="es_reservable" name="es_reservable"
                                value="1" {{ old('es_reservable', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold text-navy-950" for="es_reservable">
                                Permitir reservas online desde la aplicación móvil
                            </label>
                        </div>

                        <!-- LINK DE RESERVA (solo para gastronomía) -->
                        <div class="mb-4 p-3 bg-info-subtle rounded border border-info" id="link_reserva_container" style="display: none;">
                            <label for="link_reserva" class="form-label text-navy-950 fw-bold">
                                <i class="fas fa-external-link-alt me-1"></i>Link de reserva externa (Gastronomía)
                            </label>
                            <input type="url" class="form-control @error('link_reserva') is-invalid @enderror"
                                id="link_reserva" name="link_reserva"
                                value="{{ old('link_reserva') }}"
                                placeholder="https://mirestaurante.com/reservas">
                            <small class="text-muted d-block mt-1">
                                Los residentes verán un botón de redirección que abrirá esta URL para gestionar la reserva en el sitio externo del local.
                            </small>
                            @error('link_reserva')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- BOTONES -->
                        <div class="form-actions-bar">
                            <a href="{{ route('admin.instalaciones.index') }}"
                                class="btn-modern-secondary">Cancelar</a>
                            <button type="submit" class="btn-modern-primary">
                                <i class="fas fa-save"></i> Guardar Instalación
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipoSelect = document.getElementById('tipo');
        const linkContainer = document.getElementById('link_reserva_container');
        
        function toggleLinkReserva() {
            if (tipoSelect && linkContainer) {
                if (tipoSelect.value === 'gastronomia') {
                    linkContainer.style.display = 'block';
                } else {
                    linkContainer.style.display = 'none';
                }
            }
        }
        
        if (tipoSelect) {
            tipoSelect.addEventListener('change', toggleLinkReserva);
            toggleLinkReserva();
        }
    });
</script>

</x-layouts.admin>