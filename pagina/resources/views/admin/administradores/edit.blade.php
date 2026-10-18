<x-layouts.admin title="Editar Administrador" active="administradores">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Editar administrador: {{ $administrador->nombre_completo }}</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Modificá los datos personales, de acceso o asignación de comunidad.</p>
        </div>
        <div>
            <a href="{{ route('admin.administradores.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- FORMULARIO -->
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-success-subtle text-success">
                            <i class="fas fa-user-shield"></i>
                        </span>
                        Información del administrador
                    </h2>
                </div>

                <div class="admin-card-body">
                    <!-- Banner de campos obligatorios -->
                    <div class="form-legend-banner">
                        <i class="fas fa-info-circle"></i>
                        <span>Los campos marcados con <span class="required-asterisk">*</span> son obligatorios.</span>
                    </div>

                    <form method="POST" action="{{ route('admin.administradores.update', $administrador) }}">
                        @csrf
                        @method('PUT')

                        <!-- NOMBRE Y APELLIDO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    Nombre <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre" name="nombre" value="{{ old('nombre', $administrador->nombre) }}"
                                    required>
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="apellido" class="form-label">
                                    Apellido <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control @error('apellido') is-invalid @enderror"
                                    id="apellido" name="apellido" value="{{ old('apellido', $administrador->apellido) }}"
                                    required>
                                @error('apellido')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- CORREO Y TELÉFONO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="correo" class="form-label">
                                    Correo electrónico <span class="required-asterisk">*</span>
                                </label>
                                <input type="email" class="form-control @error('correo') is-invalid @enderror"
                                    id="correo" name="correo" value="{{ old('correo', $administrador->correo) }}"
                                    required>
                                @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    Teléfono <span class="required-asterisk">*</span>
                                </label>
                                <input type="tel" class="form-control @error('telefono') is-invalid @enderror"
                                    id="telefono" name="telefono" value="{{ old('telefono', $administrador->telefono) }}"
                                    required>
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- CONTRASEÑA -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    Nueva contraseña <span class="badge-field-optional">Opcional</span>
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Dejar en blanco para mantener la actual">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    Confirmar nueva contraseña
                                </label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Repetí la nueva contraseña">
                            </div>
                        </div>

                        <!-- COMUNIDAD Y CARGO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="comunidad_id" class="form-label">
                                    Comunidad asignada <span class="required-asterisk">*</span>
                                </label>
                                <select id="comunidad_id" name="comunidad_id"
                                    class="form-select @error('comunidad_id') is-invalid @enderror" required>
                                    <option value="">Seleccionar Comunidad...</option>
                                    @foreach($comunidades as $comunidad)
                                    <option value="{{ $comunidad->id }}"
                                        {{ old('comunidad_id', $administrador->comunidad_id) == $comunidad->id ? 'selected' : '' }}>
                                        {{ $comunidad->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('comunidad_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cargo" class="form-label">
                                    Cargo o función <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control @error('cargo') is-invalid @enderror" id="cargo"
                                    name="cargo" value="{{ old('cargo', $administrador->cargo) }}"
                                    placeholder="Ej: Administrador General, Intendente, etc." required>
                                @error('cargo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- DOCUMENTOS -->
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
                                        {{ old('tipo_documento', $administrador->tipo_documento ?? 'DNI') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('tipo_documento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8 mb-3">
                                <label for="numero_documento" class="form-label">
                                    Número de documento <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" id="numero_documento" name="numero_documento"
                                    class="form-control @error('numero_documento') is-invalid @enderror"
                                    value="{{ old('numero_documento', $administrador->numero_documento) }}" placeholder="Ej: 35123456" maxlength="15" required>
                                @error('numero_documento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- BOTONES -->
                        <div class="form-actions-bar">
                            <a href="{{ route('admin.administradores.index') }}" class="btn-modern-secondary">
                                Cancelar
                            </a>
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
                        Información de cuenta
                    </h2>
                </div>

                <div class="admin-card-body">
                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Comunidad:</span>
                        <span class="fw-bold text-navy-950">{{ $administrador->comunidad->nombre ?? 'Sin asignar' }}</span>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Estado de acceso:</span>
                        @if($administrador->estado)
                            <span class="badge-admin badge-success">
                                <i class="fas fa-check-circle me-1"></i>Activo
                            </span>
                        @else
                            <span class="badge-admin badge-danger">
                                <i class="fas fa-times-circle me-1"></i>Inactivo
                            </span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1">Fecha de registro:</span>
                        <span class="small fw-semibold text-secondary">{{ $administrador->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="pt-2 border-top">
                        <span class="text-muted small d-block mb-1">Última actualización:</span>
                        <span class="small fw-semibold text-secondary">{{ $administrador->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
