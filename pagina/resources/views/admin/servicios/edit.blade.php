<x-layouts.admin title="Editar Servicio" active="servicios">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Editar servicio: {{ $servicio->nombre }}</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Actualiza datos de contacto, ubicación, categoría o cronograma de atención.</p>
        </div>
        <div>
            <a href="{{ route('admin.servicios.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver a la guía
            </a>
        </div>
    </div>

    {{-- Errores de validación --}}
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <div><strong>Errores de validación:</strong> Revisá los campos indicados abajo.</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    <div class="row g-4">
        <!-- Formulario principal -->
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-edit"></i>
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

                    <form method="POST" action="{{ route('admin.servicios.update', $servicio) }}">
                        @csrf
                        @method('PUT')

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                Nombre del servicio o comercio <span class="required-asterisk">*</span>
                            </label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre"
                                name="nombre" value="{{ old('nombre', $servicio->nombre) }}" required>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Categoría + Contacto -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="categoria" class="form-label">
                                    Categoría <span class="required-asterisk">*</span>
                                </label>
                                <select class="form-select @error('categoria') is-invalid @enderror" id="categoria"
                                    name="categoria" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categorias as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('categoria', $servicio->categoria) == $value ? 'selected' : '' }}>
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
                                    id="nombre_contacto" name="nombre_contacto"
                                    value="{{ old('nombre_contacto', $servicio->nombre_contacto) }}" required>
                                @error('nombre_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Teléfono + Correo -->
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
                                        id="telefono" name="telefono" value="{{ old('telefono', $servicio->telefono) }}"
                                        required>
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
                                        id="correo" name="correo" value="{{ old('correo', $servicio->correo) }}"
                                        required>
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
                                    id="direccion" name="direccion"
                                    value="{{ old('direccion', $servicio->direccion) }}" required>
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
                                name="descripcion" rows="3">{{ old('descripcion', $servicio->descripcion) }}</textarea>
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
                                        <input type="time" class="form-control" id="horario_general_desde" name="horario_general_desde" 
                                            value="{{ old('horario_general_desde', $horariosProcesados['general']['desde'] ?? '') }}">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label small text-muted">Cierre (Hasta)</label>
                                        <input type="time" class="form-control" id="horario_general_hasta" name="horario_general_hasta" 
                                            value="{{ old('horario_general_hasta', $horariosProcesados['general']['hasta'] ?? '') }}">
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
                                                {{ (is_array(old('horario_general_dias')) && in_array($diaValue, old('horario_general_dias'))) || 
                                                   (!old('horario_general_dias') && isset($horariosProcesados['general']['dias']) && in_array($diaValue, $horariosProcesados['general']['dias'])) ? 'checked' : '' }}>
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
                                    @if(isset($horariosProcesados['adicionales']) && count($horariosProcesados['adicionales']) > 0)
                                        @foreach($horariosProcesados['adicionales'] as $index => $adicional)
                                            <div class="border rounded-3 p-3 mb-3 horario-adicional bg-light">
                                                <div class="row align-items-center g-2">
                                                    <div class="col-md-4">
                                                        <label class="form-label small text-muted">Día</label>
                                                        <select class="form-select horario-adicional-dia" name="horarios_adicionales[{{ $loop->index }}][dia]">
                                                            <option value="">Seleccionar día</option>
                                                            @foreach($dias as $diaValue => $diaLabel)
                                                                <option value="{{ $diaValue }}" {{ $adicional['dia'] === $diaValue ? 'selected' : '' }}>
                                                                    {{ $diaLabel }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label small text-muted">Desde</label>
                                                        <input type="time" class="form-control horario-adicional-desde" name="horarios_adicionales[{{ $loop->index }}][desde]" value="{{ $adicional['desde'] }}">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label small text-muted">Hasta</label>
                                                        <input type="time" class="form-control horario-adicional-hasta" name="horarios_adicionales[{{ $loop->index }}][hasta]" value="{{ $adicional['hasta'] }}">
                                                    </div>
                                                    <div class="col-md-2 text-end">
                                                        <label class="form-label small text-muted d-block">&nbsp;</label>
                                                        <button type="button" class="btn btn-sm btn-outline-danger w-100 eliminar-horario-adicional">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
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
                                <i class="fas fa-save me-2"></i>Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Información lateral -->
        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-info-subtle text-info">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        Estado del registro
                    </h2>
                </div>
                <div class="admin-card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="feature-icon bg-primary-subtle text-primary" style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                        <div>
                            <h3 class="h6 fw-bold text-navy-950 mb-0">{{ $servicio->nombre }}</h3>
                            <span class="badge-admin badge-info">
                                {{ ucfirst($servicio->categoria) }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-light rounded-3 p-3 small mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Estado actual:</span>
                            <span class="badge-admin {{ $servicio->estado ? 'badge-success' : 'badge-danger' }}">
                                <i class="fas fa-circle" style="font-size: 6px;"></i> {{ $servicio->estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Creado:</span>
                            <span class="fw-semibold text-navy-950">{{ optional($servicio->created_at)->format('d/m/Y H:i') ?? 'Sin registro' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Última actualización:</span>
                            <span class="fw-semibold text-navy-950">{{ optional($servicio->updated_at)->format('d/m/Y H:i') ?? 'Sin registro' }}</span>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('admin.servicios.show', $servicio) }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                            <i class="fas fa-eye me-1"></i>Ver ficha pública del servicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>