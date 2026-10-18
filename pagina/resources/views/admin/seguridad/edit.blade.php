<x-layouts.admin title="Editar Usuario de Seguridad" active="seguridad">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Editar usuario de seguridad: {{ $seguridad->nombre_completo }}</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Actualiza la información del guardia, credenciales de garita y datos de contacto.</p>
        </div>
        <div>
            <a href="{{ route('admin.seguridad.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <div><strong>Errores de validación:</strong> Revisá los campos marcados abajo.</div>
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
                            <i class="fas fa-user-edit"></i>
                        </span>
                        Datos del usuario de seguridad
                    </h2>
                </div>

                <div class="admin-card-body">
                    <!-- Banner de campos obligatorios -->
                    <div class="form-legend-banner">
                        <i class="fas fa-info-circle"></i>
                        <span>Los campos marcados con <span class="required-asterisk">*</span> son obligatorios. La contraseña solo debe completarse si deseas cambiarla.</span>
                    </div>

                    <form method="POST" action="{{ route('admin.seguridad.update', $seguridad) }}">
                        @csrf
                        @method('PUT')

                        <!-- NOMBRE Y APELLIDO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    Nombre <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre" name="nombre" value="{{ old('nombre', $seguridad->nombre) }}"
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
                                    id="apellido" name="apellido" value="{{ old('apellido', $seguridad->apellido) }}"
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
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        <i class="far fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control border-start-0 @error('correo') is-invalid @enderror"
                                        id="correo" name="correo" value="{{ old('correo', $seguridad->correo) }}"
                                        required>
                                </div>
                                @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    Teléfono / Interno <span class="required-asterisk">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">
                                        <i class="fas fa-phone-alt"></i>
                                    </span>
                                    <input type="tel" class="form-control border-start-0 @error('telefono') is-invalid @enderror"
                                        id="telefono" name="telefono" value="{{ old('telefono', $seguridad->telefono) }}"
                                        required>
                                </div>
                                @error('telefono')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- DOCUMENTO -->
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="tipo_documento" class="form-label">
                                    Tipo de documento <span class="required-asterisk">*</span>
                                </label>
                                <select id="tipo_documento" name="tipo_documento"
                                    class="form-select @error('tipo_documento') is-invalid @enderror" required>
                                    <option value="">Seleccionar</option>
                                    @foreach($tiposDocumento as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('tipo_documento', $seguridad->tipo_documento) === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('tipo_documento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-7 mb-3">
                                <label for="numero_documento" class="form-label">
                                    Número de documento <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" id="numero_documento" name="numero_documento"
                                    class="form-control @error('numero_documento') is-invalid @enderror"
                                    value="{{ old('numero_documento', $seguridad->numero_documento) }}" placeholder="Ej: 34567890" maxlength="9" required>
                                @error('numero_documento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- CONTRASEÑA OPCIONAL -->
                        <div class="bg-light rounded-3 p-3 mb-4 border">
                            <h6 class="fw-bold text-navy-950 mb-2">
                                <i class="fas fa-key text-warning me-2"></i>Actualizar contraseña de acceso
                            </h6>
                            <p class="text-muted small mb-3">Completá estos campos únicamente si deseás cambiar la contraseña vigente del guardia.</p>

                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="password" class="form-label">Nueva contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white text-muted border-end-0">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="Dejar en blanco para mantener">
                                    </div>
                                    @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="password_confirmation" class="form-label">Confirmar nueva contraseña</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white text-muted border-end-0">
                                            <i class="fas fa-shield-alt"></i>
                                        </span>
                                        <input type="password" class="form-control border-start-0" id="password_confirmation"
                                            name="password_confirmation" placeholder="Repetir nueva contraseña">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BOTONES -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.seguridad.index') }}" class="btn-modern-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-modern-primary px-4">
                                <i class="fas fa-save me-2"></i>Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: ESTADO ACTUAL Y ACCESO DIRECTO -->
        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">
                        <span class="admin-card-title-icon bg-info-subtle text-info">
                            <i class="fas fa-id-card"></i>
                        </span>
                        Perfil del guardia
                    </h2>
                </div>

                <div class="admin-card-body text-center p-4">
                    @php
                        $initials = mb_substr($seguridad->nombre ?? 'G', 0, 1) . mb_substr($seguridad->apellido ?? '', 0, 1);
                    @endphp
                    <div class="profile-avatar-large mx-auto mb-3" style="background: linear-gradient(135deg, #0284c7, #0f766e);">
                        {{ strtoupper($initials ?: 'GS') }}
                    </div>
                    <h3 class="h5 fw-bold text-navy-950 mb-1">{{ $seguridad->nombre_completo }}</h3>
                    <p class="text-muted small mb-2">{{ $seguridad->correo }}</p>

                    <span class="badge-admin {{ $seguridad->estado ? 'badge-success' : 'badge-danger' }} mb-3">
                        <i class="fas fa-circle" style="font-size: 6px;"></i> {{ $seguridad->estado ? 'Cuenta Activa' : 'Inactivo' }}
                    </span>

                    <div class="bg-light rounded-3 p-3 text-start small mt-2">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Comunidad:</span>
                            <span class="fw-semibold text-navy-950">{{ $seguridad->comunidad->nombre ?? 'Comunidad' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Documento:</span>
                            <span class="fw-semibold text-navy-950">{{ $seguridad->tipo_documento }} {{ $seguridad->numero_documento }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Teléfono:</span>
                            <span class="fw-semibold text-navy-950">{{ $seguridad->telefono ?: 'No registrado' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Alta:</span>
                            <span class="fw-semibold text-navy-950">{{ $seguridad->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Modificado:</span>
                            <span class="fw-semibold text-navy-950">{{ $seguridad->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.seguridad.show', $seguridad) }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                            <i class="fas fa-eye me-1"></i>Ver expediente completo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
