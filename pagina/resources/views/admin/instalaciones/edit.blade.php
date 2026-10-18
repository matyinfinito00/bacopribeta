<x-layouts.admin title="Editar Instalación - {{ $instalacion->nombre }}" active="instalaciones">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Editar instalación: {{ $instalacion->nombre }}</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Actualizá los datos generales, fotos, horarios y reglas de uso del área común.</p>
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

    <div class="row g-4">
        <div class="col-lg-8">
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

                    <form method="POST" action="{{ route('admin.instalaciones.update', $instalacion) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- NOMBRE -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                Nombre de la instalación <span class="required-asterisk">*</span>
                            </label>
                            <input type="text" id="nombre" name="nombre"
                                class="form-control @error('nombre') is-invalid @enderror"
                                value="{{ old('nombre', $instalacion->nombre) }}" required>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- DESCRIPCIÓN -->
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">
                                Descripción <span class="badge-field-optional">Opcional</span>
                            </label>
                            <textarea id="descripcion" name="descripcion" rows="3"
                                class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $instalacion->descripcion) }}</textarea>
                            @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- IMÁGENES -->
                        <div class="mb-3">
                            <label for="imagenes" class="form-label">
                                Fotografías del espacio <span class="badge-field-optional">Opcional</span>
                            </label>
                            
                            <!-- Imágenes existentes -->
                            @if($instalacion->imagenes && count($instalacion->imagenes) > 0)
                            <div class="mb-3 p-3 bg-light rounded border">
                                <label class="form-label small fw-bold text-muted d-block mb-2">Imágenes actuales cargadas:</label>
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    @foreach($instalacion->imagenes as $index => $imagen)
                                    <div class="position-relative d-inline-block border rounded p-1 bg-white">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($imagen) }}" 
                                             alt="Imagen {{ $index + 1 }}" 
                                             style="width: 100px; height: 100px; object-fit: cover; border-radius: 6px;">
                                        <div class="form-check position-absolute top-0 end-0 m-1 bg-white rounded-circle p-1 shadow-sm">
                                            <input type="checkbox" 
                                                   name="imagenes_existentes[]" 
                                                   value="{{ $index }}" 
                                                   checked 
                                                   class="form-check-input ms-0"
                                                   title="Desmarcar para eliminar">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Desmarcá la casilla de una foto si deseás eliminarla al guardar.</small>
                            </div>
                            @endif
                            
                            <!-- Nuevas imágenes -->
                            <input type="file" class="form-control @error('imagenes.*') is-invalid @enderror" 
                                id="imagenes" name="imagenes[]" multiple accept="image/*">
                            <small class="form-text text-muted">Podés subir imágenes adicionales (máx. 5MB cada una). Formatos: JPG, PNG, WEBP.</small>
                            @error('imagenes.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="imagenes-preview" class="mt-3 d-flex flex-wrap gap-2"></div>
                        </div>

                        <!-- TIPO DE INSTALACIÓN Y CAPACIDAD -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo" class="form-label">
                                    Tipo de instalación <span class="required-asterisk">*</span>
                                </label>
                                <select id="tipo" name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                                    <option value="">Seleccionar tipo...</option>
                                    @foreach($tipos as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('tipo', $instalacion->tipo) === $value ? 'selected' : '' }}>
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
                                <input type="number" id="capacidad" name="capacidad" min="1"
                                    class="form-control @error('capacidad') is-invalid @enderror"
                                    value="{{ old('capacidad', $instalacion->capacidad) }}" placeholder="Ej: 4 o 50">
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
                                <input type="text" id="ubicacion" name="ubicacion"
                                    class="form-control @error('ubicacion') is-invalid @enderror"
                                    value="{{ old('ubicacion', $instalacion->ubicacion) }}" placeholder="Sector Norte">
                                @error('ubicacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    Teléfono de contacto / Intendencia <span class="required-asterisk">*</span>
                                </label>
                                <input type="tel" id="telefono" name="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono', $instalacion->telefono) }}" required>
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- REGLAS Y AMENIDADES -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="reglas" class="form-label">
                                    Reglas de uso <span class="badge-field-optional">Opcional</span>
                                </label>
                                <textarea id="reglas" name="reglas" rows="3"
                                    class="form-control @error('reglas') is-invalid @enderror">{{ old('reglas', implode("\n", $instalacion->reglas_array)) }}</textarea>
                                @error('reglas')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="amenidades" class="form-label">
                                    Amenidades disponibles <span class="badge-field-optional">Opcional</span>
                                </label>
                                <textarea id="amenidades" name="amenidades" rows="3"
                                    class="form-control @error('amenidades') is-invalid @enderror">{{ old('amenidades', implode("\n", $instalacion->amenidades_array)) }}</textarea>
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
                            
                            <!-- Horario General -->
                            <div class="admin-card border mb-3 bg-light">
                                <div class="admin-card-body p-3">
                                    <h3 class="h6 fw-bold text-navy-950 mb-3">Horario habitual</h3>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label small">Hora de apertura</label>
                                            <input type="time" class="form-control" id="horario_general_desde" 
                                                name="horario_general_desde" 
                                                value="{{ old('horario_general_desde', $instalacion->horario_general['desde'] ?? '') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small">Hora de cierre</label>
                                            <input type="time" class="form-control" id="horario_general_hasta" 
                                                name="horario_general_hasta" 
                                                value="{{ old('horario_general_hasta', $instalacion->horario_general['hasta'] ?? '') }}">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="form-label small d-block">Días habilitados:</label>
                                        <div class="row g-2">
                                            @php
                                                $diasGuardados = old('horario_general_dias', $instalacion->horario_general['dias'] ?? []);
                                            @endphp
                                            @foreach($dias as $diaValue => $diaLabel)
                                            <div class="col-md-3 col-6">
                                                <div class="form-check p-2 bg-white rounded border">
                                                    <input class="form-check-input ms-0 me-2 horario-general-dia" type="checkbox" 
                                                        id="general_dia_{{ $diaValue }}" 
                                                        name="horario_general_dias[]" 
                                                        value="{{ $diaValue }}"
                                                        {{ in_array($diaValue, $diasGuardados) ? 'checked' : '' }}>
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
                                        @if(isset($instalacion->horarios_adicionales) && count($instalacion->horarios_adicionales) > 0)
                                            @foreach($instalacion->horarios_adicionales as $index => $horario)
                                                <div class="admin-card border mb-3 horario-adicional-item p-3">
                                                    <div class="row align-items-end g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label small">Día</label>
                                                            <select class="form-select form-select-sm" name="horarios_adicionales[{{ $index }}][dia]">
                                                                @foreach($dias as $dValue => $dLabel)
                                                                    <option value="{{ $dValue }}" {{ ($horario['dia'] ?? '') == $dValue ? 'selected' : '' }}>
                                                                        {{ $dLabel }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">Desde</label>
                                                            <input type="time" class="form-control form-control-sm" name="horarios_adicionales[{{ $index }}][desde]" value="{{ $horario['desde'] ?? '' }}">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label small">Hasta</label>
                                                            <input type="time" class="form-control form-control-sm" name="horarios_adicionales[{{ $index }}][hasta]" value="{{ $horario['hasta'] ?? '' }}">
                                                        </div>
                                                        <div class="col-md-2 text-end">
                                                            <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-horario">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
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
                            <input type="checkbox" id="es_reservable" name="es_reservable" value="1"
                                class="form-check-input ms-0 me-2"
                                {{ old('es_reservable', $instalacion->es_reservable) ? 'checked' : '' }}>
                            <label for="es_reservable" class="form-check-label fw-semibold text-navy-950">
                                Permitir reservas online desde la aplicación móvil
                            </label>
                        </div>

                        <!-- LINK DE RESERVA (solo para gastronomía) -->
                        <div class="mb-4 p-3 bg-info-subtle rounded border border-info" id="link_reserva_container" style="display: none;">
                            <label for="link_reserva" class="form-label text-navy-950 fw-bold">
                                <i class="fas fa-external-link-alt me-1"></i>Link de reserva externo (Gastronomía)
                            </label>
                            <input type="url" class="form-control @error('link_reserva') is-invalid @enderror"
                                id="link_reserva" name="link_reserva"
                                value="{{ old('link_reserva', $instalacion->link_reserva) }}"
                                placeholder="https://mirestaurante.com/reservas">
                            <small class="text-muted d-block mt-1">
                                Los residentes verán un botón de redirección que abrirá esta URL para reservar en el sitio externo del local.
                            </small>
                            @error('link_reserva')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- BOTÓN -->
                        <div class="form-actions-bar">
                            <a href="{{ route('admin.instalaciones.index') }}"
                                class="btn-modern-secondary">Cancelar</a>
                            <button type="submit" class="btn-modern-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: ESTADO -->
        <div class="col-lg-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-info-subtle text-info">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        Estado del área
                    </h2>
                </div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Estado actual:</span>
                        @if($instalacion->estado)
                            <span class="badge-admin badge-success">
                                <i class="fas fa-check-circle me-1"></i>Activa / Operativa
                            </span>
                        @else
                            <span class="badge-admin badge-danger">
                                <i class="fas fa-times-circle me-1"></i>Inactiva / En mantenimiento
                            </span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Reservas por App:</span>
                        <span class="badge-admin {{ $instalacion->es_reservable ? 'badge-info' : 'badge-secondary' }}">
                            {{ $instalacion->es_reservable ? 'Habilitadas' : 'Deshabilitadas' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Fecha de alta:</span>
                        <span class="small fw-semibold text-secondary">{{ optional($instalacion->created_at)->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="pt-2 border-top">
                        <span class="text-muted small d-block mb-1">Última actualización:</span>
                        <span class="small fw-semibold text-secondary">{{ optional($instalacion->updated_at)->format('d/m/Y H:i') }}</span>
                    </div>
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