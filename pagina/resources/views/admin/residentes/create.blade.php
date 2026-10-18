<x-layouts.admin title="Crear Residente" active="residentes">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Registrar nuevo residente</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Dá de alta a un habitante de la comunidad con sus datos de contacto y vivienda.</p>
        </div>
        <div>
            <a href="{{ route('admin.residentes.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <div><strong>Se encontraron algunos errores:</strong> Revisá los campos marcados abajo.</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-primary-subtle text-primary">
                            <i class="fas fa-user-plus"></i>
                        </span>
                        Datos del residente
                    </h2>
                </div>

                <div class="admin-card-body">
                    <!-- Banner de campos obligatorios -->
                    <div class="form-legend-banner">
                        <i class="fas fa-info-circle"></i>
                        <span>Los campos marcados con <span class="required-asterisk">*</span> son obligatorios.</span>
                    </div>

                    <form method="POST" action="{{ route('admin.residentes.store') }}">
                        @csrf

                        <!-- Nombre + Apellido -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    Nombre <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" id="nombre" name="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre') }}" placeholder="Ej: Jorge" required>
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="apellido" class="form-label">
                                    Apellido <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" id="apellido" name="apellido"
                                    class="form-control @error('apellido') is-invalid @enderror"
                                    value="{{ old('apellido') }}" placeholder="Ej: Pérez" required>
                                @error('apellido')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Correo + Teléfono -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="correo" class="form-label">
                                    Correo electrónico <span class="required-asterisk">*</span>
                                </label>
                                <input type="email" id="correo" name="correo"
                                    class="form-control @error('correo') is-invalid @enderror"
                                    value="{{ old('correo') }}" placeholder="jorge.perez@email.com" required>
                                @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    Teléfono <span class="required-asterisk">*</span>
                                </label>
                                <input type="tel" id="telefono" name="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono') }}" placeholder="Ej: +54 11 4747-0001" required>
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    Contraseña de acceso <span class="required-asterisk">*</span>
                                </label>
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" 
                                    placeholder="Mínimo 8 caracteres" required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    Confirmar contraseña <span class="required-asterisk">*</span>
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="Repetir la contraseña" required>
                            </div>
                        </div>

                        <!-- Vivienda y Unidad -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="numero_unidad" class="form-label">
                                    Número de unidad o vivienda <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" id="numero_unidad" name="numero_unidad"
                                    class="form-control @error('numero_unidad') is-invalid @enderror"
                                    value="{{ old('numero_unidad') }}" placeholder="Ej: Casa 45, Depto 12B" required>
                                @error('numero_unidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="direccion" class="form-label">
                                    Calle / Pasaje <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" id="direccion" name="direccion"
                                    class="form-control @error('direccion') is-invalid @enderror"
                                    value="{{ old('direccion') }}" placeholder="Ej: Los Robles" required>
                                @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Altura y Lote -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="altura" class="form-label">
                                    Altura / Número de puerta <span class="badge-field-optional">Opcional</span>
                                </label>
                                <input type="text" id="altura" name="altura"
                                    class="form-control @error('altura') is-invalid @enderror"
                                    value="{{ old('altura') }}" placeholder="Ej: 1234 o S/N">
                                @error('altura')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="numero_lote" class="form-label">
                                    Nº de lote <span class="badge-field-optional">Opcional</span>
                                </label>
                                <input type="text" id="numero_lote" name="numero_lote"
                                    class="form-control @error('numero_lote') is-invalid @enderror"
                                    value="{{ old('numero_lote') }}" placeholder="Ej: Lote 15">
                                @error('numero_lote')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Documentos y Fecha de Nacimiento -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="tipo_documento" class="form-label">
                                    Tipo de documento <span class="required-asterisk">*</span>
                                </label>
                                <select id="tipo_documento" name="tipo_documento"
                                    class="form-select @error('tipo_documento') is-invalid @enderror" required>
                                    <option value="">Seleccionar...</option>
                                    @foreach($tiposDocumento as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('tipo_documento', 'DNI') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('tipo_documento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="numero_documento" class="form-label">
                                    Número de documento <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" id="numero_documento" name="numero_documento"
                                    class="form-control @error('numero_documento') is-invalid @enderror"
                                    value="{{ old('numero_documento') }}" placeholder="Ej: 32123456" maxlength="15" required>
                                @error('numero_documento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="fecha_nacimiento" class="form-label">
                                    Fecha de nacimiento <span class="required-asterisk">*</span>
                                </label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                    class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                    value="{{ old('fecha_nacimiento') }}" 
                                    max="{{ date('Y-m-d') }}" min="1900-01-01" required>
                                @error('fecha_nacimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Checkbox propietario -->
                        <div class="mb-4 form-check p-3 bg-light rounded border">
                            <input type="hidden" name="es_propietario" value="0">
                            <input type="checkbox" id="es_propietario" name="es_propietario" class="form-check-input ms-0 me-2"
                                value="1" {{ old('es_propietario') ? 'checked' : '' }}>
                            <label for="es_propietario" class="form-check-label fw-semibold text-navy-950">
                                Es propietario (desmarcar si es inquilino o inquilina)
                            </label>
                        </div>

                        <!-- Botones -->
                        <div class="form-actions-bar">
                            <a href="{{ route('admin.residentes.index') }}" class="btn-modern-secondary">Cancelar</a>
                            <button type="submit" class="btn-modern-primary">
                                <i class="fas fa-user-check"></i> Registrar Residente
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</x-layouts.admin>