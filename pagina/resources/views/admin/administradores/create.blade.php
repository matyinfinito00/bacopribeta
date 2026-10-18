<x-layouts.admin title="Crear Administrador" active="administradores">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-navy-950 mb-1">Crear nuevo administrador</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Asigná un nuevo administrador responsable de la gestión de una comunidad.</p>
        </div>
        <div>
            <a href="{{ route('admin.administradores.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- INFO DEL ADMINISTRADOR -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
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

                    <form method="POST" action="{{ route('admin.administradores.store') }}">
                        @csrf

                        <!-- NOMBRE Y APELLIDO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">
                                    Nombre <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" 
                                       value="{{ old('nombre') }}" placeholder="Ej: Carlos" required>
                                @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="apellido" class="form-label">
                                    Apellido <span class="required-asterisk">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('apellido') is-invalid @enderror" 
                                       id="apellido" name="apellido" 
                                       value="{{ old('apellido') }}" placeholder="Ej: Gómez" required>
                                @error('apellido')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- CORREO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="correo" class="form-label">
                                    Correo electrónico <span class="required-asterisk">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control @error('correo') is-invalid @enderror" 
                                       id="correo" name="correo" 
                                       value="{{ old('correo') }}" placeholder="carlos.gomez@gmail.com" required>
                                @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">
                                    Teléfono <span class="required-asterisk">*</span>
                                </label>
                                <input type="tel" 
                                       class="form-control @error('telefono') is-invalid @enderror" 
                                       id="telefono" name="telefono" 
                                       value="{{ old('telefono') }}" placeholder="+54 9 11 9876-5432" required>
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- CONTRASEÑA -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    Contraseña <span class="required-asterisk">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    Confirmar contraseña <span class="required-asterisk">*</span>
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" name="password_confirmation" placeholder="Repetí la contraseña" required>
                            </div>
                        </div>

                        <!-- COMUNIDAD Y CARGO -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="comunidad_id" class="form-label">
                                    Comunidad asignada <span class="required-asterisk">*</span>
                                </label>
                                <select class="form-select @error('comunidad_id') is-invalid @enderror" 
                                        id="comunidad_id" name="comunidad_id" required>
                                    <option value="">Seleccionar Comunidad...</option>
                                    @foreach($comunidades as $comunidad)
                                        <option value="{{ $comunidad->id }}"
                                            {{ old('comunidad_id') == $comunidad->id ? 'selected' : '' }}>
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
                                <input type="text" 
                                       class="form-control @error('cargo') is-invalid @enderror" 
                                       id="cargo" name="cargo" 
                                       value="{{ old('cargo') }}" required
                                       placeholder="Ej: Administrador General, Intendente, etc.">
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
                                        {{ old('tipo_documento', 'DNI') == $value ? 'selected' : '' }}>
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
                                    value="{{ old('numero_documento') }}" placeholder="Ej: 35123456" maxlength="15" required>
                                @error('numero_documento')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- BOTONES -->
                        <div class="form-actions-bar">
                            <a href="{{ route('admin.administradores.index') }}" 
                               class="btn-modern-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-modern-primary">
                                <i class="fas fa-save"></i> Guardar Administrador
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
